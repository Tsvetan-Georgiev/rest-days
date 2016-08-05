<?php
    header('Content-Type: text/html; charset=utf-8');

    require_once '../connect.php';
    require_once '../dni.php';
    require_once '../import-dni-csv.php';

    $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date("Y");
    $addDay = isset($_REQUEST['addDay']) ? $_REQUEST['addDay'] : null;
    $removeDay = isset($_REQUEST['removeDay']) ? $_REQUEST['removeDay'] : null;
    $nameDay = isset($_REQUEST['nameDay']) ? $_REQUEST['nameDay'] : null;
    $again = isset($_REQUEST['again']) ? $_REQUEST['again'] : null;
    $csv = isset($_REQUEST['csv']) ? $_REQUEST['csv'] : null;

    $ybeg = "{$year}-01-01";
    $yend = "{$year}-12-31";
?>
<!DOCTYPE html>
<html lang='bg'>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Стартиране на година <?= $year ?></title>
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
    </head>
    <body>
        <form name="newDate" method="GET" action=<?php $_SERVER['PHP_SELF'] ?>>
            <input type="hidden" name="year" value="<?= $year ?>">
            <table>
                <tr>
                    <td/>
                    <td>
                        Дата
                    </td>
                    <td>
                        Наименование на почивният ден
                    </td>
                    <td/>
                </tr>
                <tr>
                    <td>
                        Добавяне на дата
                    </td>
                    <td>
                        <input type="date" name="addDay">
                    </td>
                    <td>
                        <input type="text" name="nameDay" value="Почивен ден">
                    </td>
                    <td>
                        <input type="submit" value="Прибави дата">
                    </td>
            </table>
        </form>

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
                    <td>
                        *
                    </td>
                </tr>
            </thead>
<?php

$conn = connect_db();

// добавяне на дата при параметър 'addDay'
if (!is_null($addDay)) {
    if ($stmt = $conn->prepare("INSERT INTO rest_days (restDay,name) VALUES(?, ?) ON DUPLICATE KEY UPDATE name=?")
        or trigger_error($conn->error, E_USER_ERROR)) {

            $stmt->bind_param("sss", $addDay, $nameDay, $nameDay);

            $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);
    }
    $stmt->close();
}

// изтриване на почивен ден при параметър 'removeDay'
if (!is_null($removeDay)) {
    if ($stmt = $conn->prepare("DELETE FROM rest_days WHERE restDay=?")
        or trigger_error($conn->error, E_USER_ERROR)) {

            $stmt->bind_param("s", $removeDay);
            $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);
            $stmt->close();
    }
}

// стартиране на месеца отначало
if ($again == 1) {
    // създаване на списък с почивните дни
    if (!is_null($csv) && $csv !== '') {
        $yearCsv = ImportDniCSV::rowToYear($csv);
        if ($year != $yearCsv) {
            trigger_error('Invalid year in CSV', E_USER_ERROR);
        }
        $hdays = ImportDniCSV::rowToArray($csv);
    } else {
        $hdays = null;
    }
    $rdo = new RestDays($year, $hdays, (!is_null($hdays)));
    $hdays = $rdo->get();

    // изтриване на почивните дни за тази година в базата данни
    if ($stmt = $conn->prepare("DELETE FROM rest_days WHERE restDay>=? AND restDay<=?")
        or trigger_error($conn->error, E_USER_ERROR)) {

        $stmt->bind_param("ss", $ybeg, $yend);
        $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);
        $stmt->close();
    }

    // въвеждане почивните дни от масива в базата данни
    if ($stmt = $conn->prepare("INSERT INTO rest_days(restDay, name) VALUES(?,?)")
        or trigger_error($conn->error, E_USER_ERROR)) {

        foreach ($hdays as $key => $value) {
            $stmt->bind_param("ss", $key, $value);
            $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);
        }
        $stmt->close();
    }
}

// печат на таблицата с почивните дни
if ($stmt = $conn->prepare("SELECT restDay, name from rest_days WHERE restDay>=? AND restDay<=? ORDER BY restDay")
    or trigger_error($conn->error, E_USER_ERROR)) {

    $stmt->bind_param("ss", $ybeg, $yend);
    $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);
    $stmt->bind_result($restDay, $name);

    while ($stmt->fetch()) {
        echo "
            <tr>
                <td>
                    {$restDay}
                </td>
                <td>
                    {$name}
                </td>
                <td>
                    <a href='bootstrap.php?year={$year}&removeDay={$restDay}'>X</a>
                </td>
            </tr>
        ";
    }

    $stmt->close();
}

$conn->close();

?>
        </table>
        <h3>Стартиране на година <?= $year ?> <i>отначало</i>:</h3>
        <form action="bootstrap.php" method="POST">
            Csv <i>(optional)</i>:<br>
            <input type="text" name="csv" size="60"><br><br>
            <input type="hidden" name="year" value="<?= $year ?>">
            <input type="hidden" name="again" value="1">
            <input type="submit" value="Старт!">
        </form>
    </body>
</html>
