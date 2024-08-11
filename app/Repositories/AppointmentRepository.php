<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\AppointmentInterface;

class AppointmentRepository implements AppointmentInterface {

    public function getById($id)
    {
        return Appointment::where("id", $id)->with("user")->get();
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            $is_not_reservable = Appointment::where('appointment_date', $data["date"]->setHour($data["available_hour"])->setMinute(0)->setSecond(0))
                ->lockForUpdate() // Bloqueo de registros para la concurrencia
                ->exists();

            if ($is_not_reservable) {
                return false;
            }

            $user = User::firstOrCreate(
                ['dni' =>  $data["dni"]],
                [
                    'name'  => $data["name"],
                    'dni'   => $data["dni"],
                    'phone' => $data["phone"],
                    'email' => $data["email"],
                ]);

            $user->appointments()->create(
                [
                    'appointment_type' => $data["appointment_type"],
                    'appointment_date' => $data["date"]->setHour($data["available_hour"])->setMinute(0)->setSecond(0),
                ]
            );

            DB::commit();

            return $user->load('appointments');

        } catch (\Throwable $th) {
            Log::error("Error creando la cita", [
                "error" => $th->getMessage(),
                "data" => $data
            ]);
            DB::rollback();
            return false;
        }
    }

    public function destroy($id)
    {
        return Appointment::destroy($id);
    }

    public function getAvailableHours($data) {
        // Las horas disponibles
        $available_hour = [];

        // Definimos las horas de inicio y fin de las citas
        $start_hour = env('START_HOUR', 10);
        $end_hour = env('END_HOUR', 22);

        // Obtener la fecha y hora actuales
        $now = Carbon::now();

        // Si la fecha seleccionada es hoy, la primer cita disponible no puede ser antes de la hora actual
        if ($data["date"]->isToday()) {
            $start_hour = max($start_hour, $now->hour + 1);
        }

        $existing_appointments = Appointment::whereDate('appointment_date', $data["date"]->format('Y-m-d'))
                ->orderBy('appointment_date', 'asc')
                ->pluck('appointment_date')
                ->map(function($datetime) {
                    return Carbon::parse($datetime)->hour;
                })->toArray();

        // Buscamos la primera hora libre
        for($hour = $start_hour; $hour <= $end_hour; $hour++) {
            if (!in_array($hour, $existing_appointments)) {
                $available_hour = $hour;
                break;
            }
        }
        return $available_hour;
    }

}
