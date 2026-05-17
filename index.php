<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>QuizLab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #1B0824;
            color: #F8FAFC;
            font-family: Arial, sans-serif;
        }

        /* NAVBAR */
        .navbar {
            padding: 18px 0;
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: bold;
            color: #fff;
        }

        .navbar-brand span {
            color: #EC4899;
        }

        .nav-link {
            color: #F8FAFC !important;
            margin-left: 20px;
            transition: 0.3s;
        }

        .nav-link:hover {
            color: #EC4899 !important;
        }

        .btn-login {
            border: 1px solid #EC4899;
            color: #fff;
            padding: 8px 18px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #EC4899;
            color: #fff;
        }

        .btn-signup {
            background: #EC4899;
            color: #fff;
            padding: 8px 18px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-signup:hover {
            background: #d63d84;
            color: #fff;
        }

        .hero {
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .hero h1 {
            font-size: 100px;
            font-weight: 800;
            line-height: 1.1;
        }

        .hero h1 span {
            color: #EC4899;
        }

        .hero p {
            color: #cbd5e1;
            font-size: 18px;
            margin-top: 20px;
        }

        .hero-buttons {
            margin-top: 30px;
        }

        .hero-buttons .btn {
            padding: 14px 28px;
            border-radius: 12px;
            margin-right: 15px;
            font-weight: bold;
        }

        .btn-primary-custom {
            background: #2563EB;
            color: white;
        }

        .btn-primary-custom:hover {
            background: #1d4ed8;
            color: white;
        }

        .btn-secondary-custom {
            border: 1px solid #fff;
            color: white;
        }

        .btn-secondary-custom:hover {
            background: white;
            color: black;
        }

        /* FEATURES */
        .features {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 42px;
            font-weight: bold;
        }

        .feature-card {
            background: #2A102F;
            padding: 30px;
            border-radius: 18px;
            transition: 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-8px);
        }

        .feature-icon {
            font-size: 40px;
            color: #EC4899;
            margin-bottom: 20px;
        }

        .feature-card h4 {
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #cbd5e1;
        }

        /* FOOTER */
        footer {
            padding: 30px 0;
            text-align: center;
            color: #94A3B8;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

    </style>

</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">

        <div class="container">

            <a class="navbar-brand" href="#">
                Quiz<span>Lab</span>
            </a>

            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>

                    <li class="nav-item ms-3">
                        <a href="login.php" class="btn btn-login">
                            Login
                        </a>
                    </li>

                    <li class="nav-item ms-2">
                        <a href="register.php" class="btn btn-signup">
                            Sign Up
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>

    <section class="hero">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <h1>
                        Create <span>Quizzes</span> Instantly
                    </h1>

                    <p>
                        QuizLab helps teachers create quizzes, manage students,
                        and track results in real-time with a secure and modern platform.
                    </p>

                    <div class="hero-buttons">

                        <a href="register.php" class="btn btn-primary-custom">
                            Get Started
                        </a>

                        <a href="#" class="btn btn-secondary-custom">
                            Learn More
                        </a>

                    </div>

                </div>

                <div class="col-lg-6 text-center">

                    <img src="images/hero.png"
                        class="img-fluid"
                        width="500">

                </div>

            </div>

        </div>

    </section>

    <!-- FEATURES -->
    <section class="features">

        <div class="container">

            <div class="section-title">

                <h2>Why Choose QuizLab?</h2>

            </div>

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="feature-card">

                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>

                        <h4>Auto Quiz Generator</h4>

                        <p>
                            Upload documents and generate quizzes automatically.
                        </p>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="feature-card">

                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge"></i>
                        </div>

                        <h4>Real-Time Updates</h4>

                        <p>
                            Instantly monitor quiz submissions using Pusher.
                        </p>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="feature-card">

                        <div class="feature-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>

                        <h4>Secure Platform</h4>

                        <p>
                            Protected against SQL Injection, XSS, and CSRF attacks.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- FOOTER -->
    <footer>

        <div class="container">

            <p>
                © 2026 QuizLab. All Rights Reserved.
            </p>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>