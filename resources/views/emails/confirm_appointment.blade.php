<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Cita</title>
</head>
<body>
    <h1>Hola {{ $appointment["name"] }},</h1>
    <p>Tu cita ha sido confirmada con éxito.</p>
    <p><strong>Tipo de Cita:</strong> {{ $appointment["appointment_type"] }}</p>
    <p><strong>Fecha y Hora:</strong> {{ $appointment["appointment_date"] }}</p>
    <p>Gracias por confiar en nosotros.</p>
</body>
</html>
