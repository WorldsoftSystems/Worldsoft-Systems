const fs = require('fs');
const axios = require('axios');
const cheerio = require('cheerio');
const readline = require('readline');

function scrapePamiPage(beneficio, parent) {
    // Construir la URL con los parámetros proporcionados
    const url = `https://prestadores.pami.org.ar/result.php?c=6-2-1-1&beneficio=${beneficio}&parent=${parent}&vm=2`;

    // Realizar la solicitud GET a la URL
    axios.get(url)
        .then(response => {
            // Comprobar si la solicitud fue exitosa (código de estado 200)
            if (response.status === 200) {
                // Analizar el contenido HTML de la página
                const $ = cheerio.load(response.data);
                
                // Encontrar el nombre y apellido del beneficiario
                const nombreApellido = $('.grisClaro').find('p').first().text().trim();
                const fechaBaja = $('td:contains("BAJA:")').next('td').text().trim();// Imprimir el nombre, apellido y UGL
                if (nombreApellido && !fechaBaja) {
                    console.log(nombreApellido);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            return false;
        });
}

// Llamar a la función con un beneficio y parentesco específico
scrapePamiPage('10015604104', '00');