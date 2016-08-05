<?php
    header('Content-Type: text/html; charset=utf-8');

    require_once '../connect.php';
    require_once '../dni.php';
    require_once '../import-dni-csv.php';

    $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date("Y");
    $removeDay = isset($_REQUEST['removeDay']) ? $_REQUEST['removeDay'] : null;
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
        <tr>
            <td>
            </td>
            <td>
                Дата
            </td>
            <td>
                Наименование на почивният ден
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                Добавяне на дата
            </td>
            <td>
    <form name = "newDate" method = "GET" action = <?php $_SERVER['PHP_SELF'] ?>>
                <input type = "date" name = "addDay">
            </td>
            <td>
                <input type = "text" name = "nameDay" value = "Почивен ден">
            </td>
            <td>
                <input type = "submit" name = "newDate" value = "Прибави дата">
    </form>
</table>
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

// присвоява датите при натискане на 'submit'
if(isset($_GET['newDate'])){
    $addDay = $_GET['addDay'];
    $nameDay = $_GET['nameDay'];
}

// добавяне на дата при параметър 'addDay'
if (!is_null($addDay)) {
    if ($stmt = $conn->prepare("INSERT INTO rest_days (restDay,name) VALUES(?, ?)")
        or trigger_error($conn->error, E_USER_ERROR)) {

            $stmt->bind_param("ss", $addDay, $nameDay  );

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
    }
}

if ($stmt = $conn->prepare("SELECT restDay, name from rest_days WHERE restDay>=? and restDay<=? ORDER BY restDay")
    or trigger_error($conn->error, E_USER_ERROR)) {

    $ybeg = "{$year}-01-01";
    $yend = "{$year}-12-31";
    $stmt->bind_param("ss", $ybeg, $yend);

    $stmt->execute() or trigger_error($stmt->error, E_USER_ERROR);

    $stmt->bind_result($restDay, $name);

    while ($stmt->fetch()) {
        echo "
            <tr>
                <td>
                    ".$restDay."
                </td>
                <td>
                    ".$name."
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
