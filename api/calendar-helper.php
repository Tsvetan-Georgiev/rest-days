<?php
require_once 'routines.php';

function printCalendar($year, $month, $hdays, &$workDays) {

    $firstDayOfMonth = strtotime(isoDate($year, $month, 1));
    $firstDayOfWeek = date('w', $firstDayOfMonth);
    // нека понеделник е 0
    $firstDayOfWeek = ($firstDayOfWeek + 6) % 7;

    $lastDayOfMonth = strtotime(date("Y-m-t", $firstDayOfMonth));
    $lastDayOfWeek = date('w', $lastDayOfMonth);
    // нека понеделник е 0
    $lastDayOfWeek = ($lastDayOfWeek + 6) % 7;
?>

    <table>
        <thead>
            <td colspan="7"><?= $month ?></td>
        </thead>
        <thead>
            <td>П</td>
            <td>В</td>
            <td>С</td>
            <td>Ч</td>
            <td>П</td>
            <td>С</td>
            <td>Н</td>
        </thead>
        <tr>
<?php
    // празни клетки до първия ден на месеца
    for ($i=0; $i<$firstDayOfWeek; $i++) {
            echo "<td>&nbsp;</td>";
    }
    $currDay = $firstDayOfMonth;
    $currDayOfWeek = $firstDayOfWeek;
    $workDays = 0;
    while ($currDay <= $lastDayOfMonth) {

        $isRest = isset($hdays[date("Y-m-d", $currDay)]);

        $dayI = date('d', $currDay);
        if ($isRest) {
            echo "<td class='rest'>{$dayI}</td>";
        } else {
            echo "<td>{$dayI}</td>";
            $workDays++;
        }

        // неделя - нов ред
        if ($currDayOfWeek == 6) {
            echo "</tr>";
            if ($currDay < $lastDayOfMonth) {
                echo "<tr>";
            }
        }

        $currDay = strtotime("+1 day", $currDay);
        $currDayOfWeek = ($currDayOfWeek + 1) % 7;
    }
    if ($currDayOfWeek != 0) {
        for ($i=$currDayOfWeek; $i<7; $i++) {
            echo "<td>&nbsp;</td>";
        }
        echo "</tr>";
    }
?>
    </table>

<?php
    echo "р.д.: {$workDays}";
}
?>
