<?php

$cuitRefugio = '30-71684920-4';

return [
    'telefono' => '11 2345 6789',
    'email' => 'profesionalizantes29@gmail.com',
    'direccion' => 'Merlo, Buenos Aires, Argentina',
    'lema' => 'Amor que se nota, huellas que quedan. 🐾',
    'alias_principal' => 'PATITAS.SEGURAS.MP',
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
            'titulo' => 'Castramos',
            'icono' => 'bi-scissors',
            'texto' => 'Organizamos y difundimos campañas de castración para prevenir el abandono y mejorar la salud de perros y gatos.'
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
                'Artículos de limpieza: lavandina, desinfectante y detergente'
            ]
        ],
        [
            'titulo' => 'Medicamentos de veterinaria',
            'icono' => 'bi-heart-pulse',
            'items' => [
                'Antiparasitarios en comprimidos o suspensión',
                'Pipetas antipulgas para perros y gatos',
                'Antibióticos en comprimidos o inyectables',
                'Antiartrósicos',
                'Colirios y gotas óticas',
                'Shampoo con clorhexidina o con peróxido de benzoílo',
                'Curabichera'
            ]
        ],
        [
            'titulo' => 'Medicamentos de humanos',
            'icono' => 'bi-capsule',
            'items' => [
                'Antibióticos de uso común (cefalexina, metronidazol, doxiciclina)',
                'Complejo vitamínico del grupo B, en comprimidos o inyectable',
                'Analgésicos y antiinflamatorios'
            ]
        ],
        [
            'titulo' => 'Insumos de enfermería',
            'icono' => 'bi-bandaid',
            'items' => [
                'Gasas, vendas y tela adhesiva',
                'Jeringas de 1, 3 y 5 ml',
                'Guantes de látex',
                'Alcohol, clorhexidina y agua oxigenada',
                'Sueros: solución fisiológica, ringer lactato y dextrosa',
                'Catéteres de 20, 22 y 24 G',
                'Pañales para adultos (G, XG y XXG)'
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
            'url' => 'https://mpago.la/patitas-seguras-demo-1000'
        ],
        [
            'monto' => 2500,
            'url' => 'https://mpago.la/patitas-seguras-demo-2500'
        ],
        [
            'monto' => 5000,
            'url' => 'https://mpago.la/patitas-seguras-demo-5000'
        ],
        [
            'monto' => 10000,
            'url' => 'https://mpago.la/patitas-seguras-demo-10000'
        ]
    ]
];
