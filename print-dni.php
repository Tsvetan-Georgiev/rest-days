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

	include "dni.php";
	$check = restDaysYear("2015");
	//var_dump($check) ;
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
