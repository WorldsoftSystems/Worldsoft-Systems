const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
recognition.lang = "es-ES";
recognition.continuous = true;
recognition.interimResults = true;

recognition.onresult = async (event) => {
    const command = event.results[0][0].transcript;

    console.log("📡 Enviando comando al backend...");
    const response = await fetch("http://localhost:3000/buscar-paciente", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query: command }),
    });

    const data = await response.json();

    const resultContainer = document.getElementById("resultContainer");
    if (data.success) {
        resultContainer.innerHTML = `
            <div class="floating-container">
                <div class="floating-content">
                    <h3>Paciente Encontrado</h3>
                    <p><strong>Nombre:</strong> ${data.data.nombre}</p>
                    <p><strong>Obra Social:</strong> ${data.data.obra_social}</p>
                    <p><strong>Beneficio:</strong> ${data.data.benef}</p>
                    <p><strong>Parentesco:</strong> ${data.data.parentesco}</p>
                    <p><strong>UGL Paciente:</strong> ${data.data.ugl_paciente}</p>
                    <button class="close-btn" onclick="cerrarContainer()">Cerrar</button>
                </div>
            </div>`;
    } else {
        resultContainer.innerHTML = `
            <div class="floating-container">
                <div class="floating-content">
                    <h3>Paciente no encontrado</h3>
                    <p>Intenta con otro nombre.</p>
                    <button class="close-btn" onclick="cerrarContainer()">Cerrar</button>
                </div>
            </div>`;
    }
};

// Función para cerrar el contenedor flotante
function cerrarContainer() {
    document.getElementById("resultContainer").innerHTML = "";
} document.getElementById("resultContainer").innerHTML = "";


document.getElementById("btnHablar").addEventListener("click", () => {
    console.log("🎤 Botón presionado, iniciando reconocimiento...");
    recognition.start();
});