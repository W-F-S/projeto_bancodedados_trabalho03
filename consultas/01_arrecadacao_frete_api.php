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




$post_type = $_POST['query_type'];

if ($post_type == "01") {
    $input = $_POST['input']; //cidade ou estado

    if (!$input) {
        echo json_encode(['error' => 'Tabela, ID ou dados não especificados.']);
        pg_close($dbconn);
        exit();
    }

    try {
        // Define the search parameter (e.g., 'Goiânia')

        $var_pesquisa = ["%$input%"];

        $query = "SELECT * FROM cidade WHERE fk_uf LIKE $1";
        $result = pg_query_params($dbconn, $query, $var_pesquisa);
        if (!$result) {
            throw new Exception('Erro ao realizar consulta: ' . pg_last_error($dbconn));
        }
        // Fetch all rows from the result
        $data = pg_fetch_all($result);

        $var_pesquisa = [];
        foreach ($data as $cidade) {
            $var_pesquisa[] = [$cidade['codigo_cid'], $cidade['nome_cid']];

        }


        
        $result_list = [];
        foreach ($var_pesquisa as $cidade_pesquisa) {
            $result_tmp = [];
            $query = "
                SELECT
                    COUNT(f.id_frete) AS total_quantidade_fretes,
                    SUM(f.valor_frete) AS total_frete
                FROM public.frete f
                WHERE
                    fk_cod_cidade_destino in (".$cidade_pesquisa[0].")
                    OR fk_cod_cidade_origem in (".$cidade_pesquisa[0].")
                    ";


            $result = pg_query($dbconn, $query);


            if (!$result) {
                echo "Erro no PostgreSQL: " . pg_last_error($dbconn);
            }



            if($result != false){
                $result_tmp = (pg_fetch_all($result));
                $result_tmp[0]["nomecidade"] = $cidade_pesquisa[1];
    
                $result_list[] = ($result_tmp[0]);
            }
        }


        $result = $result_list;

        echo json_encode(value: ['dados' => $result]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($post_type == "02") {


    $tipoPesquisa = $_POST['tipoPesquisa'];
    $input = $_POST['input']; //cidade ou estado

    if (!$input || !$tipoPesquisa) {
        echo json_encode(['error' => 'Tabela, ID ou dados não especificados.']);
        pg_close($dbconn);
        exit();
    }

    try {
        // Define the search parameter (e.g., 'Goiânia')

        $var_pesquisa = ["%$input%"];

        // Perform the query using pg_query_params
        if ($tipoPesquisa == 'cidade') {
            $query = "SELECT * FROM cidade WHERE nome_cid LIKE $1";
        } else {
            $query = "SELECT * FROM cidade WHERE fk_uf LIKE $1";
        }

        $result = pg_query_params($dbconn, $query, $var_pesquisa);

        if (!$result) {
            throw new Exception('Erro ao realizar consulta: ' . pg_last_error($dbconn));
        }

        // Fetch all rows from the result
        $data = pg_fetch_all($result);

        if ($tipoPesquisa == 'cidade') {
            $var_pesquisa = $data[0]['codigo_cid'];

            $query = "
                SELECT
                    COUNT(f.id_frete) AS total_quantidade_fretes,
                    SUM(f.valor_frete) as total_frete
                FROM
                    public.frete f
                where
                    fk_cod_cidade_destino = $var_pesquisa
                    or
                    fk_cod_cidade_origem = $var_pesquisa
            ";
        } else {
            $var_pesquisa = [];
            foreach ($data as $cidade) {
                $var_pesquisa[] = $cidade['codigo_cid'];
            }
            $var_pesquisa = implode(',', $var_pesquisa);


            $query = "
        SELECT
            COUNT(f.id_frete) AS total_quantidade_fretes,
            SUM(f.valor_frete) AS total_frete
        FROM public.frete f
        WHERE
            fk_cod_cidade_destino in ($var_pesquisa)
            OR fk_cod_cidade_origem in ($var_pesquisa)
            ";
        }

        $result = pg_query($dbconn, $query);

        if (!$result) {
            echo "Erro no PostgreSQL: " . pg_last_error($dbconn);
        }
        $result = pg_fetch_all($result);
        echo json_encode(value: ['dados' => $result]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($post_type == "03") {

} else {

}



?>