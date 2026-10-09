<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\WebDesignPackage;
use Illuminate\Http\Request;

class AdminWebDesignController extends Controller
{
    /**
     * Hiển thị trang quản lý thiết kế website (Cài đặt nội dung + Quản lý gói)
     */
    public function index()
    {
        $packages = WebDesignPackage::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        $settings = [
            'hero_title'     => SiteSetting::getValue('web_design_hero_title', 'Thiết kế website giá rẻ'),
            'hero_subtitle'  => SiteSetting::getValue('web_design_hero_subtitle', 'Trao đổi nhanh, chốt trong 1-2 tiếng. Thiết kế chuẩn SEO, tối ưu mobile, bàn giao nhanh.'),
            'service_tag'    => SiteSetting::getValue('web_design_service_tag', 'Dịch vụ'),
            'service_title'  => SiteSetting::getValue('web_design_service_title', 'Thiết kế website giá rẻ'),
            'service_desc'   => SiteSetting::getValue('web_design_service_desc', 'Chỉ nhận: website bán hàng, website blog, website tin tức. Vui lòng liên hệ qua Zalo hoặc Facebook. Thời gian thiết kế 3-14 ngày tùy độ phức tạp. Tên domain và hosting shop sẽ đứng hộ để bảo trì nâng cấp.'),
            'btn_text'       => SiteSetting::getValue('web_design_btn_text', 'Nhận tư vấn'),
        ];

        return view('admin.web-design.index', compact('packages', 'settings'));
    }

    /**
     * Lưu cài đặt thông tin văn bản trang Thiết kế website
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'web_design_hero_title'    => 'required|string|max:255',
            'web_design_hero_subtitle' => 'nullable|string|max:500',
            'web_design_service_tag'   => 'nullable|string|max:100',
            'web_design_service_title' => 'required|string|max:255',
            'web_design_service_desc'  => 'nullable|string|max:1000',
            'web_design_btn_text'      => 'nullable|string|max:100',
        ]);

        SiteSetting::setValue('web_design_hero_title', $request->input('web_design_hero_title'));
        SiteSetting::setValue('web_design_hero_subtitle', $request->input('web_design_hero_subtitle'));
        SiteSetting::setValue('web_design_service_tag', $request->input('web_design_service_tag'));
        SiteSetting::setValue('web_design_service_title', $request->input('web_design_service_title'));
        SiteSetting::setValue('web_design_service_desc', $request->input('web_design_service_desc'));
        SiteSetting::setValue('web_design_btn_text', $request->input('web_design_btn_text', 'Nhận tư vấn'));

        return redirect()->route('admin.web-design.index')->with('success', 'Đã cập nhật nội dung trang thiết kế website thành công!');
    }

    /**
     * Thêm gói thiết kế website mới
     */
    public function storePackage(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'badge'       => 'nullable|string|max:100',
            'badge_color' => 'nullable|string|max:50',
            'features'    => 'nullable|string',
            'sort_order'  => 'nullable|integer',
        ]);

        $features = $this->parseFeatures($request->input('features', ''));

        WebDesignPackage::create([
            'name'        => $request->input('name'),
            'price'       => (int) $request->input('price'),
            'badge'       => $request->input('badge'),
            'badge_color' => $request->input('badge_color', 'primary'),
            'features'    => $features,
            'sort_order'  => (int) $request->input('sort_order', 0),
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.web-design.index')->with('success', 'Đã thêm gói thiết kế website mới thành công!');
    }

    /**
     * Cập nhật thông tin gói thiết kế website
     */
    public function updatePackage(Request $request, WebDesignPackage $package)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'badge'       => 'nullable|string|max:100',
            'badge_color' => 'nullable|string|max:50',
            'features'    => 'nullable|string',
            'sort_order'  => 'nullable|integer',
        ]);

        $features = $this->parseFeatures($request->input('features', ''));

        $package->update([
            'name'        => $request->input('name'),
            'price'       => (int) $request->input('price'),
            'badge'       => $request->input('badge'),
            'badge_color' => $request->input('badge_color', 'primary'),
            'features'    => $features,
            'sort_order'  => (int) $request->input('sort_order', 0),
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('admin.web-design.index')->with('success', 'Đã cập nhật gói "' . $package->name . '" thành công!');
    }

    /**
     * Xóa gói thiết kế website
     */
    public function destroyPackage(WebDesignPackage $package)
    {
        $name = $package->name;
        $package->delete();

        return redirect()->route('admin.web-design.index')->with('success', 'Đã xóa gói "' . $name . '" thành công!');
    }

    /**
     * Chuyển đổi trạng thái Bật/Tắt gói
     */
    public function toggleStatus(WebDesignPackage $package)
    {
        $package->is_active = !$package->is_active;
        $package->save();

        return redirect()->route('admin.web-design.index')->with('success', 'Đã đổi trạng thái gói "' . $package->name . '" thành công!');
    }

    /**
     * Tách danh sách tính năng theo từng dòng
     */
    private function parseFeatures(string $text): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $features = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed !== '') {
                $features[] = $trimmed;
            }
        }

        return $features;
    }
}
