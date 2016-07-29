<?php
    require_once "dni.php";
    require_once "import-dni-csv.php";

    // варианти на справката
    if (isset($_REQUEST['csv'])) {
        $csv = $_REQUEST['csv'];

        $year = ImportDniCSV::rowToYear($csv);
        $check = ImportDniCSV::rowToArray($csv);

        // смесен вариант - първо се вика CSV-то, после официалните празници
        $mix = $_REQUEST['mix'];
        if (isset($mix) && $mix == '1') {
            $check = restDaysYear($year, $check);
        }
    } else {
        if (isset($_REQUEST['year']))
            $year = $_REQUEST['year'];
        else {
            $year = date("Y");
        }

        $check = restDaysYear($year);
    }
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
                    Unixtime
                </td>
                <td>
                    Дата
                </td>
                <td>
                     Наименование
                </td>
            </tr>
        </thead>

<?php
    // сортиране и попълване на датата във формат timestamp
    ksort($check);
    foreach ($check as $key => $value) {
        $ts = strtotime($key);
        $check2[$ts] = $value;
    }
    $check = $check2;

    // печат на таблицата
    $countHDays = 0;
    foreach ($check as $key => $value) {
        $countHDays += 1;
        $unixTime = $key;
        $name = $value;
        $date = date("Y-m-d",$key);
        echo "
            <tr>
                <td>
                    ".$unixTime."
                </td>
                <td>
                    ".$date."
                </td>
                <td>
                    ".$name."
                </td>
            </tr>
        ";
    }
?>
</table>
<p>
    Общо почивни дни:
    <?php
        echo $countHDays;
    ?>
</p>
