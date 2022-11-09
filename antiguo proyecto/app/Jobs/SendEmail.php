<?php

namespace App\Jobs;
use App\Models\Usuario;
class SendEmail extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $usuario;
    public function __construct(Usuario $usuario)
    {
        //
        $this->usuario=$usuario;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        echo "TEST".$this->usuario->correo;
    }
}
