const apiUrl = 'http://localhost:3000/api/items';

const tambahkanBarisItem = (item) => {
    const tableBody = document.querySelector('#itemTable tbody');
    const row = document.createElement('tr');

    console.log('Menambahkan item ke tabel:', item);

    const nameCell = document.createElement('td');
    nameCell.textContent = item.item;

    const descriptionCell = document.createElement('td');
    descriptionCell.textContent = item.description;

    const actionsCell = document.createElement('td');
    const hapusButton = document.createElement('button');
    hapusButton.textContent = 'Hapus';
    hapusButton.onclick = () => {
        hapusItem(item.id);
        row.remove(); 
    };

    actionsCell.appendChild(hapusButton);
    row.appendChild(nameCell);
    row.appendChild(descriptionCell);
    row.appendChild(actionsCell);

    tableBody.appendChild(row); 
};

const tambahItem = async (item, description) => {
    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ item, description })
        });

        if (!response.ok) {
            throw new Error('Gagal menambahkan item');
        }

        const newItem = await response.json();
        tambahkanBarisItem(newItem);

    } catch (error) {
        console.error('Error saat menambahkan item:', error);
    }
};

const hapusItem = async (id) => {
    try {
        await fetch(`${apiUrl}/${id}`, { method: 'DELETE' });
    } catch (error) {
        console.error('Error saat menghapus item:', error);
    }
};

document.getElementById('item-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const itemName = document.getElementById('item-name').value;
    const itemDescription = document.getElementById('item-description').value;

    console.log('Menambahkan item:', itemName, itemDescription);

    tambahItem(itemName, itemDescription);
    document.getElementById('item-form').reset(); 
});

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch(apiUrl);
        const items = await response.json();
        items.forEach(tambahkanBarisItem);
    } catch (error) {
        console.error('Error saat mengambil item:', error);
    }
});
