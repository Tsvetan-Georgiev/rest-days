<?php

include 'routines.php';

/**
*   Помощни функции за импорт от CSV
*/
class ImportDniCSV {

    /**
    *   Парсва CSV ред и прави масив с почивни дни
    *
    *   @param string $row Ред от CSV файла
    *   @return array
    */
    public static function rowToArray($row) {
        $cols = explode(",", $row);
        $year = self::rowToYear($row);
        if ($year < 1990) {
            throw new Exception("Невалидна година ".$year);
        }
        // попълване на почивните дни
        $restDays = [];
        for ($mon=1; $mon<=12; $mon++) {
            $bin = $cols[$mon];
            for ($i=0; $i<strlen($bin); $i++) {
                if ($bin[$i] == '0') {
                    $restDays[isoDate($year, $mon, $i+1)] = "Почивен ден";
                }
            }
        }

        // сортиране и попълване на датата във формат timestamp
        ksort($restDays);
        foreach ($restDays as $key => $value) {
            $ts = strtotime($key);
            $res[$ts] = $value;
        }

        return $res;
    }

    /**
    *   Взима годината от CSV реда
    *
    *   @param string $row CSV ред
    *
    *   @return int Година
    */
    public static function rowToYear($row) {
        $cols = explode(",", $row);
        if (count($cols) != 13) {
            throw new Exception("Невалиден CSV ред\n\n".$row);
        }
        $year = (int) $cols[0];
        return $year;
    }
}

?>
