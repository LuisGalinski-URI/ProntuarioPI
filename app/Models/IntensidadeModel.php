<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntensidadeModel extends Model
{
    public static function opcoes(): array
    {
        return [
            1 => 'Baixa',
            2 => 'Média',
            3 => 'Alta',
        ];
    }
}
