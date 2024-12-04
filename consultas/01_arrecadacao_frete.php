<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SGBD</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        label,
        input,
        button {
            margin: 10px 0;
            display: block;
        }

        button {
            padding: 5px 10px;
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

    <label for="estado">Estado:</label>
    <input type="text" id="cidade" name="cidade" placeholder="Informe o estado" required>
    <button id="sendRequest">Consultar</button>

    <script>
        $(document).ready(function () {
            // Evento para o botão
            $('#sendRequest').click(function () {
                // Captura o valor do campo 'estado'
                const cidade = $('#cidade').val();

                if (!cidade) {
                    alert('Por favor, informe o estado.');
                    return;
                }

                // Realiza o POST para a página 01_arrecadacao_frete_api.php
                $.ajax({
                    url: '01_arrecadacao_frete_api.php',
                    type: 'POST',
                    data: { cidade: cidade },
                    success: function (response) {
                        console.log('Resposta do servidor:', response);
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
