<?php
header('Content-Type: text/html; charset=utf-8');

require_once '../connect.php';
require_once '../dni.php';
require_once '../import-dni-csv.php';

$year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date("Y");

$conn = connect_db();

if ($stmt = $conn->prepare("SELECT restDay, name from rest_days WHERE restDay>=? and restDay<=? ORDER BY restDay")
    or trigger_error($conn->error, E_USER_ERROR)) {

    $ybeg = "{$year}-01-01";
    $yend = "{$year}-12-31";
    $stmt->bind_param("ss", $ybeg, $yend);

    $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);

    $stmt->bind_result($restDay, $name);

    while ($stmt->fetch()) {
        echo "$restDay  $name";
    }

    $stmt->close();
}

$conn->close();

?>
