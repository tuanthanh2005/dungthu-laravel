<footer class="footer-techfeed">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="footer-brand">
                    <div class="brand-icon"><i class="fa-solid fa-bolt"></i></div>
                    DungThu.com
                </div>
                <p class="small text-muted">{{ __('Nền tảng cung cấp giải pháp công nghệ, công cụ Marketing và sản phẩm số chất lượng cho cộng đồng Việt Nam.') }}</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">{{ __('Liên kết nhanh') }}</h6>
                <ul class="footer-links">
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#aboutModal">{{ __('Về chúng tôi') }}</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">{{ __('Chính sách bảo mật') }}</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#advertisingModal">{{ __('Liên hệ quảng cáo') }}</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#contactModal">{{ __('Liên hệ ngay') }}</a></li>
                    <li><a href="{{ route('shop') }}">{{ __('Cửa hàng') }}</a></li>
                    <li><a href="{{ route('blog.index') }}">{{ __('Blog') }}</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">{{ __('Sản phẩm nổi bật') }}</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('product.keyword', 'gpt') }}">{{ __('Mua tài khoản ChatGPT') }}</a></li>
                    <li><a href="{{ route('product.keyword', 'cursor') }}">{{ __('Mua tài khoản Cursor AI Pro') }}</a></li>
                    <li><a href="{{ route('product.keyword', 'gemini') }}">{{ __('Mua tài khoản Gemini Advanced') }}</a></li>
                    <li><a href="{{ route('product.keyword', 'youtube') }}">{{ __('Mua YouTube Premium') }}</a></li>
                    <li><a href="{{ route('product.keyword', 'office') }}">{{ __('Mua Office 365') }}</a></li>
                    <li><a href="{{ route('product.keyword', 'canva') }}">{{ __('Mua Canva Pro') }}</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-3">{{ __('Blog & nhận tin') }}</h6>
                <ul class="footer-links mb-3">
                    <li><a href="{{ route('blog.topic', 'ai') }}">{{ __('Blog AI') }}</a></li>
                    <li><a href="{{ route('blog.topic', 'chatgpt') }}">{{ __('Hướng dẫn ChatGPT') }}</a></li>
                    <li><a href="{{ route('blog.topic', 'cursor') }}">{{ __('Hướng dẫn Cursor AI') }}</a></li>
                </ul>
                <form id="newsletter-form">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('Email của bạn') }}" required>
                        <button class="btn btn-primary" type="submit" id="subscribe-btn">{{ __('Gửi') }}</button>
                    </div>
                    <div id="newsletter-message" class="small mt-2"></div>
                </form>
            </div>
            <script>
                document.getElementById('newsletter-form').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const email = this.querySelector('input[name="email"]').value;
                    const btn = document.getElementById('subscribe-btn');
                    const message = document.getElementById('newsletter-message');

                    btn.disabled = true;
                    btn.textContent = @json(__('Đang gửi...'));

                    fetch('{{ route("newsletter.subscribe") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ email: email })
                    })
                        .then(response => response.json())
                        .then(data => {
                            message.textContent = data.message;
                            message.style.color = data.success ? '#28a745' : '#dc3545';
                            if (data.success) {
                                this.reset();
                            }
                        })
                        .catch(error => {
                            message.textContent = '❌ ' + @json(__('Lỗi kết nối'));
                            message.style.color = '#dc3545';
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.textContent = @json(__('Gửi'));
                        });
                });
            </script>
        </div>
        <div class="footer-copy">{{ date('Y') }} DungThu.com &mdash; Made with <i
                class="fa-solid fa-heart text-danger"></i> in Vietnam</div>
    </div>
    </div>

    <!-- About Modal -->
    <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                    <h5 class="modal-title fw-bold" id="aboutModalLabel">
                        <i class="fas fa-info-circle me-2"></i>{{ __('Về Chúng Tôi') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body p-4" style="font-size: 15px; line-height: 1.8; color: #4a5568;">
                    <h6 class="fw-bold mb-3" style="color: #667eea;">{{ __('🎯 Sứ Mệnh Của Chúng Tôi') }}</h6>
                    <p>{{ __('DungThu.com là một nền tảng cung cấp giải pháp công nghệ, thời trang và công cụ Marketing miễn phí cho cộng đồng Việt Nam. Chúng tôi cam kết mang lại giá trị tốt nhất cho khách hàng với sản phẩm chất lượng cao và giá cạnh tranh.') }}</p>

                    <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">{{ __('✨ Tại Sao Chọn DungThu?') }}</h6>
                    <ul class="ms-3">
                        <li><strong>{{ __('Sản phẩm chất lượng:') }}</strong> {{ __('Tất cả sản phẩm được kiểm định chất lượng trước khi bán') }}</li>
                        <li><strong>{{ __('Giá cạnh tranh:') }}</strong> {{ __('Giá tốt nhất trên thị trường') }}</li>
                        <li><strong>{{ __('Hỗ trợ 24/7:') }}</strong> {{ __('Đội ngũ chuyên gia sẵn sàng hỗ trợ bạn') }}</li>
                        <li><strong>{{ __('An toàn:') }}</strong> {{ __('Bảo vệ thông tin cá nhân và giao dịch an toàn') }}</li>
                    </ul>

                    <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">{{ __('📊 Thành Tích') }}</h6>
                    <ul class="ms-3">
                        <li>{{ __('👥 Hơn 5.000 khách hàng tin tưởng') }}</li>
                        <li>{{ __('📦 Hơn 1.000 đơn hàng thành công mỗi tháng') }}</li>
                        <li>{{ __('⭐ Đánh giá 4.8/5 từ khách hàng') }}</li>
                    </ul>

                    <div class="alert alert-success mt-4" style="border-radius: 12px;">
                        <i class="fas fa-heart me-2"></i>
                        <strong>{{ __('Cảm ơn bạn đã tin tưởng DungThu.com!') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                    <h5 class="modal-title fw-bold" id="privacyModalLabel">
                        <i class="fas fa-shield-alt me-2"></i>{{ __('Chính Sách Bảo Mật') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body p-4" style="font-size: 15px; line-height: 1.8; color: #4a5568;">
                    <h6 class="fw-bold mb-3" style="color: #667eea;">{{ __('📋 Thông Tin Chúng Tôi Thu Thập') }}</h6>
                    <p>{{ __('Chúng tôi thu thập những thông tin sau để phục vụ bạn tốt hơn:') }}</p>
                    <ul class="ms-3">
                        <li>{{ __('Tên, email, số điện thoại khi bạn đăng ký') }}</li>
                        <li>{{ __('Địa chỉ giao hàng để xử lý đơn hàng') }}</li>
                        <li>{{ __('Lịch sử mua hàng và sở thích sản phẩm') }}</li>
                    </ul>

                    <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">{{ __('🔒 Bảo Vệ Thông Tin') }}</h6>
                    <p>{{ __('Chúng tôi sử dụng mã hóa SSL/TLS cho tất cả giao tiếp và không chia sẻ thông tin cá nhân với bên thứ ba.') }}</p>

                    <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">{{ __('💾 Cách Sử Dụng Thông Tin') }}</h6>
                    <ul class="ms-3">
                        <li>{{ __('Xử lý và giao hàng đơn hàng') }}</li>
                        <li>{{ __('Gửi thông báo về tình trạng đơn hàng') }}</li>
                        <li>{{ __('Cải thiện dịch vụ và sản phẩm') }}</li>
                        <li>{{ __('Tuân thủ pháp luật') }}</li>
                    </ul>

                    <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">{{ __('👥 Quyền Của Bạn') }}</h6>
                    <ul class="ms-3">
                        <li>{{ __('Truy cập và xem thông tin cá nhân') }}</li>
                        <li>{{ __('Chỉnh sửa hoặc cập nhật thông tin') }}</li>
                        <li>{{ __('Yêu cầu xóa tài khoản') }}</li>
                    </ul>

                    <div class="alert alert-info mt-4" style="border-radius: 12px;">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>{{ __('Cần hỗ trợ?') }}</strong> {{ __('Liên hệ email: tranthanhtuanfix@gmail.com') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advertising Modal -->
    <div class="modal fade" id="advertisingModal" tabindex="-1" aria-labelledby="advertisingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                    <h5 class="modal-title fw-bold" id="advertisingModalLabel">
                        <i class="fas fa-bullhorn me-2"></i>{{ __('Liên Hệ Quảng Cáo') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body p-4" style="font-size: 15px; line-height: 1.8; color: #4a5568;">
                    <h6 class="fw-bold mb-3" style="color: #667eea;">{{ __('📢 Cơ Hội Quảng Cáo') }}</h6>
                    <p>{{ __('DungThu.com có hàng ngàn khách hàng tiềm năng hàng tháng. Chúng tôi cung cấp các giải pháp quảng cáo linh hoạt giúp thương hiệu của bạn tiếp cận khán giả chính xác.') }}</p>

                    <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">{{ __('🎯 Các Gói Quảng Cáo') }}</h6>
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <strong>{{ __('Gói Basic:') }}</strong> {{ __('100.000đ/tháng') }}<br>
                        <small>{{ __('Banner trên trang chủ, hiển thị 30 ngày') }}</small>
                    </div>
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <strong>{{ __('Gói Premium:') }}</strong> {{ __('300.000đ/tháng') }}<br>
                        <small>{{ __('2 vị trí banner, quảng cáo email, báo cáo chi tiết') }}</small>
                    </div>
                    <div class="bg-light p-3 rounded-3">
                        <strong>{{ __('Gói Enterprise:') }}</strong> {{ __('Liên hệ để tư vấn') }}<br>
                        <small>{{ __('Giải pháp tùy chỉnh, partnership kéo dài') }}</small>
                    </div>

                    <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">{{ __('✨ Tại Sao Chọn Chúng Tôi?') }}</h6>
                    <ul class="ms-3">
                        <li>{{ __('Khán giả chất lượng cao') }}</li>
                        <li>{{ __('Báo cáo chi tiết về hiệu suất') }}</li>
                        <li>{{ __('Giá cạnh tranh') }}</li>
                        <li>{{ __('Hỗ trợ 24/7') }}</li>
                    </ul>

                    <div class="alert alert-primary mt-4" style="border-radius: 12px;">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>{{ __('Liên hệ quảng cáo:') }}</strong> tranthanhtuanfix@gmail.com<br>
                        <i class="fab fa-telegram me-2"></i>
                        <strong>Telegram:</strong> @specademy
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                    <h5 class="modal-title fw-bold" id="contactModalLabel">
                        <i class="fas fa-phone-alt me-2"></i>{{ __('Liên Hệ Chúng Tôi') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="filter: brightness(0) invert(1);"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-center mb-4" style="font-size: 16px; line-height: 1.8;">
                        <strong>{{ __('Hãy liên hệ với chúng tôi qua các kênh dưới đây:') }}</strong>
                    </p>

                    <!-- Email Contact -->
                    <a href="mailto:tranthanhtuanfix@gmail.com" class="d-block p-3 mb-3 rounded-3"
                        style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%); border: 2px solid #f5576c; text-decoration: none; color: inherit; transition: all 0.3s ease;">
                        <div style="text-align: center;">
                            <i class="fas fa-envelope"
                                style="font-size: 2rem; color: #f5576c; margin-bottom: 10px; display: block;"></i>
                            <h6 class="fw-bold mt-2 mb-1">📧 Email</h6>
                            <small style="color: #718096;">tranthanhtuanfix@gmail.com</small>
                        </div>
                    </a>

                    <!-- Telegram Contact -->
                    <a href="https://t.me/specademy" target="_blank" class="d-block p-3 mb-3 rounded-3"
                        style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%); border: 2px solid #0088cc; text-decoration: none; color: inherit; transition: all 0.3s ease;">
                        <div style="text-align: center;">
                            <i class="fab fa-telegram"
                                style="font-size: 2rem; color: #0088cc; margin-bottom: 10px; display: block;"></i>
                            <h6 class="fw-bold mt-2 mb-1">💬 Telegram</h6>
                            <small style="color: #718096;">{{ __('Chat trực tiếp với chúng tôi') }}</small>
                        </div>
                    </a>

                    <!-- Zalo Contact -->
                    <a href="https://zalo.me/0708910952" target="_blank" class="d-block p-3 mb-3 rounded-3"
                        style="background: linear-gradient(135deg, #43e97b15 0%, #38f9d715 100%); border: 2px solid #0068ff; text-decoration: none; color: inherit; transition: all 0.3s ease;">
                        <div style="text-align: center;">
                            <i class="fas fa-comments"
                                style="font-size: 2rem; color: #0068ff; margin-bottom: 10px; display: block;"></i>
                            <h6 class="fw-bold mt-2 mb-1">📱 Zalo</h6>
                            <small style="color: #718096;">{{ __('Chat trực tiếp với chúng tôi') }}</small>
                        </div>
                    </a>

                    <!-- Info Box -->
                    <div class="alert alert-info mt-4 mb-0" style="border-radius: 12px;">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>
                            <strong>{{ __('Giờ hoạt động:') }}</strong> {{ __('Tất cả các ngày, từ 8:00 AM - 10:00 PM') }}<br>
                            <strong>{{ __('Thời gian phản hồi:') }}</strong> {{ __('Trong vòng 1-2 giờ') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
