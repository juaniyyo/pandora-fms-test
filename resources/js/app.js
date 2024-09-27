import './bootstrap';

const dateInput = document.getElementById('appointment-date');
const today = new Date().toISOString().split('T')[0];
dateInput.setAttribute('min', today);

document.getElementById('dni').addEventListener('blur', function () {
    const dni = this.value;
    if(dni) {
        fetch('/users/check-dni', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ dni: dni })
        })
        .then(response => response.json())
        .then(data => {
            const tipoCitaSelect = document.getElementById('appointment-type');
            tipoCitaSelect.innerHTML = '<option value="consulta">Primera consulta</option>';
            if(data.success) {
                tipoCitaSelect.innerHTML = '<option value="revision">Revisión</option>';
            }
        });
    }
});

document.getElementById('email').addEventListener('blur', function () {
    const email = this.value;
    const emailError = document.getElementById('email-error');
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    if (!isValid) {
        emailError.classList.remove('hidden');
    } else {
        emailError.classList.add('hidden');
    }
});

async function sendForm() {
    const validatedEmail = document.getElementById('email').value;
    const emailError = document.getElementById('email-error');
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(validatedEmail);
    //Comprobamos el email antes de enviar el formulario
    if (!isValid) {
        event.preventDefault();
        emailError.classList.remove('hidden');
        return;
    }
    //Obtenemos el formulario
    const form = document.getElementById('appointment-form');
    const formData = new FormData(form);

    const appointment = {
        name: formData.get('name'),
        email: formData.get('email'),
        dni: formData.get('dni'),
        phone: formData.get('phone'),
        appointment_type: formData.get('appointment-type'),
        appointment_date: formData.get('appointment-date'),
    }

    try {
        //Creamos la reserva en la base de datos
        const appointmentCreated = await createAppointment(appointment);

        if (appointmentCreated) {
            Swal.fire(
                'Acme Psicologia Online',
                'Cita creada correctamente',
                'success'
            ).then(() => {
                form.reset();

                Swal.fire({
                    title: 'Confirmar Cita',
                    html: `
                        <div class="relative block overflow-hidden rounded-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
                            <span class="absolute inset-x-0 bottom-0 h-2 bg-gradient-to-r from-green-300 via-blue-500 to-purple-600"></span>
                            <div class="sm:flex sm:justify-between sm:gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 sm:text-xl">
                                        Acme Psicología Online
                                    </h3>

                                    <p class="mt-1 text-xs font-medium text-gray-600">Confirmación de la cita</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <p class="text-pretty text-sm text-gray-500">
                                En este momento puede confirmar la cita y recibirá un correo electronico con la confirmación o
                                puede cancelarla para que otra persona pueda utilizarla.
                                </p>
                            </div>

                            <dl class="mt-6 flex gap-4 sm:gap-6">

                                <div class="flex flex-col-reverse">
                                    <dt class="text-sm font-medium text-gray-600">Tipo de cita</dt>
                                    <dd class="text-xs text-gray-500">${appointmentCreated.appointment_type}</dd>
                                </div>

                                <div class="flex flex-col-reverse">
                                    <dt class="text-sm font-medium text-gray-600">Fecha y Hora</dt>
                                    <dd class="text-xs text-gray-500">${appointmentCreated.appointment_date}</dd>
                                </div>
                            </dl>
                            <dl class="mt-6 flex gap-4 sm:gap-6">
                                <div class="flex flex-col-reverse">
                                    <dt class="text-sm font-medium text-gray-600">Nombre</dt>
                                    <dd class="text-xs text-gray-500">${appointmentCreated.name}</dd>
                                </div>

                                <div class="flex flex-col-reverse">
                                    <dt class="text-sm font-medium text-gray-600">Email</dt>
                                    <dd class="text-xs text-gray-500">${appointmentCreated.email}</dd>
                                </div>
                            </dl>
                        </div>
                    `,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar Reserva',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, envía un email de confirmación
                        sendConfirmationEmail(appointmentCreated);
                        Swal.fire(
                            'Confirmado',
                            'La reserva ha sido confirmada y el email fue enviado.',
                            'success'
                        );
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Si el usuario cancela, elimina la reserva
                        deleteAppointment(appointmentCreated.appointmet_id);
                        Swal.fire(
                            'Cancelado',
                            'La reserva ha sido cancelada.',
                            'error'
                        );
                    }
                });
            });
        }
    } catch (error) {
        Swal.fire(
            'Acme Psicologia Online',
            `${error.message}`,
            'error'
        ).then(function() {
            form.reset();
        });
    }
}

async function createAppointment(appointmentData) {
    const response = await fetch('/appointments', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(appointmentData)
    });

    const data = await response.json();

    if (data.success) {
        return data.data;
    }
    throw new Error(data.data.message);
}

function sendConfirmationEmail(appointment) {
    // Implementa la lógica para enviar el email de confirmación aquí
    fetch('/users/send-confirmation-email', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(appointment)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Email enviado:', data);
    })
    .catch(error => {
        console.error('Error al enviar el email:', error);
    });
}

function deleteAppointment(appointmentId) {
    // Implementa la lógica para eliminar la reserva aquí
    fetch(`/appointments/${appointmentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Cita eliminada:', data);
    })
    .catch(error => {
        console.error('Error al eliminar la cita:', error);
    });
}

window.sendForm = sendForm;
