from app.rules import calcular_compatibilidad_adopcion, calcular_compatibilidad_transito


def calcular_compatibilidad(tipo, formulario, mascota):
    if tipo == "adopcion":
        return calcular_compatibilidad_adopcion(formulario, mascota)
    elif tipo == "transito":
        return calcular_compatibilidad_transito(formulario, mascota)
    else:
        raise ValueError("Tipo no válido. Debe ser 'adopcion' o 'transito'.")