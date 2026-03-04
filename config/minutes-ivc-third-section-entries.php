<?php

use App\Models\MinutesIvcSectionEntry as EntryType;

return [

    /*
    |--------------------------------------------------------------------------
    | Minutes IVC Sections
    |--------------------------------------------------------------------------
    |
    | 
    | 
    | 
    |
    */
    [
        'apply' => true,
        'entry_id' => '3.1',
        'criticality' => 'menor',
        'question' => '¿El área es mayor o igual a veinte (20) metros cuadrados, en un lugar de fácil acceso e independiente de cualquier otro establecimiento comercial o de habitación?',
        'answer' => '',
        'entry_type' => EntryType::FOLDER,
        'links' => [
            [
                'key' => 'etiqueta',
                'value' => '3.1'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '3.2',
        'criticality' => 'menor',
        'question' => '¿El establecimiento está identificado con un aviso en letras visibles con el nombre de este, ubicado en la parte exterio del local o edificio que ocupe, que incluya la mención "droguería" o "farmacia-droguería" según aplique?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '3.3',
        'criticality' => 'menor',
        'question' => '¿Se cuenta con pisos en material impermeable, resistente que permita su fácil limpieza?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '3.4',
        'criticality' => 'menor',
        'question' => '¿Las paredes y muros son impermeables, sólidos, de fácil limpieza y resistentes a factores ambientales como humedad y temperatura?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '3.5',
        'criticality' => 'menor',
        'question' => '¿Los techos y cielo rasos son resistentes, uniformes y de fácil limpieza?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '3.6',
        'criticality' => 'menor',
        'question' => '¿Posee un sistema de iluminación natural y/o artificial que permita la visibilidad de los productos autorizados, así como el desempeño de las actividades?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '3.7',
        'criticality' => 'menor',
        'question' => '¿Tiene un sistema de ventilación natural y/o artificial que garantiza la conservación adecuada de los medicamentos y demás producto autorizados?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '3.8',
        'criticality' => 'Crítico',
        'question' => '¿Se evita la incidencia directa de los rayos solares sobre los medicamentos y dispositivos médicos, y demás productos autorizados?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '3.9',
        'criticality' => 'menor',
        'question' => '¿Las intalaciones elécticas se encuentran en buen estado (tomas, interruptores y cableado protegido)?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
];



