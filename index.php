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

/* RESET */
*{
margin:0;
padding:0;
box-sizing:border-box;
}

html{
scroll-behavior:smooth;
}

body{
background:#1B0824;
color:#F8FAFC;
font-family:Arial, sans-serif;
overflow-x:hidden;
}

/* NAVBAR */
.navbar{
padding:18px 0;
}

.navbar-brand{
font-size:28px;
font-weight:800;
color:#fff;
}

.navbar-brand span{
color:#EC4899;
}

.nav-link{
color:#F8FAFC !important;
margin-left:20px;
transition:0.3s;
}

.nav-link:hover{
color:#EC4899 !important;
}

/* BUTTONS */
.btn-login{
border:1px solid #EC4899;
color:#fff;
padding:8px 18px;
border-radius:10px;
}

.btn-login:hover{
background:#EC4899;
}

.btn-signup{
background:#EC4899;
color:#fff;
padding:8px 18px;
border-radius:10px;
}

.btn-signup:hover{
background:#d63d84;
}

/* HERO */
.hero{
min-height:90vh;
display:flex;
align-items:center;
}

.hero h1{
font-size:100px;
font-weight:900;
line-height:1.1;
}

.hero h1 span{
color:#EC4899;
}

.hero p{
color:#cbd5e1;
font-size:18px;
margin-top:20px;
}

.hero-buttons{
margin-top:30px;
}

.hero-buttons .btn{
padding:14px 28px;
border-radius:12px;
margin-right:15px;
font-weight:bold;
}

.btn-primary-custom{
background:#2563EB;
color:white;
}

.btn-secondary-custom{
border:1px solid #fff;
color:white;
}

/* FEATURES */
.features{
padding:80px 0;
}

.section-title{
text-align:center;
margin-bottom:50px;
}

.section-title h2{
font-size:42px;
font-weight:800;
}

.feature-card{
background:#2A102F;
padding:30px;
border-radius:18px;
transition:0.3s;
}

.feature-card:hover{
transform:translateY(-8px);
}

.feature-icon{
font-size:40px;
color:#EC4899;
margin-bottom:20px;
}

/* FOOTER */
footer{
padding:30px;
text-align:center;
color:#94A3B8;
border-top:1px solid rgba(255,255,255,0.1);
}

/* =========================
   LIVING MODALS DESIGN
========================= */

.modal-backdrop.show{
backdrop-filter: blur(8px);
background-color: rgba(0,0,0,0.6);
}

.modal-dialog{
max-width:700px;
animation: popIn 0.25s ease-out;
}

@keyframes popIn{
from{transform:scale(0.85);opacity:0;}
to{transform:scale(1);opacity:1;}
}

.modal-content{
background: linear-gradient(135deg, rgba(42,16,47,0.98), rgba(20,5,30,0.98));
border: 1px solid rgba(236,72,153,0.4);
border-radius: 22px;
box-shadow: 0 30px 90px rgba(0,0,0,0.85);
padding:10px;
}

.modal-header{
border-bottom:1px solid rgba(255,255,255,0.08);
padding:18px 22px;
}

.modal-title{
font-size:22px;
font-weight:900;
color:#EC4899;
}

.btn-close-white{
filter: invert(1);
}

.modal-body{
color:#ffffff;
font-size:16px;
padding:25px;
line-height:1.7;
}

/* CONTACT BOX */
.contact-box{
background:rgba(255,255,255,0.06);
padding:15px;
border-radius:12px;
margin-bottom:10px;
transition:0.3s;
}

.contact-box:hover{
background:rgba(255,255,255,0.1);
transform:translateX(5px);
}

</style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand" href="#">Quiz<span>Lab</span></a>

<button class="navbar-toggler bg-light" data-bs-toggle="collapse" data-bs-target="#nav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="nav">

<ul class="navbar-nav ms-auto align-items-center">

<li class="nav-item">
<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#homeModal">Home</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#featuresModal">Features</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#aboutModal">About</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#contactModal">Contact</a>
</li>

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

<div class="col-lg-6">

<h1>Create <span>Quizzes</span> Instantly</h1>

<p>QuizLab helps teachers manage quizzes and students easily with real-time system.</p>

<div class="hero-buttons">

<a href="register.php" class="btn btn-primary-custom">Get Started</a>

<a href="#" class="btn btn-secondary-custom" data-bs-toggle="modal" data-bs-target="#learnMoreModal">
Learn More
</a>

</div>

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
<div class="feature-icon"><i class="bi bi-file-earmark-text"></i></div>
<h4>Auto Quiz Generator</h4>
<p>Upload files and auto-generate quizzes.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-card">
<div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
<h4>Real-Time Updates</h4>
<p>Track results instantly.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-card">
<div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
<h4>Secure System</h4>
<p>Protected against attacks.</p>
</div>
</div>

</div>

</div>

</section>

<!-- FOOTER -->
<footer>
© 2026 QuizLab. All Rights Reserved.
</footer>

<!-- MODALS -->

<!-- HOME -->
<div class="modal fade" id="homeModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Home</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
Welcome to QuizLab — your smart quiz system.
</div>
</div>
</div>
</div>

<!-- FEATURES -->
<div class="modal fade" id="featuresModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Features</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
✔ Auto Quiz Generator<br>
✔ Real-time Results<br>
✔ Secure System
</div>
</div>
</div>
</div>

<!-- ABOUT -->
<div class="modal fade" id="aboutModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">About</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
QuizLab is a smart platform for teachers and students to manage quizzes easily.
</div>
</div>
</div>
</div>

<!-- CONTACT (INFO ONLY) -->
<div class="modal fade" id="contactModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Contact Us</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="contact-box">
<i class="bi bi-envelope"></i>
<strong> Email:</strong> support@quizlab.com
</div>

<div class="contact-box">
<i class="bi bi-telephone"></i>
<strong> Phone:</strong> +63 912 345 6789
</div>

<div class="contact-box">
<i class="bi bi-facebook"></i>
<strong> Facebook:</strong> QuizLab Official Page
</div>

</div>

</div>
</div>
</div>

<!-- LEARN MORE -->
<div class="modal fade" id="learnMoreModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Learn More</h5>
<button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="contact-box">
<strong>🎯 Purpose:</strong><br>
Simplify quiz creation and student management.
</div>

<div class="contact-box">
<strong>⚡ Features:</strong><br>
Auto Quiz Generator, Real-time Results, Secure System
</div>

<div class="contact-box">
<strong>👨‍🏫 Teachers:</strong><br>
Create quizzes and manage students easily.
</div>

<div class="contact-box">
<strong>🎓 Students:</strong><br>
Take quizzes and view results instantly.
</div>

</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
