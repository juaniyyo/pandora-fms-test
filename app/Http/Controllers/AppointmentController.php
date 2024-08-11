<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Interfaces\AppointmentInterface;
use App\Http\Resources\AppointmentResource;
use App\Http\Requests\StoreAppointmentRequest;

class AppointmentController extends Controller
{
    private AppointmentInterface $appointment;

    public function __construct(AppointmentInterface $appointment)
    {
        $this->appointment = $appointment;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        // Las horas disponibles
        $available_hour = [];
        // El formulario validado
        $data = $request->validated();
        // Convertimos la fecha a instancia de Carbon
        $data["date"] = Carbon::parse($data["appointment_date"]);

        // Obtenemos las horas ocupadas para la fecha dada
        $available_hour = $this->appointment->getAvailableHours($data);

        if ($available_hour !== null && !empty($available_hour)) {
            // Crear la cita en la primera hora disponible
            try {
                $data["available_hour"] = $available_hour;

                $appointment = $this->appointment->store($data);

                if($appointment) {
                    return $this->success(new AppointmentResource($appointment), 'Cita creada correctamente');
                }
                throw new \Exception("No se ha podido procesar la reserva");

            } catch (\Exception $e) {
                return $this->error(
                    'Error creando la cita',
                    ['error'=>'La cita no se ha podido crear correctamente'],
                    500,
                    $e
                );
            }
        } else {
            // No hay horas disponibles
            return $this->error(
                'No hay horas disponibles',
                ['message' => 'No hay horas disponibles para la fecha seleccionada'],
                404
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = $this->appointment->getById($id);

        if (!$appointment) {
            return $this->error(
                'Cita no encontrada',
                ['error'=>'Cita no encontrada o ID incorrecto'],
                404
            );
        }
        return $this->success(new AppointmentResource($appointment), 'Showing Appointment');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->appointment->destroy($id);

            return $this->success([], 'Cita borrada');
        } catch (\Exception $e) {
            return $this->error(
                'Error borrando la cita',
                ['error'=>'La cita no pudo ser borrada correctamente'],
                500,
                $e
            );
        }
    }
}
