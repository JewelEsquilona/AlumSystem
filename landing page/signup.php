<?php
if (isset($_POST['submit'])) {
    include '../connection.php';

    $userName = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $conPass = $_POST['conPass'];
    
    if (empty($userName) || empty($email) || empty($pass) || empty($conPass)) {
        header("location:index.php?error=all fields required");
        exit();
    }

    if ($pass !== $conPass) {
        header("location:index.php?error=passwords do not match");
        exit();
    }

    $checkQuery = $con->prepare("SELECT * FROM users WHERE username = ? OR Email = ?");
    $checkQuery->execute([$userName, $email]);

    if ($checkQuery->rowCount() > 0) {
        header("location:index.php?error=username or email already exists");
        exit();
    }

    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

    try {
        $requete = $con->prepare("INSERT INTO users(username, Email, Password) VALUES(?, ?, ?)");
        $requete->execute([$userName, $email, $hashedPass]);
        header('location:index.php?success=registration successful');
        exit();
    } catch (PDOException $e) {
        header("location:index.php?error=database error: " . $e->getMessage());
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main class="bg-sign-in d-flex justify-content-center align-items-center">
        <div class="sign-up bg-white mt-2 h-auto mb-2 text-center pt-4 pb-3 pe-4 ps-4 d-flex flex-column">
            <div>
                <h2 class="sign-in text-uppercase">Sign Up</h2>
            </div>
            <form method="POST" id="signup" action="" onsubmit="return validateInput();">
                <div class="mb-3 mt-3 text-start">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="Email" placeholder="Enter Email" name="email" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="pwd">Create New Password:</label>
                    <input type="password" class="form-control" id="Pwd" placeholder="Enter password" name="pass" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="conPwd">Confirm New Password:</label>
                    <input type="password" class="form-control" id="conPwd" placeholder="Confirm password" name="conPass" required>
                </div>
                <button type="submit" name="submit" class="btn text-white w-100 text-uppercase">Sign Up</button>
                <p class="mt-4">Already have an account? <a href="index.php">Sign In</a></p>
            </form>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/validation.js"></script>
</body>
</html>
