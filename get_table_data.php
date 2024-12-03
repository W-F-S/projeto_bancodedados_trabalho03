<?php
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234");
$table = $_GET['table'];
$id = $_GET['id'];

if ($table == 'cliente') {
    if ($id == null) {
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
        $query = "
            SELECT 
                cliente.cod_cli,
                cliente.data_insc,
                cliente.endereco,
                cliente.telefone
                FROM 
                    public.cliente
                LEFT JOIN public.pessoa_fisica ON cliente.cod_cli = pessoa_fisica.codigo_cli
                LEFT JOIN public.pessoa_juridica ON cliente.cod_cli = pessoa_juridica.codigo_cli

                WHERE cod_cli = $1;
        ";
        $result = pg_query_params($dbconn, $query, [$id]);        
    }
} else if ($id == null) {
    $result = pg_query($dbconn, "SELECT * FROM $table");
} else {
    $id_table = "id";
    if($table == "frete"){
        $id_table = "id_frete";
    }
    $query = "SELECT * FROM $table WHERE $id_table = $1";
    $result = pg_query_params($dbconn, $query, [$id]);
}

if (!empty(pg_last_error($dbconn))) {
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