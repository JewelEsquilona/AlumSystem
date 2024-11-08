<?php
session_start();
include '../connection.php';

if (isset($_POST['login_submit'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];

    $requete = "SELECT * FROM users WHERE Email = :email";
    $statment = $con->prepare($requete);
    $statment->bindParam(':email', $email);
    $statment->execute();
    $result = $statment->fetch();

    if ($result && password_verify($password, $result['Password'])) {
        $_SESSION['name'] = $result['username'];
        $_SESSION['email'] = $result['Email'];

        if (isset($_POST['check'])) {
            setcookie('email', $_SESSION['email'], time() + 3600);
        }

        header("location:../dashboard/home.php");
        exit();
    } else if (empty($email) || empty($password)) {
        $error = "please enter your email or password";
    } else {
        $error = "email or password not found";
    }
}

if (isset($_POST['register_submit'])) {
    $userName = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $conPass = $_POST['conPass'];

    if (empty($userName) || empty($email) || empty($pass) || empty($conPass)) {
        $error = "all fields required";
    } else if ($pass !== $conPass) {
        $error = "passwords do not match";
    } else {
        $checkQuery = $con->prepare("SELECT * FROM users WHERE username = ? OR Email = ?");
        $checkQuery->execute([$userName, $email]);

        if ($checkQuery->rowCount() > 0) {
            $error = "username or email already exists";
        } else {
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

            try {
                $requete = $con->prepare("INSERT INTO users(username, Email, Password) VALUES(?, ?, ?)");
                $requete->execute([$userName, $email, $hashedPass]);
                header('location: index.php');
                exit();
            } catch (PDOException $e) {
                $error = "database error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main class="bg-sign-in d-flex justify-content-center align-items-center">
        <div class="form-sign-in bg-white mt-2 h-auto mb-2 text-center pt-2 pe-4 ps-4 d-flex flex-column">
            <h1 class="E-classe text-start ms-3 ps-1">Alumni System</h1>
            <div>
                <h2 class="sign-in text-uppercase">Sign In</h2>
                <p>Enter your credentials to access your account</p>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3 mt-3 text-start">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?php if (isset($_COOKIE['email'])) {
                                                                                                                            echo htmlspecialchars($_COOKIE['email']);
                                                                                                                        } ?>" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pass" value="<?php if (isset($_COOKIE['password'])) {
                                                                                                                                echo htmlspecialchars($_COOKIE['password']);
                                                                                                                            } ?>" autocomplete="current-password" required>
                </div>
                <div class="mb-3 form-check d-flex gap-2">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="check">
                    <label class="form-check-label" for="exampleCheck1">Remember Me</label>
                </div>
                <button type="submit" name="login_submit" class="btn text-white w-100 text-uppercase">Sign In</button>
                <p class="mt-4">Forgot your password? <a href="resetpass.php">Reset Password</a></p>
                <button type="button" class="btn btn-success mb-3" onclick="window.location.href='signup.php';">Create Account</button>
            </form>
        </div>

        <div class="register d-flex justify-content-center align-items-center">
            <div class="sign-up bg-white mt-2 h-auto mb-2 text-center pt-4 pb-3 pe-4 ps-4 d-flex flex-column">
                <div>
                    <h2 class="sign-in text-uppercase">Sign Up</h2>
                </div>
                <form method="POST" action="">
                    <div class="mb-3 mt-3 text-start">
                        <label class="label-signup" for="username">Username:</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="label-signup" for="email">Email:</label>
                        <input type="email" class="form-control" id="Email" placeholder="Enter Email" name="email" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="label-signup" for="pwd">Create Password:</label>
                        <input type="password" class="form-control" id="Pwd" placeholder="Enter password" name="pass" autocomplete="on" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="label-signup" for="conPwd">Confirm Password:</label>
                        <input type="password" class="form-control" id="conPwd" placeholder="Confirm password" name="conPass" autocomplete="on" required>
                    </div>
                    <button type="submit" name="register_submit" class="btn text-white w-100 text-uppercase">Sign Up</button>
                    <p class="mt-4">Already have an account? <a href="index.php">Sign In</a></p>
                </form>
            </div>
        </div>
    </main>
    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/validation.js"></script>
</body>

</html>