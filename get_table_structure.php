<?php
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234");
$table = $_GET['table'];
$result = pg_query($dbconn, "SELECT column_name, data_type FROM information_schema.columns WHERE table_name='$table'");
$columns = [];
while ($row = pg_fetch_assoc($result)) {
    $columns[] = ['name' => $row['column_name'], 'type' => $row['data_type']];
}
echo json_encode($columns);
pg_close($dbconn);
?>
