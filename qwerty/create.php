<?php
include "connect.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <main class="main">
    <form method="post">
        <input name="name" type="text" placeholder="NAME OF THE SERIES" required>
        <input name="season" type="number" placeholder="NO. OF  SEASON" required>
        <input name="episode" type="number" placeholder="NO. OF EPISODE" required>
        <select name="status" id="status" required>
            <option value="">
                SELECT
            </option>
            <option value="ONGOING">
                ONGOING
            </option>
            <option value="COMING SOON">
                COMING SOON
            </option>
            <option value="NOT CONFIRMED">
                NOT CONFIRMED
            </option>
            <option value="COMPLETED">
                COMPLETED
            </option>
        </select>
        <input type="text" name="photo" placeholder="IMAGE LINK" required>
        <input class="btn-hover color-10" name="submit" type="submit" value="SUBMIT">
    </form>
    </main>
    

</body>

</html>
<?php
if (isset($_POST["submit"])) {
    $name = strtoupper($_POST["name"]);
    $season = strtoupper($_POST["season"]);
    $episode = strtoupper($_POST["episode"]);
    $status = $_POST["status"];
    $photo = $_POST["photo"];
    $query = "insert into animes(name,seasons,episodes,status,photo) values('$name','$season','$episode','$status','$photo') ";
    $result = mysqli_query($con, $query);
    header('Location: list.php');
}

mysqli_close($con);
?>