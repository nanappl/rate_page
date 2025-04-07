<?php
include("header.html");
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
    <link rel="stylesheet" href="style/add.css">
</head>
<body>
    <div class="add_game">
    <h1>Add a New Game</h1>
    
    <form action="" method="POST">
        <label for="game_name">Game Name:</label>
        <input type="text" id="game_name" name="game_name" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <button type="submit">Add Game</button>
       <button> <a href="home.php">Back to Game Chooser</a></button>
    </form>
    </div>


</body>

<style>

.add_game{
    margin: 100px 0;
}
    body {
    font-family: Arial, sans-serif;
    background-color: #ffffff;
    margin: 0;
    padding: 0;
}

h1 {
    color: #135085;
    text-align: center;
    margin-top: 30px;
}

form {
    background-color: #6EC0E3;
    width: 90%;
    max-width: 600px;
    margin: 30px auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 1, 0.9);
    display: flex;
    flex-direction: column;
}
a {
    text-decoration: none; /* removes underline */
    color: inherit;        /* uses parent text color */
    background: none;
    border: none;
    outline: none;
    box-shadow: none;
    cursor: pointer;       /* optional */
}


label {
    font-weight: bold;
    margin-top: 15px;
    color: #135085;
}

input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #135085; 
    border-radius: 6px;
    font-size: 14px;
    resize: vertical;
    outline: none;
    box-shadow: none;
    background-color: #fff; 
}  
button {
    background-color: #135085;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    margin-top: 20px;
    cursor: pointer;
}

button:hover {
    transition: .3s;
    background-color: #0f3f6a;
}

.back-game-chooser {
    display: inline-block;
    font-size: 18px;
    color: #135085;
    text-decoration: none;
    padding: 10px 15px;
    background-color: #fff;
    cursor: pointer;
    font-weight: bold;
    margin-top: 20px;
    text-align: center;
    transition: background-color 0.3s;
}

.back-game-chooser:hover {
    color:  #5c93cd;
}

.error-message {
    color:  #135085;
    font-weight: bold;
    margin-top: 10px;
    font-size: 16px;
}

</style>
</html>

<?php
include("footer.html");
?>
