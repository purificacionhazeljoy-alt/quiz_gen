<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY user_id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body style="background:#f4f6f9;">

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2 p-0">
                <?php include "nav.php"; ?>
            </div>

            <div class="col-md-10 p-0">

                <nav class="navbar bg-white shadow-sm px-4 py-3">
                    <div>
                        <h4 class="fw-bold mb-0">Users Management</h4>
                        <small class="text-muted">Manage teachers and students</small>
                    </div>
                </nav>

                <div class="p-4">

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">All Users</h5>
                            </div>

                            <div class="table-responsive dashboard-scroll">

                                <table class="table table-hover align-middle">

                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php foreach ($users as $index => $user): ?>

                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <?= htmlspecialchars($user['firstname']) ?>
                                                    <?= htmlspecialchars($user['lastname']) ?>
                                                </td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?= ucfirst($user['role']) ?>
                                                    </span>
                                                </td>
                                                <td>

                                                    <?php if ($user['status'] == 'active'): ?>

                                                        <span class="badge bg-success">
                                                            Active
                                                        </span>

                                                    <?php else: ?>

                                                        <span class="badge bg-danger">
                                                            Inactive
                                                        </span>

                                                    <?php endif; ?>

                                                </td>

                                                <td>

                                                    <!-- EDIT -->
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#editModal<?= $user['user_id'] ?>">

                                                        <i class="bi bi-pencil"></i>

                                                    </button>

                                                    <!-- TOGGLE STATUS -->
                                                    <?php if ($user['status'] == 'active'): ?>

                                                        <a href="../../backend/deactivate_user.php?id=<?= $user['user_id'] ?>"
                                                            class="btn btn-sm btn-danger">

                                                            <i class="bi bi-person-x"></i>

                                                        </a>

                                                    <?php else: ?>

                                                        <a href="../../backend/activate_user.php?id=<?= $user['user_id'] ?>"
                                                            class="btn btn-sm btn-success">

                                                            <i class="bi bi-person-check"></i>

                                                        </a>

                                                    <?php endif; ?>

                                                </td>
                                            </tr>

                                            <!-- EDIT MODAL -->
<div class="modal fade"
     id="editModal<?= $user['user_id'] ?>"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="../../backend/update_user.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Edit User
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="user_id"
                           value="<?= $user['user_id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">
                            Firstname
                        </label>

                        <input type="text"
                               name="firstname"
                               class="form-control"
                               value="<?= htmlspecialchars($user['firstname']) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Lastname
                        </label>

                        <input type="text"
                               name="lastname"
                               class="form-control"
                               value="<?= htmlspecialchars($user['lastname']) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="<?= htmlspecialchars($user['email']) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Role
                        </label>

                        <select name="role"
                                class="form-select">

                            <option value="admin"
                                <?= $user['role'] == 'admin' ? 'selected' : '' ?>>
                                Admin
                            </option>

                            <option value="teacher"
                                <?= $user['role'] == 'teacher' ? 'selected' : '' ?>>
                                Teacher
                            </option>

                            <option value="student"
                                <?= $user['role'] == 'student' ? 'selected' : '' ?>>
                                Student
                            </option>

                        </select>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-primary">

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>