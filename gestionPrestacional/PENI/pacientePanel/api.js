const express = require('express');
const cors = require('cors'); // Importa el módulo cors
const buscarBenef = require('./buscarBenef');

const app = express();

app.use(cors());

app.get('/buscar', async (req, res) => {
    const beneficio = req.query.beneficio;
    const parentesco = req.query.parentesco;

    try {
        // Llama a la función scrapePamiPage de buscarBenef.js
        const resultado = await buscarBenef.scrapePamiPage(beneficio, parentesco);
        
        // Envía el resultado como respuesta JSON
        res.json({ resultado });
    } catch (error) {
        // Maneja los errores y envía una respuesta de error
        console.error(error);
        res.status(500).json({ error: 'Error al buscar el beneficio' });
    }
});

app.listen(3000, () => {
    console.log('Servidor escuchando en el puerto 3000');
});
