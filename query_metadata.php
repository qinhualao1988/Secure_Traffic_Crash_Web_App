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
    //"street_no",
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
    // Removing these because they're derived (and difficult to implement in filtering and sorting)- can add back in later if we have time
    // "crash_hour",
    // "crash_day_of_week",
    // "crash_month",
    "latitude",
    "longitude",
]; // attributes from crash table

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
];

$attributeSections = [

    "Time & Date" => [
        "crash_date",
        "date_police_notified",
        // "crash_hour",
        // "crash_day_of_week",
        // "crash_month",
    ],

    "Location" => [
        //"street_no",
        "street_direction",
        "street_name",
        "beat_of_occurrence",
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


$attributeTypes = [
    "crash_date" => "date",
    "posted_speed_limit" => "number",
    "traffic_control_device" => "string", // 04/17 editted: changed from category to string.
    "device_condition" => "string",
    "weather_condition" => "category",
    "lighting_condition" => "category",
    "first_crash_type" => "category",
    "trafficway_type" => "category",
    "alignment" => "category",
    "roadway_surface_cond" => "category",
    "road_defect" => "category",
    "report_type" => "string",
    "crash_type" => "category",
    "intersection_related_i" => "boolean",
    "hit_and_run_i" => "boolean",
    "damage" => "string",
    "date_police_notified" => "date",
    "prim_contributory_cause" => "category",
    "sec_contributory_cause" => "category",
    //"street_no" => "number",
    "street_direction" => "string",
    "street_name" => "string",
    "beat_of_occurrence" => "number",
    "num_units" => "number",
    "most_severe_injury" => "category",
    "injuries_total" => "number",
    "injuries_fatal" => "number",
    "injuries_incapacitating" => "number",
    "injuries_non_incapacitating" => "number",
    "injuries_reported_not_evident" => "number",
    "injuries_no_indication" => "number",
    "injuries_unknown" => "number",
    // "crash_hour" => "number",
    // "crash_day_of_week" => "category",
    // "crash_month" => "category",
    "latitude" => "number",
    "longitude" => "number",
];


# META DATA

$selectionTypes = [
    "posted_speed_limit" => "main",
    "crash_date" => "main",
    "date_police_notified" => "main",
    "hit_and_run_i" => "main",
    "intersection_related_i" => "main",
    "damage" => "main",
    "beat_of_occurrence" => "main",
    "num_units" => "main",
    "report_type" => "main",
    "latitude" => "main",
    "longitude" => "main",

    //"street_no" => "secondary",
    "street_direction" => "secondary",
    "street_name" => "secondary",

    "traffic_control_device" => "secondary",
    "device_condition" => "secondary",

    "injuries_total" => "secondary",
    "injuries_fatal" => "secondary",
    "injuries_incapacitating" => "secondary",
    "injuries_non_incapacitating" => "secondary",
    "injuries_reported_not_evident" => "secondary",
    "injuries_no_indication" => "secondary",
    "injuries_unknown" => "secondary",

    "weather_condition" => "single_lookup",
    "first_crash_type" => "single_lookup",
    "crash_type" => "single_lookup",
    "prim_contributory_cause" => "single_lookup", // Both reference contributorycausetype table
    "sec_contributory_cause" => "single_lookup", // Both reference contributorycausetype table
    "most_severe_injury" => "single_lookup",

    "lighting_condition" => "multi_lookup",
    "trafficway_type" => "multi_lookup",
    "alignment" => "multi_lookup",
    "roadway_surface_cond" => "multi_lookup",
    "road_defect" => "multi_lookup",

    // "crash_hour" => "derived",
    // "crash_day_of_week" => "derived",
    // "crash_month" => "derived"
];

$rangeAttributes = [
    "posted_speed_limit" => [
        "table" => "crash",
        "column" => "posted_speed_limit",
        "type" => "int"
    ],
    "crash_date" => [
        "table" => "crash",
        "column" => "crash_date",
        "type" => "date"
    ],
    "date_police_notified" => [
        "table" => "crash",
        "column" => "date_police_notified",
        "type" => "date"
    ],
    "injuries_total" => [
        "table" => "crashinjuries",
        "column" => "injuries_total",
        "type" => "int"
    ],
    "injuries_fatal" => [
        "table" => "crashinjuries",
        "column" => "injuries_fatal",
        "type" => "int"
    ],
    "injuries_incapacitating" => [
        "table" => "crashinjuries",
        "column" => "injuries_incapacitating",
        "type" => "int"
    ],
    "injuries_non_incapacitating" => [
        "table" => "crashinjuries",
        "column" => "injuries_non_incapacitating",
        "type" => "int"
    ],
    "injuries_reported_not_evident" => [
        "table" => "crashinjuries",
        "column" => "injuries_reported_not_evident",
        "type" => "int"
    ],
    "injuries_no_indication" => [
        "table" => "crashinjuries",
        "column" => "injuries_no_indication",
        "type" => "int"
    ],
    "injuries_unknown" => [
        "table" => "crashinjuries",
        "column" => "injuries_unknown",
        "type" => "int"
    ],
    "num_units" => [
        "table" => "crash",
        "column" => "num_units",
        "type" => "int"
    ],
    "latitude" => [
        "table" => "crash",
        "column" => "latitude",
        "type" => "double"
    ],
    "longitude" => [
        "table" => "crash",
        "column" => "longitude",
        "type" => "double"
    ],
    "beat_of_occurrence" => [
        "table" => "crash",
        "column" => "beat_of_occurrence",
        "type" => "int"
    ],
    // "street_no" => [
    //     "table" => "street",
    //     "column" => "street_no",
    //     "type" => "int"
    // ]
    // etc.
];

$singleValueCategories = [
    "weather_condition" => [
        "fk_table" => "crash",
        "fk_column" => "weather_condition_id",
        "lookup_table" => "weathercondition",
        "lookup_id" => "weather_condition_id",
        "lookup_label" => "condition_name"
    ],
    "first_crash_type" => [
        "fk_table" => "crash",
        "fk_column" => "first_crash_id",
        "lookup_table" => "firstcrashtype",
        "lookup_id" => "first_crash_id",
        "lookup_label" => "crash_type"
    ],
    "crash_type" => [
        "fk_table" => "crash",
        "fk_column" => "crash_type_id",
        "lookup_table" => "typeofcrash",
        "lookup_id" => "crash_type_id",
        "lookup_label" => "crash_name"
    ],
    "prim_contributory_cause" => [
        "fk_table" => "crash",
        "fk_column" => "prim_contributory_cause",
        "lookup_table" => "contributorycausetype",
        "lookup_id" => "contributory_cause_id",
        "lookup_label" => "contributory_cause"
    ],
    "sec_contributory_cause" => [
        "fk_table" => "crash",
        "fk_column" => "sec_contributory_cause",
        "lookup_table" => "contributorycausetype",
        "lookup_id" => "contributory_cause_id",
        "lookup_label" => "contributory_cause"
    ],
    "most_severe_injury" => [
        "fk_table"    => "crashinjuries",
        "fk_column"   => "most_severe_injury_id",
        "lookup_table" => "mostsevereinjurytype",
        "lookup_id"   => "most_severe_injury_id",
        "lookup_label" => "injury_type"
    ]
];

$multiValueCategories = [
    "lighting_condition" => [
        "junction_table" => "crashlighting",
        "junction_crash_fk" => "crash_record_id",
        "junction_cat_fk" => "lighting_condition_id",
        "lookup_table" => "lightingcondition",
        "lookup_id" => "lighting_condition_id",
        "lookup_label" => "condition_name"
    ],
    "trafficway_type" => [
        "junction_table" => "crashtrafficway",
        "junction_crash_fk" => "crash_record_id",
        "junction_cat_fk" => "traffic_way_type_id",
        "lookup_table" => "trafficwaytype",
        "lookup_id" => "traffic_way_type_id",
        "lookup_label" => "trafficway_type"
    ],
    "alignment" => [
        "junction_table" => "crashalignment",
        "junction_crash_fk" => "crash_record_id",
        "junction_cat_fk" => "alignment_id",
        "lookup_table" => "alignmenttype",
        "lookup_id" => "alignment_id",
        "lookup_label" => "alignment_name"
    ],
    "roadway_surface_cond" => [
        "junction_table" => "crashroadsurface",
        "junction_crash_fk" => "crash_record_id",
        "junction_cat_fk" => "road_surface_id",
        "lookup_table" => "roadsurfacecondition",
        "lookup_id" => "road_surface_id",
        "lookup_label" => "road_surface_name"
    ],
    "road_defect" => [
        "junction_table" => "crashroaddefect",
        "junction_crash_fk" => "crash_record_id",
        "junction_cat_fk" => "road_defect_id",
        "lookup_table" => "roaddefecttype",
        "lookup_id" => "road_defect_id",
        "lookup_label" => "defect_name"
    ]
    // add more multi-valued attributes here
];

// Keeps track of which attributes come from which tables for building SELECT and JOIN clauses
$secondaryAttributes = [
    //"street_no" => "street",
    "street_direction" => "street",
    "street_name" => "street",

    "traffic_control_device" => "trafficcontrol",
    "device_condition" => "trafficcontrol",

    "injuries_total" => "crashinjuries",
    "injuries_fatal" => "crashinjuries",
    "injuries_incapacitating" => "crashinjuries",
    "injuries_non_incapacitating" => "crashinjuries",
    "injuries_reported_not_evident" => "crashinjuries",
    "injuries_no_indication" => "crashinjuries",
    "injuries_unknown" => "crashinjuries",
    "most_severe_injury_id" => "crashinjuries"
];

// $literalCategories = [
//     "street_direction" => ["N", "E", "S", "W"], //This is never going to change, so we can hardcode it instead of doing a lookup to the database
//     // etc.
// ];


// $derivedAttributes = [
//     "crash_month" => [
//         "expression" => "MONTH(crash.crash_date)",
//         "type" => "number"
//     ],
//     "crash_day_of_week" => [
//         "expression" => "DAYOFWEEK(crash.crash_date)",
//         "type" => "number"
//     ],
//     "crash_hour" => [
//         "expression" => "HOUR(crash.crash_date)",
//         "type" => "number"
//     ]
// ];

// Generative Functions

function buildRangeMetaData($conn, $rangeAttributes)
{
    foreach ($rangeAttributes as $attr => $meta) {
        $table = $meta['table'];
        $column = $meta['column'];
        $sql = "SELECT MIN($column) AS min_val, MAX($column) AS max_val FROM $table";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $attributeSortBy[$attr] = [
            "min" => $row['min_val'],
            "max" => $row['max_val']
        ];
    }

    return $attributeSortBy;
}

function buildSingleValueCategoryMetaData($conn, $singleValueCategories)
{
    $attributeCategory = [];

    foreach ($singleValueCategories as $attr => $meta) {
        $lookup = $meta['lookup_table'];
        $id = $meta['lookup_id'];
        $label = $meta['lookup_label'];

        $sql = "SELECT $id, $label FROM $lookup ORDER BY $label";
        $result = $conn->query($sql);

        $attributeCategory[$attr] = [];

        while ($row = $result->fetch_assoc()) {
            $attributeCategory[$attr][] = $row[$label];
        }
    }

    return $attributeCategory;
}

function buildMultiValueCategoryMetaData($conn, $multiValueCategories)
{
    $attributeCategory = [];

    foreach ($multiValueCategories as $attr => $meta) {
        $lookup = $meta['lookup_table'];
        $id = $meta['lookup_id'];
        $label = $meta['lookup_label'];

        $sql = "SELECT $id, $label FROM $lookup ORDER BY $label";
        $result = $conn->query($sql);

        $attributeCategory[$attr] = [];

        while ($row = $result->fetch_assoc()) {
            $attributeCategory[$attr][] = $row[$label];
        }
    }

    return $attributeCategory;
}
