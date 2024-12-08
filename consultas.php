<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Indexador - SGBD</title>
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

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin: 10px 0;
        }

        ul li a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        ul li a:hover {
            color: #0056b3;
        }

        footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>

<body>
    <h1>Indexador - SGBD</h1>

    <p>Lista de consultas disponíveis:</p>

    <ul>
        <li><a href="./consultas/01_arrecadacao_frete.php">Arrecadação com frete por cidade.</a></li>
        <li><a href="./consultas/02_quantidade_media_de_fretes.php">Quantidade média de fretes por origem e destino, por estado.</a></li>
        <li><a href="./consultas/03_funcionarios_atenderam_pessoas_juridicas.php">Todos funcionarios que atenderam pessoas jurídicas dado um mês e ano.</a></li>
    </ul>

    <footer>
        
    </footer>
</body>

</html>
