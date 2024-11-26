<?php require("db.inc.php");

$errors = [];
$submitted = false;
if (@$_POST['submit']) {
    $submitted = true;
    if (!isset($_POST['other_name'])) {
        $errors[] = "Enter an other artist...";
    }
    if (!isset($_POST['artist'])) {
        $errors[] = "Select an artist please!";
    }
    if ($_POST['artist'] != 'other' && !empty($_POST['other_name'])) {
        $errors[] = "you already selected an artist!";
    }
    if ($_POST['artist'] == 'other' && empty($_POST['other_name'])) {
        $errors[] = "Please enter an artist name";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($errors) == 0) {
    $selectedArtist = $_POST['artist'];

    if ($selectedArtist == 'other') {
        $selectedArtist = $_POST['other_name'] ?: 'other';
    }

    $sql = "SELECT id, vote_count FROM votes WHERE artist_name = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$selectedArtist]);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $newVoteCount = $row['vote_count'] + 1;
        $sql = "UPDATE votes SET vote_count = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$newVoteCount, $row['id']]);
    } else {
        $sql = "INSERT INTO votes (artist_name, vote_count) VALUES (?, 1)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$selectedArtist]);
    }
    header('Location: results.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite singer</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <h1>Who is your favorite singer? 🎤</h1>

    <form action="index.php" method="POST">
        <label>
            <input type="radio" name="artist" value="Taylor Swift"> Taylor Swift
        </label><br>
        <label>
            <input type="radio" name="artist" value="Justin Bieber"> Justin Bieber
        </label><br>
        <label>
            <input type="radio" name="artist" value="Michael Jackson"> Michael Jackson
        </label><br>
        <label>
            <input type="radio" name="artist" value="Lady Gaga"> Lady Gaga
        </label><br>
        <label>
            <input type="radio" name="artist" value="other"> Others...
        </label><br>
        <input type="text" name="other_name" id="other_name" value="<?= @$other_name; ?>" placeholder="Enter artist name">
        <br><br>
        <?php if (count($errors) > 0): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <input type="submit" name="submit" id="submit" value="Vote">
    </form>
</body>

</html>