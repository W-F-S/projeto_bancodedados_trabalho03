<?php
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234");
$result = pg_query($dbconn, "SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
$tables = [];
while ($row = pg_fetch_assoc($result)) {
    $tables[] = $row['table_name'];
}
echo json_encode($tables);
pg_close($dbconn);
?>
