<!-- Reviews Tab -->
<div class="tab-pane fade" id="reviews" role="tabpanel" data-aos="fade-up">
    <div class="card border-0 shadow-sm rounded-4" style="background: white;">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-comments text-warning me-2"></i>Đánh Giá Sản Phẩm
            </h4>
            
            <!-- Overall Rating -->
            <div class="text-center mb-5 p-4 bg-light rounded-4">
                <div class="display-4 fw-bold text-primary mb-2">{{ number_format($averageRating, 1) }}</div>
                <div class="mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($averageRating))
                            <i class="fas fa-star text-warning"></i>
                        @elseif($i - $averageRating < 1)
                            <i class="fas fa-star-half-alt text-warning"></i>
                        @else
                            <i class="far fa-star text-warning"></i>
                        @endif
                    @endfor
                </div>
                <p class="text-muted mb-0">Dựa trên <strong>{{ $totalReviews }} đánh giá</strong></p>
            </div>

            <!-- Comment Form (Only for logged in users) -->
            @auth
            <div class="card bg-light border-0 mb-4 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-edit text-primary me-2"></i>Viết đánh giá của bạn
                    </h5>
                    <form action="{{ route('product.comment', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đánh giá của bạn <span class="text-danger">*</span></label>
                            <div class="rating-input mb-2">
                                <input type="radio" name="rating" value="5" id="star5" required>
                                <label for="star5" title="5 sao"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4" title="4 sao"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3" title="3 sao"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2" title="2 sao"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1" title="1 sao"><i class="fas fa-star"></i></label>
                            </div>
                            @error('rating')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nhận xét <span class="text-danger">*</span></label>
                            <textarea name="comment" class="form-control rounded-3" rows="4" 
                                      placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..." required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-info rounded-4 mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Bạn cần <a href="{{ route('login') }}" class="alert-link fw-bold">đăng nhập</a> để viết đánh giá.
            </div>
            @endauth

            <!-- Individual Reviews -->
            @forelse($product->comments as $comment)
            <div class="review-item mb-4 pb-4 border-bottom">
                <div class="d-flex align-items-start">
                    <div class="avatar me-3">
                        <div class="bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][($comment->id) % 5] }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px; font-weight: bold;">
                            {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">{{ $comment->user->name }}</h6>
                            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $comment->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted mb-0">{{ $comment->comment }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
