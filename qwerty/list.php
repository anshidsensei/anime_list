<?php
include "connect.php";
$query = "select * from animes";
$result = mysqli_query($con, $query);
$numRows = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitter:ital@1&family=Borel&family=Noto+Sans+JP:wght@600&family=Playfair+Display:wght@400;700&family=Poppins&family=Roboto+Slab:wght@100&display=swap" rel="stylesheet">
</head>

<body>
    <header style="<?php echo ($numRows > 0) ? 'display: flex;' : 'display: none;'; ?>">
        <div>
            <h2>ANIME UFOTABLE</h2>
        </div>
        <div>
            <!-- Filter button initially displayed -->
            <div class="icon" id="filter-button">
                    <svg class="space" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M440-120v-240h80v80h320v80H520v80h-80Zm-320-80v-80h240v80H120Zm160-160v-80H120v-80h160v-80h80v240h-80Zm160-80v-80h400v80H440Zm160-160v-240h80v80h160v80H680v80h-80Zm-480-80v-80h400v80H120Z" />
                    </svg>
                <span>Filter</span>
            </div>
            <!-- Filter dropdown initially hidden -->
            <div class="filter-container" id="filter-dropdown" style="display: none;">
                <select id="status-filter">
                    <option value="all">All</option>
                    <option value="on going">On Going</option>
                    <option value="coming soon">Coming Soon</option>
                    <option value="not confirmed">Not Confirmed</option>
                    <option value="completed">Completed</option>

                </select>
            </div>

            <div class="search-container">
                <input type="text" id="search-input" placeholder="Search by name...">
                <div class="icon" id="search-button">
                    <svg id="icon-svg" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"></path>
                    </svg>
                    <span>Search</span>
                </div>
            </div>

        </div>
    </header>
    <main style="<?php echo ($numRows > 0) ? 'justify-content:start' : 'justify-content: center;'; ?>">
        <div class="wrap" style="<?php echo ($numRows > 0) ? 'display: block;' : 'display: none;'; ?>">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>SEASON</th>
                        <th>EPISODE</th>
                        <th>STATUS</th>
                        <th>EDIT</th>
                        <th>DELETE</th>
                        <th>INFO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $i = 1;
                        while ($row = mysqli_fetch_array($result)) {
                    ?>
                            <tr style=<?php echo $i % 2 == 0 ? "background-color:#e8e9ea;" : "background-color:#caccce;" ?>>
                                <td><?php echo $row['id'] ?></td>
                                <td><?php echo $row['name'] ?></td>
                                <td><?php echo $row['seasons'] ?></td>
                                <td><?php echo $row['episodes'] ?></td>
                                <td><?php echo $row['status'] ?></td>
                                <td>
                                    <a href=<?php echo 'edit.php?id=' . $row['id'] ?>>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                            <path d="M200-200h56l345-345-56-56-345 345v56Zm572-403L602-771l56-56q23-23 56.5-23t56.5 23l56 56q23 23 24 55.5T829-660l-57 57Zm-58 59L290-120H120v-170l424-424 170 170Zm-141-29-28-28 56 56-28-28Z" />
                                        </svg>
                                    </a>
                                </td>
                                <td>
                                    <a href=<?php echo 'delete.php?id=' . $row['id'] ?>>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>
                                    </a>
                                </td>
                                <td>
                                    <a href=<?php echo 'info.php?id=' . $row['id'] ?>>
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                            <path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                    <?php
                            $i++;
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    ?>
                </tbody>

            </table>

        </div>

        <a href="create.php">
            <div class="add">
                <svg class="addbtn" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                </svg>
            </div>

        </a>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterButton = document.getElementById("filter-button");
            const filterDropdown = document.getElementById("filter-dropdown");
            const statusFilter = document.getElementById("status-filter");
            const tableRows = document.querySelectorAll("tbody tr");
            const searchInput = document.getElementById("search-input");
            const searchButton = document.getElementById("search-button");

            searchInput.style.display = "none"; // Hide the search bar initially
            let isSearchBarVisible = false;


            function toggleSearchBar() {
                if (!isSearchBarVisible) {
                    searchInput.style.display = "block";
                    document.getElementById('icon-svg').style.display = "none";
                    searchButton.querySelector("span").textContent = "✕"; // Change button text to "x"
                    isSearchBarVisible = true;
                } else {
                    searchInput.style.display = "none";
                    document.getElementById('icon-svg').style.display = "block";
                    searchButton.querySelector("span").textContent = "Search"; // Change button text back to "Search"
                    isSearchBarVisible = false;
                    // Clear the search input
                    searchInput.value = "";
                    // Show all table rows when hiding the search bar
                    tableRows.forEach(function(row) {
                        row.style.display = "table-row";
                    });
                }
            }


            searchButton.addEventListener("click", function() {
                toggleSearchBar();
            });

            function filterByName(searchQuery) {
                tableRows.forEach(function(row) {
                    const nameCell = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
                    if (nameCell.includes(searchQuery)) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            }

            filterButton.addEventListener("click", function() {
                // Toggle the visibility of the filter dropdown
                if (filterDropdown.style.display === "none" || filterDropdown.style.display === "") {
                    filterDropdown.style.display = "block";
                    filterButton.querySelector("span").textContent = "Clear"; // Change button text
                } else {
                    filterDropdown.style.display = "none";
                    filterButton.querySelector("span").textContent = "Filter"; // Reset button text
                    statusFilter.value = "all"; // Reset the filter dropdown to "All"
                    // Show all table rows
                    tableRows.forEach(function(row) {
                        row.style.display = "table-row";
                    });
                }
            });

            statusFilter.addEventListener("change", function() {
                const selectedStatus = statusFilter.value;

                tableRows.forEach(function(row) {
                    const statusCell = row.querySelector("td:nth-child(5)").textContent.toLowerCase();
                    if (selectedStatus === "all" || statusCell.includes(selectedStatus)) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            });

            searchInput.addEventListener("input", function() {
                const searchQuery = searchInput.value.trim().toLowerCase();
                filterByName(searchQuery);
            });
        });
    </script>

</body>

</html>
<?php
mysqli_close($con);
?>