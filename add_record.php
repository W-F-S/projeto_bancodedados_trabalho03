<?php
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234")
    or die('Não foi possível conectar: ' . pg_last_error());

$table = $_POST['table']; 
parse_str($_POST['data'], $data); 

$columns = implode(", ", array_keys($data)); 
$values = implode(", ", array_map(fn($v) => "'" . pg_escape_string($v) . "'", array_values($data))); // Escapa os valores

$query = "INSERT INTO $table ($columns) VALUES ($values)";
$result = pg_query($dbconn, $query); 

if ($result && pg_affected_rows($result) > 0) {
    echo json_encode(['message' => 'Registro adicionado com sucesso.']);
} else {
    echo json_encode(['message' => 'Erro: Não foi possível adicionar o registro.', 'error' => pg_last_error($dbconn)]);
}

pg_close($dbconn);
?>

