<?php

# private 		= no puede ser utilizada en ningun otro lado mas que dentro de la clase dueña
# protected 	= puede ser utilizada por la clase dueña e hijas, pero no por fuera
# public		= puede ser utilizada por fuera de la clase, dentro de la clase dueña e hijas

# Version de los Plugins
# Vue           : 2.7.14
# Axios         : 1.5.0
# Sweetalert2   : 11.4.17
# Toastify-js   : 1.12.0
# iziToast      : 1.4.0
# Afip SDK      : 0.5.1


# Clase para inicializar toda la WEB. v 2.2.0 03/04/2024
class iD
{
    #  Propiedades del Framework

    private $framework = 'FlexiMVC Framework';
    private $version = '2.2.0';
    private $uri = [];

    #  La funcion principal  que se ejecuta al instanciar nuetra clase
    function __construct()
    {
        $this->init();
    }

    /**
     * Metodo para ejecutar cada "metodo" de forma subsecuente
     * 
     * @return void
     */

    private function init()
    {
        #  Todos los metodos que queremos ejecutar consecutivamente
        $this->init_load_configs();
        $this->init_load_structure();
        $this->init_load_sets();
        $this->init_session();

        $this->init_load_function();
        $this->init_load_plugins();
        $this->init_autoload();
        $this->init_csrf();

        #  Mostrar errores si el sistema esta en desarrollo
        if (site_production === true) {
            ini_set('display_errors', 'Off');
            error_reporting(E_ALL & ~E_WARNING);
        }

        $this->dispatch();

        return;
    }

    /**
     * Metodo para iniciar la sesion en el sistema
     * @return void
     */

    private function init_session()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_name(cookie_iD);
            session_start();
        }
    }

    /**
     * Metodo para cargar la configuracion del sistema
     * 
     * @return void
     */

    private function init_load_configs()
    {
        # CONFIGURACION DEL FRAMEWORK
        $file = 'app/config/iD_settings.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }
        require_once $file;

        $file = 'app/config/iD_routers.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }
        require_once $file;
    }

    /**
     * Metodo para cargar las funciones del sistema
     * 
     * @return void
     */
    private function init_load_function()
    {
        $file = FUNCTIONS . 'iD_core_functions.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }

        #  Cargando archivo de funciones core
        require_once $file;

        return;
    }

    /**
     * Metodo para cargar los archivos que estructuran la app.-
     * 
     * @return void
     */
    private function init_load_structure()
    {
        # Abrimos la carpeta que nos pasan como parámetro
        # $path = './' . $path . '/';
        $path = FOLDER_STRUCTURE;
        $dir = opendir($path);

        #  Leo todos los ficheros de la carpeta
        while ($elemento = readdir($dir)) {
            #  Tratamos los elementos . y .. que tienen todas las carpetas
            if ($elemento != "." && $elemento != "..") {
                #  Si es una carpeta
                if (!is_dir($path . $elemento)) {
                    $file = $path . $elemento;
                    if (!is_file($file)) {
                        die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
                    }

                    #  Cargando archivo de funciones core
                    require_once $file;
                }
            }
        }
    }


    /**
     * Metodo para cargar los sets del sistema y del usuario
     * 
     * @return void
     */
    private function init_load_sets()
    {
        # Abrimos la carpeta que nos pasan como parámetro
        # $path = './' . $path . '/';
        $path = FOLDER_SETS;
        $dir = opendir($path);

        #  Leo todos los ficheros de la carpeta
        while ($elemento = readdir($dir)) {
            #  Tratamos los elementos . y .. que tienen todas las carpetas
            if ($elemento != "." && $elemento != "..") {
                #  Si es una carpeta
                if (!is_dir($path . $elemento)) {
                    $file = $path . $elemento;
                    if (!is_file($file)) {
                        die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
                    }

                    #  Cargando archivo de funciones core
                    require_once $file;
                }
            }
        }
    }

    /**
     * Metodo para cargar las Plugins
     * 
     * @return void
     */
    private function init_load_plugins()
    {
        /* Plugin de AFIP */
        $file = PLUGINS_PHP . 'afip_sdk/Afip.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }
        require_once $file;

        /* Plugin de FPDF */
        $file = PLUGINS_PHP . 'fpdf/fpdf.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }
        require_once $file;

        /* Plugin de QR */
        $file = PLUGINS_PHP . 'qr/qrlib.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }
        require_once $file;

        /* Plugin de Mobile_Detect */
        $file = PLUGINS_PHP . 'mobile_detect/Mobile_Detect.php';
        if (!is_file($file)) {
            die(sprintf('El archivo %s no se encuentra, es requerido para que %s funcione', $file, $this->framework));
        }
        require_once $file;

        return;
    }

    /**
     * Metodo para cargar los archivos de forma automatica
     * 
     * @return void
     */
    private function init_autoload()
    {
        require_once CLASSES . 'Autoloader.php';
        Autoloader::init();

        return;
    }

    /**
     * Método para crear un nuevo token de la sesión del usuario
     *
     * @return void
     */
    private function init_csrf()
    {
        $csrf = new Csrf();
        define('CSRF_TOKEN', $csrf->get_token()); # Versión 1.0.2 para uso en aplicaciones
    }

    /**
     * Metodo para filtrar y descomponer los elementos de nuestra url y uri
     * 
     * @return void
     */
    private function filter_url()
    {
        if (isset($_GET['uri'])) {
            $this->uri = $_GET['uri'];
            $this->uri = rtrim($this->uri, '/');
            $this->uri = filter_var($this->uri, FILTER_SANITIZE_SPECIAL_CHARS);
            $this->uri = explode('/', $this->uri);
        }
    }

    /**
     * Metodo para ejecutar y cargar de forma automatica el controlador solicitado por el usuario 
     * su metodo y pasar parametros a el.
     * @return void
     */

    private function dispatch()
    {
        # Filtrar la URL y separar el URI
        $this->filter_url();

        #  Necesitamos saber si se esta pasando el nombre de un controlador en nuestro URI
        #  $this->uri[0] es el controlador en cuestion
        if (isset($this->uri[0])) {
            $current_controller = strtolower($this->uri[0]);
            unset($this->uri[0]);
        } else {
            $current_controller = DEFAULT_CONTROLLER;
        }

        #  Ejecucion del controlador
        #  Verificamos si existe una clase con el controlador solicitado, si no existe buscamos en el controlador URLS
        $controller = $current_controller . 'Controller';
        if (!class_exists($controller)) {
            # Si en el controlador no existe. Busca en el controllador de URLS
            check_url($current_controller);
        }
        #  Ejecucion del metodo solicitado
        if (isset($this->uri[1])) {
            $method = str_replace('-', '_', strtolower($this->uri[1]));
            #  Existe o no el metodo dentro de la clase a ejecutar (controlador)
            if (!method_exists($controller, $method)) {
                $controller         = DEFAULT_ERROR_CONTROLLER . 'Controller';
                $current_method     = DEFAULT_METHOD;
                $current_controller = DEFAULT_ERROR_CONTROLLER;
            } else {
                # Comprobar si el metodo es publico
                $reflection_method  = new ReflectionMethod($controller, $method);
                if (!$reflection_method->isPublic()) {
                    debug("Error: El método $method no es accesible en el controlador $controller.", true);
                } else {
                    $current_method = $method;
                }
            }

            unset($this->uri[1]);
        } else {
            $current_method = DEFAULT_METHOD;
        }

        define('CONTROLLER', $current_controller);
        define('METHOD', $current_method);

        #  Ejecutando controlador y metodo segun se haga la peticion
        $controller = new $controller;

        #  Obteniendo los parametros de la URI
        $params = array_values(empty($this->uri) ? [] : $this->uri);

        #  Llamada al metodo que solicita el usuario en curso
        if (empty($params)) {
            call_user_func([$controller, $current_method]);
        } else {
            call_user_func_array([$controller, $current_method], $params);
        }
    }

    public static function imagine()
    {
        $iD = new self();
        return;
    }
}
