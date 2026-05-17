<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reports</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="style.css">
</head>

<body style="background:#f4f6f9;">

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2 p-0">
                <?php include "nav.php"; ?>
            </div>

            <div class="col-md-10 p-4">

                <div class="row g-3">

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-file-earmark-bar-graph fs-1 text-primary"></i>
                                <h5 class="fw-bold mt-3">Quiz Reports</h5>
                                <p class="text-muted small">View performance analytics</p>
                                <button class="btn btn-primary btn-sm">Generate</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-people fs-1 text-success"></i>
                                <h5 class="fw-bold mt-3">Student Reports</h5>
                                <p class="text-muted small">Track student progress</p>
                                <button class="btn btn-success btn-sm">Generate</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-bar-chart fs-1 text-danger"></i>
                                <h5 class="fw-bold mt-3">System Reports</h5>
                                <p class="text-muted small">Overall platform analytics</p>
                                <button class="btn btn-danger btn-sm">Generate</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</body>

</html>