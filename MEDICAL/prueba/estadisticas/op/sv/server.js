const express = require('express');
const cors = require('cors');
const path = require('path'); // Asegúrate de importar path
const dataOP = require('./dataOP');
const scrapePamiTodasOps = require('./todasLasOp'); // Importa la función

const app = express();
app.use(cors());
app.use(express.static(path.join(__dirname, '../public')));

app.get('/buscar', async (req, res) => { //CAMBIAR NOMBRE DE /BUSCAR 
    const valor_n_op = req.query.n_op;
    const usuario = req.query.usuario;
    const clave = req.query.clave;

    if (!valor_n_op) {
        return res.status(400).json({ error: 'El parámetro n_op es obligatorio' });
    }

    try {
        const resultado = await dataOP.scrapePamiPage(valor_n_op, usuario, clave);
        res.json({ resultado });
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al buscar el beneficio' });
    }
});

app.get('/todasOps', async (req, res) => {
    const { usuario, clave } = req.query;
    console.log(`Usuario: ${usuario}, Clave: ${clave}`); // Debug

    if (!usuario || !clave) {
        return res.status(400).json({ error: 'Usuario y clave son requeridos' });
    }

    try {
        const data = await scrapePamiTodasOps(usuario, clave);
        res.json(data);
    } catch (error) {
        console.error(error); // Debug
        res.status(500).json({ error: 'Error al obtener datos' });
    }
});


app.listen(3000, () => {
    console.log('Servidor escuchando en el puerto 3000');
});
