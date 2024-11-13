<?php
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234");
$table = $_GET['table'];
$result = pg_query($dbconn, "SELECT * FROM $table");
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode($data);
pg_close($dbconn);
?>
