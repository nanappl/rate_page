<?php
    include("header.html");
?>

<?php
    session_start();
    include("dbs.php"); 

    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }

    if (!isset($_GET['game_id'])) {
        header("Location: home.php");
        exit();
    }

    $game_id = intval($_GET['game_id']);
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("SELECT name FROM games WHERE id = ?");
    $stmt->bind_param("i", $game_id);
    $stmt->execute();
    $stmt->bind_result($game_name);
    $stmt->fetch();
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collect rating values from the form
        $emotional_connection = intval($_POST["emotional_connection"]);
        $exploratory_learning = intval($_POST["exploratory_learning"]);
        $visual_feedback = intval($_POST["visual_feedback"]);
        $real_life_relevance = intval($_POST["real_life_relevance"]);
        $progress_tracking = intval($_POST["progress_tracking"]);
        $conceptual_understanding = intval($_POST["conceptual_understanding"]);
        $collaboration_experimentation = intval($_POST["collaboration_experimentation"]);
        $game_balance = intval($_POST["game_balance"]);
        $immediate_feedback = intval($_POST["immediate_feedback"]);

        // Check if the user has already rated this game
        $check_stmt = $conn->prepare("SELECT id FROM ratings WHERE user_id = ? AND game_id = ?");
        $check_stmt->bind_param("ii", $user_id, $game_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // User has already rated the game, so we will update the rating
            $update_stmt = $conn->prepare("UPDATE ratings 
                SET emotional_connection = ?, exploratory_learning = ?, visual_feedback = ?, 
                real_life_relevance = ?, progress_tracking = ?, conceptual_understanding = ?, 
                collaboration_experimentation = ?, game_balance = ?, immediate_feedback = ? 
                WHERE user_id = ? AND game_id = ?");
            
            $update_stmt->bind_param(
                "iiiiiiiiiii", 
                $emotional_connection, 
                $exploratory_learning, 
                $visual_feedback, 
                $real_life_relevance, 
                $progress_tracking, 
                $conceptual_understanding, 
                $collaboration_experimentation, 
                $game_balance, 
                $immediate_feedback,
                $user_id,
                $game_id
            );

            if ($update_stmt->execute()) {
                echo "<p>Rating updated successfully!</p>";
                header("Refresh:2; url=home.php");  // Redirect to home after 2 seconds
                exit();
            } else {
                echo "<p>Error updating rating: " . $update_stmt->error . "</p>";
            }

            $update_stmt->close();
        } else {
            // User has not rated the game before, so we will insert a new rating
            $insert_stmt = $conn->prepare("INSERT INTO ratings 
                (user_id, game_id, emotional_connection, exploratory_learning, visual_feedback, 
                real_life_relevance, progress_tracking, conceptual_understanding, 
                collaboration_experimentation, game_balance, immediate_feedback) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $insert_stmt->bind_param(
                "iiiiiiiiiii", 
                $user_id, 
                $game_id, 
                $emotional_connection, 
                $exploratory_learning, 
                $visual_feedback, 
                $real_life_relevance, 
                $progress_tracking, 
                $conceptual_understanding, 
                $collaboration_experimentation, 
                $game_balance, 
                $immediate_feedback
            );

            if ($insert_stmt->execute()) {
                echo "<p>Rating submitted successfully!</p>";
                header("Refresh:2; url=home.php");  // Redirect to home after 2 seconds
                exit();
            } else {
                echo "<p>Error submitting rating: " . $insert_stmt->error . "</p>";
            }

            $insert_stmt->close();
        }

        $check_stmt->close();
    }

    $conn->close();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate <?php echo htmlspecialchars($game_name); ?></title>
    <link rel="stylesheet" href="style/rate.css">
    <style>
        /* Tooltip styling */
        .tooltip {
            display: none;
            position: absolute;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            font-size: 12px;
        }

        .info-icon {
            cursor: pointer;
            color: #007bff;
        }

        .rating-container {
            position: relative;
        }
    </style>
</head>
<body>
    <h1>Rate the Game: <?php echo htmlspecialchars($game_name); ?></h1>
    <form method="POST">
        <div class="rating-container">
            <label for="emotional_connection">Emotional Connection (1-9):</label>
            <span class="info-icon" onmouseover="showTooltip('tooltip1')" onmouseout="hideTooltip('tooltip1')">?</span>
            <input type="number" id="emotional_connection" name="emotional_connection" min="1" max="9" required>
            <div id="tooltip1" class="tooltip">Coming soon</div>
        </div>

        <div class="rating-container">
            <label for="exploratory_learning">Exploratory Learning (1-9):</label>
            <span class="info-icon" onmouseover="showTooltip('tooltip2')" onmouseout="hideTooltip('tooltip2')">?</span>
            <input type="number" id="exploratory_learning" name="exploratory_learning" min="1" max="9" required>
            <div id="tooltip2" class="tooltip">Coming soon</div>
        </div>

        <div class="rating-container">
            <label for="visual_feedback">Visual Feedback (1-9):</label>
            <span class="info-icon" onmouseover="showTooltip('tooltip3')" onmouseout="hideTooltip('tooltip3')">?</span>
            <input type="number" id="visual_feedback" name="visual_feedback" min="1" max="9" required>
            <div id="tooltip3" class="tooltip">Coming soon</div>
        </div>

        <div class="rating-container">
            <label for="real_life_relevance">Real-Life Relevance (1-9):</label>
            <span class="info-icon" onmouseover="showTooltip('tooltip4')" onmouseout="hideTooltip('tooltip4')">?</span>
            <input type="number" id="real_life_relevance" name="real_life_relevance" min="1" max="9" required>
            <div id="tooltip4" class="tooltip">Coming soon</div>
        </div>

        <div class="rating-container">
            <label for="progress_tracking">Progress Tracking (1-9):</label>
            <span class="info-icon" onmouseover="showTooltip('tooltip5')" onmouseout="hideTooltip('tooltip5')">?</span>
            <input type="number" id="progress_tracking" name="progress_tracking" min="1" max="9" required>
            <div id="tooltip5" class="tooltip">Coming soon</div>
        </div>

        <div class="rating-container">
            <label for="conceptual_understanding">Conceptual Understanding (1-9):</label>
            <span class="info-icon" onmouseover="showTooltip('tooltip6')" onmouseout="hideTooltip('tooltip6')">?</span>
            <input type="number" id="conceptual_understanding" name="conceptual_understanding" min="1" max="9" required>
            <div id="tooltip6" class="tooltip">Coming soon</div>
        </div>

        <div class="rating-container">
            <label for="collaboration_experimentation">Collaboration/Experimentation (1-9):</label>
            <span class="info-icon" onmouseover="showTooltip('tooltip7')" onmouseout="hideTooltip('tooltip7')">?</span>
            <input type="number" id="collaboration_experimentation" name="collaboration_experimentation" min="1" max="9" required>
            <div id="tooltip7" class="tooltip">Coming soon</div>
        </div>

        <div class="rating-container">
            <label for="game_balance">Game Balance (1-9):</label>
            <span class="info-icon" onmouseover="showTooltip('tooltip8')" onmouseout="hideTooltip('tooltip8')">?</span>
            <input type="number" id="game_balance" name="game_balance" min="1" max="9" required>
            <div id="tooltip8" class="tooltip">Coming soon</div>
        </div>

        <div class="rating-container">
            <label for="immediate_feedback">Immediate Feedback (1-9):</label>
            <span class="info-icon" onmouseover="showTooltip('tooltip9')" onmouseout="hideTooltip('tooltip9')">?</span>
            <input type="number" id="immediate_feedback" name="immediate_feedback" min="1" max="9" required>
            <div id="tooltip9" class="tooltip">Coming soon</div>
        </div>

        <button type="submit">Submit Rating</button>
    </form>

    <a href="home.php">Back to Home</a>

    <script>
        function showTooltip(id) {
            document.getElementById(id).style.display = 'block';
        }

        function hideTooltip(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>

</body>
</html>

<style>
    body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #fff;
    margin: 0;
    padding: 20px;
    color: #135085;
}

h1, h2 {
    text-align: center;
    color: #135085;
}

form {
    background: #6EC0E3;
    padding: 15px;
    max-width: 400px;
    margin: 20px auto;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    align-items: flex-start; 
}

.rating-container {
    display: flex;
    align-items: center; /* Aligns label and input in the center vertically */
    margin-bottom: 15px; /* Space between each rating container */
    width: 100%;
}

label {
    font-size: 18px;
    margin-right: 15px; /* Space between label and input (about 3cm) */
    flex: 1; /* Ensures the label occupies available space */
}

input[type="number"] {
    width: 30px; 
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #135085;
    font-size: 15px;
    background-color: #fff;
    color: #135085;
}

.info-icon {
    cursor: pointer;
    color: #007bff;
    margin-left: 5px;
}

.tooltip {
    display: none;
    position: absolute;
    color: #fff;
    padding: 5px;
    border-radius: 5px;
    font-size: 12px;
}

button {
    background-color: #135085;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 20px; 
    display: block;
    width: auto;
    margin: 20px auto;
}

button:hover {
    background-color: #0e3b63;
}

.back-to-home {
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

.back-to-home:hover {
    color:  #5c93cd;
}


</style>

<?php
    include("footer.html");
?>