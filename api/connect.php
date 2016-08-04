<?php
require_once 'config.php';

function connect_db() {

    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $mysqli->set_charset("utf8");

    /*
     * Use this instead of $connect_error if you need to ensure
     * compatibility with PHP versions prior to 5.2.9 and 5.3.0.
     */
    if (mysqli_connect_error()) {
        die('Connect Error (' . mysqli_connect_errno() . ') '
                . mysqli_connect_error());
    }

    return $mysqli;
}

?>
