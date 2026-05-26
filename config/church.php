<?php

return [
    'name' => env('CHURCH_NAME', 'Ministère Prophétique de la Foi'),
    'logo' => env('CHURCH_LOGO', 'favicon.svg'),
    'communion_student_fee' => (int) env('COMMUNION_STUDENT_FEE', 500),
    'communion_worker_fee' => (int) env('COMMUNION_WORKER_FEE', 2500),
    'google_maps_api_key' => env('GOOGLE_MAPS_API_KEY'),
    'programs' => [
        'Dimanche : Culte à 8h45',
        'Mercredi : Enseignement biblique',
        'Dernier vendredi : Prière pour les malades',
        'Deuxième dimanche : Sainte Cène',
        'Dernier dimanche : Grenier du Pasteur',
    ],
    'a4_print' => [
        'columns' => 2,
        'rows' => 5,
        'card_width_mm' => 85,
        'card_height_mm' => 54,
        'margin_mm' => 10,
        'gap_mm' => 4,
    ],
];
