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

    <style>

        body{
            text-align: center;
        }
        .tooltip {
            width: 300px;
            right: -380px;
            display: none;
            position: absolute;
            background-color: #0e3b63;
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

        

        /* Styling for the select dropdown */
        select {
            font-size: 14px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #135085;
            background-color: #fff;
            color: #135085;
            width: 100px;
        }


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
    justify-content: space-between;
}

.rating-container {
    display: flex;
    align-items: center; /* Aligns label and input in the center vertically */
    margin-bottom: 15px; /* Space between each rating container */
    width: 100%;
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


.form_con{
    
    margin: 100px 0 300px 0;
}

    </style>
</head>
<body>
    <div class = "form_con">
    <h1>Rate the Game: <?php echo htmlspecialchars($game_name); ?></h1>
    <form method="POST">
        <?php
            $criteria = [
                "emotional_connection" => [
                    "label" => "Emotional Connection",
                    "tooltip" => "A game’s ability to make students feel personally connected to mathematical concepts, reducing anxiety and increasing engagement."
                ],
                "exploratory_learning" => [
                    "label" => "Exploratory Learning",
                    "tooltip" => "The extent to which a game allows students to investigate and experiment with mathematical ideas, rather than just solving pre-set problems."
                ],
                "visual_feedback" => [
                    "label" => "Visual Feedback",
                    "tooltip" => "How well a game provides immediate and clear visual responses to student actions, reinforcing their learning process."
                ],
                "real_life_relevance" => [
                    "label" => "Real-Life Relevance",
                    "tooltip" => "The ability of a game to connect mathematical concepts to real-world applications."
                ],
                "progress_tracking" => [
                    "label" => "Progress Tracking",
                    "tooltip" => "The game’s ability to monitor student development without relying solely on traditional assessments."
                ],
                "conceptual_understanding" => [
                    "label" => "Conceptual Understanding",
                    "tooltip" => "Whether a game reinforces deep comprehension rather than just memorization of procedures."
                ],
                "collaboration_experimentation" => [
                    "label" => "Collaboration / Experimentation",
                    "tooltip" => "The extent to which a game encourages teamwork, discussion, and creative problem-solving."
                ],
                "game_balance" => [
                    "label" => "Game Balance",
                    "tooltip" => "How well a game maintains challenge without becoming frustrating or too easy."
                ],
                "immediate_feedback" => [
                    "label" => "Immediate Feedback",
                    "tooltip" => "How quickly and effectively a game responds to student actions, helping them learn from mistakes."
                ]
            ];

            $i = 1;
            foreach ($criteria as $key => $data) {
                $label = $data["label"];
                $tooltip = $data["tooltip"];
                echo '<div class="rating-container">';
                echo "<label for=\"$key\">$label (1–9):</label>";
                echo "<select name=\"$key\" id=\"$key\" required>";
                echo "<option value=\"\" disabled selected>Select</option>";
                for ($j = 1; $j <= 9; $j++) {
                    echo "<option value=\"$j\">$j</option>";
                }
                echo "</select>";
                echo "<span class='info-icon' onmouseover=\"showTooltip('tooltip$i')\" onmouseout=\"hideTooltip('tooltip$i')\">?</span>";
                echo "<div id='tooltip$i' class='tooltip'>$tooltip</div>";
                echo '</div>';
                $i++;
            }
        ?>

        <button type="submit">Submit Rating</button>
        <button> <a href="home.php" class="back-to-home">Back to Home</a></button>
    </form>

    </div>
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

<?php
    include("footer.html");
?>
