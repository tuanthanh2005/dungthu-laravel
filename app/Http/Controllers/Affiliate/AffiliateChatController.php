<?php

namespace App\Http\Controllers\Affiliate;

use App\Helpers\PathHelper;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AffiliateChatController extends Controller
{
    public function index()
    {
        $affiliate = Auth::guard('affiliate')->user();
        
        $messages = Message::where('affiliate_id', $affiliate->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $affiliate = Auth::guard('affiliate')->user();

        $request->validate([
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if (!$request->message && !$request->hasFile('image')) {
            return response()->json(['error' => 'Vui lòng nhập tin nhắn hoặc chọn ảnh'], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $uploadPath = PathHelper::publicRootPath('uploads/chat');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file->move($uploadPath, $fileName);
            $imagePath = 'uploads/chat/' . $fileName;
        }

        $message = Message::create([
            'affiliate_id' => $affiliate->id,
            'message' => $request->message,
            'image' => $imagePath,
            'is_admin' => false,
            'is_read' => false
        ]);

        return response()->json($message);
    }

    public function getNewMessages(Request $request)
    {
        $affiliate = Auth::guard('affiliate')->user();
        $lastId = $request->get('last_id', 0);
        
        $messages = Message::where('affiliate_id', $affiliate->id)
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function unreadCount()
    {
        $affiliate = Auth::guard('affiliate')->user();

        $count = Message::where('affiliate_id', $affiliate->id)
            ->where('is_admin', true)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread' => $count]);
    }

    public function markRead()
    {
        $affiliate = Auth::guard('affiliate')->user();

        Message::where('affiliate_id', $affiliate->id)
            ->where('is_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}
