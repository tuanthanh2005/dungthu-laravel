<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h4 class="fw-bold text-primary">DungThu.com</h4>
                <p class="small opacity-75">Nền tảng cung cấp giải pháp công nghệ, thời trang và công cụ Marketing miễn phí cho cộng đồng Việt Nam.</p>
            </div>
            <div class="col-md-4">
                <h5>Liên kết nhanh</h5>
                <ul class="list-unstyled opacity-75">
                    <li><a href="#" class="text-white text-decoration-none" data-bs-toggle="modal" data-bs-target="#aboutModal">Về chúng tôi</a></li>
                    <li><a href="#" class="text-white text-decoration-none" data-bs-toggle="modal" data-bs-target="#privacyModal">Chính sách bảo mật</a></li>
                    <li><a href="#" class="text-white text-decoration-none" data-bs-toggle="modal" data-bs-target="#advertisingModal">Liên hệ quảng cáo</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Đăng ký nhận tin</h5>
                <form id="newsletter-form">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email của bạn" required>
                        <button class="btn btn-primary" type="submit" id="subscribe-btn">Gửi</button>
                    </div>
                    <div id="newsletter-message" class="small mt-2"></div>
                </form>
            </div>
            <script>
                document.getElementById('newsletter-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const email = this.querySelector('input[name="email"]').value;
                    const btn = document.getElementById('subscribe-btn');
                    const message = document.getElementById('newsletter-message');
                    
                    btn.disabled = true;
                    btn.textContent = 'Đang gửi...';
                    
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
                        message.textContent = '❌ Lỗi kết nối';
                        message.style.color = '#dc3545';
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.textContent = 'Gửi';
                    });
                });
            </script>
        </div>
        <hr class="border-secondary">
        <div class="text-center small opacity-50">
            &copy; {{ date('Y') }} DungThu.com. All rights reserved.
        </div>
    </div>
</div>

<!-- About Modal -->
<div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                <h5 class="modal-title fw-bold" id="aboutModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Về Chúng Tôi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 15px; line-height: 1.8; color: #4a5568;">
                <h6 class="fw-bold mb-3" style="color: #667eea;">🎯 Sứ Mệnh Của Chúng Tôi</h6>
                <p>DungThu.com là một nền tảng cung cấp giải pháp công nghệ, thời trang và công cụ Marketing miễn phí cho cộng đồng Việt Nam. Chúng tôi cam kết mang lại giá trị tốt nhất cho khách hàng với sản phẩm chất lượng cao và giá cạnh tranh.</p>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">✨ Tại Sao Chọn DungThu?</h6>
                <ul class="ms-3">
                    <li><strong>Sản phẩm chất lượng:</strong> Tất cả sản phẩm được kiểm định chất lượng trước khi bán</li>
                    <li><strong>Giá cạnh tranh:</strong> Giá tốt nhất trên thị trường</li>
                    <li><strong>Hỗ trợ 24/7:</strong> Đội ngũ chuyên gia sẵn sàng hỗ trợ bạn</li>
                    <li><strong>An toàn:</strong> Bảo vệ thông tin cá nhân và giao dịch an toàn</li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">📊 Thành Tích</h6>
                <ul class="ms-3">
                    <li>👥 Hơn 5.000 khách hàng tin tưởng</li>
                    <li>📦 Hơn 1.000 đơn hàng thành công mỗi tháng</li>
                    <li>⭐ Đánh giá 4.8/5 từ khách hàng</li>
                </ul>

                <div class="alert alert-success mt-4" style="border-radius: 12px;">
                    <i class="fas fa-heart me-2"></i>
                    <strong>Cảm ơn bạn đã tin tưởng DungThu.com!</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                <h5 class="modal-title fw-bold" id="privacyModalLabel">
                    <i class="fas fa-shield-alt me-2"></i>Chính Sách Bảo Mật
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 15px; line-height: 1.8; color: #4a5568;">
                <h6 class="fw-bold mb-3" style="color: #667eea;">📋 Thông Tin Chúng Tôi Thu Thập</h6>
                <p>Chúng tôi thu thập những thông tin sau để phục vụ bạn tốt hơn:</p>
                <ul class="ms-3">
                    <li>Tên, email, số điện thoại khi bạn đăng ký</li>
                    <li>Địa chỉ giao hàng để xử lý đơn hàng</li>
                    <li>Lịch sử mua hàng và sở thích sản phẩm</li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">🔒 Bảo Vệ Thông Tin</h6>
                <p>Chúng tôi sử dụng mã hóa SSL/TLS cho tất cả giao tiếp và không chia sẻ thông tin cá nhân với bên thứ ba.</p>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">💾 Cách Sử Dụng Thông Tin</h6>
                <ul class="ms-3">
                    <li>Xử lý và giao hàng đơn hàng</li>
                    <li>Gửi thông báo về tình trạng đơn hàng</li>
                    <li>Cải thiện dịch vụ và sản phẩm</li>
                    <li>Tuân thủ pháp luật</li>
                </ul>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">👥 Quyền Của Bạn</h6>
                <ul class="ms-3">
                    <li>Truy cập và xem thông tin cá nhân</li>
                    <li>Chỉnh sửa hoặc cập nhật thông tin</li>
                    <li>Yêu cầu xóa tài khoản</li>
                </ul>

                <div class="alert alert-info mt-4" style="border-radius: 12px;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Cần hỗ trợ?</strong> Liên hệ email: tranthanhtuanfix@gmail.com
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advertising Modal -->
<div class="modal fade" id="advertisingModal" tabindex="-1" aria-labelledby="advertisingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                <h5 class="modal-title fw-bold" id="advertisingModalLabel">
                    <i class="fas fa-bullhorn me-2"></i>Liên Hệ Quảng Cáo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body p-4" style="font-size: 15px; line-height: 1.8; color: #4a5568;">
                <h6 class="fw-bold mb-3" style="color: #667eea;">📢 Cơ Hội Quảng Cáo</h6>
                <p>DungThu.com có hàng ngàn khách hàng tiềm năng hàng tháng. Chúng tôi cung cấp các giải pháp quảng cáo linh hoạt giúp thương hiệu của bạn tiếp cận khán giả chính xác.</p>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">🎯 Các Gói Quảng Cáo</h6>
                <div class="bg-light p-3 rounded-3 mb-3">
                    <strong>Gói Basic:</strong> 100.000đ/tháng<br>
                    <small>Banner trên trang chủ, hiển thị 30 ngày</small>
                </div>
                <div class="bg-light p-3 rounded-3 mb-3">
                    <strong>Gói Premium:</strong> 300.000đ/tháng<br>
                    <small>2 vị trí banner, quảng cáo email, báo cáo chi tiết</small>
                </div>
                <div class="bg-light p-3 rounded-3">
                    <strong>Gói Enterprise:</strong> Liên hệ để tư vấn<br>
                    <small>Giải pháp tùy chỉnh, partnership kéo dài</small>
                </div>

                <h6 class="fw-bold mb-3 mt-4" style="color: #667eea;">✨ Tại Sao Chọn Chúng Tôi?</h6>
                <ul class="ms-3">
                    <li>Khán giả chất lượng cao</li>
                    <li>Báo cáo chi tiết về hiệu suất</li>
                    <li>Giá cạnh tranh</li>
                    <li>Hỗ trợ 24/7</li>
                </ul>

                <div class="alert alert-primary mt-4" style="border-radius: 12px;">
                    <i class="fas fa-envelope me-2"></i>
                    <strong>Liên hệ quảng cáo:</strong> tranthanhtuanfix@gmail.com<br>
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
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none; padding: 25px 30px;">
                <h5 class="modal-title fw-bold" id="contactModalLabel">
                    <i class="fas fa-phone-alt me-2"></i>Liên Hệ Chúng Tôi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-center mb-4" style="font-size: 16px; line-height: 1.8;">
                    <strong>Hãy liên hệ với chúng tôi qua các kênh dưới đây:</strong>
                </p>

                <!-- Email Contact -->
                <a href="mailto:tranthanhtuanfix@gmail.com" class="d-block p-3 mb-3 rounded-3" style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%); border: 2px solid #f5576c; text-decoration: none; color: inherit; transition: all 0.3s ease;">
                    <div style="text-align: center;">
                        <i class="fas fa-envelope" style="font-size: 2rem; color: #f5576c; margin-bottom: 10px; display: block;"></i>
                        <h6 class="fw-bold mt-2 mb-1">📧 Email</h6>
                        <small style="color: #718096;">tranthanhtuanfix@gmail.com</small>
                    </div>
                </a>

                <!-- Telegram Contact -->
                <a href="https://t.me/specademy" target="_blank" class="d-block p-3 mb-3 rounded-3" style="background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%); border: 2px solid #0088cc; text-decoration: none; color: inherit; transition: all 0.3s ease;">
                    <div style="text-align: center;">
                        <i class="fab fa-telegram" style="font-size: 2rem; color: #0088cc; margin-bottom: 10px; display: block;"></i>
                        <h6 class="fw-bold mt-2 mb-1">💬 Telegram</h6>
                        <small style="color: #718096;">Chat trực tiếp với chúng tôi</small>
                    </div>
                </a>

                <!-- Zalo Contact -->
                <a href="https://zalo.me/0708910952" target="_blank" class="d-block p-3 mb-3 rounded-3" style="background: linear-gradient(135deg, #43e97b15 0%, #38f9d715 100%); border: 2px solid #0068ff; text-decoration: none; color: inherit; transition: all 0.3s ease;">
                    <div style="text-align: center;">
                        <i class="fas fa-comments" style="font-size: 2rem; color: #0068ff; margin-bottom: 10px; display: block;"></i>
                        <h6 class="fw-bold mt-2 mb-1">📱 Zalo</h6>
                        <small style="color: #718096;">Chat trực tiếp với chúng tôi</small>
                    </div>
                </a>

                <!-- Info Box -->
                <div class="alert alert-info mt-4 mb-0" style="border-radius: 12px;">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>
                        <strong>Giờ hoạt động:</strong> Tất cả các ngày, từ 8:00 AM - 10:00 PM<br>
                        <strong>Thời gian phản hồi:</strong> Trong vòng 1-2 giờ
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
