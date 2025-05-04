// 🔥 Se encarga solo de la comunicación con la API de Beneficiarios
export async function buscarBeneficiario(beneficio, parentesco) {
    try {
        const response = await fetch(`https://worldsoftsystems.com.ar/buscar?beneficio=${beneficio}&parentesco=${parentesco}`, {
            method: 'GET',
            headers: {
                "Content-Type": "application/json"
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error buscando beneficiario:', error);
        throw error; // Propagamos el error para manejarlo afuera
    }
}
