<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 - Akses Ditolak</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            color: #2c3e50;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            padding: 2rem;
            overflow: hidden;
        }

        .container {
            max-width: 800px;
            position: relative;
            animation: fadeIn 0.8s ease-out;
        }

        .error-image {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto 2rem;
        }

        .police {
            animation: wobble 3s ease-in-out infinite;
            transform-origin: bottom center;
        }

        .sign {
            animation: float 4s ease-in-out infinite;
        }

        h1 {
            font-size: 5rem;
            font-weight: 800;
            background: linear-gradient(90deg, #1e88e5, #0d47a1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
            position: relative;
        }

        h1::after {
            content: "";
            position: absolute;
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #1e88e5, #0d47a1);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        p {
            font-size: 1.4rem;
            margin-bottom: 2.5rem;
            color: #455a64;
            animation: slideUp 0.8s ease-out 0.3s both;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(90deg, #1e88e5, #0d47a1);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(14, 102, 209, 0.3);
            position: relative;
            overflow: hidden;
            animation: slideUp 0.8s ease-out 0.5s both;
            z-index: 1;
        }

        .btn:before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(14, 102, 209, 0.4);
        }

        .btn:hover:before {
            left: 100%;
        }

        .barriers {
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            transform: translateY(0);
            animation: slideUp 0.8s ease-out 0.2s both;
        }

        /* Particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background-color: #1e88e5;
            border-radius: 50%;
            animation: float-particles 15s linear infinite;
            opacity: 0.5;
        }

        .particle:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-duration: 20s;
            width: 8px;
            height: 8px;
        }

        .particle:nth-child(2) {
            top: 20%;
            left: 80%;
            animation-duration: 18s;
            animation-delay: 1s;
        }

        .particle:nth-child(3) {
            top: 80%;
            left: 15%;
            animation-duration: 17s;
            animation-delay: 2s;
        }

        .particle:nth-child(4) {
            top: 40%;
            left: 40%;
            animation-duration: 15s;
            animation-delay: 3s;
            width: 7px;
            height: 7px;
        }

        .particle:nth-child(5) {
            top: 70%;
            left: 70%;
            animation-duration: 19s;
            animation-delay: 4s;
        }

        .particle:nth-child(6) {
            top: 30%;
            left: 90%;
            animation-duration: 21s;
            animation-delay: 5s;
            width: 9px;
            height: 9px;
        }

        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes wobble {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-2deg); }
            75% { transform: rotate(2deg); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes float-particles {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.5;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            h1 {
                font-size: 3.5rem;
            }

            p {
                font-size: 1.2rem;
            }

            .error-image {
                max-width: 300px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 2.8rem;
            }

            p {
                font-size: 1rem;
            }

            .error-image {
                max-width: 220px;
            }
        }
    </style>
</head>
<body>
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="container">
        <div class="error-image">
            <svg viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
                <!-- Sign -->
                <g class="sign">
                    <rect x="200" y="50" width="100" height="80" rx="5" fill="#1e88e5" />
                    <rect x="210" y="60" width="80" height="60" rx="3" fill="#fff" />
                    <text x="250" y="85" font-family="Arial" font-size="24" font-weight="bold" fill="#1e88e5" text-anchor="middle">403</text>
                    <text x="250" y="105" font-family="Arial" font-size="12" font-weight="bold" fill="#1e88e5" text-anchor="middle">FORBIDDEN</text>
                    <rect x="248" y="130" width="4" height="60" fill="#444" />
                </g>

                <!-- Police officer -->
                <g class="police">
                    <!-- Body -->
                    <rect x="230" y="260" width="40" height="60" rx="5" fill="#333" />
                    <rect x="220" y="320" width="60" height="10" fill="#333" />

                    <!-- Head -->
                    <circle cx="250" cy="240" r="20" fill="#ffd699" />

                    <!-- Cap -->
                    <path d="M230 235 Q250 220 270 235 L270 240 Q250 225 230 240 Z" fill="#1e88e5" />
                    <rect x="235" y="235" width="30" height="5" fill="#1e88e5" />
                    <circle cx="250" cy="232" r="3" fill="#ffcc00" />

                    <!-- Arms -->
                    <rect x="200" y="270" width="30" height="10" rx="5" fill="#333" />
                    <rect x="270" y="270" width="40" height="10" rx="5" fill="#333" />

                    <!-- Hand holding tablet -->
                    <rect x="300" y="260" width="20" height="30" rx="2" fill="#777" />

                    <!-- Legs -->
                    <rect x="230" y="330" width="10" height="40" rx="5" fill="#1e88e5" />
                    <rect x="260" y="330" width="10" height="40" rx="5" fill="#1e88e5" />

                    <!-- Belt -->
                    <rect x="230" y="260" width="40" height="5" fill="#000" />
                    <rect x="247" y="260" width="6" height="5" fill="#ffcc00" />
                </g>

                <!-- Barriers -->
                <g class="barriers">
                    <!-- Traffic cones -->
                    <path d="M150 370 L160 330 L170 370 Z" fill="#ff6b00" />
                    <rect x="155" y="350" width="10" height="2" fill="#fff" />
                    <rect x="155" y="360" width="10" height="2" fill="#fff" />

                    <path d="M330 370 L340 330 L350 370 Z" fill="#ff6b00" />
                    <rect x="335" y="350" width="10" height="2" fill="#fff" />
                    <rect x="335" y="360" width="10" height="2" fill="#fff" />

                    <!-- Barrier -->
                    <rect x="180" y="350" width="140" height="15" fill="#ffcc00" />
                    <rect x="180" y="350" width="140" height="5" fill="#ff6b00" />
                    <rect x="185" y="335" width="10" height="15" fill="#ddd" />
                    <rect x="305" y="335" width="10" height="15" fill="#ddd" />

                    <!-- Stripes -->
                    <rect x="190" y="355" width="15" height="5" fill="#333" />
                    <rect x="220" y="355" width="15" height="5" fill="#333" />
                    <rect x="250" y="355" width="15" height="5" fill="#333" />
                    <rect x="280" y="355" width="15" height="5" fill="#333" />
                </g>
            </svg>
        </div>

        <h1>403</h1>
        <p>Oops! Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="#" class="btn">Kembali ke Beranda</a>
    </div>

    <script>
        // Add hover animation for buttons
        document.querySelector('.btn').addEventListener('mouseover', function() {
            this.style.transition = 'all 0.3s ease';
        });

        // Add random movement to particles
        const particles = document.querySelectorAll('.particle');
        particles.forEach(particle => {
            const randomLeft = Math.floor(Math.random() * 100);
            particle.style.left = `${randomLeft}%`;
            particle.style.animationDelay = `${Math.random() * 5}s`;
        });
    </script>
</body>
</html>
