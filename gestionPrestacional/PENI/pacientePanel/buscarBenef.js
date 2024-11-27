const axios = require('axios');
const cheerio = require('cheerio');

async function scrapePamiPage(beneficio, parent) {
    try {
        // Construir la URL con los parámetros proporcionados
        const url = `https://prestadores.pami.org.ar/result.php?c=6-2-1-1&beneficio=${beneficio}&parent=${parent}&vm=2`;

        // Realizar la solicitud GET a la URL
        const response = await axios.get(url);

        // Comprobar si la solicitud fue exitosa (código de estado 200)
        if (response.status === 200) {
            // Analizar el contenido HTML de la página
            const $ = cheerio.load(response.data);
            
            // Encontrar el nombre y apellido del beneficiario
            const nombreApellido = $('.grisClaro').find('p').first().text().trim();
            const fechaBaja = $('td:contains("BAJA:")').next('td').text().trim();// Imprimir el nombre, apellido y UGL
            
            // Verificar si se encontró el nombre y apellido
            if (nombreApellido && !fechaBaja) {
                // Devolver el nombre y apellido
                return nombreApellido;
            } else {
                throw new Error('Nombre y apellido no encontrados');
            }
        } else {
            throw new Error('Error en la solicitud: ' + response.status);
        }
    } catch (error) {
        // Capturar y lanzar cualquier error
        throw new Error('Error al buscar el nombre y apellido: ' + error.message);
    }
}

module.exports = {
    scrapePamiPage: scrapePamiPage
};
