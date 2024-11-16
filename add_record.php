<?php
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234")
    or die(json_encode(['error' => 'Não foi possível conectar: ' . pg_last_error()]));

$table = $_POST['table'];
parse_str($_POST['data'], $data);

$columns = implode(", ", array_keys($data));
$values = implode(", ", array_map(fn($v) => "'" . pg_escape_string($v) . "'", array_values($data)));

try {
    $query = "INSERT INTO $table ($columns) VALUES ($values)";
    $result = pg_query($dbconn, $query);

    if ($result && pg_affected_rows($result) > 0) {
        echo json_encode(['message' => 'Registro adicionado com sucesso.']);
    } else {
        throw new Exception('Erro ao inserir registro: ' . pg_last_error($dbconn));
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'duplicate key') !== false) {
        echo json_encode(['error' => 'Erro: Registro duplicado.', 'original_error' => $e->getMessage()]);
    } else {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

pg_close($dbconn);
?>
