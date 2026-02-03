<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TiktokDeal;
use Illuminate\Http\Request;
use App\Helpers\PathHelper;
use Illuminate\Support\Facades\Storage;

class TiktokDealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deals = TiktokDeal::ordered()->paginate(20);
        return view('admin.tiktok-deals.index', compact('deals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tiktok-deals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tiktok_link' => 'required|url',
            'original_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(PathHelper::publicRootPath('images/products'), $imageName);
            $validated['image'] = $imageName;
        }

        TiktokDeal::create($validated);

        return redirect()->route('admin.tiktok-deals.index')
            ->with('success', 'Deal Tiktok đã được thêm thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TiktokDeal $tiktokDeal)
    {
        return view('admin.tiktok-deals.edit', compact('tiktokDeal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TiktokDeal $tiktokDeal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tiktok_link' => 'required|url',
            'original_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($tiktokDeal->image && file_exists(PathHelper::publicRootPath('images/products/' . $tiktokDeal->image))) {
                unlink(PathHelper::publicRootPath('images/products/' . $tiktokDeal->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(PathHelper::publicRootPath('images/products'), $imageName);
            $validated['image'] = $imageName;
        }

        $tiktokDeal->update($validated);

        return redirect()->route('admin.tiktok-deals.index')
            ->with('success', 'Deal Tiktok đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TiktokDeal $tiktokDeal)
    {
        // Xóa ảnh nếu có
        if ($tiktokDeal->image && file_exists(PathHelper::publicRootPath('images/products/' . $tiktokDeal->image))) {
            unlink(PathHelper::publicRootPath('images/products/' . $tiktokDeal->image));
        }

        $tiktokDeal->delete();

        return redirect()->route('admin.tiktok-deals.index')
            ->with('success', 'Deal Tiktok đã được xóa thành công!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(TiktokDeal $tiktokDeal)
    {
        $tiktokDeal->update(['is_active' => !$tiktokDeal->is_active]);

        return back()->with('success', 'Trạng thái đã được cập nhật!');
    }
}

