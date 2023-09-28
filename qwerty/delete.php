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
    <title>Delete Anime</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <main class="main">
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <h2>Are you sure you want to delete this anime?</h2>
            <p>Name: <?php echo $row['name']; ?></p>
            <p>Seasons: <?php echo $row['seasons']; ?></p>
            <p>Episodes: <?php echo $row['episodes']; ?></p>
            <p>Status: <?php echo $row['status']; ?></p>
            <input class="btn-hover color-10" name="delete" type="submit" value="DELETE">
        </form>
    </main>
</body>

</html>

<?php
if (isset($_POST["delete"])) {
    $id = $_POST["id"];

    $query = "DELETE FROM animes WHERE id=$id";
    $result = mysqli_query($con, $query);

    if ($result) {
        $reset_query_1 = "SET @num := 0;";
        $reset_query_2 = "UPDATE animes SET id = @num := (@num + 1);";
        $reset_query_3 = "ALTER TABLE animes AUTO_INCREMENT = 1;";
        $reset_query_4 = "DELETE FROM anime_info WHERE id=$id";
        
        // Execute each query individually
        $result_1 = mysqli_query($con, $reset_query_1);
        $result_2 = mysqli_query($con, $reset_query_2);
        $result_3 = mysqli_query($con, $reset_query_3);
        $result_4 = mysqli_query($con, $reset_query_4);
        
        if ($result_1 && $result_2 && $result_3) {
            header('Location: list.php'); // Redirect to the list page after resetting.
        } else {
            echo "Error resetting auto-increment: " . mysqli_error($con);
        }
        
    } else {
        echo "Error deleting anime: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>