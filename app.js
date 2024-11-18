const express = require('express');
const cors = require('cors');
const app = express();
const port = 3000;

app.use(cors());
app.use(express.json());

let items = [];

app.post('/api/items', (req, res) => {
    const { item, description } = req.body;
    const newItem = { id: items.length + 1, item, description };
    items.push(newItem);
    res.status(201).json(newItem); 
});


app.get('/api/items', (req, res) => {
    res.json(items);
});

app.put('/api/items/:id', (req, res) => {
    const { id } = req.params;
    const { item, description } = req.body;
    const index = items.findIndex((i) => i.id == id);

    if (index !== -1) {
        items[index] = { id: parseInt(id), item, description };
        res.json(items[index]);
    } else {
        res.status(404).json({ message: 'Item tidak ditemukan' });
    }
});

app.delete('/api/items/:id', (req, res) => {
    const { id } = req.params;
    const index = items.findIndex((i) => i.id == id);

    if (index !== -1) {
        const deletedItem = items.splice(index, 1);
        res.json(deletedItem);
    } else {
        res.status(404).json({ message: 'Item tidak ditemukan' });
    }
});

app.listen(port, () => {
    console.log(`Server berjalan di http://localhost:${port}`);
});
