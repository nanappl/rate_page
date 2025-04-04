<?php
session_start();
include("dbs.php"); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $game_name = filter_input(INPUT_POST, 'game_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);

    // Insert the new game into the database
    if (!empty($game_name) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO games (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $game_name, $description);

        if ($stmt->execute()) {
            echo "Game added successfully!";
        } else {
            echo "Error adding game: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

mysqli_close($conn); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Game</title>
</head>
<body>
    <h1>Add a New Game</h1>
    
    <form action="" method="POST">
        <label for="game_name">Game Name:</label>
        <input type="text" id="game_name" name="game_name" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <button type="submit">Add Game</button>
    </form>

    <p><a href="home.php">Back to Game Chooser</a></p>

</body>
</html>
