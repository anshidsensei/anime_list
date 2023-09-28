<?php
    include 'connect.php';
    $id = $_GET["id"];
    $qu = "select `photo` from `animes` where id=$id";
    $result = mysqli_query($con, $qu);
    $photo = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Info</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="info" style="background-image: <?php echo 'url('. $photo['photo'] . ')';?>;color:white;-webkit-text-stroke: 1px black;font-size:1.5em">
<?php
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
    ?>
                    <p class="pppp">Data has been successfully updated.</p>
    <?php
                } else {
    ?>
                    <p class="pppp">Error: Data update failed.</p>
    <?php
                }
            } else {
                // Data does not exist, insert it
                if (insertData($con, $id, $seasons, $serialized_episodes)) {
    ?>
                    <p class="pppp">Data has been successfully inserted into the database.</p>
    <?php
                } else {
    ?>
                    <p>Error: Data insertion failed.</p>
    <?php
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
          <main><div class="aa">
          <h2><?php echo $name['name']; ?></h2>
            <p>Seasons: <?php echo $row["seasons"]; ?></p>
            <label for="selectSeason">Select Season:</label>
            <select id="selectSeason">
                <option value="">Select</option>
                <?php for ($i = 1; $i <= $row["seasons"]; $i++) { ?>
                    <option value="<?php echo $i; ?>">Season <?php echo $i; ?></option>
                <?php } ?>
            </select>
            Episodes: 
            <input type="text" id="episodeInfo" value="Please select a season." readonly></input>
            <a href=<?php echo "update.php?id=".$id ?>>
            <button class="add" id="usthad"><svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/></svg></button>
        </a>
          </div>
            <script>
                $(document).ready(function() {
                    $("#seasons").on("input", function() {
                        var numSeasons = $(this).val();
                        var episodesContainer = $("#episodes-container");
                        episodesContainer.empty(); // Clear previous input fields

                        for (var i = 1; i <= numSeasons; i++) {
                            episodesContainer.append('<label for="season' + i + 'episodes">Episodes for Season ' + i + ':</label>');
                            episodesContainer.append('<input type="number" id="season' + i + 'episodes" name="season' + i + 'episodes" required><br>');
                        }
                    });
                    var element = document.querySelector(".pppp");

                    // Use setTimeout to hide the element after 3 seconds (3000 milliseconds)
                    setTimeout(function() {
                        // Change the CSS style of the element to make it hidden
                        element.style.display = "none";
                    }, 3000);
                    $("#selectSeason").change(function() {
                        var selectedSeason = $(this).val();
                        var episodes = <?php echo json_encode($episodes); ?>;
                        var episodeInfo = $("#episodeInfo");
                        episodeInfo.val(episodes[selectedSeason - 1]);
                    });
                    function myFunction() {
                        // Get the snackbar DIV
                        var x = document.querySelector(".snackbar");

                        // Add the "show" class to DIV
                        x.className = "show";

                        // After 3 seconds, remove the show class from DIV
                        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
                    }
                });
            </script>
    <?php
        } else {
            // Data does not exist, show the input form for editing
    ?>
          <div class="aa">
          <h2><?php echo $name['name']; ?></h2>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="seasons">Number of Seasons:</label>
                <input type="number" id="seasons" name="seasons" required>
                <div id="episodes-container">
                    <!-- Dynamic episode input fields will be added here -->
                </div>
                <input class="btn-hover color-10" type="submit" value="Submit">
            </form>
          </div>
          </main>
            <script>
                $(document).ready(function() {
                    $("#seasons").on("input", function() {
                        var numSeasons = $(this).val();
                        var episodesContainer = $("#episodes-container");
                        episodesContainer.empty(); // Clear previous input fields

                        for (var i = 1; i <= numSeasons; i++) {
                            episodesContainer.append('<label for="season' + i + 'episodes">Episodes for Season ' + i + ':</label>');
                            episodesContainer.append('<input type="number" id="season' + i + 'episodes" name="season' + i + 'episodes" required><br>');
                        }
                    });
                });
            </script>
    <?php
        }

        // Close the database connection
        $con->close();
    } else {
        // 'id' is not set in the query string, provide a message
    ?>
        <p>Please provide an 'id' parameter in the URL to view or edit the data.</p>
    <?php
    }
    ?>
</body>
</html>
