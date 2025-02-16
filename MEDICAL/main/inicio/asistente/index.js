const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
recognition.lang = "es-ES";
recognition.continuous = true; // Mantiene el reconocimiento activo más tiempo
recognition.interimResults = true; // Captura resultados parciales

recognition.onstart = () => console.log("🎤 Reconocimiento de voz activado...");
recognition.onspeechend = () => console.log("🛑 Fin del discurso detectado.");
recognition.onerror = (event) => console.error("❌ Error en el reconocimiento:", event.error);


recognition.onresult = async (event) => {
    const command = event.results[0][0].transcript;
    console.log("✅ Comando reconocido:", command);
    document.getElementById("resultado").innerText = `Comando: ${command}`;

    // Enviar al backend
    console.log("📡 Enviando comando al backend...");
    const response = await fetch("http://localhost:3000/buscar-paciente", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query: command }),
    });

    const data = await response.json();
    console.log("📩 Respuesta del servidor:", data);
    document.getElementById("resultado").innerText = JSON.stringify(data, null, 2);
};

recognition.onerror = (event) => {
    console.error("❌ Error en el reconocimiento:", event.error);
};

document.getElementById("btnHablar").addEventListener("click", () => {
    console.log("🎤 Botón presionado, iniciando reconocimiento...");
    recognition.start();
});
