<?php
$dbconn = pg_connect("host=localhost dbname=bancodedados user=root password=1234")
    or die(json_encode(['error' => 'Não foi possível conectar: ' . pg_last_error()]));

$table = $_POST['table'];
$id = $_POST['id'];

if (!$table || !$id) {
    echo json_encode(['error' => 'Tabela ou ID não especificado.']);
    pg_close($dbconn);
    exit();
}

try {
    if ($table == "cliente") {
        $query_check_fisica = "SELECT 1 FROM pessoa_fisica WHERE codigo_cli = $1";
        $result_check_fisica = pg_query_params($dbconn, $query_check_fisica, [$id]);
        if (!$result_check_fisica) {
            throw new Exception('Erro ao verificar pessoa_fisica: ' . pg_last_error($dbconn));
        }
        if (pg_num_rows($result_check_fisica) > 0) {
            $query_delete_fisica = "DELETE FROM pessoa_fisica WHERE codigo_cli = $1";
            $result_delete_fisica = pg_query_params($dbconn, $query_delete_fisica, [$id]);
            if (!$result_delete_fisica) {
                throw new Exception('Erro ao deletar pessoa_fisica: ' . pg_last_error($dbconn));
            }
        }

        $query_check_juridica = "SELECT 1 FROM pessoa_juridica WHERE codigo_cli = $1";
        $result_check_juridica = pg_query_params($dbconn, $query_check_juridica, [$id]);
        if (!$result_check_juridica) {
            throw new Exception('Erro ao verificar pessoa_juridica: ' . pg_last_error($dbconn));
        }
        if (pg_num_rows($result_check_juridica) > 0) {
            $query_delete_juridica = "DELETE FROM pessoa_juridica WHERE codigo_cli = $1";
            $result_delete_juridica = pg_query_params($dbconn, $query_delete_juridica, [$id]);
            if (!$result_delete_juridica) {
                throw new Exception('Erro ao deletar pessoa_juridica: ' . pg_last_error($dbconn));
            }
        }

        $query_delete_frete = "DELETE FROM frete WHERE fk_cliente_destinatario = $1 or fk_cliente_remetente = $1";
        $result = pg_query_params($dbconn, $query_delete_frete, [$id]);

        if ($result && pg_affected_rows($result) > 0) {
            echo json_encode(['error' => 'Erro ao remover registro de frete: ' . pg_last_error($dbconn)]);
        }

        $query_delete_cliente = "DELETE FROM cliente WHERE cod_cli = $1";
        $result_delete_cliente = pg_query_params($dbconn, $query_delete_cliente, [$id]);
        if (!$result_delete_cliente) {
            throw new Exception('Erro ao deletar cliente: ' . pg_last_error($dbconn));
        }
        
        echo json_encode(['message' => 'Registro removido com sucesso.']);
    }else if($table == "estado"){
        $query = "DELETE FROM estado WHERE uf = $1";
        $result = pg_query_params($dbconn, $query, [$id]);
        if (!$result) {
            throw new Exception('Erro ao deletar registro da tabela: ' . pg_last_error($dbconn));
        }

        if ($result && pg_affected_rows($result) > 0) {
            echo json_encode(['message' => 'Registro removido com sucesso.']);
        } else {
            throw new Exception('Erro ao remover registro: ' . pg_last_error($dbconn));
        }
    }else {
        $query = "DELETE FROM $table WHERE id = $1";
        $result = pg_query_params($dbconn, $query, [$id]);
        if (!$result) {
            throw new Exception('Erro ao deletar registro da tabela: ' . pg_last_error($dbconn));
        }

        if ($result && pg_affected_rows($result) > 0) {
            echo json_encode(['message' => 'Registro removido com sucesso.']);
        } else {
            throw new Exception('Erro ao remover registro: ' . pg_last_error($dbconn));
        }
    }

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

pg_close($dbconn);
?>
