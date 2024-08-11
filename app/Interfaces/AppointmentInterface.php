<?php

namespace App\Interfaces;

interface AppointmentInterface
{
    public function getById($id);

    public function store(array $data);

    public function destroy($id);

    public function getAvailableHours($date);
}
