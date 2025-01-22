const puppeteer = require('puppeteer');

async function scrapePamiPage(valor_n_op, user, password) {
    const browser = await puppeteer.launch({
        headless: true,
        executablePath: 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe', // Ruta al ejecutable de Edge
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
        ]
    });
    const page = await browser.newPage();

    try {
        await page.goto('https://efectores.pami.org.ar/pami_efectores/login.php');
        await page.waitForSelector('#c_usuario');
        await page.waitForSelector('#password');
        await page.type('#c_usuario', user);
        await page.type('#password', password);
        await page.click('#ingresar');
        await page.waitForNavigation();
       
        

        await page.goto('https://efectores.pami.org.ar/pami_nc/OP/op_panel_listado.php');
        await page.waitForSelector('input[name="n_op"]');

        await page.evaluate((valor_n_op) => {
            const input = document.querySelector('input[name="n_op"]');
            if (input) {
                input.value = valor_n_op;
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
            } else {
                throw new Error('Input n_op no encontrado.');
            }
        }, valor_n_op);



        function esperarSegundoAsync() {
            return new Promise(resolve => {
                setTimeout(() => {
                    resolve();
                }, 5000);
            });
        }

        await page.waitForSelector('button'); // Espera a que los botones estén disponibles
        await page.evaluate(() => {
            const buttons = [...document.querySelectorAll('button')];
            const buscarButton = buttons.find(button => button.textContent.trim() === 'Buscar');
            if (buscarButton) {
                buscarButton.click();
            } else {
                console.log('Botón "Buscar" no encontrado');
            }
        });





        await esperarSegundoAsync();
        await page.waitForSelector('table.estilo_tabla');
        await esperarSegundoAsync();
        await page.waitForSelector('a[title="Detalle"]');
        await page.click('a[title="Detalle"]');
        await esperarSegundoAsync();

        const data = await page.evaluate(() => {
            const lastRow = (() => {
                const rows = Array.from(document.querySelectorAll('.table.estilo_tabla.tabla_estados tbody tr'));
                if (rows.length === 0) return null;
                const lastRow = rows[rows.length - 1];
                const columns = lastRow.querySelectorAll('td');
                const fechaHora = columns[4].innerText.trim();
                const fecha = fechaHora.split(' ')[0]; // Toma solo la parte de la fecha
                return {
                    nroOp: columns[0].innerText.trim(),
                    estado: columns[1].innerText.trim(),
                    fechaCambio: fecha
                };
            })();

            const details = (() => {
                const nombreApellido = document.querySelector('input[name="nombre_apellido"]').value.trim();
                const nroBeneficioInput = document.querySelectorAll('.row.content.marginBottom10 .form-control')[2];
                const gpInput = document.querySelectorAll('.row.content.marginBottom10 .form-control')[3];
                const nroBeneficio = nroBeneficioInput ? nroBeneficioInput.value.trim() : "";
                const gp = gpInput ? gpInput.value.trim() : "";
                return {
                    nombreApellido,
                    nroBeneficio,
                    gp
                };
            })();

            const tableRows = Array.from(document.querySelectorAll('.table.estilo_tabla.tabla_practicas tbody tr'));
            const practices = tableRows.map(row => {
                const columns = row.querySelectorAll('td');
                return {
                    codigo: columns[0].innerText.trim(),
                    cantidad: columns[3].querySelector('input').value.trim(),
                    accion: columns[6].querySelector('select').selectedOptions[0].text.trim(),
                    estado: columns[8].innerText.trim()
                };
            });

            return { lastRow, details, practices };
        });

        return data;
    } catch (error) {
        console.error('Error en el procesamiento de la op', valor_n_op, ':', error);
        throw error;
    } finally {
        await browser.close();
    }
}

module.exports = { scrapePamiPage };