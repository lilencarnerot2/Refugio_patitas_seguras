<?php

return [
    'patron_email' => '[A-Za-z0-9_%+\-]+(\.[A-Za-z0-9_%+\-]+)*@[A-Za-z0-9]([A-Za-z0-9\-]*[A-Za-z0-9])?(\.[A-Za-z0-9]([A-Za-z0-9\-]*[A-Za-z0-9])?)*\.[A-Za-z]{2,}',
    'texto_email' => 'Ingresá un correo válido, por ejemplo nombre@dominio.com.',
    'patron_clave' => '(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])\S{8,64}',
    'texto_clave' => 'La contraseña debe tener entre 8 y 64 caracteres, sin espacios, con al menos una mayúscula, una minúscula y un número.',
    'ayuda_clave' => 'Entre 8 y 64 caracteres, con mayúscula, minúscula y número, sin espacios.'
];
