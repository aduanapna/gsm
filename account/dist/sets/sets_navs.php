<?php
// Creamos un array y le vamos añadiendo los diferentes modulos
$menu_principal = array(
    'menu_principal' =>
    array(
        'vista' => 'Deposito',
        'name'  => 'nav_dashboard',
        'icon'  => 'ri-archive-line',
        'links' => array(
            array(
                'name'  => 'Lotes',
                'link'  => 'lotes',
            ),
            array(
                'name'  => 'Lotes Disponibles',
                'link'  => 'lote_disponible',
            ),
            array(
                'name'  => 'Nuevo Lote',
                'link'  => 'lote_alta',
            ),
            array(
                'name'  => 'Editar Lote',
                'link'  => 'lote_edicion',
            ),
        )
    )
);
$menu_administracion = array(
    'menu_administracion' =>
    array(
        'vista' => 'Administracion',
        'name'  => 'nav_administration',
        'icon'  => 'ri-user-4-line',
        'links' => array(
            array(
                'name'  => 'Listado Personal',
                'link'  => 'personal',
            ),
            array(
                'name'  => 'Sucursal',
                'link'  => 'sucursal',
            ),
            array(
                'name'  => 'Fiscal',
                'link'  => 'sucursal_fiscal',
            ),
        )
    )
);
$menu_paginas = array(
    'menu_paginas' =>
    array(
        'vista' => 'Gestion Paginas ',
        'name'  => 'nav_paginas',
        'icon'  => 'ri-window-2-line',
        'links' => array(
            array(
                'name'  => 'Links',
                'link'  => 'links',
            ),
        )
    )
);

define('gestion_navs', array_merge(
    $menu_principal,
    $menu_administracion,
));
