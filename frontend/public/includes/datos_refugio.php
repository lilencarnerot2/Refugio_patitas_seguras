<?php

$cuitRefugio = '30-71684920-4';

return [
    'telefono' => '11 2345 6789',
    'email' => 'profesionalizantes29@gmail.com',
    'direccion' => 'Merlo, Buenos Aires, Argentina',
    'lema' => 'Amor que se nota, huellas que quedan. 🐾',
    'sobre_nosotros' => 'Somos un refugio independiente sin fines de lucro. Trabajamos con compromiso y amor para rescatar animales en situación de calle o abandono, brindarles atención veterinaria, alimento y contención, y encontrarles el hogar que merecen.',
    'trabajo' => [
        [
            'titulo' => 'Rescatamos',
            'icono' => 'bi-life-preserver',
            'texto' => 'Rescatamos animales en situación de calle o abandono.'
        ],
        [
            'titulo' => 'Cuidamos',
            'icono' => 'bi-heart-pulse',
            'texto' => 'Les damos atención veterinaria, alimento y contención. Todos nuestros animales están vacunados, desparasitados y evaluados por nuestro veterinario.'
        ],
        [
            'titulo' => 'Campañas conjuntas',
            'icono' => 'bi-scissors',
            'texto' => 'Unimos fuerzas con más organizaciones para llevar a cabo campañas de castración accesibles. Para revenir, el abandono y mejorar la salud de perros y gatos.'
        ],
        [
            'titulo' => 'Buscamos un hogar',
            'icono' => 'bi-house-heart',
            'texto' => 'Les encontramos el hogar que merecen, ya sea por adopción o en un hogar de tránsito.'
        ]
    ],
    'insumos' => [
        [
            'titulo' => 'Alimento e higiene',
            'icono' => 'bi-basket',
            'items' => [
                'Alimento balanceado para perros y gatos (adultos y cachorros)',
                'Alimento húmedo en latas o sobres',
                'Piedras sanitarias para gatos',
                'Mantas, camitas y zaleas en buen estado',
                'Artículos de limpieza: lavandina, desinfectante y detergente',
                'Shampoo con Peroxido de benzoilo',
    'Shampoo con clorexhidina',
    'Shampoo Formula Mac Donald',
            ]
        ],
        [
            'titulo' => 'Medicamentos de veterinaria',
            'icono' => 'bi-heart-pulse',
            'items' => [
              'Antiparasitarios en comprimidos o suspensión (Total Full, Meltra, Endofour, Apto Max, Sulfadim, Giacoccide)',
    'Pipetas antipulgas perros y gatos',
    'Front line spray',
    'Comprimidos antipulgas: comfortis, next gard, bravecto',
    'Antiartrosicos: Artrin, Colotrin, Sosten CG, Gericoox, Old Trans',
    'Antibióticos en comprimido o inyectables (cualquier gramaje): Enrofloxacina, Amoxicilina con acido clavulanico, Clindamicina, Spectryl, Cefalexina, Metronidazol, Doxiciclina, dipenisol, tribiotic',
    'Tramadol 20, 50, 60, 75 u 80mg, en comprimidos',
    'Nogastrol 10mg',
    'Fenobarbital PG',
    'Bro k 300',
    'Contal 60mg',
    'Curabichera',

            ]
        ],
        [
            'titulo' => 'Medicamentos de humanos',
            'icono' => 'bi-capsule',
            'items' => [
                'Cefalexina en comprimidos de 500mg - 1g o suspensión',
    'Rumiclamox 250mg y 500mg',
    'Metronidazol en comprimidos 500 mg o suspensión',
    'Complejo vitaminico del Grupo B (B1, B6, B12) en comprimidos o inyectable',
    'Vitamina B12 en cápsulas o comprimidos',
    'Tramadol en comprimidos o inyectable',
    'Prednisolona 4, 8 y 40 mg en comprimidos',
    'Acemuk comprimidos, jarabe',
    'Doxiciclina comprimidos',
            ]
        ],
        [
            'titulo' => 'Insumos de enfermería',
            'icono' => 'bi-bandaid',
            'items' => [
               'Zaleas',
    'Pañales adultos G, XG y XXG',
    'Sueros de solución fisiológica, solución ringer lactato, dextrosa, manitol',
    'Cateteres 20, 22 y 24 G.',
    'Alcohol, pervinox, clorhexidina, agua oxigenada',
    'Guantes de látex',
    'Vendas tipo cambric, coban',
    'Gasas',
    'Jeringas 1, 3 y 5mL',
    'Tela adhesiva'
            ]
        ]
    ],
    'cuentas' => [
        [
            'medio' => 'Mercado Pago',
            'alias' => 'PATITAS.SEGURAS.MP',
            'icono' => 'bi-wallet2',
            'datos' => [
                'CVU' => '0000003100084739201847',
                'CUIT' => $cuitRefugio,
                'Titular' => 'Asociación Civil Patitas Seguras'
            ]
        ],
        [
            'medio' => 'Banco Nación',
            'alias' => 'patitas.seguras.nacion',
            'icono' => 'bi-bank',
            'datos' => [
                'CBU' => '0110592730059281048201',
                'CUIT' => $cuitRefugio,
                'Titular' => 'Refugio Patitas Seguras'
            ]
        ],
        [
            'medio' => 'Banco Provincia',
            'alias' => 'patitas.refugio.ba',
            'icono' => 'bi-bank2',
            'datos' => [
                'CBU' => '0140999803100051283920',
                'CUIT' => $cuitRefugio,
                'Titular' => 'Refugio Patitas Seguras'
            ]
        ]
    ],
    'montos_mp' => [
        [
            'monto' => 1000,
            'url' => 'https://mpago.la/1GYoJk3'
        ],
        [
            'monto' => 2500,
            'url' => 'https://mpago.la/2sg2VTV'
        ],
        [
            'monto' => 5000,
            'url' => 'https://mpago.la/1N3PdQ7'
        ],
        [
            'monto' => 10000,
            'url' => 'https://mpago.la/1Y3qp8f'
        ]
    ],
    'debito_automatico_url' => 'https://mpago.la/2nwCFC8',
    'enlaces_apadrinamiento' => [
        'una_castracion' => 'https://mpago.la/1hdM8Lo',
        'media_castracion' => 'https://mpago.la/1Q91V7e',
        'dos_castraciones' => 'https://mpago.la/2yBSApw'
    ]
];
