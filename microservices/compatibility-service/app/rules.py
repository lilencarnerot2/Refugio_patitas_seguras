from datetime import datetime


def calcular_edad(fecha_nacimiento_str):
    if not fecha_nacimiento_str:
        return None
    try:
        fecha = datetime.strptime(fecha_nacimiento_str, "%Y-%m-%d")
        hoy = datetime.today()
        return hoy.year - fecha.year - ((hoy.month, hoy.day) < (fecha.month, fecha.day))
    except Exception:
        return None


def clasificar(puntaje):
    if puntaje >= 80:
        return "alta"
    if puntaje >= 60:
        return "media"
    if puntaje >= 40:
        return "baja"
    return "muy_baja"


def calcular_compatibilidad_adopcion(formulario, mascota):
    puntaje = 100
    razones = []

    tamano = (mascota.get("tamano") or "").lower().strip()
    tiene_patio = bool(formulario.get("tiene_patio", False))
    metros = formulario.get("metros_cuadrados_patio")
    try:
        metros = float(metros) if metros is not None else None
    except Exception:
        metros = None

    hay_ninos = bool(formulario.get("hay_ninos", False))
    horas_solo = formulario.get("horas_solo")
    try:
        horas_solo = int(horas_solo) if horas_solo is not None else None
    except Exception:
        horas_solo = None

    puede_pasear = bool(formulario.get("puede_realizar_paseos", False))
    experiencia = (formulario.get("experiencia_cuidado") or "").strip()
    cuenta_recursos = formulario.get("cuenta_con_recursos")
    cuenta_apoyo = bool(formulario.get("cuenta_con_apoyo", False))

    integrantes = formulario.get("cantidad_integrantes")
    try:
        integrantes = int(integrantes) if integrantes is not None else None
    except Exception:
        integrantes = None

    edad_mascota = calcular_edad(mascota.get("fecha_nacimiento_estimada"))

    # Tamaño y patio
    if tamano == "grande":
        if not tiene_patio:
            puntaje -= 40
            razones.append("Mascota grande sin patio (-40)")
        elif metros is not None and metros < 10:
            puntaje -= 20
            razones.append("Mascota grande con patio chico (-20)")
        else:
            puntaje += 5
            razones.append("Mascota grande con patio adecuado (+5)")
    elif tamano == "mediano":
        if not tiene_patio:
            puntaje -= 20
            razones.append("Mascota mediana sin patio (-20)")
    elif tamano == "pequeño":
        if not tiene_patio:
            razones.append("Mascota pequeña sin patio (0)")
        else:
            puntaje += 5
            razones.append("Mascota pequeña con patio (+5)")

    # Horas solo
    if horas_solo is not None:
        if horas_solo > 8:
            if edad_mascota is not None and edad_mascota < 1:
                puntaje -= 30
                razones.append("Cachorro solo muchas horas (-30)")
            else:
                puntaje -= 10
                razones.append("Mascota sola muchas horas (-10)")
        elif horas_solo <= 4:
            puntaje += 10
            razones.append("Pocas horas solo (+10)")

    # Niños
    if hay_ninos:
        if tamano == "grande":
            puntaje -= 15
            razones.append("Niños en casa con mascota grande (-15)")
        elif tamano == "pequeño":
            puntaje += 5
            razones.append("Niños en casa con mascota pequeña (+5)")

    # Paseos
    if not puede_pasear and tamano == "grande":
        puntaje -= 25
        razones.append("No puede pasear y mascota grande (-25)")
    elif puede_pasear and tamano == "grande":
        puntaje += 10
        razones.append("Puede pasear y mascota grande (+10)")

    # Experiencia
    if tamano == "grande":
        if experiencia == "":
            puntaje -= 20
            razones.append("Sin experiencia y mascota grande (-20)")
        else:
            puntaje += 10
            razones.append("Con experiencia y mascota grande (+10)")

    # Recursos
    if cuenta_recursos is False:
        puntaje -= 10
        razones.append("Sin recursos declarados (-10)")

    # Apoyo
    if cuenta_apoyo:
        puntaje += 5
        razones.append("Cuenta con apoyo (+5)")

    # Integrantes
    if integrantes is not None and integrantes >= 4 and tamano == "pequeño":
        puntaje += 5
        razones.append("Hogar numeroso con mascota pequeña (+5)")

    puntaje = max(0, min(100, puntaje))
    return puntaje, clasificar(puntaje), razones


def calcular_compatibilidad_transito(formulario, mascota):
    puntaje = 100
    razones = []

    tamano = (mascota.get("tamano") or "").lower().strip()
    tiene_patio = bool(formulario.get("tiene_patio", False))
    metros = formulario.get("metros_cuadrados_patio")
    try:
        metros = float(metros) if metros is not None else None
    except Exception:
        metros = None

    horas_solo = formulario.get("horas_solo")
    try:
        horas_solo = int(horas_solo) if horas_solo is not None else None
    except Exception:
        horas_solo = None

    puede_pasear = bool(formulario.get("puede_realizar_paseos", False))
    puede_medicar = bool(formulario.get("puede_administrar_medicacion", False))
    cuenta_apoyo = bool(formulario.get("cuenta_con_apoyo", False))

    cantidad_max = formulario.get("cantidad_maxima_mascotas")
    try:
        cantidad_max = int(cantidad_max) if cantidad_max is not None else None
    except Exception:
        cantidad_max = None

    tiene_mascotas = bool(formulario.get("tiene_mascotas", False))

    # Tamaño y patio
    if tamano == "grande":
        if not tiene_patio:
            puntaje -= 40
            razones.append("Mascota grande sin patio (-40)")
        elif metros is not None and metros < 10:
            puntaje -= 20
            razones.append("Mascota grande con patio chico (-20)")
    elif tamano == "mediano":
        if not tiene_patio:
            puntaje -= 20
            razones.append("Mascota mediana sin patio (-20)")

    # Capacidad
    if cantidad_max is not None and cantidad_max <= 1 and tiene_mascotas:
        puntaje -= 20
        razones.append("Capacidad máxima baja con mascotas existentes (-20)")

    # Horas solo
    if horas_solo is not None:
        if horas_solo > 8:
            puntaje -= 15
            razones.append("Mascota sola muchas horas (-15)")
        elif horas_solo <= 4:
            puntaje += 10
            razones.append("Pocas horas solo (+10)")

    # Paseos
    if not puede_pasear and tamano == "grande":
        puntaje -= 25
        razones.append("No puede pasear y mascota grande (-25)")

    # Medicación
    if puede_medicar:
        puntaje += 10
        razones.append("Puede administrar medicación (+10)")

    # Apoyo
    if cuenta_apoyo:
        puntaje += 5
        razones.append("Cuenta con apoyo (+5)")

    puntaje = max(0, min(100, puntaje))
    return puntaje, clasificar(puntaje), razones