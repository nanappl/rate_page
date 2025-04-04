<?php
// Enable error reporting to display any issues
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("dbs.php");

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Get the selected game ID from the query string
if (!isset($_GET['game_id'])) {
    header("Location: home.php");
    exit();
}

$game_id = intval($_GET['game_id']);

// Fetch game details for display
$stmt = $conn->prepare("SELECT name FROM games WHERE id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$stmt->bind_result($game_name);
$stmt->fetch();
$stmt->close();

// Handle form submission for rating
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $emotional_connection = intval($_POST["emotional_connection"]);
    $exploratory_learning = intval($_POST["exploratory_learning"]);
    $visual_feedback = intval($_POST["visual_feedback"]);
    $real_life_relevance = intval($_POST["real_life_relevance"]);
    $progress_tracking = intval($_POST["progress_tracking"]);
    $conceptual_understanding = intval($_POST["conceptual_understanding"]);
    $collaboration_experimentation = intval($_POST["collaboration_experimentation"]);
    $game_balance = intval($_POST["game_balance"]);
    $immediate_feedback = intval($_POST["immediate_feedback"]);
    $user_id = $_SESSION["user_id"];  // Get user ID from session

    // Check if the user has already rated this game
    $stmt = $conn->prepare("SELECT id FROM ratings WHERE user_id = ? AND game_id = ?");
    $stmt->bind_param("ii", $user_id, $game_id);
    $stmt->execute();
    $stmt->store_result();

    // If the user has already rated the game, update the rating
    if ($stmt->num_rows > 0) {
        // Update the existing rating
        $stmt = $conn->prepare("UPDATE ratings SET 
                                emotional_connection = ?, 
                                exploratory_learning = ?, 
                                visual_feedback = ?, 
                                real_life_relevance = ?, 
                                progress_tracking = ?, 
                                conceptual_understanding = ?, 
                                collaboration_experimentation = ?, 
                                game_balance = ?, 
                                immediate_feedback = ? 
                                WHERE user_id = ? AND game_id = ?");
        $stmt->bind_param("iiiiiiiiii", $emotional_connection, $exploratory_learning, $visual_feedback, 
                         $real_life_relevance, $progress_tracking, $conceptual_understanding, 
                         $collaboration_experimentation, $game_balance, $immediate_feedback, $user_id, $game_id);

        if ($stmt->execute()) {
            echo "<p>Rating updated successfully!</p>";
        } else {
            echo "<p>Error updating rating: " . $stmt->error . "</p>";
        }
    } else {
        // If the user hasn't rated the game yet, insert a new rating
        $stmt = $conn->prepare("INSERT INTO ratings 
                                (user_id, game_id, emotional_connection, exploratory_learning, visual_feedback, 
                                 real_life_relevance, progress_tracking, conceptual_understanding, 
                                 collaboration_experimentation, game_balance, immediate_feedback) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiiiiiiiii", $user_id, $game_id, $emotional_connection, $exploratory_learning, 
                         $visual_feedback, $real_life_relevance, $progress_tracking, $conceptual_understanding, 
                         $collaboration_experimentation, $game_balance, $immediate_feedback);

        if ($stmt->execute()) {
            echo "<p>Rating submitted successfully!</p>";
        } else {
            echo "<p>Error submitting rating: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
}

// Fetch average ratings for the selected game
$avg_query = "
    SELECT 
        AVG(emotional_connection) AS avg_emotional_connection,
        AVG(exploratory_learning) AS avg_exploratory_learning,
        AVG(visual_feedback) AS avg_visual_feedback,
        AVG(real_life_relevance) AS avg_real_life_relevance,
        AVG(progress_tracking) AS avg_progress_tracking,
        AVG(conceptual_understanding) AS avg_conceptual_understanding,
        AVG(collaboration_experimentation) AS avg_collaboration_experimentation,
        AVG(game_balance) AS avg_game_balance,
        AVG(immediate_feedback) AS avg_immediate_feedback
    FROM ratings
    WHERE game_id = ?";
$stmt = $conn->prepare($avg_query);
$stmt->bind_param("i", $game_id);
$stmt->execute();
$stmt->bind_result($avg_emotional_connection, $avg_exploratory_learning, $avg_visual_feedback, 
                   $avg_real_life_relevance, $avg_progress_tracking, $avg_conceptual_understanding, 
                   $avg_collaboration_experimentation, $avg_game_balance, $avg_immediate_feedback);
$stmt->fetch();
$stmt->close();

// Fetch the user's rating for the selected game
$user_ratings = [];
$stmt = $conn->prepare("SELECT emotional_connection, exploratory_learning, visual_feedback, 
                               real_life_relevance, progress_tracking, conceptual_understanding, 
                               collaboration_experimentation, game_balance, immediate_feedback 
                        FROM ratings 
                        WHERE game_id = ? AND user_id = ?");
$stmt->bind_param("ii", $game_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($user_ratings['emotional_connection'], $user_ratings['exploratory_learning'], 
                   $user_ratings['visual_feedback'], $user_ratings['real_life_relevance'], 
                   $user_ratings['progress_tracking'], $user_ratings['conceptual_understanding'], 
                   $user_ratings['collaboration_experimentation'], $user_ratings['game_balance'], 
                   $user_ratings['immediate_feedback']);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate <?php echo htmlspecialchars($game_name); ?></title>
</head>
<body>
    <h1>Rate the Game: <?php echo htmlspecialchars($game_name); ?></h1>

    <form method="POST">
        <label for="emotional_connection">Emotional Connection (1-9):</label>
        <input type="number" id="emotional_connection" name="emotional_connection" min="1" max="9" required><br>

        <label for="exploratory_learning">Exploratory Learning (1-9):</label>
        <input type="number" id="exploratory_learning" name="exploratory_learning" min="1" max="9" required><br>

        <label for="visual_feedback">Visual Feedback (1-9):</label>
        <input type="number" id="visual_feedback" name="visual_feedback" min="1" max="9" required><br>

        <label for="real_life_relevance">Real-Life Relevance (1-9):</label>
        <input type="number" id="real_life_relevance" name="real_life_relevance" min="1" max="9" required><br>

        <label for="progress_tracking">Progress Tracking (1-9):</label>
        <input type="number" id="progress_tracking" name="progress_tracking" min="1" max="9" required><br>

        <label for="conceptual_understanding">Conceptual Understanding (1-9):</label>
        <input type="number" id="conceptual_understanding" name="conceptual_understanding" min="1" max="9" required><br>

        <label for="collaboration_experimentation">Collaboration/Experimentation (1-9):</label>
        <input type="number" id="collaboration_experimentation" name="collaboration_experimentation" min="1" max="9" required><br>

        <label for="game_balance">Game Balance (1-9):</label>
        <input type="number" id="game_balance" name="game_balance" min="1" max="9" required><br>

        <label for="immediate_feedback">Immediate Feedback (1-9):</label>
        <input type="number" id="immediate_feedback" name="immediate_feedback" min="1" max="9" required><br>

        <button type="submit">Submit Rating</button>
    </form>

    <h2>Average Ratings for Game: <?php echo htmlspecialchars($game_name); ?></h2>
    <table border="1">
        <tr><th>Category</th><th>Average Rating</th></tr>
        <tr><td>Emotional Connection</td><td><?php echo number_format($avg_emotional_connection, 2); ?></td></tr>
        <tr><td>Exploratory Learning</td><td><?php echo number_format($avg_exploratory_learning, 2); ?></td></tr>
        <tr><td>Visual Feedback</td><td><?php echo number_format($avg_visual_feedback, 2); ?></td></tr>
        <tr><td>Real-Life Relevance</td><td><?php echo number_format($avg_real_life_relevance, 2); ?></td></tr>
        <tr><td>Progress Tracking</td><td><?php echo number_format($avg_progress_tracking, 2); ?></td></tr>
        <tr><td>Conceptual Understanding</td><td><?php echo number_format($avg_conceptual_understanding, 2); ?></td></tr>
        <tr><td>Collaboration/Experimentation</td><td><?php echo number_format($avg_collaboration_experimentation, 2); ?></td></tr>
        <tr><td>Game Balance</td><td><?php echo number_format($avg_game_balance, 2); ?></td></tr>
        <tr><td>Immediate Feedback</td><td><?php echo number_format($avg_immediate_feedback, 2); ?></td></tr>
    </table>

    <h2>Your Rating for Game: <?php echo htmlspecialchars($game_name); ?></h2>
    <table border="1">
        <tr><th>Category</th><th>Your Rating</th></tr>
        <tr><td>Emotional Connection</td><td><?php echo $user_ratings['emotional_connection'] ?? 'Not rated'; ?></td></tr>
        <tr><td>Exploratory Learning</td><td><?php echo $user_ratings['exploratory_learning'] ?? 'Not rated'; ?></td></tr>
        <tr><td>Visual Feedback</td><td><?php echo $user_ratings['visual_feedback'] ?? 'Not rated'; ?></td></tr>
        <tr><td>Real-Life Relevance</td><td><?php echo $user_ratings['real_life_relevance'] ?? 'Not rated'; ?></td></tr>
        <tr><td>Progress Tracking</td><td><?php echo $user_ratings['progress_tracking'] ?? 'Not rated'; ?></td></tr>
        <tr><td>Conceptual Understanding</td><td><?php echo $user_ratings['conceptual_understanding'] ?? 'Not rated'; ?></td></tr>
        <tr><td>Collaboration/Experimentation</td><td><?php echo $user_ratings['collaboration_experimentation'] ?? 'Not rated'; ?></td></tr>
        <tr><td>Game Balance</td><td><?php echo $user_ratings['game_balance'] ?? 'Not rated'; ?></td></tr>
        <tr><td>Immediate Feedback</td><td><?php echo $user_ratings['immediate_feedback'] ?? 'Not rated'; ?></td></tr>
    </table>

</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
