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
        select,
        button,
        form {
            margin: 10px 0;
        }

        #dynamicForm {
            margin: 20px auto;
            display: inline-block;
            text-align: left;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        button {
            padding: 5px 10px;
            margin: 5px;
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
    <h1>SGBD</h1>

    <!-- Tabelas do banco -->
    <label for="tableSelector">Escolha a tabela:</label>
    <select id="tableSelector">
    </select>

    <br><br>

    <!-- adicionar informacoes -->
    <form id="dynamicForm">
    </form>

    <!-- dados -->
    <h2>Dados</h2>
    <table id="dataTable">
        <thead></thead>
        <tbody></tbody>
    </table>

    <script>
        // Define deleteRecord outside the $(document).ready block
        function deleteRecord(id) {
            const tableName = $('#tableSelector').val();
            if (confirm('Tem certeza que deseja excluir este registro?')) {
                $.post('delete_record.php', { table: tableName, id: id }, function (response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        alert(response.message);
                        loadTableData(tableName);
                    }
                }, 'json').done(

                );
            }

        }

        function editRecord(id) {
            const tableName = $('#tableSelector').val();
            $.get(`get_table_data.php?table=${tableName}&id=${id}`, function (record) {
                if (!record) {
                    alert("Erro ao obter os dados do registro.");
                    return;
                }
                console.log("editRecord");
                console.log(record);

                $('#dynamicForm').empty(); // Clear existing form
                record = record[0];
                const columns = Object.keys(record);

                // Populate form fields with existing data
                columns.forEach(column => {
                    $('#dynamicForm').append(`
                <label>${column}:</label>
                <input type="text" name="${column}" value="${record[column]}" required><br>
            `);
                });

                // Add Save Changes button
                $('#dynamicForm').append(`
            <button type="button" id="saveBtn">Salvar Alterações</button>
        `);

                // Handle Save Changes click
                $('#saveBtn').click(function () {
                    const formData = $('#dynamicForm').serialize(); // Serialize form data
                    $.post('edit_record.php', { table: tableName, id: id, data: formData }, function (response) {
                        if (response.error) {
                            alert(response.error);
                        } else {
                            alert(response.message);
                            loadTableData(tableName); // Reload table data
                        }
                    }, 'json');
                });
            }, 'json');
        }


        function loadTableData(tableName) {
            console.log("loadTableData");
            $.get(`get_table_data.php?table=${tableName}`, function (records) {
                const columns = Object.keys(records[0] || {});

                $('#dataTable thead').html('<tr>' + columns.map(col => `<th>${col}</th>`).join('') + '<th>Ações</th></tr>');

                $('#dataTable tbody').empty();

                console.log(records);
                if(tableName == 'cliente'){
                    records.forEach(record => {
                    const row = columns.map(col => `<td>${record[col]}</td>`).join('');
                    $('#dataTable tbody').append(`
                    <tr>${row}<td>
                        <button onclick="editRecord(${record.cod_cli})">Editar</button>
                        <button onclick="deleteRecord(${record.cod_cli})">Excluir</button>
                    </td></tr>`);
                    });                    
                }else if(tableName == 'frete'){
                    records.forEach(record => {
                    const row = columns.map(col => `<td>${record[col]}</td>`).join('');
                    $('#dataTable tbody').append(`
                    <tr>${row}<td>
                        <button onclick="editRecord(${record.id_frete})">Editar</button>
                        <button onclick="deleteRecord(${record.id_frete})">Excluir</button>
                    </td></tr>`);
                    }); 
                }else if(tableName == 'estado'){
                    records.forEach(record => {
                        const row = columns.map(col => `<td>${record[col]}</td>`).join('');
                        $('#dataTable tbody').append(`
                        <tr>${row}<td>
                            <button onclick="editRecord('${record.uf}')">Editar</button>
                            <button onclick="deleteRecord('${record.uf}')">Excluir</button>
                        </td></tr>`);
                    });
                }else{                  
                    records.forEach(record => {
                        const row = columns.map(col => `<td>${record[col]}</td>`).join('');
                        $('#dataTable tbody').append(`
                        <tr>${row}<td>
                            <button onclick="editRecord('${record.cod_cli}')">Editar</button>
                            <button onclick="deleteRecord('${record.cod_cli}')">Excluir</button>
                        </td></tr>`);
                    });
                }
                
            }, 'json');
        }


        $(document).ready(function () {
            // Obtem os nomes das tabelas e popula o dropdown
            $.get('get_tables.php', function (tables) {
                tables.forEach(function (table) {
                    $('#tableSelector').append(`<option value="${table}">${table}</option>`);
                });
            }, 'json');

            // Carrega a estrutura da tabela e os dados ao selecionar uma tabela
            $('#tableSelector').change(function () {
                const tableName = $(this).val();
                loadTableStructure(tableName);
                loadTableData(tableName);
            });

            // Função para carregar a estrutura da tabela selecionada
            function loadTableStructure(tableName) {
                $.get(`get_table_structure.php?table=${tableName}`, function (columns) {
                    console.log("loadTableStructure");
                    $('#dynamicForm').empty();

                    console.log(columns);
                    // Para cada coluna, cria um campo no formulário
                    if (tableName == 'cliente') {
                        columns.forEach(function (column) {
                            console.log(column);

                            if (column.name == "tipo") {

                            } else {
                                $('#dynamicForm').append(`
                                    <label>${column.name} (${column.type}):</label>
                                    <input type="text" name="${column.name}" required><br>
                                `);
                            }
                        });
                    } else {
                        columns.forEach(function (column) {
                            console.log(column);
                            $('#dynamicForm').append(`
                            <label>${column.name} (${column.type}):</label>
                            <input type="text" name="${column.name}" required><br>
                        `);

                        });
                    }


                    // Adiciona um botão para adicionar um novo registro
                    $('#dynamicForm').append(`
                <button type="button" id="addBtn">Adicionar Registro</button>
            `);

                    // Associa o evento de clique ao botão "Adicionar Registro"
                    $('#addBtn').click(function () {
                        const formData = $('#dynamicForm').serialize();
                        $.post('add_record.php', { table: $('#tableSelector').val(), data: formData }, function (response) {
                            if (response.error) {
                                console.log(response);
                                alert(response.error);
                            }
                            loadTableData($('#tableSelector').val());
                        }, 'json');
                    });
                }, 'json');
            }


        });

    </script>
</body>

</html>