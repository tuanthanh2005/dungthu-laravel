<canvas id="tet-fireworks-canvas"></canvas>

<style>
    #tet-fireworks-canvas {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 99999;
        /* Đảm bảo nằm trên cùng nhưng không chắn click */
    }
</style>

<script>
    (function () {
        // Cấu hình pháo hoa
        const CONFIG = {
            particleCount: 80, // Số lượng hạt nổ
            decay: 0.95, // Độ mờ dần (càng cao càng lâu tan)
            gravity: 1, // Trọng lực
            flicker: false, // Nhấp nháy
            hue: { min: 0, max: 360 }, // Màu sắc (0-360 là full màu)
            delay: { min: 30, max: 60 }, // Tốc độ bắn (frame) - càng cao càng thưa
            rocketSpeed: 15, // Tốc độ bay lên
            baseRadius: 2 // Kích thước hạt
        };

        const canvas = document.getElementById('tet-fireworks-canvas');
        const ctx = canvas.getContext('2d');
        let width = window.innerWidth;
        let height = window.innerHeight;
        let particles = [];
        let rockets = [];

        // Resize canvas khi window thay đổi
        canvas.width = width;
        canvas.height = height;
        window.addEventListener('resize', () => {
            width = window.innerWidth;
            height = window.innerHeight;
            canvas.width = width;
            canvas.height = height;
        });

        // Hàm random
        function random(min, max) {
            return Math.random() * (max - min) + min;
        }

        // Lớp Rocket (Pháo bay lên)
        class Rocket {
            constructor() {
                this.x = random(width * 0.2, width * 0.8); // Bắn từ vùng giữa màn hình
                this.y = height;
                this.destY = random(height * 0.1, height * 0.5); // Điểm nổ (trên cao)
                this.speed = CONFIG.rocketSpeed;
                this.angle = -Math.PI / 2 + random(-0.2, 0.2); // Góc bắn hơi nghiêng
                this.vx = Math.cos(this.angle) * this.speed;
                this.vy = Math.sin(this.angle) * this.speed;
                this.hue = Math.floor(random(CONFIG.hue.min, CONFIG.hue.max));
                this.brightness = random(50, 80);
                this.exploded = false;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                // Giảm tốc khi lên cao (hiệu ứng trọng lực ảo cho rocket)
                this.vy *= 0.96; 
                this.vx *= 0.96;

                // Nếu đạt độ cao hoặc vận tốc quá chậm thì nổ
                if (this.y <= this.destY || Math.abs(this.vy) < 1) {
                    this.explode();
                    return false; // Rocket biến mất
                }
                return true; // Rocket còn sống
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, 3, 0, Math.PI * 2);
                ctx.fillStyle = `hsl(${this.hue}, 100%, ${this.brightness}%)`;
                ctx.fill();
                
                // Hiệu ứng đuôi mờ
                ctx.beginPath();
                ctx.moveTo(this.x, this.y);
                ctx.lineTo(this.x - this.vx * 3, this.y - this.vy * 3);
                ctx.strokeStyle = `hsla(${this.hue}, 100%, ${this.brightness}%, 0.3)`;
                ctx.stroke();
            }

            explode() {
                this.exploded = true;
                for (let i = 0; i < CONFIG.particleCount; i++) {
                    particles.push(new Particle(this.x, this.y, this.hue));
                }
                // Sound effect (Tùy chọn, hiện tại tắt cho "nhẹ nhàng")
            }
        }

        // Lớp Particle (Hạt nổ)
        class Particle {
            constructor(x, y, hue) {
                this.x = x;
                this.y = y;
                this.hue = hue;
                // Góc nổ tỏa tròn
                this.angle = random(0, Math.PI * 2);
                // Tốc độ nổ
                const speed = random(1, 8);
                this.vx = Math.cos(this.angle) * speed;
                this.vy = Math.sin(this.angle) * speed;
                // Thuộc tính vật lý
                this.friction = CONFIG.decay; // Ma sát không khí
                this.gravity = CONFIG.gravity * 0.1; // Trọng lực nhẹ
                this.alpha = 1; // Độ trong suốt
                this.decay = random(0.015, 0.03); // Tốc độ tan biến
                this.brightness = random(60, 100);
            }

            update() {
                this.vx *= this.friction;
                this.vy *= this.friction;
                this.vy += this.gravity;
                this.x += this.vx;
                this.y += this.vy;
                this.alpha -= this.decay;

                if (CONFIG.flicker) {
                    this.brightness = random(50, 100);
                }

                return this.alpha > 0;
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, CONFIG.baseRadius, 0, Math.PI * 2);
                ctx.fillStyle = `hsla(${this.hue}, 100%, ${this.brightness}%, ${this.alpha})`;
                ctx.fill();
            }
        }

        // Loop animation
        let timer = 0;
        let nextLaunch = 0;

        function loop() {
            // Xóa canvas với hiệu ứng mờ đuôi (trail effect)
            ctx.globalCompositeOperation = 'destination-out';
            ctx.fillStyle = 'rgba(0, 0, 0, 0.2)'; // 0.2 -> đuôi dài vừa phải
            ctx.fillRect(0, 0, width, height);
            ctx.globalCompositeOperation = 'lighter';

            // Update & Draw Rockets
            for (let i = rockets.length - 1; i >= 0; i--) {
                const r = rockets[i];
                if (!r.update()) {
                    rockets.splice(i, 1);
                } else {
                    r.draw();
                }
            }

            // Update & Draw Particles
            for (let i = particles.length - 1; i >= 0; i--) {
                const p = particles[i];
                if (!p.update()) {
                    particles.splice(i, 1);
                } else {
                    p.draw();
                }
            }

            // Bắn pháo hoa mới theo nhịp độ ngẫu nhiên
            if (timer >= nextLaunch) {
                rockets.push(new Rocket());
                timer = 0;
                nextLaunch = random(CONFIG.delay.min, CONFIG.delay.max);
            }
            timer++;

            requestAnimationFrame(loop);
        }

        // Start
        loop();
    })();
</script>
