<?php
# Perfiles de Usuario

# dashboard
# articulos
# aumentos
# stock
# stock_form
# movimientos_stock
# movimientos_formulario
# ordenes
# facturacion
# resumen_facturacion
# caja
# listado_cajas
# caja_fuerte
# clientes
# ingredientes
# orden_local
# orden_plataforma

define('profiles', array(
    'profile_administrator' => array(
        'index'     => '/lote_disponible',
        'navs'      => array(
            'nav_dashboard',
            'nav_administration',
            'nav_paginas',
        ),
        'pages'     => array(
            'lotes',
            'lote_alta',
            'lote_edicion',
            'lote_disponible',
            'personal',
            'links',
        ),
        'methods'   => array()
    ),
));
