<?php
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234")
    or die(json_encode(['error' => 'Não foi possível conectar ao banco de dados.']));

$table = $_GET['table'];


    $result = pg_query($dbconn, "SELECT column_name, data_type FROM information_schema.columns WHERE table_name='$table'");




if (!$result) {
    echo json_encode(['error' => 'Erro ao tentar obter informações da tabela: ' . pg_last_error($dbconn)]);
    pg_close($dbconn);
    exit();
}

$columns = [];
while ($row = pg_fetch_assoc($result)) {
    $columns[] = ['name' => $row['column_name'], 'type' => $row['data_type']];
}

if (empty($columns)) {
    echo json_encode(['error' => 'Tabela não encontrada ou não possui colunas.']);
} else {
    echo json_encode($columns);
}

pg_close($dbconn);
?>
