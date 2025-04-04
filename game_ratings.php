<?php
session_start();
include("dbs.php");
include("header.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch all games
$games_result = $conn->query("SELECT id, name FROM games");
$games = [];
while ($row = $games_result->fetch_assoc()) {
    $games[] = $row;
}

// Handle game selection
$selected_game_id = isset($_POST["game_id"]) ? intval($_POST["game_id"]) : null;
$selected_game_name = "";
$average_ratings = [];
$user_ratings = [];

if ($selected_game_id) {
    // Fetch selected game name
    $stmt = $conn->prepare("SELECT name FROM games WHERE id = ?");
    $stmt->bind_param("i", $selected_game_id);
    $stmt->execute();
    $stmt->bind_result($selected_game_name);
    $stmt->fetch();
    $stmt->close();

    // Fetch average ratings for the selected game
    $avg_stmt = $conn->prepare("
        SELECT 
            AVG(emotional_connection),
            AVG(exploratory_learning),
            AVG(visual_feedback),
            AVG(real_life_relevance),
            AVG(progress_tracking),
            AVG(conceptual_understanding),
            AVG(collaboration_experimentation),
            AVG(game_balance),
            AVG(immediate_feedback)
        FROM ratings
        WHERE game_id = ?
    ");
    $avg_stmt->bind_param("i", $selected_game_id);
    $avg_stmt->execute();
    $avg_stmt->bind_result(
        $average_ratings["emotional_connection"],
        $average_ratings["exploratory_learning"],
        $average_ratings["visual_feedback"],
        $average_ratings["real_life_relevance"],
        $average_ratings["progress_tracking"],
        $average_ratings["conceptual_understanding"],
        $average_ratings["collaboration_experimentation"],
        $average_ratings["game_balance"],
        $average_ratings["immediate_feedback"]
    );
    $avg_stmt->fetch();
    $avg_stmt->close();

    // Fetch the user's rating for this game
    $user_stmt = $conn->prepare("
        SELECT 
            emotional_connection,
            exploratory_learning,
            visual_feedback,
            real_life_relevance,
            progress_tracking,
            conceptual_understanding,
            collaboration_experimentation,
            game_balance,
            immediate_feedback
        FROM ratings
        WHERE game_id = ? AND user_id = ?
    ");
    $user_stmt->bind_param("ii", $selected_game_id, $user_id);
    $user_stmt->execute();
    $user_stmt->bind_result(
        $user_ratings["emotional_connection"],
        $user_ratings["exploratory_learning"],
        $user_ratings["visual_feedback"],
        $user_ratings["real_life_relevance"],
        $user_ratings["progress_tracking"],
        $user_ratings["conceptual_understanding"],
        $user_ratings["collaboration_experimentation"],
        $user_ratings["game_balance"],
        $user_ratings["immediate_feedback"]
    );
    $user_stmt->fetch();
    $user_stmt->close();
}

// For chart: Fetch average ratings for all games
$all_game_series = [];
foreach ($games as $game) {
    $stmt = $conn->prepare("
        SELECT 
            AVG(emotional_connection),
            AVG(exploratory_learning),
            AVG(visual_feedback),
            AVG(real_life_relevance),
            AVG(progress_tracking),
            AVG(conceptual_understanding),
            AVG(collaboration_experimentation),
            AVG(game_balance),
            AVG(immediate_feedback)
        FROM ratings
        WHERE game_id = ?
    ");
    $stmt->bind_param("i", $game["id"]);
    $stmt->execute();
    $stmt->bind_result(
        $ec, $el, $vf, $rr, $pt, $cu, $ce, $gb, $if
    );
    $stmt->fetch();
    $all_game_series[] = [
        "name" => $game["name"],
        "data" => [round($ec,2), round($el,2), round($vf,2), round($rr,2), round($pt,2), round($cu,2), round($ce,2), round($gb,2), round($if,2)]
    ];
    $stmt->close();
}

// For chart: Fetch current user's ratings for all games
$user_game_series = [];
foreach ($games as $game) {
    $stmt = $conn->prepare("
        SELECT 
            emotional_connection,
            exploratory_learning,
            visual_feedback,
            real_life_relevance,
            progress_tracking,
            conceptual_understanding,
            collaboration_experimentation,
            game_balance,
            immediate_feedback
        FROM ratings
        WHERE game_id = ? AND user_id = ?
    ");
    $stmt->bind_param("ii", $game["id"], $user_id);
    $stmt->execute();
    $stmt->bind_result(
        $ec, $el, $vf, $rr, $pt, $cu, $ce, $gb, $if
    );
    if ($stmt->fetch()) {
        $user_game_series[] = [
            "name" => $game["name"],
            "data" => [intval($ec), intval($el), intval($vf), intval($rr), intval($pt), intval($cu), intval($ce), intval($gb), intval($if)]
        ];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Ratings</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="style/rating.css">
</head>
<body>

<h1>Game Ratings</h1>

<form method="POST">
    <label for="game_id">Select a game:</label>
    <select name="game_id" id="game_id" required>
        <option value="">-- Choose a game --</option>
        <?php foreach ($games as $game): ?>
            <option value="<?= $game["id"] ?>" <?= $selected_game_id == $game["id"] ? "selected" : "" ?>>
                <?= htmlspecialchars($game["name"]) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Show Ratings</button>
    <a href="home.php">go home</a>
</form>


<?php if ($selected_game_id): ?>
    <h2>Average Ratings for <?= htmlspecialchars($selected_game_name) ?></h2>
    <table border="1">
        <tr>
            <?php foreach ($average_ratings as $key => $val): ?>
                <th><?= ucwords(str_replace("_", " ", $key)) ?></th>
            <?php endforeach; ?>
        </tr>
        <tr>
            <?php foreach ($average_ratings as $val): ?>
                <td><?= round($val, 2) ?></td>
            <?php endforeach; ?>
        </tr>
    </table>

    <h2>Your Rating for <?= htmlspecialchars($selected_game_name) ?></h2>
    <?php if (!empty($user_ratings)): ?>
        <table border="1">
            <tr>
                <?php foreach ($user_ratings as $key => $val): ?>
                    <th><?= ucwords(str_replace("_", " ", $key)) ?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <?php foreach ($user_ratings as $val): ?>
                    <td><?= $val ?></td>
                <?php endforeach; ?>
            </tr>
        </table>
    <?php else: ?>
        <p>You have not rated this game yet.</p>
    <?php endif; ?>
<?php endif; ?>

<div class="chart">
    <h2>All Games Average Ratings (Radar Chart)</h2>
    <div id="allGamesChart"></div>
</div>

<div class="chart">
    <h2>Your Ratings Across All Games (Radar Chart)</h2>
    <div id="userGamesChart"></div>
</div>

<script>
const categories = [
    "Emotional Connection", "Exploratory Learning", "Visual Feedback",
    "Real-Life Relevance", "Progress Tracking", "Conceptual Understanding",
    "Collaboration/Experimentation", "Game Balance", "Immediate Feedback"
];

const allGamesOptions = {
    chart: {
        type: 'radar',
        height: 500
    },
    title: {
        text: 'All Game Average Ratings'
    },
    xaxis: {
        categories: categories
    },
    yaxis: {
        min: 0,
        max: 9,
        tickAmount: 9
    },
    legend: {
        position: 'bottom'
    },
    series: <?= json_encode($all_game_series) ?>
};

const userGamesOptions = {
    chart: {
        type: 'radar',
        height: 500
    },
    title: {
        text: 'Your Ratings Across Games'
    },
    xaxis: {
        categories: categories
    },
    yaxis: {
        min: 0,
        max: 9,
        tickAmount: 9
    },
    legend: {
        position: 'bottom'
    },
    series: <?= json_encode($user_game_series) ?>
};

new ApexCharts(document.querySelector("#allGamesChart"), allGamesOptions).render();
new ApexCharts(document.querySelector("#userGamesChart"), userGamesOptions).render();
</script>

</body>
</html>

<?php
include("footer.html");
?>

