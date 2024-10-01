<?php
// Start the session
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = NULL; // Add new user
$_id = NULL;

if (!empty($_GET['id'])) {
    $_id = base64_decode($_GET['id']);
    $user = $userModel->findUserById($_id);

    // Nếu không tìm thấy user, thoát sớm
    if (!$user) {
        $_id = NULL; // Xóa id nếu không tìm thấy
    }
}

if (!empty($_POST['submit'])) {
    $errors = []; // Mảng để lưu lỗi

    // Validate Name
    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match("/^[A-Za-z0-9]{5,15}$/", $name)) {
        $errors[] = "Name must be 5 to 15 characters long and can only contain A-Z, a-z, 0-9.";
    }

    // Validate Password
    $password = trim($_POST['password']);
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[~!@#$%^&*()])[A-Za-z0-9~!@#$%^&*()]{5,10}$/", $password)) {
        $errors[] = "Password must be 5 to 10 characters long, including at least one lowercase letter, one uppercase letter, one digit, and one special character.";
    }

    // Nếu không có lỗi, thực hiện thêm hoặc cập nhật người dùng
    if (empty($errors)) {
        if (!empty($_id)) {
            $userModel->updateUser($_POST);
        } else {
            $userModel->insertUser($_POST);
        }
        header('location: list_users.php');
        exit(); // Đảm bảo không có mã nào được thực thi sau header
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Form</title>
    <?php include 'views/meta.php'; ?>
</head>
<body>
    <?php include 'views/header.php'; ?>
    <div class="container">
        <?php if (!empty($_id) && $user): ?>
            <div class="alert alert-warning" role="alert">
                User form
            </div>
        <?php elseif (empty($_id)): ?>
            <div class="alert alert-warning" role="alert">
                Add new user form
            </div>
        <?php else: ?>
            <div class="alert alert-danger" role="alert">
                User not found!
            </div>
        <?php endif; ?>

        <!-- Hiển thị form -->
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($_id); ?>">

            <div class="form-group">
                <label for="name">Name</label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="name" 
                    placeholder="Name" 
                    value="<?= htmlspecialchars($user['name'] ?? ''); ?>"
                >
                <?php if (!empty($errors) && in_array("Name is required.", $errors)): ?>
                    <div class="text-danger">Name is required.</div>
                <?php elseif (!empty($errors) && in_array("Name must be 5 to 15 characters long and can only contain A-Z, a-z, 0-9.", $errors)): ?>
                    <div class="text-danger">Name must be 5 to 15 characters long and can only contain A-Z, a-z, 0-9.</div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Password"
                >
                <?php if (!empty($errors) && in_array("Password is required.", $errors)): ?>
                    <div class="text-danger">Password is required.</div>
                <?php elseif (!empty($errors) && in_array("Password must be 5 to 10 characters long, including at least one lowercase letter, one uppercase letter, one digit, and one special character.", $errors)): ?>
                    <div class="text-danger">Password must be 5 to 10 characters long, including at least one lowercase letter, one uppercase letter, one digit, and one special character.</div>
                <?php endif; ?>
            </div>

            <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>
</body>
</html>
