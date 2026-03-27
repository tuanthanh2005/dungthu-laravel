<?php

namespace App\Http\Controllers;

use App\Models\ChatbotMessage;
use App\Services\GroqChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    protected GroqChatService $chatService;

    public function __construct(GroqChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Get chat page
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Send message and get AI response (API endpoint)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'required|string',
        ]);

        $message = trim($request->input('message'));
        $sessionId = $request->input('session_id');
        $userId = auth()->id();

        // Check if chatbot is enabled
        if (!config('chatbot.enabled', true)) {
            return response()->json([
                'success' => false,
                'message' => 'Chatbot hiện không hoạt động. Vui lòng liên hệ support!',
            ]);
        }

        // Get AI response
        $result = $this->chatService->chat($message, $sessionId, $userId);

        return response()->json($result);
    }

    /**
     * Get chat history for a session
     */
    public function history(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        $sessionId = $request->input('session_id');
        $messages = ChatbotMessage::session($sessionId)
            ->completed()
            ->latest('created_at')
            ->take(50)
            ->get()
            ->reverse();

        return response()->json([
            'success' => true,
            'messages' => $messages->map(fn($msg) => [
                'id' => $msg->id,
                'message' => $msg->message,
                'response' => $msg->response,
                'created_at' => $msg->created_at->format('H:i'),
                'is_helpful' => $msg->is_helpful,
            ])->values(),
        ]);
    }

    /**
     * Generate session ID for new chat
     */
    public function createSession()
    {
        $sessionId = (string) Str::uuid();

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
        ]);
    }

    /**
     * Provide feedback on AI response
     */
    public function feedback(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:chatbot_messages,id',
            'is_helpful' => 'required|boolean',
            'feedback_note' => 'nullable|string|max:500',
        ]);

        $message = ChatbotMessage::find($request->input('message_id'));

        $message->update([
            'is_helpful' => $request->input('is_helpful'),
            'feedback_note' => $request->input('feedback_note'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn feedback của bạn! 🙏',
        ]);
    }

    /**
     * Get available products for recommendations
     */
    public function getProducts(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string',
        ]);

        $query = \App\Models\Product::query();

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        $products = $query->where('stock', '>', 0)
            ->select(['id', 'name', 'description', 'price', 'sale_price', 'category'])
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    /**
     * Get Buff Services for recommendations
     */
    public function getBuffServices(Request $request)
    {
        $request->validate([
            'platform' => 'nullable|string',
        ]);

        $query = \App\Models\BuffService::query()->where('is_active', true);

        if ($request->has('platform')) {
            $query->where('platform', $request->input('platform'));
        }

        $services = $query->select(['id', 'name', 'platform', 'service_type', 'description', 'base_price', 'price_per_unit'])
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services,
        ]);
    }
}
