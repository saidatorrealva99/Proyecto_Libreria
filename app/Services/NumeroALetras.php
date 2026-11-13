<?php

namespace App\Services;

/**
 * Convierte un monto a letras para el comprobante:
 *   125.50  ->  "CIENTO VEINTICINCO CON 50/100 SOLES"
 */
class NumeroALetras
{
    private const UNIDADES = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
        'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE',
        'VEINTE', 'VEINTIUNO', 'VEINTIDÓS', 'VEINTITRÉS', 'VEINTICUATRO', 'VEINTICINCO', 'VEINTISÉIS',
        'VEINTISIETE', 'VEINTIOCHO', 'VEINTINUEVE'];

    private const DECENAS = ['', '', '', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];

    private const CENTENAS = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
        'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

    public static function soles(float $monto): string
    {
        $monto     = round($monto, 2);
        $enteros   = (int) floor($monto);
        $centimos  = (int) round(($monto - $enteros) * 100);

        $texto = $enteros === 0 ? 'CERO' : self::convertir($enteros);

        return trim($texto) . ' CON ' . str_pad((string) $centimos, 2, '0', STR_PAD_LEFT) . '/100 SOLES';
    }

    private static function convertir(int $n): string
    {
        if ($n >= 1000000) {
            $millones = intdiv($n, 1000000);
            $resto    = $n % 1000000;
            $texto    = $millones === 1 ? 'UN MILLÓN' : self::convertir($millones) . ' MILLONES';
            return $texto . ($resto ? ' ' . self::convertir($resto) : '');
        }

        if ($n >= 1000) {
            $miles = intdiv($n, 1000);
            $resto = $n % 1000;
            $texto = $miles === 1 ? 'MIL' : self::apocopar(self::convertir($miles)) . ' MIL';
            return $texto . ($resto ? ' ' . self::convertir($resto) : '');
        }

        if ($n >= 100) {
            if ($n === 100) {
                return 'CIEN';
            }
            $resto = $n % 100;
            return self::CENTENAS[intdiv($n, 100)] . ($resto ? ' ' . self::convertir($resto) : '');
        }

        if ($n >= 30) {
            $resto = $n % 10;
            return self::DECENAS[intdiv($n, 10)] . ($resto ? ' Y ' . self::UNIDADES[$resto] : '');
        }

        return self::UNIDADES[$n];
    }

    // "VEINTIUNO MIL" -> "VEINTIÚN MIL",  "UNO" -> "UN"
    private static function apocopar(string $texto): string
    {
        return preg_replace(['/VEINTIUNO$/', '/UNO$/'], ['VEINTIÚN', 'UN'], $texto);
    }
}