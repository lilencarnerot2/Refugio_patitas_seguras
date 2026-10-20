package handlers

import (
	"notification-service/services"

	"github.com/gofiber/fiber/v2"
)

type NotificacionWhatsApp struct {
	Telefono string `json:"telefono"`
	Tipo     string `json:"tipo"`
	Nombre   string `json:"nombre"`
	Mascota  string `json:"mascota"`
}

func EnviarWhatsApp(c *fiber.Ctx) error {
	var datos NotificacionWhatsApp
	if err := c.BodyParser(&datos); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(
			fiber.Map{
				"success": false,
				"message": "JSON inválido",
			},
		)
	}

	if datos.Telefono == "" {
		return c.Status(fiber.StatusBadRequest).JSON(
			fiber.Map{
				"success": false,
				"message": "El teléfono es obligatorio",
			},
		)
	}

	if datos.Tipo == "" {
		return c.Status(fiber.StatusBadRequest).JSON(
			fiber.Map{
				"success": false,
				"message": "El tipo de notificación es obligatorio",
			},
		)
	}

	if datos.Nombre == "" {
		return c.Status(fiber.StatusBadRequest).JSON(
			fiber.Map{
				"success": false,
				"message": "El nombre es obligatorio",
			},
		)
	}

	mensaje, err := services.EnviarWhatsApp(
		datos.Telefono,
		datos.Tipo,
		datos.Nombre,
		datos.Mascota,
	)

	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(
			fiber.Map{
				"success": false,
				"message": err.Error(),
			},
		)
	}

	return c.Status(fiber.StatusOK).JSON(
		fiber.Map{
			"success": true,
			"message": "Notificación simulada correctamente",
			"data": fiber.Map{
				"telefono": datos.Telefono,
				"tipo":     datos.Tipo,
				"mensaje":  mensaje,
			},
		},
	)
}
