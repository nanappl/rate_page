<?php
include("header.html");
session_start();
include("dbs.php"); 


$query = "SELECT * FROM games";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching games: " . mysqli_error($conn));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];
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
        <button type="submit" class="button_click">Rate Game</button>
       <button> <a class = "ratame" href="game_ratings.php">Game ratings</a></button>

        <p id = "nu_p">If your game is not listed, click here to <a href="add_game.php">Add a New Game</a>.</p>
    </form>
</div>

</body>
<style>




#nu_p{
    margin-top: 20px;
    color: #0a3a60;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.button_click{  
    background-attachment: fixed;
}


.home{
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 50px auto 150px auto;
}

h1 {
    color: #135085;
    text-align: center;
    margin-top: 20px;
}



form {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 600px;
    margin: 20px auto;
    box-shadow: 0 4px 8px rgba(0, 0, 1, 0.9);
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

select, button {
    height: 50px;
    width: 100%;
    border-radius: 5px;
    margin-top: 15px;
    border: 1px solid #135085;
}

.buttons {
    background-color: #5c93cd;
    display: flex;
    flex-direction: column; 
    margin-top: 20px;
}

.button-link {
    display: inline-block;
    height: 50px;
    line-height: 50px;
    text-align: center;
    background-color: #135085;
    color: #ffffff;
    font-size: 16px;
    text-decoration: none;
    border-radius: 5px;
    width: 100%;
    box-sizing: border-box;
    border: none;
    cursor: pointer;
}

.button-link:hover {
    background-color: #0a3a60;
    transition: 0.3s;
}

p {
    text-align: center; 
    color: #135085; 
    margin-top: 10px; 
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
</style>
</html>

<?php
mysqli_close($conn);
include("footer.html");
?>