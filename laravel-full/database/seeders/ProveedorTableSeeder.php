<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
class ProveedorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //documentacion https://pathros.blogspot.com/2020/04/como-crear-un-seeder-para-laravel-roles.html
        DB::table('proveedors')->delete();
        $proveedor = \App\Models\Proveedor::create([
            'nombre' => env('ADMIN_NAME'),
            'apellido' => env('ADMIN_NAME'),
            'apodo' => env('ADMIN_NAME'),
            'correo' => env('ADMIN_EMAIL'),
            'password' => Hash::make(env('ADMIN_PASSWORD')),
            'img' => env('ADMIN_NAME'),
            'estado' => 1,
            'fecha' => Carbon::now()
        ]);
        $proveedor->assignRole('super-admin');
        //$proveedor->assignRole('super-admin');
    }
}
