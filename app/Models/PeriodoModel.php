<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoModel extends Model
{
    public static function opcoes(): array
    {
        return [
            1 => 'Recente (menos de 3 horas)',
            2 => 'Hoje',
            3 => 'Mais de um dia',
        ];
    }
}
