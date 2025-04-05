<?php
include("header.html");
session_start();
include("dbs.php"); // Database connection

// Fetch all games from the database
$query = "SELECT * FROM games";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching games: " . mysqli_error($conn));
}

// Check if the user selected a game
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];
    // Redirect to the rate game page with the selected game_id
    header("Location: rate_game.php?game_id=" . $game_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Chooser</title>
    <link rel="stylesheet" href="style/home.css">
</head>
<body>
<body>
<div class="home">
    <form action="home.php" method="POST">
    <h1>Choose a Game to Rate</h1>
        <select name="game_id" id="game_id" required>
            <option value="" disabled selected>Select a game</option>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php } ?>
        </select>
        <button type="submit">Rate Game</button>
        <p>If your game is not listed, click here to <a href="add_game.php">Add a New Game</a>.</p>
    </form>

    <a class = "rategame" href="game_ratings.php"><button>Game ratings</button></a>
</div>

</body>
</html>

<?php
mysqli_close($conn);
include("footer.html");
?>