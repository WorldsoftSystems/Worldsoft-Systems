# Nombre del archivo de texto
archivo_salida = "scripSQLAceptacion.txt"

# Lista para almacenar los números
numeros = []

# Leer el archivo línea por línea
with open(archivo_salida, "w") as f_out:
    with open("resultadosACEPTACION.txt", "r") as f_in:
        for linea in f_in:
            # Dividir la línea por la coma y tomar el primer elemento
            numero = linea.split(",")[0]
            # Agregar el número a la lista
            numeros.append("'" + numero + "'")

        # Escribir el UPDATE con el WHERE IN en el archivo
        f_out.write("UPDATE paciente\n")
        f_out.write("SET cargado = 'cargado'\n")
        f_out.write("WHERE benef IN (" + ", ".join(numeros) + ");\n")
