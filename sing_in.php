<?php
include("header.html");
session_start();
include("dbs.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($name) || empty($password)) {
        echo "Please enter a username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM user WHERE name = ?");
        if ($stmt === false) {
            die("Error in prepare statement: " . $conn->error);
        }
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();


        if ($stmt->num_rows > 0) {
            echo "Username already taken. Try another.";
        } else {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO user (name, pas, date) VALUES (?, ?, NOW())");
            if ($stmt === false) {
                die("Error in prepare statement: " . $conn->error);
            }
            $stmt->bind_param("ss", $name, $hashed_pass);

            if ($stmt->execute()) {
                $_SESSION["user_id"] = $stmt->insert_id;
                $_SESSION["name"] = $name;
                header("Location: home.php");
                exit();
            } else {
                echo "Error: Could not register user. " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="reg_box">
        <h1>Sign Up</h1>
        
        <form action="sing_in.php" method="POST">
            <div class="label_part">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="label_part">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>

<?php
include("footer.html");
?>
