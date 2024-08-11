<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'           => User::factory(),
            'appointment_type'  => 'consulta',
            'appointment_date'  => $this->generateAppointmentDateTime()
        ];
    }

    private function generateAppointmentDateTime()
    {
        $date = $this->faker->dateTimeBetween('now', '+1 month');

        $hour = rand(10, 22);
        $date->setTime($hour, 0, 0);

        return $date;
    }
}
