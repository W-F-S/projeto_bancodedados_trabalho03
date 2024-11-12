<?php
//https://www.php.net/manual/en/pgsql.examples-basic.php


// Connecting, selecting database
$dbconn = pg_connect("host=localhost dbname=publishing user=www password=foo")
    or die('Could not connect: ' . pg_last_error());



?>
