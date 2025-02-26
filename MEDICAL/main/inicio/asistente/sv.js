const express = require("express");
const mysql = require("mysql2");
const cors = require("cors");

const app = express();
app.use(cors()); // Habilita CORS
app.use(express.json());

const db = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "prueba",
});

const stopWords = ["buscar", "al", "paciente", "la", "el", "del", "de"];

function limpiarTexto(texto) {
    return texto
        .toLowerCase()
        .replace(/[.,]/g, "")
        .split(" ")
        .filter((word) => !stopWords.includes(word))
        .join(" ")
        .toUpperCase();
}

app.post("/buscar-paciente", (req, res) => {
    console.log("📩 Recibida petición:", req.body);

    if (!req.body.query) {
        return res.status(400).json({ error: "Falta el parámetro query" });
    }

    const nombre = limpiarTexto(req.body.query);
    console.log("🔍 Buscando al paciente:", nombre);

    const sql = "SELECT nombre,obra_social,benef,parentesco,ugl_paciente FROM paciente WHERE nombre LIKE ? LIMIT 1";
    db.query(sql, [`%${nombre}%`], (err, result) => {
        if (err) {
            console.error("❌ Error en la consulta SQL:", err.message);
            return res.status(500).json({ success: false, error: err.message });
        }
        
        if (result.length > 0) {
            res.json({ success: true, data: result[0] });
        } else {
            res.json({ success: false, message: "Paciente no encontrado" });
        }
    });
});

app.listen(3000, () => console.log("Servidor corriendo en el puerto 3000"));