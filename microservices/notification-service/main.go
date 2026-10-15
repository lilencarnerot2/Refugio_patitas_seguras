package main

import (
	"log"
	"notification-service/routes"

	"github.com/gofiber/fiber/v2"
)

func main() {

	app := fiber.New()

	app.Get("/health", func(c *fiber.Ctx) error {
		return c.JSON(
			fiber.Map{
				"success": true,
				"message": "Notification service funcionando",
			},
		)
	})

	routes.RegistrarRutas(app)

	log.Fatal(
		app.Listen(":3000"),
	)
}
