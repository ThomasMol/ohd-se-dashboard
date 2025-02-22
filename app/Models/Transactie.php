<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactie extends Model
{
    protected $table = 'transactie';
    protected $primaryKey = 'transactie_id';
    protected $guarded = [];

    public function mutatieOptions()
    {
        return [
            'AC' => 'Acceptgiro',
            'BA' => 'Betaalautomaat',
            'DV' => 'Diversen',
            'FL' => 'Filiaalboeking',
            'GF' => 'Telefonisch bankieren',
            'GM' => 'Geldautomaat',
            'GT' => 'Online bankieren',
            'IC' => 'Incasso',
            'ID' => 'iDeal',
            'OV' => 'Overschrijving',
            'PK' => 'Opname kantoor',
            'PO' => 'Periodieke overschrijving',
            'ST' => 'Storting',
            'VZ' => 'Verzamelbetaling',

        ];
    }

    public function afbijOptions()
    {
        return [
            'Af' => 'Af',
            'Bij' => 'Bij'
        ];
    }

    public function spaarplanOptions()
    {
        return [
            1 => 'Wel spaarplan',
            0 => 'Geen spaarplan',
            -1  => 'Niet van toepassing'
        ];
    }
    public function delimiterOptions()
    {
        return [
            "," => 'Komma (,)',
            ";" => 'Puntkomma (;)',
            "\t" => 'Tab (  )',
            "|" => 'Bar (|)',
            ":" => 'Dubbelepunt  (:)',
            " " => 'Spatie ( )',
            "^" => 'Caret (^)'
        ];
    }
}
