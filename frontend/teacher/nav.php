<div class="sidebar p-3 bg-dark text-white vh-100">

        <div class="text-center mb-4">
        <div class="fw-bold fs-4">
            <i></i>
            Quiz<span style="color:#EC4899;">Lab</span>
        </div>

        <small class="text-muted">Quiz Management System</small>
    </div>

    <a href="dashboard.php" class="d-block text-white mb-2">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="my_quizzes.php" class="d-block text-white mb-2">
        <i class="bi bi-journal-text"></i> My Quizzes
    </a>

    <a href="question_bank.php" class="d-block text-white mb-2">
        <i class="bi bi-journal-text"></i> Question Bank
    </a>


    <a href="student_results.php" class="d-block text-white mb-2">
        <i class="bi bi-bar-chart"></i> Student Results
    </a>

    <a href="live_submissions.php" class="d-block text-white mb-2">
        <i class="bi bi-bar-chart"></i> Live Submissions
    </a>


    <hr>

    <a href="#" class="d-block text-danger" onclick="event.preventDefault(); Swal.fire({
            title: 'Are you sure?',
            text: 'You will be logged out.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href='../../backend/logout.php';
            }
        });">

        <i class="bi bi-box-arrow-right"></i> Logout
    </a>

</div>