<?php
	include "dni.php";
    include "import-dni-csv.php";

    // варианти на справката
    $csv = $_REQUEST['csv'];
    if (isset($csv)) {
        $year = ImportDniCSV::rowToYear($csv);

        $check = ImportDniCSV::rowToArray($csv);
    } else {
        $year = $_REQUEST['year'];
        if (!isset($year)) {
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
