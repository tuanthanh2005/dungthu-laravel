<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    use \App\Traits\HandleImage;

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
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if (!$request->message && !$request->hasFile('image')) {
            return response()->json(['error' => 'Vui lòng nhập tin nhắn hoặc chọn ảnh'], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadStandardImage($request->file('image'), 'chat', 800, 800, false);
        }

        $message = Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'image' => $imagePath,
            'is_admin' => false,
            'is_read' => false
        ]);

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

    // Admin: Xem danh sách users/affiliates có tin nhắn
    public function adminIndex()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Get users who chatted
        $usersResult = Message::whereNotNull('user_id')
            ->with('user')
            ->select('user_id')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('SUM(CASE WHEN is_admin = 0 AND is_read = 0 THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('user_id')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($item) {
                $user = $item->user;
                if ($user) {
                    $user->unread_count = $item->unread_count;
                    $user->last_message_at = $item->last_message_at;
                    $user->type = 'user';
                }
                return $user;
            })->filter();

        // Get affiliates who chatted
        $affiliatesResult = Message::whereNotNull('affiliate_id')
            ->with('affiliate')
            ->select('affiliate_id')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('SUM(CASE WHEN is_admin = 0 AND is_read = 0 THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('affiliate_id')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($item) {
                $aff = $item->affiliate;
                if ($aff) {
                    $aff->unread_count = $item->unread_count;
                    $aff->last_message_at = $item->last_message_at;
                    $aff->type = 'affiliate';
                }
                return $aff;
            })->filter();

        // Merge and sort
        $recentChats = $usersResult->concat($affiliatesResult)->sortByDesc('last_message_at');

        $allUsers = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $allAffiliates = \App\Models\Affiliate::orderBy('name')->get();

        return view('admin.chat.index', compact('recentChats', 'allUsers', 'allAffiliates'));
    }

    // Admin: Xem tin nhắn của một user/affiliate
    public function adminMessages($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $type = request('type', 'user');
        
        $query = Message::query();
        if ($type === 'affiliate') {
            $query->where('affiliate_id', $id);
        } else {
            $query->where('user_id', $id);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        // Đánh dấu tin nhắn từ user là đã đọc
        $updateQuery = Message::query();
        if ($type === 'affiliate') {
            $updateQuery->where('affiliate_id', $id);
        } else {
            $updateQuery->where('user_id', $id);
        }
        
        $updateQuery->where('is_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    // Admin: Gửi tin nhắn cho user/affiliate
    public function adminReply(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'type' => 'required|in:user,affiliate'
        ]);

        if (!$request->message && !$request->hasFile('image')) {
            return response()->json(['error' => 'Vui lòng nhập tin nhắn hoặc chọn ảnh'], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadStandardImage($request->file('image'), 'chat', 800, 800, false);
        }

        $data = [
            'message' => $request->message,
            'image' => $imagePath,
            'is_admin' => true,
            'is_read' => false
        ];

        if ($request->type === 'affiliate') {
            $data['affiliate_id'] = $id;
        } else {
            $data['user_id'] = $id;
        }

        $message = Message::create($data);

        return response()->json($message);
    }
}
