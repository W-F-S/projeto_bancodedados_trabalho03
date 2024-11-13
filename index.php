<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Multi-Table CRUD</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Dynamic Multi-Table CRUD</h1>

    <!-- Dropdown to Select Table -->
    <label>Select Table:</label>
    <select id="tableSelector">
        <!-- Options will be loaded dynamically -->
    </select>

    </br>

    <!-- Form to add or edit records, dynamically generated -->
    <form id="dynamicForm">
        <!-- Form fields will be loaded based on selected table -->
    </form>

    <!-- Table to display records, dynamically generated -->
    <h2>Records</h2>
    <table border="1" id="dataTable">
        <thead></thead>
        <tbody></tbody>
    </table>

    <script>
    $(document).ready(function () {
        // Fetch table names and populate dropdown
        $.get('get_tables.php', function (tables) {
            tables.forEach(function (table) {
                $('#tableSelector').append(`<option value="${table}">${table}</option>`);
            });
        }, 'json');

        // Load table structure and data when a table is selected
        $('#tableSelector').change(function () {
            const tableName = $(this).val();
            loadTableStructure(tableName);
            loadTableData(tableName);
        });

        function loadTableStructure(tableName) {
            $.get(`get_table_structure.php?table=${tableName}`, function (columns) {
                console.log("loadTableStructure");
                $('#dynamicForm').empty();  // Clear existing form fields
                columns.forEach(function (column) {
                    console.log(column);
                    $('#dynamicForm').append(`
                        <label>${column.name} (${column.type}):</label>
                        <input type="text" name="${column.name}" required><br>
                    `);
                });
                $('#dynamicForm').append(`
                    <button type="button" id="addBtn">Add Record</button>
                `);

                // Attach click event for Add Record button after form generation
                $('#addBtn').click(function () {
                    const formData = $('#dynamicForm').serialize(); // Serialize form data
                    $.post('add_record.php', { table: $('#tableSelector').val(), data: formData }, function (response) {
                        alert(response.message);
                        loadTableData($('#tableSelector').val()); // Refresh data
                    }, 'json');
                });
            }, 'json');
        }

        function loadTableData(tableName) {
            $.get(`get_table_data.php?table=${tableName}`, function (records) {
                // Populate table headers
                const columns = Object.keys(records[0] || {});
                $('#dataTable thead').html('<tr>' + columns.map(col => `<th>${col}</th>`).join('') + '<th>Actions</th></tr>');

                // Populate table rows
                $('#dataTable tbody').empty();
                records.forEach(record => {
                    const row = columns.map(col => `<td>${record[col]}</td>`).join('');
                    $('#dataTable tbody').append(`<tr>${row}<td>
                        <button onclick="editRecord(${record.id})">Edit</button>
                        <button onclick="deleteRecord(${record.id})">Delete</button>
                    </td></tr>`);
                });
            }, 'json');
        }
    });
    </script>
</body>
</html>
