<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;

use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Psy\Command\Command;
use App\Models\Estudiante;
use Illuminate\Database\Eloquent\Model;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        "App\Console\Commands\NotificarUsuarios"
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:notificarUsuarios')->everyMinute();
        //$schedule->command('command:eliminarMensaje')->everyMinute();
    }

}
