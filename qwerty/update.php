<!DOCTYPE html>
<html>
<head>
    <title>TV Show Update Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

    <?php
    include 'connect.php';
    $id = $_GET["id"];
    $qu = "select `photo` from `animes` where id=$id";
    $result = mysqli_query($con, $qu);
    $photo = mysqli_fetch_assoc($result);

    // Function to insert data into the database
    function insertData($con, $id, $seasons, $episodes) {
        $sql = "INSERT INTO anime_info (id, seasons, episodes) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iss", $id, $seasons, $episodes);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update data in the database
    function updateData($con, $id, $seasons, $episodes) {
        $sql = "UPDATE anime_info SET seasons = ?, episodes = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssi", $seasons, $episodes, $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Check if 'id' is set in the query string
    if (isset($_GET['id'])) {
        // Connect to your database (replace with your database credentials)

        // Check connection
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        // Check if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["id"];
            $seasons = $_POST["seasons"];
            $episodes = array();

            // Collect episode data based on the season input fields
            for ($i = 1; $i <= $seasons; $i++) {
                $episodes[] = $_POST["season" . $i . "episodes"];
            }

            // Serialize the episodes array before inserting or updating in the database
            $serialized_episodes = serialize($episodes);

            // Check if data for this ID already exists in the database
            $check_sql = "SELECT seasons, episodes FROM anime_info WHERE id = $id";
            $check_result = mysqli_query($con, $check_sql);

            if ($check_result->num_rows > 0) {
                // Data exists, update it
                if (updateData($con, $id, $seasons, $serialized_episodes)) {
                    echo "<p class='snackbar'>Data has been successfully updated.</p>";
                    header("Location: info.php?id=".$id);
                } else {
                    echo "<p class='snackbar'>Error: Data update failed.</p>";
                }
            } else {
                // Data does not exist, insert it
                if (insertData($con, $id, $seasons, $serialized_episodes)) {
                    echo "<p class='snackbar'>Data has been successfully inserted into the database.</p>";
                } else {
                    echo "<p class='snackbar'>Error: Data insertion failed.</p>";
                }
            }
        }

        // Prepare and execute SQL query to fetch data for the given ID
        $id = $_GET['id'];
        $sql = "SELECT seasons, episodes FROM anime_info WHERE id = $id";
        $sql1 = "SELECT `name` FROM animes WHERE id = $id";

        $result = mysqli_query($con, $sql);
        $result1 = mysqli_query($con, $sql1);

        $name = mysqli_fetch_assoc($result1);
        if ($result->num_rows > 0) {
            // Data exists, display it
            $row = $result->fetch_assoc();
            $episodes = unserialize($row["episodes"]);
            ?>
            <body class="info" style="background-image: <?php echo 'url('. $photo['photo'] . ')';?>;color:#d2f53b;">

<main>
           <div class="aa">
           <h2><?php echo $name['name']; ?></h2>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="seasons">Number of Seasons:</label>
                <input type="number" id="seasons" name="seasons" value="<?php echo $row["seasons"]; ?>" required>
                <div id="episodes-container">
                    <!-- Dynamic episode input fields will be added here -->
                    <?php
                    for ($i = 1; $i <= $row["seasons"]; $i++) {
                        $episodeValue = isset($episodes[$i - 1]) ? $episodes[$i - 1] : '';
                        ?>
                        <label for="season<?php echo $i; ?>episodes">Season <?php echo $i; ?>:</label>
                        <input type="number" id="season<?php echo $i; ?>episodes" name="season<?php echo $i; ?>episodes" value="<?php echo $episodeValue; ?>" required><br>
                    <?php } ?>
                </div>
                <input class="btn-hover color-10" type="submit" value="Update" onclick="myFunction()">
            </form>
           </div>
           </main>
            <script>
               $(document).ready(function() {
    $("#seasons").on("input", function() {
        var numSeasons = $(this).val();
        var episodesContainer = $("#episodes-container");

        // Remove or add input fields based on the change in the number of seasons
        var currentSeasons = episodesContainer.find("label").length;
        if (numSeasons > currentSeasons) {
            // Add new input fields for additional seasons
            for (var i = currentSeasons + 1; i <= numSeasons; i++) {
                episodesContainer.append('<label for="season' + i + 'episodes">Episodes for Season ' + i + ':</label>');
                episodesContainer.append('<input type="number" id="season' + i + 'episodes" name="season' + i + 'episodes" required><br>');
            }
        } else if (numSeasons < currentSeasons) {
            // Remove input fields for reduced seasons
            episodesContainer.find("label:gt(" + (numSeasons - 1) + ")").remove();
            episodesContainer.find("input:gt(" + (numSeasons - 1) + ")").remove();
        }
    });
    function myFunction() {
  // Get the snackbar DIV
  var x = document.getElementsByClassName("snackbar");

  // Add the "show" class to DIV
  x.className = "show";

  // After 3 seconds, remove the show class from DIV
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}F
});

            </script>
            <?php
        } else {
            // Data does not exist, show an error message
            echo "<p>Error: Data for ID $id not found.</p>";
        }

        // Close the database connection
        $con->close();
    } else {
        // 'id' is not set in the query string, provide a message
        echo "<p>Please provide an 'id' parameter in the URL to view or edit the data.</p>";
    }
    ?>
</body>
</html>
