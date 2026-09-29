<?php

require_once __DIR__ . "/query_metadata.php";

// Function to build the SELECT clause based on user input (uses other functions to build different parts of the query)
function buildSelectQuery($selectedAttributes, $selectionTypes, $singleValueCategories, $multiValueCategories, $secondaryAttributes)
{
    // SELECT
    $select = buildSelectClause($selectedAttributes, $selectionTypes,  $singleValueCategories, $multiValueCategories, $secondaryAttributes);

    // FROM & JOIN
    $from = "FROM crash " . buildJoinClause($selectedAttributes, $selectionTypes,  $singleValueCategories, $multiValueCategories, $secondaryAttributes);

    // WHERE
    $where = buildWhereClause($_POST, $selectionTypes,  $singleValueCategories, $multiValueCategories, $secondaryAttributes);

    // ORDER BY
    $order = buildOrderByClause($_POST, $selectionTypes, $singleValueCategories, $multiValueCategories, $secondaryAttributes);

    return "$select $from $where $order";
}

// 04/15 - new function
function buildStatsQuery($post, $selectionTypes, $singleValueCategories, $multiValueCategories, $secondaryAttributes)
{
    $joins = [];

    // Stats always need crashinjuries once
    $joins["crashinjuries"] = "LEFT JOIN crashinjuries ON crash.crash_record_id = crashinjuries.crash_record_id";

    // Add joins needed for category filters
    if (isset($post['categories'])) {
        foreach ($post['categories'] as $attr => $labels) {
            if (!isset($selectionTypes[$attr])) {
                continue;
            }

            if ($selectionTypes[$attr] === "single_lookup") {
                $meta = $singleValueCategories[$attr];

                $fk_table  = $meta["fk_table"];
                $fk_column = $meta["fk_column"];
                $lookup    = $meta["lookup_table"];
                $lookup_id = $meta["lookup_id"];

                // join FK table if needed, but don't duplicate crashinjuries
                if ($fk_table !== "crash" && $fk_table !== "crashinjuries") {
                    $joins[$fk_table] = "LEFT JOIN $fk_table ON crash.crash_record_id = $fk_table.crash_record_id";
                }

                $joins[$lookup] = "LEFT JOIN $lookup ON $fk_table.$fk_column = $lookup.$lookup_id";
            }

            if ($selectionTypes[$attr] === "multi_lookup") {
                $meta = $multiValueCategories[$attr];

                $junction  = $meta["junction_table"];
                $crash_fk  = $meta["junction_crash_fk"];
                $cat_fk    = $meta["junction_cat_fk"];
                $lookup    = $meta["lookup_table"];
                $lookup_id = $meta["lookup_id"];

                $joins[$junction] = "LEFT JOIN $junction ON crash.crash_record_id = $junction.$crash_fk";
                $joins[$lookup]   = "LEFT JOIN $lookup ON $junction.$cat_fk = $lookup.$lookup_id";
            }
        }
    }

    // Add joins needed for range filters on secondary tables
    if (isset($post['ranges'])) {
        foreach ($post['ranges'] as $attr => $range) {
            if (!isset($selectionTypes[$attr])) {
                continue;
            }

            if ($selectionTypes[$attr] === "secondary") {
                $table = $secondaryAttributes[$attr];

                // crashinjuries already joined above
                if ($table === "crashinjuries") {
                    continue;
                }

                $joins[$table] = "LEFT JOIN $table ON crash.crash_record_id = $table.crash_record_id";
            }
        }
    }

    // Reuse existing WHERE builder
    $where = buildWhereClause(
        $post,
        $selectionTypes,
        $singleValueCategories,
        $multiValueCategories,
        $secondaryAttributes
    );

    $joinSql = implode(" ", $joins);

    return "
        SELECT
            COUNT(DISTINCT crash.crash_record_id) AS crash_count,
            COALESCE(SUM(crashinjuries.injuries_total), 0) AS total_injuries,
            COALESCE(SUM(crashinjuries.injuries_fatal), 0) AS total_fatal,
            COALESCE(SUM(crashinjuries.injuries_incapacitating), 0) AS total_incapacitating,
            COALESCE(SUM(crashinjuries.injuries_non_incapacitating), 0) AS total_non_incapacitating,
            COALESCE(AVG(crash.posted_speed_limit), 0) AS avg_speed_limit
        FROM crash
        $joinSql
        $where
    ";
}


// Function to build the SELECT clause based on selected attributes and their types
function buildSelectClause($selectedAttributes, $selectionTypes, $singleValueCategories, $multiValueCategories, $secondaryAttributes)
{

    $parts = [];

    foreach ($selectedAttributes as $attr) {

        // Switch based on selection type to determine how to select this attribute
        switch ($selectionTypes[$attr]) {

            case "main":
                // main crash table
                $parts[] = "crash.$attr";
                break;

            case "secondary":
                $table = $secondaryAttributes[$attr];
                $parts[] = "$table.$attr";
                break;

            case "single_lookup":
                $meta = $singleValueCategories[$attr];
                $lookup = $meta["lookup_table"];
                $label = $meta["lookup_label"];
                $parts[] = "$lookup.$label AS $attr";
                break;

            case "multi_lookup":
                $meta = $multiValueCategories[$attr];
                $lookup = $meta["lookup_table"];
                $label = $meta["lookup_label"];
                $parts[] = "$lookup.$label AS $attr";
                break;

                // case "derived":
                //     $expr = $derivedAttributes[$attr]["expression"];
                //     $parts[] = "$expr AS $attr";
                //     break;
        }
    }

    return "SELECT " . implode(", ", $parts);
}

// Function to build the JOIN clauses based on selected attributes and their types
function buildJoinClause($selectedAttributes, $selectionTypes, $singleValueCategories, $multiValueCategories, $secondaryAttributes)
{

    $joins = [];

    foreach ($selectedAttributes as $attr) {

        switch ($selectionTypes[$attr]) {

        case "secondary":
            $table = $secondaryAttributes[$attr];

            if ($table === "street") {
                $joins[$table] = "JOIN street ON crash.street_id = street.street_id";
            } elseif ($table === "trafficcontrol") {
                $joins[$table] = "JOIN trafficcontrol ON crash.crash_record_id = trafficcontrol.crash_record_id";
            } elseif ($table === "crashinjuries") {
                $joins[$table] = "JOIN crashinjuries ON crash.crash_record_id = crashinjuries.crash_record_id";
            }

            break;

            case "single_lookup":
                $meta = $singleValueCategories[$attr];

                $fk_table  = $meta["fk_table"];       
                $fk_column = $meta["fk_column"];      
                $lookup    = $meta["lookup_table"];   
                $lookup_id = $meta["lookup_id"];

                // Make sure FK table is joined (if it's not crash)
                if ($fk_table !== "crash") {
                    $joins[$fk_table] = "JOIN $fk_table ON crash.crash_record_id = $fk_table.crash_record_id";
                }

                // Join lookup table
                $joins[$lookup] =
                    "JOIN $lookup ON $fk_table.$fk_column = $lookup.$lookup_id";
                break;

            case "multi_lookup":
                $meta = $multiValueCategories[$attr];

                $junction = $meta["junction_table"];        
                $crash_fk = $meta["junction_crash_fk"];     
                $cat_fk = $meta["junction_cat_fk"];         

                $lookup = $meta["lookup_table"];            
                $lookup_id = $meta["lookup_id"];            

                // Junction join
                $joins[$junction] =
                    "JOIN $junction ON crash.crash_record_id = $junction.$crash_fk";

                // Lookup join
                $joins[$lookup] =
                    "JOIN $lookup ON $junction.$cat_fk = $lookup.$lookup_id";

                break;
        }
    }

    return implode(" ", $joins);
}

// Function to build the WHERE clause based on user input (ranges, yes/no, categories)
function buildWhereClause($post, $selectionTypes, $singleValueCategories, $multiValueCategories, $secondaryAttributes)
{

    $conditions = [];

    // Ranges
    if (isset($post['ranges'])) {
        foreach ($post['ranges'] as $attr => $range) {

            // Skip if no selection type
            if (!isset($selectionTypes[$attr])) {
                continue;
            }

            // Determine if it's date range (start/end) or numeric range (min/max)
            $isDate = isset($range['start']) || isset($range['end']);
            $isNumeric = isset($range['min']) || isset($range['max']);

            switch ($selectionTypes[$attr]) {

                case "main":
                    $table = "crash";
                    break;

                case "secondary":
                    $table = $secondaryAttributes[$attr];
                    break;

                // case "derived":
                //     $expr = $derivedAttributes[$attr]["expression"];
                //     $conditions[] = "$expr BETWEEN {$range['min']} AND {$range['max']}";
                //     break;

                default:
                    $table = "crash";
                    break;
            }

            // Date range: start / end
            if ($isDate) {
                $start = $range['start'] ?? null;
                $end   = $range['end']   ?? null;

                if ($start !== "" && $end !== "") {
                    $conditions[] = "$table.$attr BETWEEN '$start' AND '$end'";
                } elseif ($start !== "") {
                    $conditions[] = "$table.$attr >= '$start'";
                } elseif ($end !== "") {
                    $conditions[] = "$table.$attr <= '$end'";
                }
            }

            // Numeric range: min / max
            if ($isNumeric) {
                $min = $range['min'] ?? null;
                $max = $range['max'] ?? null;

                if ($min !== "" && $max !== "") {
                    $conditions[] = "$table.$attr BETWEEN $min AND $max";
                } elseif ($min !== "") {
                    $conditions[] = "$table.$attr >= $min";
                } elseif ($max !== "") {
                    $conditions[] = "$table.$attr <= $max";
                }
            }
        }
    }

    // Yes/No
    if (isset($post['yesno'])) {
        foreach ($post['yesno'] as $attr => $values) {

            $values = array_unique($values);

            // If both yes and no, don't appply any filter (select all)
            if (in_array("yes", $values) && in_array("no", $values)) {
                continue;
            }

            // Only yes
            if (in_array("yes", $values)) {
                $conditions[] = "crash.$attr = 'Y'";
                continue;
            }

            // Only no
            if (in_array("no", $values)) {
                $conditions[] = "crash.$attr = 'N'";
                continue;
            }
        }
    }



    // Single lookup categories
    if (isset($post['categories'])) {
        foreach ($post['categories'] as $attr => $labels) {

            if ($selectionTypes[$attr] === "single_lookup") {
                $meta = $singleValueCategories[$attr];
                $lookup = $meta["lookup_table"];
                $lookup_id = $meta["lookup_id"];
                $lookup_label = $meta["lookup_label"];
                $fk_column = $meta["fk_column"];
                $fk_table = $meta["fk_table"];

                // Convert labels into IDs
                $ids = [];
                foreach ($labels as $label) {
                    $ids[] = "'$label'";
                }

                $conditions[] = "$fk_table.$fk_column IN (
                    SELECT $lookup_id
                    FROM $lookup
                    WHERE $lookup_label IN (" . implode(",", $ids) . ")
                )";
            }

            // Multi lookup categories
            if ($selectionTypes[$attr] === "multi_lookup") {
                $meta = $multiValueCategories[$attr];

                $junction = $meta["junction_table"];
                $crash_fk = $meta["junction_crash_fk"];
                $cat_fk = $meta["junction_cat_fk"];
                $lookup = $meta["lookup_table"];
                $lookup_id = $meta["lookup_id"];
                $lookup_label = $meta["lookup_label"];

                // Convert labels into IDs
                $ids = [];
                foreach ($labels as $label) {
                    $ids[] = "'$label'";
                }

                // EXISTS clause
                $conditions[] = "EXISTS (
                    SELECT 1
                    FROM $junction j
                    JOIN $lookup l ON j.$cat_fk = l.$lookup_id
                    WHERE j.$crash_fk = crash.crash_record_id
                      AND l.$lookup_label IN (" . implode(",", $ids) . ")
                )";
            }
        }
    }

    return empty($conditions) ? "" : "WHERE " . implode(" AND ", $conditions);
}

// Function to build the ORDER BY clause based on user input (which attribute to sort by and direction)
function buildOrderByClause($post, $selectionTypes, $singleValueCategories, $multiValueCategories, $secondaryAttributes)
{
    $sort = $post['sort'] ?? null;

    if (!isset($sort['column']) || $sort['column'] === "") {
        return ""; // no sorting selected
    }

    $attr = $sort['column'];
    $dir  = strtoupper($sort['direction'] ?? "ASC");

    switch ($selectionTypes[$attr]) {

        case "main":
            return "ORDER BY crash.$attr $dir";

        case "secondary":
            $table = $secondaryAttributes[$attr];
            return "ORDER BY $table.$attr $dir";

        case "single_lookup":
            $meta = $singleValueCategories[$attr];
            $lookup = $meta["lookup_table"];
            $label  = $meta["lookup_label"];
            return "ORDER BY $lookup.$label $dir";

        case "multi_lookup":
            $meta = $multiValueCategories[$attr];
            $lookup = $meta["lookup_table"];
            $label  = $meta["lookup_label"];
            return "ORDER BY $lookup.$label $dir";

            // case "derived":
            //     $expr = $derivedAttributes[$attr]["expression"];
            //     return "ORDER BY $expr $dir";
    }

    return "";
}
