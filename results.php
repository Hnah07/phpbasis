<?php require("db.inc.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Poll Results</h1>
    <?php
    // Berekenen van de totaal stemen
    $totalVotesQuery = "SELECT SUM(vote_count) as total_votes FROM votes ORDER BY id";
    $totalVotesResult = $db->query($totalVotesQuery);
    $totalVotesRow = $totalVotesResult->fetch(PDO::FETCH_ASSOC);
    $totalVotes = $totalVotesRow['total_votes'];

    if ($totalVotes > 0) {
        $resultsQuery = "SELECT artist_name, vote_count FROM votes";
        $results = $db->query($resultsQuery);

        foreach ($results as $row) {
            $artistName = $row['artist_name'];
            $voteCount = $row['vote_count'];
            $percentage = ($voteCount / $totalVotes) * 100;

            echo '<div class="result-bar">';
            echo '<div class="label">' . $artistName . ' - ' . number_format($percentage, 2) . '%</div>';
            echo '<div class="bar" style="width:' . $percentage . '%"></div>';
            echo '</div>';
        }
    } else {
        echo '<p>No votes have been submitted yet.</p>';
    }

    if (!empty($allOthers)) {
        echo '<h2>Other Artists</h2>';
        echo '<ul>';
        foreach ($allOthers as $artist) {
            echo '<li>' . $artist . '</li>';
        }
        echo '</ul>';
    }

    ?>
</body>

<a href="index.php">Back to Index.PHP</a>

</html>