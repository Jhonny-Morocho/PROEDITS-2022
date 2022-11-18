<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Genero;
class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('generos')->delete();
        Genero::create([
            'genero' => 'Cumbia',
            'estado' => 1,
            'fecha' => Carbon::now()
        ]);
        Genero::create([
            'genero' => 'Reggeton',
            'estado' => 1,
            'fecha' => Carbon::now()
        ]);
        Genero::create([
            'genero' => 'Salsa',
            'estado' => 1,
            'fecha' => Carbon::now()
        ]);
        Genero::create([
            'genero' => 'Merengue',
            'estado' => 1,
            'fecha' => Carbon::now()
        ]);
    }
}
