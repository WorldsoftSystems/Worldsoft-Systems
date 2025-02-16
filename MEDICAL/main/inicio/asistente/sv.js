const express = require("express");
const mysql = require("mysql2");
const app = express();

app.use(express.json());

const db = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "medical_pq0241_test",
});

app.post("/buscar-paciente", (req, res) => {
    console.log("📩 Recibida petición:", req.body);

    const { query } = req.body;
    if (!query) {
        return res.status(400).json({ error: "Falta el parámetro query" });
    }

    const nombre = query.replace("Buscar paciente ", "").trim();
    console.log("🔍 Buscando paciente:", nombre);

    const sql = "SELECT * FROM pacientes WHERE nombre = ?";
    db.query(sql, [nombre], (err, result) => {
        if (err) {
            console.error("❌ Error en la consulta SQL:", err.message);
            return res.status(500).json({ error: err.message });
        }
        console.log("✅ Resultado de la consulta:", result);
        res.json(result);
    });
});


app.listen(3000, () => console.log("Servidor corriendo en el puerto 3000"));
