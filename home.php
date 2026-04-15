<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Traffic Crashes Viewer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<?php

$servername = "localhost";
$username = "root";  //user name
$password = "";  //password used to login MySQL server - replace with your own password if you have one set
$dbname = "trafficcrash";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require_once "query_metadata.php";

# GENERATE DATA STRUCTURES
$attributeSortBy = buildRangeMetaData($conn, $rangeAttributes);
$attributeCategory = buildSingleValueCategoryMetaData($conn, $singleValueCategories);
$attributeCategory += buildMultiValueCategoryMetaData($conn, $multiValueCategories);

?>


<body class="container-fluid">
    <div class="row bg-primary-subtle bg-gradient p-4">
        <div class="col-12 border-bottom border-light border-3">
            <h1 class="">Traffic Crashes Viewer</h1>
        </div>
        <div class="col-12 pt-2">
            <p class="text-primary">View traffic crashes from the <a href="https://data.cityofchicago.org/Transportation/Traffic-Crashes-Crashes/85ca-t3if/about_data" class="link-primary">City of Chicago</a></p>

        </div>

    </div>
    <div class="row align-items-start">
        <div class="col m-2 p-3 rounded bg-light">
            <div class="col text-center">
                <h3 class="border-bottom border-3 pb-3">Selection Criteria</h3>

                <!--Includes Section -->
                <form method="POST" action="">
                    <div class="row border-bottom border-3 pb-3 mb-2 align-items-center">
                        <div class="col-12 border-bottom p-2 me-2 bg-warning rounded-start-pill">Include</div>
                        <div class="col">
                            <!--Dynamically generate attributes from list -->
                            <?php foreach ($attributeSections as $sectionName => $attrs): ?>
                                <h6 class="p-1"><?= $sectionName ?></h6>
                                <div class="row border-bottom border-3">
                                    <?php foreach ($attrs as $index => $attr): ?>
                                        <div class="col-lg-3 col-5">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="attributes[]"
                                                    value="<?= htmlspecialchars($attr) ?>"
                                                    id="<?= $sectionName . '_' . $index ?>">
                                                <label class="form-check-label" for="<?= $sectionName . '_' . $index ?>">
                                                    <?= ucwords(str_replace('_', ' ', $attr)) ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                            <div class="col-12 pt-3"><input class="btn btn-outline-warning" type="submit" value="Apply Includes"></div>
                        </div>
                    </div>
                </form>

                <!--Generate Rest of Filters-->
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $selectedAttributes = $_POST['attributes'] ?? [];
                    echo "<form method='POST' action=''>";
                    #Sort By Section
                    echo "<div class='row border-bottom border-3 pb-3 mb-2'>
                    <div class='col-12 bg-warning rounded-start-pill p-1 mb-2'>Sort By</div>
                    <div class='col'>
                        <select class='form-select' name='sort[column]' aria-label='Sort by column'>";

                    #Dynamically generate each of the options from selectedAttributes
                    foreach ($selectedAttributes as $key => $value) {
                        echo "<option value='" . htmlspecialchars($value) . "'>" . htmlspecialchars($value) . "</option>";
                    }

                    echo "</select>
                        </div>
                        <div class='col'>
                            <div class='form-check form-check-inline'>
                                <input class='form-check-input'
                                    type='radio'
                                    name='sort[direction]'
                                    id='sortAsc'
                                    value='ASC'
                                    checked>
                                <label class='form-check-label' for='sortAsc'>Ascending</label>
                            </div>

                            <div class='form-check form-check-inline'>
                                <input class='form-check-input'
                                    type='radio'
                                    name='sort[direction]'
                                    id='sortDesc'
                                    value='DESC'>
                                <label class='form-check-label' for='sortDesc'>Descending</label>
                            </div>
                        </div>
                    </div>
                    ";

                    # Range Filter Section - two separate formats- one for numbers, one for dates
                    echo "<div class='row'>
                            <div class='col-12 bg-warning rounded-start-pill p-1 mb-2'>Range</div>";

                    foreach ($selectedAttributes as $key => $value) {

                        # Because there is a second seperate submit button, have to add back in the chosen attributes
                        echo "<input type='hidden' name='attributes[]' value='" . htmlspecialchars($value) . "'>";


                        // Numeric ranges
                        if (isset($rangeAttributes[$value]) && $rangeAttributes[$value]["type"] == "int") {

                            $min = $attributeSortBy[$value]["min"];
                            $max = $attributeSortBy[$value]["max"];

                            echo "
                                <div class='row border-bottom border-3 pb-3 mb-2'>
                                    <h6 class='pt-1'>" . htmlspecialchars($value) . "</h6>

                                    <div class='col'>
                                        <label for='range_{$value}_min' class='form-label'>Minimum</label>
                                        <input type='range'
                                            class='form-range'
                                            id='range_{$value}_min'
                                            name='ranges[{$value}][min]'
                                            min='{$min}'
                                            max='{$max}'>
                                    </div>

                                    <div class='col'>
                                        <label for='range_{$value}_max' class='form-label'>Maximum</label>
                                        <input type='range'
                                            class='form-range'
                                            id='range_{$value}_max'
                                            name='ranges[{$value}][max]'
                                            min='{$min}'
                                            max='{$max}'>
                                    </div>
                                </div>";
                        }

                        // Date ranges
                        elseif (isset($rangeAttributes[$value]) && $rangeAttributes[$value]["type"] == "date") {

                            echo "
                                <div class='row border-bottom border-3 pb-3 mb-2'>
                                    <h6 class='pt-1'>" . htmlspecialchars($value) . "</h6>

                                    <div class='col'>
                                        <label for='date_{$value}_start' class='form-label'>Start Date</label>
                                        <input type='date'
                                            id='date_{$value}_start'
                                            name='ranges[{$value}][start]'>
                                    </div>

                                    <div class='col'>
                                        <label for='date_{$value}_end' class='form-label'>End Date</label>
                                        <input type='date'
                                            id='date_{$value}_end'
                                            name='ranges[{$value}][end]'>
                                    </div>
                                </div>";
                        }
                    }

                    echo "</div>";


                    # Yes/No Section
                    echo "<div class='row border-bottom border-3 pb-3 mb-2 align-items-center'>
                            <div class='col-12 bg-warning rounded-start-pill p-1 mb-2'>Yes/No</div>";

                    foreach ($selectedAttributes as $key => $value) {

                        if ($attributeTypes[$value] != "boolean") {
                            continue;
                        }

                        echo "
                            <div class='col-4'>
                                <div class='col'>
                                    <h6 class='pt-1'>" . htmlspecialchars($value) . "</h6>
                                </div>

                                <div class='col border-start border-warning border-3 ps-2'>

                                    <div class='form-check'>
                                        <input class='form-check-input'
                                            type='checkbox'
                                            id='yes_{$value}'
                                            name='yesno[{$value}][]'
                                            value='yes'>
                                        <label class='form-check-label' for='yes_{$value}'>Yes</label>
                                    </div>

                                    <div class='form-check'>
                                        <input class='form-check-input'
                                            type='checkbox'
                                            id='no_{$value}'
                                            name='yesno[{$value}][]'
                                            value='no'>
                                        <label class='form-check-label' for='no_{$value}'>No</label>
                                    </div>

                                </div>
                            </div>";
                    }

                    echo "</div>";


                    # Categorical Section
                    echo "<div class='row border-bottom border-3 pb-3 mb-2 align-items-center'>
                        <div class='col-12 bg-warning rounded-start-pill p-1 mb-2'>Categorical</div>
                        <div class='row'>";

                    foreach ($selectedAttributes as $key => $value) {

                        if ($attributeTypes[$value] != "category") {
                            continue;
                        }

                        echo "
                            <div class='col-lg-4 col-6'>
                                <h6>" . htmlspecialchars($value) . "</h6>
                                <div class='col border-start border-warning border-3 ps-2'>
                                ";

                        foreach ($attributeCategory[$value] as $attribute => $condition) {

                            // Create a unique ID for each checkbox
                            $id = "cat_{$value}_" . preg_replace('/[^a-zA-Z0-9]/', '_', $condition);

                            echo "
                                <div class='form-check'>
                                    <input class='form-check-input'
                                        type='checkbox'
                                        id='{$id}'
                                        name='categories[{$value}][]'
                                        value='" . htmlspecialchars($condition) . "'>

                                    <label class='form-check-label' for='{$id}'>
                                        " . ucwords(strtolower($condition)) . "
                                    </label>
                                </div>
                            ";
                        }

                        echo "
                            </div>
                        </div>";
                    }

                    echo "</div></div>
                            <div class='row align-items-center'>
                                <div class='d-grid gap-2'>
                                    <button class='btn btn-primary' type='submit' name='run_query'>Sort Result</button>
                                </div>
                            </div>";
                    echo "</form>";
                }
                ?>

            </div>
        </div>
        <div class="col ms-2 p-2">
            <div class="row bg-light rounded">
                <div class="col text-center border-bottom border-3">
                    <h3 class="pb-2 pt-2">Crash Stats</h3>
                    <?php
                    # TEMP - For Debugging purposes- will remove later (shows structure of POST array)
                    // echo "<pre>";
                    // print_r($_POST);
                    // //echo $attributeSources["weather_condition"]["table"];
                    // echo "</pre>";
                    require_once "sql_builder.php";

                    if (isset($_POST['attributes'])) {
                        $selectedAttributes = $_POST["attributes"];
                    }

                    // Call the function to build the SQL query based on user input
                    if (isset($_POST['run_query'])) {
                        $query = buildSelectQuery(
                            $selectedAttributes,
                            $selectionTypes,
                            $singleValueCategories,
                            $multiValueCategories,
                            $secondaryAttributes
                        );

                        # TEMP - For Debugging purposes- will remove later (shows the generated SQL query)
                        //echo "<pre>$query</pre>";

                        $result = $conn->query($query);
                        $count = $result->num_rows;
                        
                        // Add more crash stats here as needed
                        echo "<table class='table'>
                                <tr>
                                    <th scope='row'>Crash Count</th>
                                    <td>" . $count . "</td>
                                </tr>
                            </table>";

                        if (!$result) {
                            die("Query failed: " . $conn->error);
                        }
                    }

                    ?>
                </div>
            </div>
            <div class="row mt-2 bg-light rounded">
                <div class="col text-center ">
                    <h3 class="pb-2 pt-2">Data Display</h3>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_query'])) {

                        $sortBy = $_POST['sort']['column'] ?? null;
                        $sortDir = $_POST['sort']['direction'] ?? null;
                        $arrow = ($sortDir === "DESC") ? "▼" : "▲";

                        echo "<table class='table table-striped-columns'><tr>";

                        foreach ($selectedAttributes as $attr) {

                            if ($attr === $sortBy) {
                                echo "<th class='table-warning fw-bold'>$attr $arrow</th>";
                            } else {
                                echo "<th>$attr</th>";
                            }
                        }

                        echo "</tr>";

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($selectedAttributes as $attr) {
                                echo "<td>" . htmlspecialchars($row[$attr] ?? "") . "</td>";
                            }
                            echo "</tr>";
                        }

                        echo "</table>";
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>