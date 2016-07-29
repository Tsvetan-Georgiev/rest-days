<?php

/**
*   Връща дата в ISO формат
*
*   @param int $year Година
*   @param int $month Месец
*   @param int $day Ден
*
*   @return string Текстова репрезентация на дата
*/
function isoDate($year, $month, $day) {
    $t = new DateTime($year.'-'.$month.'-'.$day);
    return $t->format('Y-m-d');
}

?>
