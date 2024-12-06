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
    <h1>quantidade média de fretes</h1>


    <label for="searchInput">Estado ou Cidade:</label>
    <input type="text" id="searchInput" name="searchInput" placeholder="Informe o estado" required>
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
                    data: { input: searchInput, tipoPesquisa: searchType, post_type: "01" },
                    success: function(response) {
                        console.log('Resposta do servidor:', response);

                        // Tenta fazer o parse do JSON (caso a resposta não esteja já em objeto)
                        let data = response;
                        if (typeof data === 'string') {
                            data = JSON.parse(response);
                        }

                        if (data && data.dados && data.dados.length > 0) {
                            const info = data.dados[0];

                            // Cria a tabela dinamicamente
                            const tableHtml = `
                                <table style="margin: 20px auto; border-collapse: collapse; border: 1px solid #333;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #333; padding: 8px;">Total Quantidade Fretes</th>
                                            <th style="border: 1px solid #333; padding: 8px;">Total Frete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border: 1px solid #333; padding: 8px;">${info.total_quantidade_fretes}</td>
                                            <td style="border: 1px solid #333; padding: 8px;">${info.total_frete}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            `;

                            // Remove a tabela anterior se existir, para evitar duplicações
                            $('table').remove();

                            // Anexa a tabela ao body
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