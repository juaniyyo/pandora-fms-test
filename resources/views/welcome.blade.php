<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Agenda Citas Online</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- SweetAlert -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Vite -->
        @vite('resources/css/app.css')

    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">

            <div class="w-full max-w-md">
                <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" id="appointment-form" name="appointment-form">
                    <!-- Nombre -->
                  	<div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                      Nombre
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" name="name" type="text" placeholder="Nombre" required>
                  	</div>

                    <!-- DNI -->
                  	<div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="dni">
                          DNI
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="dni" name="dni" type="text" placeholder="DNI" required>
                    </div>

                    <!-- Telefono -->
                  	<div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                          Teléfono
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="phone" name="phone" type="number" placeholder="Teléfono" required>
                    </div>

                    <!-- Email -->
                  	<div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                          Correo electrónico
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="email" name="email" type="email" placeholder="Correo electrónico" required>
                        <p id="email-error" class="text-red-500 text-sm mt-2 hidden">Email inválido.</p>
                    </div>

                    <!-- Tipo de cita -->
                  	<div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="appointment-type">
                          Tipo de cita
                        </label>
                        <select class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                            id="appointment-type" name="appointment-type" required>
                            <option>Selecciona el tipo de cita</option>
                            <option value="consulta">Primera consulta</option>
                        </select>
                    </div>

                    <!-- Fecha -->
                  	<div class="mb-8">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="appointment-date">
                          Fecha
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="appointment-date" name="appointment-date" type="date" placeholder="Fecha" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <button class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="button" onclick="sendForm()">
                        Solictar Cita
                        </button>
                    </div>
                </form>
                <p class="text-center text-gray-500 text-xs">
                  &copy;{{date('Y')}} Acme Psicología. Todos los derechos reservados.
                </p>
              </div>

        </div>
        @vite('resources/js/app.js')
    </body>
</html>
