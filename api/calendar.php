<?php
    header('Content-Type: text/html; charset=utf-8');

    require_once 'connect.php';
    require_once 'calendar-helper.php';

    $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date("Y");
    $ybeg = "{$year}-01-01";
    $yend = "{$year}-12-31";
?>
<!DOCTYPE html>
<html lang='bg'>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Календар за година <?= $year ?></title>
        <style>
            table{
                border: 1px solid black;
            }
            td{
                padding: 1px;
                border-left: 1px solid black;
            }
            td.rest {
                background-color: silver;
            }
            td:first-child{
                border-left: none;
            }
            thead td{
                font-weight: bold;
                border: none;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1><?= $year ?></h1>
        <table style="border: none">
            <tr>
<?php
$conn = connect_db();

if ($stmt = $conn->prepare("SELECT restDay, name FROM rest_days WHERE restDay>=? AND restDay<=?")
    or trigger_error($conn->error, E_USER_ERROR)) {

    $stmt->bind_param("ss", $ybeg, $yend);
    $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);
    $stmt->bind_result($restDay, $name);

    $hdays = [];
    while ($stmt->fetch()) {
        $hdays[$restDay] = $name;
    }

    $stmt->close();
}

for ($mon=1; $mon<=12; $mon++) {
    echo "<td style='border: none;'>";
    printCalendar($year, $mon, $hdays);
    echo "</td>";
    if ($mon === 6) {
        echo "</tr><tr>";
    }
}

$conn->close();
?>
            </tr>
        </table>
    </body>
</html>
