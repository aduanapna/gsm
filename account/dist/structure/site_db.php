<?php
# Credenciales de la base de datos

# Set para conexion local o de desarrollo
define('LDB_ENGINE',    'mysql');
define('LDB_HOST',      'localhost');
define('LDB_NAME',      'gsm_db');
define('LDB_USER',      'root');
define('LDB_PASS',      '');
define('LDB_CHARSET',   'utf8');

# Set para conexion local desde otro dispositivo
define('DB_ENGINE',     'mysql');
define('DB_HOST',       'localhost');
define('DB_NAME',       'gsm_db');
define('DB_USER',       'root');
define('DB_PASS',       '');
define('DB_CHARSET',    'utf8');


# Set para conexion en produccion o servidor real
# define('DB_ENGINE',     'mysql');
# define('DB_HOST',       'localhost');
# define('DB_NAME',       'aidelivery_db');
# define('DB_USER',       'aidelivery_db');
# define('DB_PASS',       'FsFtMWJhTW2jVH5d3e6L');
# define('DB_CHARSET',    'utf8');