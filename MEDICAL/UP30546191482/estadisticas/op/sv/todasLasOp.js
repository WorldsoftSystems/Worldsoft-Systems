const puppeteer = require('puppeteer');

async function scrapePamiTodasOps(user, password) {
    const browser = await puppeteer.launch({ headless: true, args: ['--no-sandbox', '--disable-setuid-sandbox'] });
    const page = await browser.newPage();

    try {
        await page.goto('https://efectores.pami.org.ar/pami_efectores/login.php');
        await page.waitForSelector('#c_usuario');
        await page.waitForSelector('#password');
        await page.type('#c_usuario', user);
        await page.type('#password', password);
        await page.click('#ingresar');
        await page.waitForNavigation();

        // Ir a la página específica después del login
        await page.goto('https://efectores.pami.org.ar/pami_nc/OP/op_panel_listado.php');
        await page.waitForSelector('select.cmb_estado');

        function esperar() {
            return new Promise(resolve => {
                setTimeout(() => {
                    resolve();
                }, 5000); // 
            });
        }
        await esperar();
        // Obtener el primer valor del select
        const options = await page.evaluate(() => {
            const select = document.querySelector('select.cmb_estado');
            return Array.from(select.options).map(option => option.value);
        });
        // Seleccionar el primer valor (excluyendo el valor vacío)
        if (options.length > 1) {
            await page.select('select.cmb_estado', options[1]); // Selecciona el primer valor no vacío
        } else {
            console.error('No se encontraron opciones válidas.');
        }

        await page.evaluate(() => {
            const inputElement = document.querySelector("#controlador_buscador > div.panel_paginador > div.col-lg-6.col-md-6.col-sm-6.marginBottom10.hidden-xs > div.col-lg-1.col-md-2.col-sm-2 > input");
            inputElement.value = '300';
            const event = new Event('input', { bubbles: true });
            inputElement.dispatchEvent(event);
        });

        // Hacer clic en el botón
        await page.evaluate(() => {
            const buttonElement = document.querySelector("#controlador_botonera > div:nth-child(1) > button");
            buttonElement.click();
        });
        function esperarMinutoAsync() {
            return new Promise(resolve => {
                setTimeout(() => {
                    resolve();
                }, 60000); // 60,000 milisegundos = 1 minuto
            });
        }
        
        await esperarMinutoAsync();
        // Esperar a que la tabla se cargue
        await page.waitForSelector("#controlador_buscador > table");

        // Extraer datos de la columna "NRO. OP"
        const columnData = await page.evaluate(() => {
            const rows = document.querySelectorAll("#controlador_buscador > table tbody tr");
            const columnValues = [];

            rows.forEach(row => {
                const cells = row.querySelectorAll("td"); // Obtén todos los td en la fila
                if (cells.length > 1) { // Asegúrate de que haya al menos dos columnas
                    const cell = cells[1]; // Accede al segundo td (índice 1)
                    columnValues.push(cell.textContent.trim());
                }
            });

            return columnValues;
        });

        return columnData

    } catch (error) {
        throw error;
    } finally {
        await browser.close();
    }
}

module.exports = scrapePamiTodasOps;
