<?php
include "connect.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "SELECT * FROM animes WHERE id = $id";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo "Anime not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anime</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <main class="main">
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <input name="name" type="text" placeholder="NAME OF THE SERIES" value="<?php echo $row['name']; ?>" required>
            <input name="season" type="number" placeholder="NO. OF  SEASON" value="<?php echo $row['seasons']; ?>" required>
            <input name="episode" type="number" placeholder="NO. OF EPISODE" value="<?php echo $row['episodes']; ?>" required>
            <select name="status" id="status" required>
                <option value="">SELECT</option>
                <option value="ONGOING" <?php if ($row['status'] == 'ONGOING') echo 'selected'; ?>>ONGOING</option>
                <option value="COMING SOON" <?php if ($row['status'] == 'COMING SOON') echo 'selected'; ?>>COMING SOON</option>
                <option value="NOT CONFIRMED" <?php if ($row['status'] == 'NOT CONFIRMED') echo 'selected'; ?>>NOT CONFIRMED</option>
                <option value="COMPLETED" <?php if ($row['status'] == 'COMPLETED') echo 'selected'; ?>>COMPLETED</option>
            </select>
            <input type="text" name="photo" placeholder="IMAGE LINK" value="<?php echo $row['photo']; ?>" required>
            <input class="btn-hover color-10" name="update" type="submit" value="UPDATE">
        </form>
    </main>
</body>

</html>

<?php
if (isset($_POST["update"])) {
    $id = $_POST["id"];
    $name = strtoupper($_POST["name"]);
    $season = strtoupper($_POST["season"]);
    $episode = strtoupper($_POST["episode"]);
    $photo = $_POST["photo"];
    $status = $_POST["status"];

    $query = "UPDATE animes SET name='$name', seasons='$season', episodes='$episode', status='$status', photo='$photo' WHERE id=$id";
    $result = mysqli_query($con, $query);

    if ($result) {
        header('Location: list.php'); // Redirect to the list page after updating.
    } else {
        echo "Error updating anime: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>
