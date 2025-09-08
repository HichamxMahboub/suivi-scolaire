<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Niveaux Scolaires
    |--------------------------------------------------------------------------
    |
    | Configuration des niveaux scolaires utilisés dans l'application.
    | Vous pouvez modifier les noms d'affichage selon vos besoins.
    |
    */

    'niveaux' => [
        // Primaire
        '1AP' => '1ère année primaire',
        '2AP' => '2ème année primaire',
        '3AP' => '3ème année primaire',
        '4AP' => '4ème année primaire',
        '5AP' => '5ème année primaire',
        '6AP' => '6ème année primaire',

        // Collège (Secondaire collégial)
        '1AC' => '1ère année collège (7ème année)',
        '2AC' => '2ème année collège (8ème année)',
        '3AC' => '3ème année collège (9ème année)',

        // Lycée (Secondaire qualifiant) - Tronc commun
        'TC Sciences' => 'Tronc commun Sciences',
        'TC Lettres' => 'Tronc commun Lettres',
        'TC Techniques' => 'Tronc commun Techniques',
        'TC Économie' => 'Tronc commun Économie',

        // 1ère année Bac
        '1ère Bac Sciences Mathématiques A' => '1ère année Bac Sciences Mathématiques A',
        '1ère Bac Sciences Mathématiques B' => '1ère année Bac Sciences Mathématiques B',
        '1ère Bac Sciences Physiques' => '1ère année Bac Sciences Physiques',
        '1ère Bac Sciences de la Vie et de la Terre' => '1ère année Bac Sciences de la Vie et de la Terre',
        '1ère Bac Sciences Économiques' => '1ère année Bac Sciences Économiques',
        '1ère Bac Techniques de gestion' => '1ère année Bac Techniques de gestion',
        '1ère Bac Lettres modernes' => '1ère année Bac Lettres modernes',
        '1ère Bac Lettres originelles' => '1ère année Bac Lettres originelles',

        // 2ème année Bac
        '2ème Bac Sciences Mathématiques A' => '2ème année Bac Sciences Mathématiques A',
        '2ème Bac Sciences Mathématiques B' => '2ème année Bac Sciences Mathématiques B',
        '2ème Bac Sciences Physiques' => '2ème année Bac Sciences Physiques',
        '2ème Bac SVT' => '2ème année Bac SVT',
        '2ème Bac Sciences Éco' => '2ème année Bac Sciences Économiques',
        '2ème Bac Gestion & Comptabilité' => '2ème année Bac Gestion & Comptabilité',
        '2ème Bac Lettres modernes' => '2ème année Bac Lettres modernes',
        '2ème Bac Lettres originelles' => '2ème année Bac Lettres originelles',
    ],

    /*
    |--------------------------------------------------------------------------
    | Groupes de niveaux
    |--------------------------------------------------------------------------
    |
    | Groupement des niveaux par cycle d'enseignement
    |
    */

    'groupes' => [
        'primaire' => [
            '1AP', '2AP', '3AP', '4AP', '5AP', '6AP'
        ],
        'college' => [
            '1AC', '2AC', '3AC'
        ],
        'lycee_tronc_commun' => [
            'TC Sciences', 'TC Lettres', 'TC Techniques', 'TC Économie'
        ],
        'lycee_1ere_bac' => [
            '1ère Bac Sciences Mathématiques A', '1ère Bac Sciences Mathématiques B', '1ère Bac Sciences Physiques',
            '1ère Bac Sciences de la Vie et de la Terre', '1ère Bac Sciences Économiques', '1ère Bac Techniques de gestion',
            '1ère Bac Lettres modernes', '1ère Bac Lettres originelles'
        ],
        'lycee_2eme_bac' => [
            '2ème Bac Sciences Mathématiques A', '2ème Bac Sciences Mathématiques B', '2ème Bac Sciences Physiques',
            '2ème Bac SVT', '2ème Bac Sciences Éco', '2ème Bac Gestion & Comptabilité',
            '2ème Bac Lettres modernes', '2ème Bac Lettres originelles'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Ordre d'affichage
    |--------------------------------------------------------------------------
    |
    | Ordre dans lequel les niveaux doivent être affichés
    |
    */

    'ordre' => [
        // Primaire
        '1AP', '2AP', '3AP', '4AP', '5AP', '6AP',
        // Collège
        '1AC', '2AC', '3AC',
        // Lycée - Tronc commun
        'TC Sciences', 'TC Lettres', 'TC Techniques', 'TC Économie',
        // 1ère année Bac
        '1ère Bac Sciences Mathématiques A', '1ère Bac Sciences Mathématiques B', '1ère Bac Sciences Physiques',
        '1ère Bac Sciences de la Vie et de la Terre', '1ère Bac Sciences Économiques', '1ère Bac Techniques de gestion',
        '1ère Bac Lettres modernes', '1ère Bac Lettres originelles',
        // 2ème année Bac
        '2ème Bac Sciences Mathématiques A', '2ème Bac Sciences Mathématiques B', '2ème Bac Sciences Physiques',
        '2ème Bac SVT', '2ème Bac Sciences Éco', '2ème Bac Gestion & Comptabilité',
        '2ème Bac Lettres modernes', '2ème Bac Lettres originelles'
    ]
];
