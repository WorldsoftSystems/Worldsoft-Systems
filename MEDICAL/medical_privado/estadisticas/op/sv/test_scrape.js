const { scrapePamiPage } = require('./dataOP.js'); // Reemplaza 'ruta_al_archivo' por la ruta correcta de tu archivo que contiene la función.

const valor_n_op = '9926197856';
const user = 'UP3069149922304';
const password = 'Argentina2024';

(async () => {
    try {
        const data = await scrapePamiPage(valor_n_op, user, password);
        console.log('Datos extraídos:', data);
    } catch (error) {
        console.error('Error al ejecutar la función:', error);
    }
})();
