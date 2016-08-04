<?php
    header('Content-Type: text/html; charset=utf-8');
?>
<meta charset="UTF-8">
<style>
    table{
        border: 1px solid black;
    }
    td{
        padding: 1px;
        border-left: 1px solid black;
    }
    td:first-child{
        border-left: none;
    }
    thead td{
        font-weight: bold;
        border: none;
    }
</style>
    <table>
        <thead>
            <tr align="center"><td colspan="3"><b>Година: <?= $year ?></b></td></tr>
            <tr>
                <td>
                    Дата
                </td>
                <td>
                     Наименование
                </td>
            </tr>
        </thead>

<?php

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
        echo "
            <tr
                <td>
                    ".$restDay."
                </td>
                <td>
                    ".$name."
                </td>
            </tr>
        ";
    }

    $stmt->close();
}

$conn->close();

?>
</table>
