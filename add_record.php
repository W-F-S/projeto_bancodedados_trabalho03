<?php
// Connect to the database
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234")
    or die('Could not connect: ' . pg_last_error());

// Retrieve the table name and form data
$table = $_POST['table'];
parse_str($_POST['data'], $data); // Parse the serialized form data

// Build the INSERT query
$columns = implode(", ", array_keys($data));
$values = implode(", ", array_map(fn($v) => "'" . pg_escape_string($v) . "'", array_values($data)));

$query = "INSERT INTO $table ($columns) VALUES ($values)";
$result = pg_query($dbconn, $query);

// Return JSON response
if ($result && pg_affected_rows($result) > 0) {
    echo json_encode(['message' => 'Record added successfully.']);
} else {
    echo json_encode(['message' => 'Error: Could not add record.', 'error' => $result]);
}

// Close the database connection
pg_close($dbconn);
?>
