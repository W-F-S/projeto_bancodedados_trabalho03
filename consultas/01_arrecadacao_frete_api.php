<?php

header('Content-Type: application/json');

/*
Quais a arrecadação com fretes , por cidade /estado de destino de frete
no ano 2024 . Obs:. O estado deve ser um parâmetro informado.

Mostrar Nome da cidade, estado, quantidade-de-frete, Valor-
total-arrecadado;
*/

$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234")
    or die(json_encode(['error' => 'Não foi possível conectar: ' . pg_last_error()]));




$cidade = $_POST['cidade']; //cidade ou estado

if (!$cidade) {
    echo json_encode(['error' => 'Tabela, ID ou dados não especificados.']);
    pg_close($dbconn);
    exit();
}

try {
    parse_str($data, $fields); // Parse serialized form data into an associative array


    // Determine the ID column dynamically
    $id_table = "id";
    if ($table == "frete") {
        $id_table = "id_frete";
    } else if ($table == "cliente") {
        $id_table = "cod_cli";
    }


    // Define the search parameter (e.g., 'Goiânia')
    $var_pesquisa = ["%$cidade%"];

    // Perform the query using pg_query_params
    $query = "SELECT * FROM cidade WHERE nome_cid LIKE $1";
    $result = pg_query_params($dbconn, $query, $var_pesquisa);

    if (!$result) {
        throw new Exception('Erro ao realizar consulta: ' . pg_last_error($dbconn));
    }

    // Fetch all rows from the result
    $data = pg_fetch_all($result);

    $query= "
        SELECT
            COUNT(f.id_frete) AS total_quantidade_fretes,
            SUM(f.valor_frete) as total_frete
        FROM
            public.frete f
        where
            fk_cod_cidade_destino =  or 
            fk_cod_cidade_origem = 
    ";

    $result = pg_query_params($dbconn, $query, $var_pesquisa);

    echo json_encode(value: ['aaa' => $data]);

    /**
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
    } else {
        throw new Exception('Erro ao atualizar registro: ' . pg_last_error($dbconn));
    }
    */
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>