package services

import (
	"fmt"
	"os"

	"github.com/twilio/twilio-go"
	openapi "github.com/twilio/twilio-go/rest/api/v2010"
)

func EnviarWhatsApp(
	telefono string,
	tipo string,
	nombre string,
	mascota string,
) (string, error) {

	account_sid := os.Getenv("TWILIO_ACCOUNT_SID")
	auth_token := os.Getenv("TWILIO_AUTH_TOKEN")
	whatsapp_from := os.Getenv("TWILIO_WHATSAPP_FROM")
	content_sid := os.Getenv("TWILIO_CONTENT_SID")

	if account_sid == "" ||
		auth_token == "" ||
		whatsapp_from == "" ||
		content_sid == "" {

		return "", fmt.Errorf(
			"faltan variables de entorno de Twilio",
		)
	}

	var descripcion string

	switch tipo {

	case "solicitud_aprobada":
		descripcion = fmt.Sprintf(
			"Solicitud aprobada para %s",
			mascota,
		)

	case "solicitud_rechazada":
		descripcion = fmt.Sprintf(
			"Solicitud rechazada para %s",
			mascota,
		)

	case "voluntariado_confirmado":
		descripcion = fmt.Sprintf(
			"Postulación de voluntariado recibida para %s",
			nombre,
		)

	default:
		return "", fmt.Errorf(
			"tipo de notificación no válido",
		)
	}

	client := twilio.NewRestClientWithParams(
		twilio.ClientParams{
			Username: account_sid,
			Password: auth_token,
		},
	)

	params := &openapi.CreateMessageParams{}

	params.SetFrom(whatsapp_from)
	params.SetTo("whatsapp:" + telefono)
	params.SetContentSid(content_sid)

	respuesta, err := client.Api.CreateMessage(params)

	if err != nil {
		return "", fmt.Errorf(
			"error enviando WhatsApp con Twilio: %w",
			err,
		)
	}

	fmt.Println("================================")
	fmt.Println("NOTIFICACIÓN WHATSAPP ENVIADA")
	fmt.Println("Teléfono:", telefono)
	fmt.Println("Tipo:", tipo)
	fmt.Println("Descripción interna:", descripcion)

	if respuesta.Sid != nil {
		fmt.Println(
			"Twilio SID:",
			*respuesta.Sid,
		)
	}

	fmt.Println("================================")

	return descripcion, nil
}
