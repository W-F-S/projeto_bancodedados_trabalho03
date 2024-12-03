<?php
header('Content-Type: application/json');

$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234")
    or die(json_encode(['error' => 'Não foi possível conectar: ' . pg_last_error()]));

$table = $_POST['table'];
$id = $_POST['id'];
$data = $_POST['data'];

if (!$table || !$id || !$data) {
    echo json_encode(['error' => 'Tabela, ID ou dados não especificados.']);
    pg_close($dbconn);
    exit();
}

try {
    parse_str($data, $fields); // Parse serialized form data into an associative array


    // Determine the ID column dynamically
    $id_table = "id";
    if($table == "frete"){
        $id_table = "id_frete";
    }else if($table == "cliente"){
        $id_table = "cod_cli";
    }
    if (!$id_table) {
        throw new Exception("ID Column is not set. Table: $table");
    }

    $setClauses = [];
    $values = [];
    $index = 1;

    foreach ($fields as $column => $value) {
        $setClauses[] = "$column = \$$index";
        $values[] = $value;
        $index++;
    }

    $setQuery = implode(", ", $setClauses);
    $values[] = $id;

    $query = "UPDATE $table SET $setQuery WHERE $id_table = \$$index";

    // Debugging Logs
    error_log("ID Column: " . $id_table);
    error_log("Table: " . $table);
    error_log("Query: " . $query);
    error_log("Parameters: " . print_r($values, true));

    $result = pg_query_params($dbconn, $query, $values);

    if ($result && pg_affected_rows($result) > 0) {
        echo json_encode(['message' => 'Registro atualizado com sucesso.']);
    } else {
        throw new Exception('Erro ao atualizar registro: ' . pg_last_error($dbconn));
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

pg_close($dbconn);
?>
