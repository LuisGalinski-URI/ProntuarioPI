<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SintomasModel extends Model
{
    public static function opcoes(): array
    {
        return [
            1 => 'Febre',
            2 => 'Tosse/Espirros',
            3 => 'Dor de Garganta',
            4 => 'Dor de Cabeça',
            5 => 'Dor no Peito',
            6 => 'Falta de Ar',
            7 => 'Dor muscular',
            8 => 'Fraqueaza',
            9 => 'Vomito',
            10 => 'Diarreia',
            11 => 'Catarro',
            12 => 'Coriza',
            13 => 'Coceira',
            14 => 'Tontura',
            15 => 'Outro',
        ];
    }
}
