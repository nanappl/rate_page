<?php
include("header.html");
session_start();
include("dbs.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to rate games.");
}

$user_id = $_SESSION['user_id'];

// Fetch games
$query = "SELECT * FROM games";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error fetching games: " . mysqli_error($conn));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['game_id'])) {
    $game_id = $_POST['game_id'];

    // Check if user has already rated this game
    $check_query = "SELECT * FROM ratings WHERE game_id = $game_id AND user_id = $user_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "
        <script type='text/javascript'>
            if (confirm('You have already rated this game. Would you like to update your rating?')) {
                window.location.href = 'rate_game.php?game_id=$game_id';
            } else {
                window.location.href = 'home.php';
            }
        </script>";
        exit;
    } else {
        header("Location: rate_game.php?game_id=$game_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Chooser</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .home {
            margin: 100px 0 600px 0;
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

        select, button {
            height: 50px;
            width: 100%;
            border-radius: 5px;
            margin-top: 15px;
            border: 1px solid #135085;
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
            margin-bottom: 30px;
        }

        button:hover {
            background-color: #0f3f6a;
            transition: .3s;
        }

        .ratame {
            background-color: #135085;
            color: white;
            width: 600px;
            border: none;
            text-align: center;
            padding: 12px 0;
            border-radius: 6px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            display: block;
            text-decoration: none;
        }

        .ratame:hover {
            background-color: #0f3f6a;
            transition: .3s;
        }

        #nu_p {
            margin-top: 20px;
            color: #0a3a60;
            text-align: center;
        }

        .a_add {
            font-weight: bold;
            color: #5c93cd;
            text-decoration: none;
        }

        .a_add:hover {
            text-decoration: underline;
        }
    </style>
</head>
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
    </form>

    <a class="ratame" href="game_ratings.php">Game ratings</a>
    <p id="nu_p">If your game is not listed, click here to <a class="a_add" href="add_game.php">Add a New Game</a>.</p>
</div>

</body>
</html>

<?php
mysqli_close($conn);
include("footer.html");
?>
