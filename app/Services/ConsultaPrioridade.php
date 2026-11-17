<?php

namespace App\Services;

class ConsultaPrioridade
{
    private const PESOS_SINTOMAS = [
        1 => 10,
        2 => 4,
        3 => 5,
        4 => 8,
        5 => 40,
        6 => 35,
        7 => 6,
        8 => 8,
        9 => 12,
        10 => 10,
        11 => 3,
        12 => 2,
        13 => 2,
        14 => 15,
        15 => 5,
    ];

    private const MULTIPLICADORES_INTENSIDADE = [
        1 => 1.0,
        2 => 1.5,
        3 => 2.0,
    ];

    private const MULTIPLICADORES_PERIODO = [
        1 => 1.5,
        2 => 1.2,
        3 => 1.0,
    ];

    public static function calcular(
        array $sintomas,
        int $intensidadeId,
        ?int $possuiDoencasCronicas,
        int $periodoId
    ): int {
        $pesoSintomas = array_sum(array_map(
            static fn (int|string $sintoma): int => self::PESOS_SINTOMAS[(int) $sintoma],
            $sintomas
        ));
        $multiplicadorCronica = $possuiDoencasCronicas === 1 ? 1.3 : 1.0;

        return (int) round(
            $pesoSintomas
            * self::MULTIPLICADORES_INTENSIDADE[$intensidadeId]
            * $multiplicadorCronica
            * self::MULTIPLICADORES_PERIODO[$periodoId]
        );
    }
}
