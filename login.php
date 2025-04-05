<?php
session_start();
include("dbs.php"); // Include the database connection file
include("header.html");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    // Check if name and password are provided
    if (!empty($name) && !empty($password)) {
        // Prepare the SQL query to fetch the user data
        $stmt = $conn->prepare("SELECT id, pas FROM user WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();

        // If user is found in the database
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            // Debugging: Check if the password is valid
            echo "Hashed password from database: " . $hashed_password . "<br>";
            echo "Password entered: " . $password . "<br>";

            // Verify if the password matches the stored hash
            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $user_id;
                $_SESSION["name"] = $name;

                // Debugging: Show session data to verify it's being set
                echo "SESSION data: <pre>";
                print_r($_SESSION);
                echo "</pre>";

                // Redirect to home page after successful login
                header("Location: home.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }

        $stmt->close();
    } else {
        echo "Please enter both username and password.";
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style/registr.css">
</head>
<body>
    <div class="reg_box">
        <h1>Login</h1>
        <form action="" method="POST">
            <div class="label_part">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="label_part">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="sing_in.php">Sign up here</a></p>
    </div>
</body>
</html>

<?php
include("footer.html");
?>

