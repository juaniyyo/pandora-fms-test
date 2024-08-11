<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lastAppointment = $this->appointments->sortByDesc('appointment_date')->first();

        return [
            'appointmet_id'     => $lastAppointment->id,
            'user_id'           => $this->appointments[0]->user_id,
            'name'              => $this->name,
            'dni'               => $this->dni,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'appointment_type'  => $lastAppointment->appointment_type,
            'appointment_date'  => $lastAppointment->appointment_date
        ];
    }
}
