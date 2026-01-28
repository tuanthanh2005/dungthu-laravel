// 1. KHỞI TẠO ANIMATION
document.addEventListener('DOMContentLoaded', function () {
    AOS.init({
        duration: 800, // Tốc độ animation
        once: true // Chỉ chạy 1 lần khi cuộn xuống
    });

    // Swiper for mobile product sections
    if (window.Swiper) {
        const featuredEl = document.getElementById('featuredProductSwiper');
        if (featuredEl) {
            new Swiper(featuredEl, {
                slidesPerView: 2,
                spaceBetween: 10,
                pagination: { el: featuredEl.querySelector('.swiper-pagination'), clickable: true },
                breakpoints: {
                    360: { slidesPerView: 2, spaceBetween: 10 },
                    480: { slidesPerView: 2.1, spaceBetween: 12 },
                    640: { slidesPerView: 2.4, spaceBetween: 14 }
                }
            });
        }

        const highlightEl = document.getElementById('highlightProductSwiper');
        if (highlightEl) {
            new Swiper(highlightEl, {
                slidesPerView: 2,
                spaceBetween: 10,
                pagination: { el: highlightEl.querySelector('.swiper-pagination'), clickable: true },
                breakpoints: {
                    360: { slidesPerView: 2, spaceBetween: 10 },
                    480: { slidesPerView: 2.1, spaceBetween: 12 },
                    640: { slidesPerView: 2.4, spaceBetween: 14 }
                }
            });
        }
    }
});

// 2. HIỆU ỨNG GÕ CHỮ (TYPING EFFECT)
const textElement = document.getElementById('typewriter');
if (textElement) {
    const phrases = ["Mua Sắm Thả Ga.", "Mua AI Giá Rẻ.", "Đổi Thẻ Cào.", "Đọc Blog Hay."];
    let phraseIndex = 0;
    let charIndex = 0;
    let isDeleting = false;

    function type() {
        const currentPhrase = phrases[phraseIndex];

        if (isDeleting) {
            textElement.textContent = currentPhrase.substring(0, charIndex - 1);
            charIndex--;
        } else {
            textElement.textContent = currentPhrase.substring(0, charIndex + 1);
            charIndex++;
        }

        if (!isDeleting && charIndex === currentPhrase.length) {
            isDeleting = true;
            setTimeout(type, 2000); // Dừng lại đọc
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            phraseIndex = (phraseIndex + 1) % phrases.length;
            setTimeout(type, 500);
        } else {
            setTimeout(type, isDeleting ? 50 : 100);
        }
    }
    type(); // Chạy hàm
}

// 3. XỬ LÝ FILTER (Optional: Chuyển hướng sang trang shop với tham số category)
function filterData(category) {
    window.location.href = '/shop?category=' + category;
}
