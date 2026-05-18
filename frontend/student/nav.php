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

    <a href="available_quizzes.php" class="d-block text-white mb-2">
        <i class="bi bi-journal-text"></i> Available Quizzes
    </a>

    <a href="my_attempts.php" class="d-block text-white mb-2">
        <i class="bi bi-clock-history"></i> My Attempts
    </a>

    <a href="my_results.php" class="d-block text-white mb-2">
        <i class="bi bi-bar-chart"></i> My Score / Results
    </a>

    <a href="leaderboard.php" class="d-block text-white mb-2">
        <i class="bi bi-trophy"></i> Leaderboard
    </a>

    <hr>

    <a href="#" class="d-block text-danger"
       onclick="event.preventDefault(); Swal.fire({
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