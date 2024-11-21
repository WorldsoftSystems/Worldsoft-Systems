# Ruta del archivo
archivo = r'c:\xampp\htdocs\Worldsoft-Systems\MEDICAL\pq2002\modificado.csv'

# Lee el archivo y procesa cada línea
with open(archivo, 'r') as file:
    lineas = file.readlines()

# Procesa cada línea para eliminar el ';' antes de '1;1;;' al final
lineas_modificadas = []
for linea in lineas:
    # Verifica si la línea contiene '1;1;;' al final
    if linea.endswith('1;1;;\n'):
        # Encuentra la posición del ';' antes de '1;1;;' y elimina ese carácter
        posicion_punto_y_coma = linea.rfind(';1;1;;')
        if posicion_punto_y_coma != -1:
            # Remueve el ';' justo antes de '1;1;;'
            linea = linea[:posicion_punto_y_coma] + linea[posicion_punto_y_coma + 1:]
    lineas_modificadas.append(linea)

# Escribe las líneas modificadas de vuelta al archivo
with open(archivo, 'w') as file:
    file.writelines(lineas_modificadas)
