<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Eramedia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 650px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            margin: 20px 0;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 2.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            position: relative;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .welcome-text {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9em;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-group input:hover,
        .form-group select:hover {
            border-color: #667eea;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 0.8em;
        }

        .strength-bar {
            width: 100%;
            height: 4px;
            background: #e1e5e9;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 5px;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak {
            background: #ff4757;
            width: 25%;
        }

        .strength-medium {
            background: #ffa502;
            width: 50%;
        }

        .strength-good {
            background: #2ed573;
            width: 75%;
        }

        .strength-strong {
            background: #1e90ff;
            width: 100%;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 25px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-top: 2px;
            transform: scale(1.2);
        }

        .checkbox-group label {
            font-size: 0.9em;
            color: #666;
            line-height: 1.4;
            margin-bottom: 0;
        }

        .checkbox-group a {
            color: #667eea;
            text-decoration: none;
        }

        .checkbox-group a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .register-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .register-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .register-btn:hover:not(:disabled)::before {
            left: 100%;
        }

        .register-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
            color: #999;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e1e5e9;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #764ba2;
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 40%;
            left: 80%;
            animation-delay: 4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .error-message {
            color: #ff4757;
            font-size: 0.8em;
            margin-top: 5px;
            display: none;
        }

        .success-message {
            background: #2ed573;
            color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 30px 20px;
            }

            .logo {
                font-size: 2em;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>

<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="register-container">
        <div class="logo-container">
            <div class="logo">Eramedia</div>
        </div>

        <p class="welcome-text">Bergabunglah dengan komunitas kami!</p>

        <div class="success-message" id="successMessage">
            Akun berhasil dibuat! Silakan cek email Anda untuk verifikasi.
        </div>

        <form id="registerForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">Nama Depan</label>
                    <input type="text" id="firstName" name="firstName" required>
                    <div class="error-message" id="firstNameError"></div>
                </div>

                <div class="form-group">
                    <label for="lastName">Nama Belakang</label>
                    <input type="text" id="lastName" name="lastName" required>
                    <div class="error-message" id="lastNameError"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <div class="error-message" id="usernameError"></div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <div class="error-message" id="emailError"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" required>
                    <div class="error-message" id="phoneError"></div>
                </div>

                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    <select id="gender" name="gender" required>
                        <option value="">Pilih...</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                    <div class="error-message" id="genderError"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <span id="strengthText">Masukkan password</span>
                </div>
                <div class="error-message" id="passwordError"></div>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Konfirmasi Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <div class="error-message" id="confirmPasswordError"></div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    Saya menyetujui <a href="#" onclick="showTerms()">Syarat dan Ketentuan</a>
                    serta <a href="#" onclick="showPrivacy()">Kebijakan Privasi</a>
                </label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="newsletter" name="newsletter">
                <label for="newsletter">
                    Saya ingin menerima newsletter dan update dari Eramedia
                </label>
            </div>

            <button type="submit" class="register-btn" id="registerBtn">Daftar Sekarang</button>
        </form>

        <div class="divider">
            <span>atau</span>
        </div>

        <div class="login-link">
            Sudah punya akun? <a href="#" onclick="showLogin()">Masuk di sini</a>
        </div>
    </div>

    <script>
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            const strengthClasses = ['strength-weak', 'strength-medium', 'strength-good', 'strength-strong'];
            const strengthTexts = ['Lemah', 'Sedang', 'Baik', 'Kuat'];

            strengthFill.className = 'strength-fill';

            if (password.length === 0) {
                strengthText.textContent = 'Masukkan password';
                return;
            }

            if (strength <= 2) {
                strengthFill.classList.add('strength-weak');
                strengthText.textContent = 'Lemah';
            } else if (strength === 3) {
                strengthFill.classList.add('strength-medium');
                strengthText.textContent = 'Sedang';
            } else if (strength === 4) {
                strengthFill.classList.add('strength-good');
                strengthText.textContent = 'Baik';
            } else {
                strengthFill.classList.add('strength-strong');
                strengthText.textContent = 'Kuat';
            }

            return strength;
        }

        // Password input listener
        document.getElementById('password').addEventListener('input', function(e) {
            checkPasswordStrength(e.target.value);
        });

        // Form validation
        function validateForm() {
            let isValid = true;

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(error => {
                error.style.display = 'none';
            });

            // Validate required fields
            const requiredFields = ['firstName', 'lastName', 'username', 'email', 'phone', 'gender', 'password',
                'confirmPassword'
            ];

            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                const error = document.getElementById(field + 'Error');

                if (!input.value.trim()) {
                    error.textContent = 'Field ini wajib diisi';
                    error.style.display = 'block';
                    isValid = false;
                }
            });

            // Validate email format
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                document.getElementById('emailError').textContent = 'Format email tidak valid';
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            }

            // Validate username
            const username = document.getElementById('username').value;
            if (username && username.length < 3) {
                document.getElementById('usernameError').textContent = 'Username minimal 3 karakter';
                document.getElementById('usernameError').style.display = 'block';
                isValid = false;
            }

            // Validate phone number
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^[0-9+\-\s()]+$/;
            if (phone && !phoneRegex.test(phone)) {
                document.getElementById('phoneError').textContent = 'Format nomor telepon tidak valid';
                document.getElementById('phoneError').style.display = 'block';
                isValid = false;
            }

            // Validate password strength
            const password = document.getElementById('password').value;
            if (password && checkPasswordStrength(password) < 3) {
                document.getElementById('passwordError').textContent = 'Password terlalu lemah';
                document.getElementById('passwordError').style.display = 'block';
                isValid = false;
            }

            // Validate password confirmation
            const confirmPassword = document.getElementById('confirmPassword').value;
            if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'Password tidak cocok';
                document.getElementById('confirmPasswordError').style.display = 'block';
                isValid = false;
            }

            // Validate terms acceptance
            const terms = document.getElementById('terms').checked;
            if (!terms) {
                alert('Anda harus menyetujui syarat dan ketentuan');
                isValid = false;
            }

            return isValid;
        }

        // Form submission handler
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!validateForm()) {
                return;
            }

            // Show loading state
            const registerBtn = document.getElementById('registerBtn');
            registerBtn.textContent = 'Memproses...';
            registerBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                document.getElementById('successMessage').style.display = 'block';
                document.getElementById('registerForm').reset();

                registerBtn.textContent = 'Daftar Sekarang';
                registerBtn.disabled = false;

                // Reset password strength indicator
                document.getElementById('strengthFill').className = 'strength-fill';
                document.getElementById('strengthText').textContent = 'Masukkan password';

                // Hide success message after 5 seconds
                setTimeout(() => {
                    document.getElementById('successMessage').style.display = 'none';
                }, 5000);
            }, 2000);
        });

        // Input animation effects
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Terms and conditions handler
        function showTerms() {
            alert('Syarat dan Ketentuan Eramedia akan ditampilkan di sini.');
        }

        // Privacy policy handler
        function showPrivacy() {
            alert('Kebijakan Privasi Eramedia akan ditampilkan di sini.');
        }

        // Login handler
        function showLogin() {
            alert('Mengarahkan ke halaman login...');
        }

        // Add subtle parallax effect to floating shapes
        document.addEventListener('mousemove', function(e) {
            const shapes = document.querySelectorAll('.shape');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;

            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 0.5;
                const x = (mouseX - 0.5) * speed * 20;
                const y = (mouseY - 0.5) * speed * 20;

                shape.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    </script>
</body>

</html>
