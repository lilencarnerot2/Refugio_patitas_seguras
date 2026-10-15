package routes

import (
	"notification-service/handlers"

	"github.com/gofiber/fiber/v2"
)

func RegistrarRutas(app *fiber.App) {

	app.Post(
		"/notifications/whatsapp",
		handlers.EnviarWhatsApp,
	)
}
