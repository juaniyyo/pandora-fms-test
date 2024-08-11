<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserInterface {

    public function checkDni($dni) {
        return User::where('dni', $dni)->first();
    }

}
