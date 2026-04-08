<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Traffic Crashes Viewer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<?php
$attributes = [
    "crash_date",
    "posted_speed_limit",
    "traffic_control_device",
    "device_condition",
    "weather_condition",
    "lighting_condition",
    "first_crash_type",
    "trafficway_type",
    "alignment",
    "roadway_surface_cond",
    "road_defect",
    "report_type",
    "crash_type",
    "intersection_related_i",
    "hit_and_run_i",
    "damage",
    "date_police_notified",
    "prim_contributory_cause",
    "sec_contributory_cause",
    "street_no",
    "street_direction",
    "street_name",
    "beat_of_occurrence",
    "num_units",
    "most_severe_injury",
    "injuries_total",
    "injuries_fatal",
    "injuries_incapacitating",
    "injuries_non_incapacitating",
    "injuries_reported_not_evident",
    "injuries_no_indication",
    "injuries_unknown",
    "crash_hour",
    "crash_day_of_week",
    "crash_month",
    "latitude",
    "longitude",
    "location",
];

# Using separate list for user friendly names
$attribute_names = [
    "Date",
    "Speed Limit",
    "Traffic Control Device",
    "Traffic Control Device Condition",
    "Weather",
    "Lighting",
    "First Crash Type",
    "Trafficway",
    "Alignment",
    "Roadway Surface Condition",
    "Road Defect",
    "Report Type",
    "Crash Type",
    "Intersection Related",
    "Hit and Run",
    "Damage",
    "Date Police Notified",
    "Primary Contributory Cause",
    "Secondary Contributory Cause",
    "Street Number",
    "Street Direction",
    "Street Name",
    "Beat of Occurrence",
    "Number of Units",
    "Most Severe Injury",
    "Total Injuries",
    "Fatal Injuries",
    "Incapacitating Injuries",
    "Non-Incapacitating Injuries",
    "Reported But Not Evident Injuries",
    "No Indication Injuries",
    "Unknown Injuries",
    "Hour",
    "Week",
    "Month",
    "Latitude",
    "Longitude",
    "Location",
];

$attributeSections = [

    "Time & Date" => [
        "crash_date",
        "date_police_notified",
        "crash_hour",
        "crash_day_of_week",
        "crash_month",
    ],

    "Location" => [
        "street_no",
        "street_direction",
        "street_name",
        "beat_of_occurrence",
        "location",
    ],

    "Environmental Conditions" => [
        "weather_condition",
        "lighting_condition",
    ],

    "Roadway & Infrastructure" => [
        "posted_speed_limit",
        "traffic_control_device",
        "device_condition",
        "trafficway_type",
        "alignment",
        "roadway_surface_cond",
        "road_defect",
    ],

    "Crash Characteristics" => [
        "first_crash_type",
        "crash_type",
        "intersection_related_i",
        "hit_and_run_i",
        "num_units",
        "damage",
        "prim_contributory_cause",
        "sec_contributory_cause",
    ],

    "Injury & Severity" => [
        "most_severe_injury",
        "injuries_total",
        "injuries_fatal",
        "injuries_incapacitating",
        "injuries_non_incapacitating",
        "injuries_reported_not_evident",
        "injuries_no_indication",
        "injuries_unknown",
    ],

    "Administrative / Reporting" => [
        "report_type",
    ],

    "Coordinates" => [
        "latitude",
        "longitude",
    ],
];

# Dynamically generate these by querying the database (instead of manually typing)
$attributeTypes = [
    "crash_date" => "date",
    "posted_speed_limit" => "number",
    "traffic_control_device" => "category",
    "device_condition" => "category",
    "weather_condition" => "category",
    "lighting_condition" => "category",
    "first_crash_type" => "category",
    "trafficway_type" => "category",
    "alignment" => "category",
    "roadway_surface_cond" => "category",
    "road_defect" => "category",
    "report_type" => "category",
    "crash_type" => "category",
    "intersection_related_i" => "boolean",
    "hit_and_run_i" => "boolean",
    "damage" => "number",
    "date_police_notified" => "date",
    "prim_contributory_cause" => "category",
    "sec_contributory_cause" => "category",
    "street_no" => "number",
    "street_direction" => "category",
    "street_name" => "text",
    "beat_of_occurrence" => "category",
    "num_units" => "number",
    "most_severe_injury" => "category",
    "injuries_total" => "number",
    "injuries_fatal" => "number",
    "injuries_incapacitating" => "number",
    "injuries_non_incapacitating" => "number",
    "injuries_reported_not_evident" => "number",
    "injuries_no_indication" => "number",
    "injuries_unknown" => "number",
    "crash_hour" => "number",
    "crash_day_of_week" => "category",
    "crash_month" => "category",
    "latitude" => "number",
    "longitude" => "number",
    "location" => "text",
];

# Dynamically generate these by querying the database (instead of manually typing)
$attributeSortBy = [
    "crash_date" => ["format" => "date", "min" => "", "max" => ""],
    "posted_speed_limit" => ["format" => "mph", "min" => 0, "max" => 55],
    "damage" => ["format" => "$", "min" => 0, "max" => 1500],
    "date_police_notified" => ["format" => "date", "min" => "", "max" => ""],
    "num_units" => ["format" => "", "min" => 1, "max" => 10],
    "injuries_total" => ["format" => "", "min" => 0, "max" => 20],
    "injuries_fatal" => ["format" => "", "min" => 0, "max" => 20],
    "injuries_incapacitating" => ["format" => "", "min" => 0, "max" => 20],
    "injuries_non_incapacitating" => ["format" => "", "min" => 0, "max" => 20],
    "injuries_reported_not_evident" => ["format" => "", "min" => 0, "max" => 20],
    "injuries_no_indication" => ["format" => "", "min" => 0, "max" => 20],
    "injuries_unknown" => ["format" => "", "min" => 0, "max" => 20],
    "crash_hour" => ["format" => "", "min" => 0, "max" => 24]
];

# Dynamically generate these by querying the database (instead of manually typing)
$attributeCategory = [
    "traffic_control_device" => ["NO CONTROLS", "TRAFFIC SIGNAL", "STOP SIGN/FLASHER", "UNKNOWN",],
    "device_condition" => ["NO CONTROLS", "FUNCTIONING PROPERLY", "FUNCTIONING IMPROPERLY", "UNKNOWN"],
    "weather_condition" => ["CLEAR", "SNOW", "UNKNOWN", "CLOUDY/OVERCAST", "SLEET/HAIL", "BLOWING SNOW", "FREEZING RAIN/DRIZZLE", "RAIN", "OTHER", "SEVERE CROSS WIND GATE", "FOG/SMOKE/HAZE"],
    "lighting_condition" => "category",
    "first_crash_type" => "category",
    "trafficway_type" => "category",
    "alignment" => "category",
    "roadway_surface_cond" => "category",
    "road_defect" => "category",
    "report_type" => "category",
    "crash_type" => "category",
    "prim_contributory_cause" => "category",
    "sec_contributory_cause" => "category",
    "street_direction" => "category",
    "beat_of_occurrence" => "category",
    "most_severe_injury" => "category",
    "crash_day_of_week" => "category",
    "crash_month" => "category",
]


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
    <div class="row">
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
                        if ($attributeTypes[$value] == "number") {

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
                        elseif ($attributeTypes[$value] == "date") {

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
                                    <button class='btn btn-primary' type='submit'>Sort Result</button>
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
                    <table class='table'>
                        <tr>
                            <th scope="row">Crash Count</th>
                            <td>100</td>
                        </tr>
                        <tr>
                            <th scope="row">Total Injuries</th>
                            <td>20</td>
                        </tr>
                    </table>
                    <?php
                    # TEMP - For Debugging purposes- will remove later (shows structure of POST array)
                    #echo "<pre>";
                    #print_r($_POST);
                    #echo "</pre>";
                    ?>
                </div>
            </div>
            <div class="row mt-2 bg-light rounded">
                <div class="col text-center ">
                    <h3 class="pb-2 pt-2">Data Display</h3>
                    <?php #Can update this to work with the results of SQL query instead
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        echo "<table class='table table-striped-columns'><tr>";
                        foreach ($selectedAttributes as $key => $value) {
                            echo "<th>" . $value . "</th>";
                        }
                        echo "</tr></table>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>