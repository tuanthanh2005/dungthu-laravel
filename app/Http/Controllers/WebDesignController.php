<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\WebDesignPackage;

class WebDesignController extends Controller
{
    /**
     * Hiển thị trang giới thiệu và bảng giá dịch vụ Thiết kế website
     */
    public function index()
    {
        // Phân trang 6 gói mỗi trang (2 hàng x 3 gói). Gói thứ 7 trở đi sẽ sang trang 2.
        $packages = WebDesignPackage::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(6);

        $heroTitle    = SiteSetting::getValue('web_design_hero_title', 'Thiết kế website giá rẻ');
        $heroSubtitle = SiteSetting::getValue('web_design_hero_subtitle', 'Trao đổi nhanh, chốt trong 1-2 tiếng. Thiết kế chuẩn SEO, tối ưu mobile, bàn giao nhanh.');
        $serviceTag   = SiteSetting::getValue('web_design_service_tag', 'Dịch vụ');
        $serviceTitle = SiteSetting::getValue('web_design_service_title', 'Thiết kế website giá rẻ');
        $serviceDesc  = SiteSetting::getValue('web_design_service_desc', 'Chỉ nhận: website bán hàng, website blog, website tin tức. Vui lòng liên hệ qua Zalo hoặc Facebook. Thời gian thiết kế 3-14 ngày tùy độ phức tạp. Tên domain và hosting shop sẽ đứng hộ để bảo trì nâng cấp.');
        $btnText      = SiteSetting::getValue('web_design_btn_text', 'Nhận tư vấn');

        return view('pages.web-design', compact(
            'packages',
            'heroTitle',
            'heroSubtitle',
            'serviceTag',
            'serviceTitle',
            'serviceDesc',
            'btnText'
        ));
    }
}
