<?php

namespace Database\Seeders;

use App\Models\Evento;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Evento::create([
            'nombre' => 'Concierto de Rock',
            'descripcion' => 'Un increíble evento de música rock en vivo.',
        ]);

        Evento::create([
            'nombre' => 'Feria de Comida',
        ]);

        Evento::create([
            'nombre' => 'Maratón Solidario',
            'descripcion' => 'Participa y apoya una buena causa.',
        ]);

        Evento::create([
            'nombre' => 'Exposición de Arte',
            'descripcion' => 'Disfruta de las mejores obras de artistas locales.',
        ]);

        Evento::create([
            'nombre' => 'Torneo de Ajedrez',
        ]);

        Evento::create([
            'nombre' => 'Festival de Cine',
            'descripcion' => 'Proyección de películas independientes.',
            'foto' => 'https://via.placeholder.com/150',
        ]);
    }
}
