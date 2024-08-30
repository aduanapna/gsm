<?php
# El controlador por defecto, el metodo por defecto y el controlador de errores por defecto
define('DEFAULT_CONTROLLER',        'links');
define('DEFAULT_ERROR_CONTROLLER',  'pages');
define('DEFAULT_ERROR_METHOD',      'e404');
define('DEFAULT_METHOD',            'index');   
define('DEFAULT_HOME',              URL.'gestion');

# Salt del sistema
define('HOOK_TOKEN',                'iD_hook');
define('AUTH_SALT',                 '65M!');
define('KEY',                       AUTH_SALT);
define('AES',                       'AES-128-ECB');

# Cookies Gestion
define('cookie_iD',                 'cookie_gsm');

# Sesiones Gestion
define('var_gestion',               'session_gestion');
define('var_shop',                  'session_shop');
define('var_order',                 'session_order');
define('var_cart',                  'session_cart');
define('var_vip',                   'session_vip');
define('var_links',                 'session_links');
define('var_income',                'session_income');