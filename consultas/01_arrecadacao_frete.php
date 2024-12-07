<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SGBD</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .radio-group {
            margin: 15px 0;
            text-align: center;
        }

        label,
        input,
        button {
            margin: 10px 0;
            display: block;
            text-align: center;
        }

        input[type="text"] {
            padding: 10px;
            width: 200px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            margin: 5px auto;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Arrecadação com Fretes</h1>

    <div class="radio-group">
        <label>
            <input type="radio" name="searchType" value="estado" checked> Pesquisar por Estado
        </label>
        <label>
            <input type="radio" name="searchType" value="cidade"> Pesquisar por Cidade
        </label>
    </div>

    <label for="searchInput">Estado ou Cidade:</label>
    <input type="text" id="searchInput" name="searchInput" placeholder="Informe o estado ou cidade" required>
    <button id="sendRequest">Consultar</button>

    <script>
        $(document).ready(function () {
            // Evento para o botão
            $('#sendRequest').click(function () {
                // Captura o valor do campo de pesquisa
                const searchInput = $('#searchInput').val();
                // Captura o tipo de pesquisa (estado ou cidade)
                const searchType = $('input[name="searchType"]:checked').val();

                if (!searchInput) {
                    alert('Por favor, informe o estado ou cidade.');
                    return;
                }

                // Realiza o POST para a página 01_arrecadacao_frete_api.php
                $.ajax({
                    url: '01_arrecadacao_frete_api.php',
                    type: 'POST',
                    data: { input: searchInput, tipoPesquisa: searchType, query_type: "01" },
                    success: function (response) {
                        console.log('Resposta do servidor:', response);

                        let data = response;
                        if (typeof data === 'string') {
                            data = JSON.parse(data);
                        }

                        if (data && data.dados && data.dados.length > 0) {
                            const rows = data.dados;

                            // Obtém as chaves do primeiro objeto para criar o cabeçalho
                            const keys = Object.keys(rows[0]);

                            // Cria o cabeçalho da tabela dinamicamente
                            let thead = '<thead><tr>';
                            keys.forEach(key => {
                                thead += `<th style="border: 1px solid #333; padding: 8px;">${key}</th>`;
                            });
                            thead += '</tr></thead>';

                            // Cria o corpo da tabela
                            let tbody = '<tbody>';
                            rows.forEach(row => {
                                tbody += '<tr>';
                                keys.forEach(key => {
                                    const valor = (row[key] !== null && row[key] !== undefined) ? row[key] : '';
                                    tbody += `<td style="border: 1px solid #333; padding: 8px;">${valor}</td>`;
                                });
                                tbody += '</tr>';
                            });
                            tbody += '</tbody>';

                            // Monta a tabela completa
                            const tableHtml = `
            <table style="margin: 20px auto; border-collapse: collapse; border: 1px solid #333;">
                ${thead}
                ${tbody}
            </table>
        `;

                            // Remove a tabela anterior se existir
                            $('table').remove();

                            // Anexa a nova tabela ao body
                            $('body').append(tableHtml);

                        } else {
                            console.warn('Nenhum dado encontrado.');
                        }
                    },


                    error: function (xhr, status, error) {
                        console.error('Erro:', error);
                        console.error('Detalhes:', xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>