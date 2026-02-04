<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sepomex table name
    |--------------------------------------------------------------------------
    */
    'table_name' => 'sepomex',

    /*
    |--------------------------------------------------------------------------
    | Sepomex source file
    |--------------------------------------------------------------------------
    |
    | You might need to download and move the file to the project storage path.
    | see https://datos.gob.mx/busca/dataset/catalogo-nacional-de-codigos-postales
    |
    */
    'source_file' => storage_path('cpdescarga.txt'),

    /*
    |--------------------------------------------------------------------------
    | Encoding input
    |--------------------------------------------------------------------------
    |
    | Here you may specify the encoding of the source file.
    | The default SEPOMEX file uses "iso-8859-1".
    |
    */
    'encoding_input' => 'iso-8859-1',

    /*
    |--------------------------------------------------------------------------
    | Encoding output
    |--------------------------------------------------------------------------
    |
    | Here you may configure the encoding ouput according to your database.
    |
    */
    'encoding_output' => 'utf-8',
];
