

<div class="sidebar p-3 bg-dark text-white vh-100">

        <div class="text-center mb-4">
        <div class="fw-bold fs-4">
            <i></i>
            Quiz<span style="color:#EC4899;">Lab</span>
        </div>

        <small class="text-muted">Quiz Management System</small>
    </div>

    <a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active-link' : '' ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="users.php" class="<?= ($current_page == 'users.php') ? 'active-link' : '' ?>">
        <i class="bi bi-people"></i> Users Management
    </a>

    <a href="quizzes.php" class="<?= ($current_page == 'quizzes.php') ? 'active-link' : '' ?>">
        <i class="bi bi-journal-text"></i> Quizzes
    </a>

    <a href="results.php" class="<?= ($current_page == 'results.php') ? 'active-link' : '' ?>">
        <i class="bi bi-bar-chart"></i> Results
    </a>

    <a href="logs.php" class="<?= ($current_page == 'logs.php') ? 'active-link' : '' ?>">
        <i class="bi bi-activity"></i> Activity Logs
    </a>

    <a href="reports.php" class="<?= ($current_page == 'reports.php') ? 'active-link' : '' ?>">
        <i class="bi bi-file-earmark-text"></i> Reports
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
