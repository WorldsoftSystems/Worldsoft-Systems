import pandas as pd
from datetime import datetime

# Nombre del archivo CSV original
input_csv = 'C:/xampp/htdocs/Worldsoft-Systems/MEDICAL/UP30546191482/tablas/paciente/pacienteIntModificado.csv'

# Nombre del archivo CSV de salida
output_csv = 'C:/xampp/htdocs/Worldsoft-Systems/MEDICAL/UP30546191482/tablas/paciente/pacienteIntModificado.csv'

# Función para convertir la fecha de 'dd-mm-aaaa' a 'aaaa-mm-dd'
def convertir_fecha(fecha):
    try:
        return datetime.strptime(fecha, '%d-%m-%Y').strftime('%Y-%m-%d')
    except:
        return fecha  # Si no se puede convertir, devolver la fecha original

# Leer el archivo CSV
df = pd.read_csv(input_csv, sep=';', header=None)

# Convertir la columna 13 (índice 12) que contiene las fechas
df[12] = df[12].apply(convertir_fecha)

# Guardar el nuevo archivo CSV
df.to_csv(output_csv, sep=';', index=False, header=False)

print(f'Archivo modificado guardado como {output_csv}')
