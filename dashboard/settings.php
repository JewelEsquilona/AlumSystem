<?php
session_start();
require '../connection.php'; 

// Fetch user data from the database
$user_id = $_SESSION['user_id']; 
$query = "SELECT name, email, avatar FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $avatar = $_FILES['avatar'];

    // Update user info
    $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssi", $name, $email, $user_id);
    $update_stmt->execute();

    // Handle password reset
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $password_query = "UPDATE users SET password = ? WHERE id = ?";
        $password_stmt = $conn->prepare($password_query);
        $password_stmt->bind_param("si", $hashed_password, $user_id);
        $password_stmt->execute();
    }

    // Handle avatar upload
    if ($avatar['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($avatar["name"]);
        move_uploaded_file($avatar["tmp_name"], $target_file);

        $avatar_query = "UPDATE users SET avatar = ? WHERE id = ?";
        $avatar_stmt = $conn->prepare($avatar_query);
        $avatar_stmt->bind_param("si", $target_file, $user_id);
        $avatar_stmt->execute();
    }

    // Redirect to the same page to show updated info
    header("Location: settings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body>
    <div class="container">
        <h1>Settings</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Username:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Reset Password:</label>
                <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
            </div>
            <div class="form-group">
                <label for="avatar">Profile Avatar:</label>
                <input type="file" id="avatar" name="avatar" accept="image/*">
            </div>
            <div class="form-group">
                <button type="submit">Save Changes</button>
            </div>
        </form>
        <div class="current-avatar">
            <h2>Current Avatar:</h2>
            <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Current Avatar" height="100" width="100">
        </div>
    </div>
</body>
</html>
