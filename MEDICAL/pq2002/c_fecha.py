import pandas as pd
from datetime import datetime

# Lee el archivo CSV con separador ';'
df = pd.read_csv(r'c:\xampp\htdocs\Worldsoft-Systems\MEDICAL\pq2002\pacientes.csv', sep=';', header=None)

# Función para convertir fechas al formato aaaa-mm-dd
def convertir_fecha(fecha):
    try:
        # Convierte el texto a fecha
        fecha_dt = datetime.strptime(fecha, '%d-%b-%y')
        # Ajusta el año al siglo XX si es mayor a la fecha actual
        if fecha_dt.year > datetime.now().year:
            fecha_dt = fecha_dt.replace(year=fecha_dt.year - 100)
        # Devuelve la fecha en el formato deseado
        return fecha_dt.strftime('%Y-%m-%d')
    except ValueError:
        return fecha  # Devuelve la fecha original si no se puede convertir

# Aplicar la conversión en las columnas de fecha (columnas 4 y 13 en el ejemplo)
df[3] = df[3].apply(convertir_fecha)  # Columna 4 (índice 3)
df[12] = df[12].apply(convertir_fecha)  # Columna 13 (índice 12)

# Guardar el archivo con el nuevo formato
df.to_csv(r'c:\xampp\htdocs\Worldsoft-Systems\MEDICAL\pq2002\archivo_modificado.csv', sep=';', index=False, header=False)
