<?php
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234");
$table = $_GET['table'];


if($table == 'cliente'){
    $result = pg_query($dbconn, "SELECT 
    cliente.cod_cli,
    cliente.data_insc,
    cliente.endereco,
    cliente.telefone,
    CASE 
        WHEN pessoa_fisica.codigo_cli IS NOT NULL THEN 'pessoa fisica'
        WHEN pessoa_juridica.codigo_cli IS NOT NULL THEN 'pessoa juridica'
    END AS tipo
FROM 
    public.cliente
LEFT JOIN public.pessoa_fisica ON cliente.cod_cli = pessoa_fisica.codigo_cli
LEFT JOIN public.pessoa_juridica ON cliente.cod_cli = pessoa_juridica.codigo_cli;
");
}else{
    $result = pg_query($dbconn, "SELECT * FROM $table");
}

if(!empty(pg_last_error($dbconn))){
    echo json_encode(pg_last_error($dbconn));    
    pg_close($dbconn);
}

$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode($data);
pg_close($dbconn);
?>
