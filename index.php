<?php

session_start();

if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = [];
}

function create_item(&$items, $itemName, $itemDescription) {
    $items[] = [
        'id' => count($items) + 1,
        'item' => $itemName,
        'description' => $itemDescription
    ];
}

function delete_item(&$items, $id) {
    foreach ($items as $key => $item) {
        if ($item['id'] == $id) {
            unset($items[$key]);
            break;
        }
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'create') {
        create_item($_SESSION['items'], $_POST['itemName'], $_POST['itemDescription']);
    } elseif ($_POST['action'] === 'delete') {
        delete_item($_SESSION['items'], $_POST['itemId']);
    }

    echo json_encode(array_values($_SESSION['items'])); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi CRUD PHP Native dengan AJAX</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<h2>Aplikasi CRUD PHP Native dengan AJAX</h2>

<form id="itemForm" method="POST">
    <label for="itemName">Nama Item:</label><br>
    <input type="text" id="itemName" name="itemName" required><br><br>
    <label for="itemDescription">Deskripsi:</label><br>
    <input type="text" id="itemDescription" name="itemDescription" required><br><br>
    <button type="button" onclick="addItem()">Tambah Item</button>
</form>

<table id="itemTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Item</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="itemTableBody">
    </tbody>
</table>

<script>
function addItem() {
    const itemName = document.getElementById('itemName').value;
    const itemDescription = document.getElementById('itemDescription').value;

    if (itemName && itemDescription) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                const items = JSON.parse(this.responseText);
                updateTable(items);
            }
        };

        xhr.send(`action=create&itemName=${encodeURIComponent(itemName)}&itemDescription=${encodeURIComponent(itemDescription)}`);
    }
}

function deleteItem(itemId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            const items = JSON.parse(this.responseText);
            updateTable(items);
        }
    };

    xhr.send(`action=delete&itemId=${itemId}`);
}

function updateTable(items) {
    const tableBody = document.getElementById('itemTableBody');
    tableBody.innerHTML = ''; 

    items.forEach(function(item) {
        const row = document.createElement('tr');

        const cellId = document.createElement('td');
        cellId.textContent = item.id;
        row.appendChild(cellId);

        const cellItem = document.createElement('td');
        cellItem.textContent = item.item;
        row.appendChild(cellItem);

        const cellDescription = document.createElement('td');
        cellDescription.textContent = item.description;
        row.appendChild(cellDescription);

        const cellActions = document.createElement('td');
        cellActions.innerHTML = `<button onclick="deleteItem(${item.id})">Hapus</button>`;
        row.appendChild(cellActions);

        tableBody.appendChild(row); 
    });

    document.getElementById('itemName').value = '';
    document.getElementById('itemDescription').value = '';
}
</script>

</body>
</html>
