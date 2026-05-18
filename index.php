<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>QuizLab</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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
         overflow-x: hidden;
      }

      .navbar {
         padding: 18px 0;
      }

      .navbar-brand {
         font-size: 28px;
         font-weight: 800;
         color: #fff;
      }

      .navbar-brand span {
         color: #EC4899;
      }

      .nav-link {
         color: #F8FAFC !important;
         margin-left: 20px;
      }

      .nav-link:hover {
         color: #EC4899 !important;
      }

      .btn-login {
         border: 1px solid #EC4899;
         color: #fff;
         padding: 8px 18px;
         border-radius: 10px;
      }

      .btn-login:hover {
         background: #EC4899;
      }

      .btn-signup {
         background: #EC4899;
         color: #fff;
         padding: 8px 18px;
         border-radius: 10px;
      }

      .btn-signup:hover {
         background: #d63d84;
      }

      .hero {
         min-height: 90vh;
         display: flex;
         align-items: center;
      }

      .hero h1 {
         font-size: 90px;
         font-weight: 900;
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

      .btn-secondary-custom {
         border: 1px solid #fff;
         color: white;
      }

      .features {
         padding: 80px 0;
      }

      .section-title {
         text-align: center;
         margin-bottom: 50px;
      }

      .section-title h2 {
         font-size: 42px;
         font-weight: 800;
      }

      .feature-card {
         background: #2A102F;
         padding: 30px;
         border-radius: 18px;
         transition: 0.3s;
      }

      .feature-card:hover {
         transform: translateY(-8px);
      }

      .feature-icon {
         font-size: 40px;
         color: #EC4899;
         margin-bottom: 20px;
      }

      footer {
         padding: 30px;
         text-align: center;
         color: #94A3B8;
         border-top: 1px solid rgba(255, 255, 255, 0.1);
      }
   </style>
</head>

<body>

   <div class="bg-shapes">
      <span></span>
      <span></span>
      <span></span>
   </div>

   <!-- NAV -->
   <nav class="navbar navbar-expand-lg">
      <div class="container">

         <a class="navbar-brand" href="#">Quiz<span>Lab</span></a>

         <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center">

               <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
               <li class="nav-item"><a class="nav-link" href="#about">About</a></li>

               <li class="nav-item ms-3">
                  <a href="login.php" class="btn btn-login">Login</a>
               </li>

               <li class="nav-item ms-2">
                  <a href="register.php" class="btn btn-signup">Sign Up</a>
               </li>

            </ul>
         </div>

      </div>
   </nav>

   <!-- HERO -->
   <section class="hero">
      <div class="container">
         <div class="row align-items-center">

            <div class="col-lg-7">

               <h1>Create <span>Quizzes</span> Instantly</h1>

               <p>
                  QuizLab helps teachers manage quizzes and students easily with real-time system, secure tracking, and
                  seamless experience.
               </p>

               <div class="hero-buttons">
                  <a href="register.php" class="btn btn-primary-custom">Get Started</a>
                  <a href="#features" class="btn btn-secondary-custom">
                     Explore Features
                  </a>
               </div>

            </div>

         </div>
      </div>
   </section>
   <!-- FEATURES -->
   <section class="features" id="features">

      <div class="container">

         <div class="section-title">
            <h2>Why Choose QuizLab?</h2>
         </div>

         <div class="row g-4">

            <div class="col-md-4">
               <div class="feature-card">
                  <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
                  <h4>Real-Time Updates</h4>
                  <p>See quiz results instantly as students submit answers.</p>
               </div>
            </div>

            <div class="col-md-4">
               <div class="feature-card">
                  <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
                  <h4>Secure System</h4>
                  <p>Built with protection against cheating and unauthorized access.</p>
               </div>
            </div>

            <div class="col-md-4">
               <div class="feature-card">
                  <div class="feature-icon"><i class="bi bi-people"></i></div>
                  <h4>Easy Management</h4>
                  <p>Teachers can manage quizzes and students in one platform.</p>
               </div>
            </div>

         </div>

      </div>

   </section>

   <!-- HOW IT WORKS -->
   <section class="features" id="how-it-works">

      <div class="container">

         <div class="section-title">
            <h2>How QuizLab Works</h2>
         </div>

         <div class="row g-4">

            <div class="col-md-4">
               <div class="feature-card text-center">
                  <div class="feature-icon">
                     <i class="bi bi-person-video3"></i>
                  </div>
                  <h4>1. Teacher Creates Quiz</h4>
                  <p>Teachers create quizzes with multiple question types in seconds.</p>
               </div>
            </div>

            <div class="col-md-4">
               <div class="feature-card text-center">
                  <div class="feature-icon">
                     <i class="bi bi-pencil-square"></i>
                  </div>
                  <h4>2. Students Answer</h4>
                  <p>Students take quizzes in real-time with timer and instant submission.</p>
               </div>
            </div>

            <div class="col-md-4">
               <div class="feature-card text-center">
                  <div class="feature-icon">
                     <i class="bi bi-graph-up-arrow"></i>
                  </div>
                  <h4>3. Results Update Instantly</h4>
                  <p>Scores and analytics update in real-time using live system.</p>
               </div>
            </div>

         </div>

      </div>

   </section>

   <!-- ROLES SECTION -->
   <section class="features" id="roles">

      <div class="container">

         <div class="section-title">
            <h2>Built for Everyone</h2>
         </div>

         <div class="row g-4">

            <!-- TEACHERS -->
            <div class="col-md-6">
               <div class="feature-card h-100">

                  <div class="feature-icon">
                     <i class="bi bi-person-badge"></i>
                  </div>

                  <h4>For Teachers</h4>

                  <ul style="color:#cbd5e1; padding-left:18px; line-height:1.8;">
                     <li>Create quizzes in seconds</li>
                     <li>Manage students easily</li>
                     <li>Monitor live submissions</li>
                     <li>View analytics & results</li>
                     <li>Detect cheating activity logs</li>
                  </ul>

               </div>
            </div>

            <!-- STUDENTS -->
            <div class="col-md-6">
               <div class="feature-card h-100">

                  <div class="feature-icon">
                     <i class="bi bi-mortarboard"></i>
                  </div>

                  <h4>For Students</h4>

                  <ul style="color:#cbd5e1; padding-left:18px; line-height:1.8;">
                     <li>Take quizzes anytime</li>
                     <li>Real-time scoring system</li>
                     <li>Simple and clean interface</li>
                     <li>Instant result feedback</li>
                     <li>Fair and secure testing</li>
                  </ul>

               </div>
            </div>

         </div>

      </div>

   </section>


   <section class="features" id="highlights">

      <div class="container">

         <div class="section-title">
            <h2>Live System Highlights</h2>
            <p style="color:#cbd5e1;">
               Real-time activity you can actually monitor inside QuizLab
            </p>
         </div>

         <div class="row g-4">

            <div class="col-md-4">
               <div class="feature-card text-center">
                  <div class="feature-icon">
                     <i class="bi bi-broadcast"></i>
                  </div>
                  <h4>Live Submissions</h4>
                  <p>See student answers appear instantly as they submit.</p>
               </div>
            </div>

            <div class="col-md-4">
               <div class="feature-card text-center">
                  <div class="feature-icon">
                     <i class="bi bi-exclamation-triangle"></i>
                  </div>
                  <h4>Cheating Detection</h4>
                  <p>System automatically logs suspicious behavior in real-time.</p>
               </div>
            </div>

            <div class="col-md-4">
               <div class="feature-card text-center">
                  <div class="feature-icon">
                     <i class="bi bi-bar-chart"></i>
                  </div>
                  <h4>Live Analytics</h4>
                  <p>Scores and performance update without page refresh.</p>
               </div>
            </div>

         </div>

      </div>

   </section>

   <!-- FOOTER -->
   <footer>
      © 2026 QuizLab. All Rights Reserved.
   </footer>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>