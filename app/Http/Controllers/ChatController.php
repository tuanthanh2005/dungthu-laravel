<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\TelegramHelper;

class ChatController extends Controller
{
    // Hiển thị danh sách tin nhắn của user hiện tại
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập'], 401);
        }

        $messages = Message::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    // Gửi tin nhắn mới
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập'], 401);
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => false,
            'is_read' => false
        ]);

        // Gửi thông báo Telegram cho admin
        $user = Auth::user();
        $telegramMessage = "💬 *Tin nhắn mới từ khách hàng*\n\n"
            . "👤 Khách: {$user->name}\n"
            . "📧 Email: {$user->email}\n"
            . "💬 Nội dung: {$request->message}\n\n"
            . "🔗 Xem tại: " . route('admin.chat.index');
        
        TelegramHelper::sendMessage($telegramMessage);

        return response()->json($message);
    }

    // Lấy tin nhắn mới (polling)
    public function getNewMessages(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập'], 401);
        }

        $lastId = $request->get('last_id', 0);
        
        $messages = Message::where('user_id', Auth::id())
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    // User: Đếm tin nhắn chưa đọc từ admin
    public function unreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập'], 401);
        }

        $count = Message::where('user_id', Auth::id())
            ->where('is_admin', true)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread' => $count]);
    }

    // User: Đánh dấu đã đọc tất cả tin nhắn từ admin
    public function markRead()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập'], 401);
        }

        Message::where('user_id', Auth::id())
            ->where('is_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    // Admin: Xem danh sách users có tin nhắn
    public function adminIndex()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $users = Message::with('user')
            ->select('user_id')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('SUM(CASE WHEN is_admin = 0 AND is_read = 0 THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('user_id')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($item) {
                $user = $item->user;
                $user->unread_count = $item->unread_count;
                return $user;
            });

        $existingUserIds = $users->pluck('id')->filter()->all();
        $allUsers = User::where('role', '!=', 'admin')
            ->when(!empty($existingUserIds), function ($query) use ($existingUserIds) {
                $query->whereNotIn('id', $existingUserIds);
            })
            ->orderBy('name')
            ->get();

        return view('admin.chat.index', compact('users', 'allUsers'));
    }

    // Admin: Xem tin nhắn của một user
    public function adminMessages($userId)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = Message::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Đánh dấu tin nhắn từ user là đã đọc
        Message::where('user_id', $userId)
            ->where('is_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    // Admin: Gửi tin nhắn cho user
    public function adminReply(Request $request, $userId)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'user_id' => $userId,
            'message' => $request->message,
            'is_admin' => true,
            'is_read' => false
        ]);

        return response()->json($message);
    }
}
