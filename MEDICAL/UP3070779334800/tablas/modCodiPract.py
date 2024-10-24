import pandas as pd
from datetime import datetime

# Nombre del archivo CSV original
input_csv = 'C:/xampp/htdocs/Worldsoft-Systems/MEDICAL/UP30546191482/tablas/paciEGRESOint.csv'

# Nombre del archivo CSV de salida
output_csv = 'C:/xampp/htdocs/Worldsoft-Systems/MEDICAL/UP30546191482/tablas/paciEGRESOintMODIFICADA.csv'

# Función para modificar las filas
def modificar_fila(row):
    # Verificar el valor final de la fila
    valor_final = row.iloc[-1]
    
    # Modificar el valor en la columna 4 basado en el valor final
    if valor_final == 6:
        row[4] = 490  # Cambiar el valor en la posición 4 a 490
    elif valor_final == 5:
        row[4] = 489  # Cambiar el valor en la posición 4 a 489
    elif valor_final == 4:
        row[4] = 601  # Cambiar el valor en la posición 4 a 601
    elif valor_final == 7:
        row[4] = 629  # Cambiar el valor en la posición 4 a 629
    
    return row[:-1]  # Eliminar el valor final (última columna)

# Función para convertir fechas de formato dd/mm/aaaa a aaaa-mm-dd
def convertir_fecha(fecha):
    try:
        return datetime.strptime(fecha, "%d/%m/%Y").strftime("%Y-%m-%d")
    except ValueError:
        return fecha  # Si la fecha no es válida, devolver la fecha original

# Leer el archivo CSV
df = pd.read_csv(input_csv, sep=';', header=None)

# Convertir la fecha (columna 1) al formato aaaa-mm-dd
df[1] = df[1].apply(convertir_fecha)

# Modificar las filas según el valor final y eliminar la última columna
df_modificada = df.apply(modificar_fila, axis=1)

# Guardar el archivo CSV modificado
df_modificada.to_csv(output_csv, sep=';', header=False, index=False)

print(f"Archivo modificado guardado como: {output_csv}")
