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

$query_type = $_POST['query_type'];

if ($query_type == "01") {
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
                    fk_cod_cidade_destino in (" . $cidade_pesquisa[0] . ")
                    OR fk_cod_cidade_origem in (" . $cidade_pesquisa[0] . ")
                    ";

            $result = pg_query($dbconn, $query);

            if (!$result) {
                echo "Erro no PostgreSQL: " . pg_last_error($dbconn);
            }

            if ($result != false) {
                $result_tmp = (pg_fetch_all($result));
                $result_tmp[0]["nomecidade"] = $cidade_pesquisa[1]; //inserindo manualmente o nome da cidade
                if($result_tmp[0]["total_frete"] == ''){
                    $result_tmp[0]["total_frete"] = 0;
                }
                $result_list[] = ($result_tmp[0]);
            }
        }

        $result = $result_list;

        echo json_encode(value: ['dados' => $result]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($query_type == "02") {
    /*Qual a quantidade média de fretes de origem e média de fretes de
destino, por cidade de um estado informado por parâmetro.
    Mostrar Estado, cidade, quantidade media de frete de origem
    e quantidade média de fretes de destino.*/

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


            //o case when cria uma "coluna virtual" que é usada para o avg, 
            //TODO: olhar documentacao depois
            $query = "
                    SELECT
                        ROUND(AVG(CASE WHEN f.fk_cod_cidade_origem = " . $cidade_pesquisa[0] . " THEN 1 ELSE 0 END), 2) AS media_fretes_origem,
                        ROUND(AVG(CASE WHEN f.fk_cod_cidade_destino = " . $cidade_pesquisa[0] . " THEN 1 ELSE 0 END), 2) AS media_fretes_destino
                    FROM public.frete f
                ";


            $result_fretes = pg_query($dbconn, $query);

            if (!$result_fretes) {
                throw new Exception('Erro ao realizar query: ' . pg_last_error($dbconn));
            }

            $media_fretes = pg_fetch_all($result_fretes);

            // Adiciona os resultados para a cidade
            $result_list[] = [
                'cidade' => $cidade_pesquisa[1], // Nome da cidade adicionado manualmente
                'media_fretes_origem' => $media_fretes[0]['media_fretes_origem'] ?? 0,
                'media_fretes_destino' => $media_fretes[0]['media_fretes_destino'] ?? 0,
            ];
        }

        $result = $result_list;

        echo json_encode(value: ['dados' => $result]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($query_type == "03") {
    /*
        Quais fretes os funcionários atenderam as pessoas jurídicas e quem
        eram os representantes destas empresas, no mes xx do ano yy ( a
        informar) ( consistir xx/yy )
        */
    /**
     * funcionario_nome, frete_codigo, empresa, empresa_nome, empresa_representante, no mes xx do ano yy
     * 
     * temos que informar a data, então a pesquisa vai ser em função disso;
     */

    $data = $_POST['input']; //cidade ou estado
    [$mes, $ano] = explode('/', $data);    

    //TODO: olhar documentacao depois
    $query = "
        SELECT
            a.id_frete,
            a.data_frete,
            a.fk_cliente_destinatario,
            a.fk_cliente_remetente,
            c.razao_social,
            b.nome_func as nome_funcionario,
            c.codigo_cli as id_representante,
            d.nome_cli as nome_representante


        FROM public.frete as a
        INNER JOIN public.funcionario as b 
            ON b.num_reg = a.fk_funcionario
        INNER JOIN public.pessoa_juridica as c 
            ON c.codigo_cli = a.fk_cliente_destinatario 
            OR c.codigo_cli = a.fk_cliente_remetente
        INNER JOIN public.pessoa_fisica as d 
            ON d.cpf like c.id_representante

        WHERE
            EXTRACT(MONTH FROM a.data_frete) = $mes AND
            EXTRACT(YEAR FROM a.data_frete) = $ano
    ";

    $result_fretes = pg_query($dbconn, $query);
    
    if (!$result_fretes) {
        throw new Exception('Erro ao realizar query: ' . pg_last_error($dbconn));
    }

    $result = pg_fetch_all($result_fretes);
    //var_dump($result);
    echo json_encode(value: ['dados' => $result]);
} else {

}



?>