<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class ClienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clientes')->delete();
        $cliente = \App\Models\Cliente::create([
            'nombre' => 'Jhonny',
            'apellido' => 'Morocho',
            'correo' => 'jhonnymichaedj2011@hotmail.com',
            'password' => Hash::make(env('ADMIN_PASSWORD')),
            'estado' => 1,
            'autenticacion' => 'facebook',
            'fecha' => Carbon::now()
        ]);
        $cliente->assignRole('cliente');
    }
}
