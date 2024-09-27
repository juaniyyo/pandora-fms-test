<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\UserInterface;
use App\Mail\ConfirmAppointment;
use Illuminate\Support\Facades\Log;
use Resend\Laravel\Facades\Resend;

class UserController extends Controller
{
    private UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function checkDni(Request $request) {
        // Validar que el campo 'dni' esté presente en la solicitud
        $request->validate([
            'dni' => 'required|string'
        ]);

        $user = $this->user->checkDni($request->input('dni'));

        // Si el DNI existe, devuelve los success
        if ($user) {
            return $this->success([]);
        }
        return $this->error([], [], 200);
    }

    public function sendConfirmationEmail(Request $request) {
        $request->validate([
            'email' => 'required'
        ]);

        $appointment = $request->all();

        Resend::emails()->send([
            'from'      => 'Acme Psicologia Online <confirmaciones@1024mbits.es>',
            'to'        => [$request->email],
            'subject'   => 'Cita confirmada',
            'html'      => (new ConfirmAppointment($appointment))->render(),
        ]);
        return $this->success([]);
    }
}
