from flask import Blueprint, request, jsonify
from app.services import calcular_compatibilidad

bp = Blueprint("main", __name__)


@bp.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok"}), 200


@bp.route("/compatibility", methods=["POST"])
def compatibility():
    try:
        body = request.get_json(force=True, silent=True)

        if not body or not isinstance(body, dict):
            return jsonify({"success": False, "message": "JSON inválido."}), 400

        tipo = (body.get("tipo") or "").lower().strip()
        formulario = body.get("formulario")
        mascota = body.get("mascota")

        if tipo not in ("adopcion", "transito"):
            return jsonify({"success": False, "message": "El tipo debe ser 'adopcion' o 'transito'."}), 400

        if not isinstance(formulario, dict):
            return jsonify({"success": False, "message": "Falta el formulario."}), 400

        if not isinstance(mascota, dict):
            return jsonify({"success": False, "message": "Falta la mascota."}), 400

        puntaje, clasificacion, razones = calcular_compatibilidad(tipo, formulario, mascota)

        return jsonify({
            "success": True,
            "data": {
                "puntaje": puntaje,
                "clasificacion": clasificacion,
                "razones": razones,
            }
        }), 200

    except ValueError as e:
        return jsonify({"success": False, "message": str(e)}), 400
    except Exception as e:
        return jsonify({"success": False, "message": f"Error interno: {str(e)}"}), 500