<?php
# Controlador de modulo GESTION

class gestionController extends Controller
{
    public $var         = var_gestion;
    public $controller  = 'gestion';
    public $THEME       = STYLES . 'default/';

    function __construct()
    {
        check_status();

        # Ejecuta constructor padre
        parent::__construct();

        if (!management_enabled) {
            # Revisar si el modulo esta habilitado
            Redirect::to('pages/e404');
        }
        if (management_maintenance) {
            # Revisar si el modulo esta en mantenimiento
            Redirect::to('pages/maintenance');
        }
        # Configuracion inicial
        $this->site['title']                = title_account;
        $this->site['name']                 = title_account;
        $this->site['author']               = title_account;
        $this->site['description']          = 'Sistema Gestion de Mercaderia';
        $this->site['logo']                 = management_logo;
        $this->site['about']                = 'https://imaginedesign.ar';
        $this->site['favicon']              = management_logo;
        # Modulo dependiente de enlaces 
        $this->site['controller']           = $this->controller;
        # Enlaces
        $this->site['home']                 = URL . $this->controller . '/index';
        $this->site['reset']                = URL . $this->controller . '/reset';
        $this->site['lock']                 = URL . $this->controller . '/lock';
        $this->site['locked']               = URL . $this->controller . '/locked';
        $this->site['logout']               = URL . $this->controller . '/logout';
        $this->site['unlock']               = URL . $this->controller . '/unlock';
        $this->site['login']                = URL . $this->controller . '/login';
        $this->site['html']                 = $this->create_html();
        $this->site['body_class']           = '';
        $this->site['body_style']           = '';

        $this->site['head'][]               = $this->THEME . 'css/bootstrap.min.css';
        $this->site['head'][]               = $this->THEME . 'css/app.min.css';
        $this->site['head'][]               = $this->THEME . 'css/icons.min.css';
        $this->site['head'][]               = $this->THEME . 'custom/fonts.css';
        $this->site['head'][]               = $this->THEME . 'custom/spinner.css';
        $this->site['head'][]               = $this->THEME . 'custom/tabler.css';

        $this->site['head'][]               = LIBS . 'sweetalert2/sweetalert2.min.js';
        $this->site['head'][]               = LIBS . 'sweetalert2/sweetalert2-toast.js';
        $this->site['head'][]               = LIBS . 'sweetalert2/sweetalert2.css';
        $this->site['head'][]               = LIBS . 'toastify-js/toastify.js';
        $this->site['head'][]               = LIBS . 'vue/vue.js';
        $this->site['head'][]               = LIBS . 'axios/axios.min.js';
        $this->site['head'][]               = LIBS . 'fleximvc/app.js';
        $this->site['footer'][]             = LIBS . 'bootstrap/js/bootstrap.bundle.min.js';
        $this->site['footer'][]             = LIBS . 'node-waves/waves.min.js';
        $this->site['footer'][]             = LIBS . 'feather-icons/feather.min.js';
        $this->site['footer'][]             = LIBS . 'simplebar/simplebar.min.js';
        $this->site['footer'][]             = $this->THEME . 'js/layout.js';
        $this->site['footer'][]             = $this->THEME . 'js/app.js';
    }
    # ===== Globales

    /** Funcion para bloquear sesion de usuario. */
    function lock()
    {
        $_SESSION[$this->var]['user']['block']  = true;
        Redirect::to($this->site['locked']);
    }
    /** Funcion para desbloquear sesion de usuario. */
    function unlock()
    {
        $us     = get_user($this->var, $this->site['login']);
        $pass   = get_form($_POST, 'userpassword');

        try {
            if (password_verify($pass . AUTH_SALT, $us->pass)) {
                $_SESSION[$this->var]['user']['block']   = false;
                Redirect::to($this->site['home']);
            } else {
                logger('Ingreso de contraseña erroneo en login de usuario. Modulo Gestion. IP: ' . get_user_ip(), 'error');
                Flasher::new('La contraseña no es valida.', 'danger');
                Redirect::to($this->site['locked']);
            }
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    /** Funcion para cambiar pass de usuario. */
    function change_pass()
    {
        # Primero comprobamos que los valores no vengan nulos
        $us             = get_user($this->var, $this->site['login']);

        $pass_old       = get_form($_POST, 'pass_old');
        $pass_new       = get_form($_POST, 'pass_new');
        $pass_renew     = get_form($_POST, 'pass_renew');

        if ($pass_old == '' || $pass_new == '' || $pass_renew == '') {
            Flasher::new('Las contraseñas no pueden ser nulas');
            Redirect::to($this->site['reset']);
        }
        # Comprobar si la clave nueva es identica a la repeticion
        if ($pass_new != $pass_renew) {
            Flasher::new('Las contraseñas ingresadas no coinciden');
            Redirect::to($this->site['reset']);
        }
        # Comprobar si la clave ingresada sea valida
        if (check_pass($pass_old, $us->pass)) {
            try {
                $person                 = new personsModel();
                $person->person_pass    = hash_pass($pass_new);
                $person->person_id      = $us->person_id;

                $person->pass_update();

                Flasher::new('La contraseña a sido actualizada.');
                Auth::logout($this->var);
                Redirect::to($this->site['login']);
            } catch (Exception $e) {
                json_response(404, null, $e->getMessage());
            }
        } else {
            Flasher::new('Las contraseña ingresada no es correcta');
            Redirect::to($this->site['reset']);
        }
    }
    /** Link para cerrar sesion. */
    function logout()
    {
        if (Auth::logout($this->var)) {
            Redirect::to($this->site['login']);
        }
    }
    /** Funcion para bloquear sesion de usuario. */
    function locked()
    {
        $us  = get_user($this->var, $this->site['login']);

        if ($us->block == true) {
            $this->site['title']    = 'Gestion Bloqueda';

            $this->site['ref']      = 'gestion';
            $this->site['user']     = $us;
            $this->site['action']   = $this->site['unlock'];
            $this->del_appjs();

            View2::render('pages', 'locked', $this->site, 'lock_screen');
        }
    }
    function access()
    {
        try {
            $var                        = var_gestion;
            $usuario                    = get_form($_POST, 'user', ['trim', 'strtolower']);
            $password                   = get_form($_POST, 'pass');
            $store_id                   = get_form($_POST, 'store', ['strtolower']);
            $profile                    = 'profile_administrator'; # get_form($_POST, 'profile', ['notnull']);

            $person                     = new personsModel();
            $person->person_id          = $usuario;
            $data_person                = $person->one();

            if ($data_person['person_condition'] == 0) {
                log_error('Persona no admitida para el uso del sistema', 'gestion');
                die();
            }
            if ($data_person['person_condition'] == 2) {
                log_error('Persona inhabilitada para uso del sistema', 'gestion');
                die();
            }
            if (!password_verify($password . AUTH_SALT, $data_person['person_pass'])) {
                log_error('Error en las credenciales', 'gestion');
                die();
            }

            $profiles                       = new persons_accessModel();
            $profiles->profile_person_id    = $data_person['person_id'];
            $profiles->profile_store_id     = $store_id;
            $profiles->profile_column       = $profile;

            # Comprobamos que el profile sea valido
            $data_profiles                  = $profiles->check_rol();

            if ($data_profiles === []) {
                log_error('Perfil no valido', 'gestion');
                die();
            }
            $data_profiles                 = $profiles->check_person();
            # Comprobamos que la persona esta habilitada para operar en sucursal
            if ($data_profiles[$profile] == 0) {
                log_error('Perfil no habilitado en sucursal seleccionada', 'gestion');
                die();
            }

            if ($data_profiles[$profile] == 2) {
                log_error('Usted se encuentra inhabilitado para usar el sistema', 'gestion');
                die();
            }
            # Cargamos los datos de la sucursal
            $store                          = new storesModel();
            $store->store_id                = $store_id;
            $store                          = $store->one();

            $person->person_id              = $data_person['person_id'];
            $person->lastlogin();

            $user_data['person_id']         = $data_person['person_id'];
            $user_data['person_name']       = ucwords($data_person['person_lastname'] . ' ' . $data_person['person_name']);
            $user_data['person_picture']    = IMAGES . $data_person['person_picture'];
            $user_data['profile']           = $profile;
            $user_data['profiles_name']     = ucwords($data_profiles['person_profile_text']);
            $user_data['user']              = $data_person['person_document'];
            $user_data['pass']              = $data_person['person_pass'];
            $user_data['store_id']          = $store_id;
            $user_data['store_name']        = ucwords($store['store_name']);
            $user_data['store_cash']        = $store['store_cash'];
            $user_data['printer_id']        = 0;
            $user_data['block']             = false;

            $_SESSION['defaults']['category']           = 1;
            $_SESSION['defaults']['category_supply']    = 0;
            # Loggear al usuario
            Auth::login($var, $data_person['person_id'], $user_data);
            Redirect::to($this->site['home']);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }

    # =============== Inicio vistas html ======================
    function reset()
    {
        $us                     = get_user($this->var, $this->site['login']);

        $this->site['title']    = 'Cambiar Contraseña';
        $this->site['action']   = URL . 'gestion/change_pass';
        $this->del_appjs();

        View2::render('gestion', 'reset', $this->site, 'reset_pass');
    }
    function about()
    {
        Redirect::to($this->site['about']);
    }
    function index()
    {
        $this->page_access();
        debug('Detente! No me gusta juga asi', true);
        $this->compare_pass();
        $this->locked();
    }
    function lotes()
    {
        $user                           = get_user($this->var, URL . 'gestion/login');
        $this->locked();
        $this->compare_pass();
        $this->page_access();
        $this->site['user']             = $user;

        $this->site['title']            = 'Deposito';
        $this->site['ref']              = 'lotes';

        $this->site['footer'][] = get_vue('deposito', 'vue_lotes', 'lotes');

        View2::render('deposito', 'lotes', $this->site, 'lotes');
    }
    function lote_disponible()
    {
        $user                           = get_user($this->var, URL . 'gestion/login');
        $this->locked();
        $this->compare_pass();
        $this->page_access();
        $this->site['user']             = $user;

        $this->site['title']            = 'Deposito';
        $this->site['ref']              = 'lote_disponible';

        $this->site['footer'][] = get_vue('deposito', 'vue_lotes', 'lote_disponible');

        View2::render('deposito', 'lotes', $this->site, 'lote_disponible');
    }
    function lote_alta()
    {
        $user                   = get_user($this->var, URL . 'gestion/login');
        $this->locked();
        $this->compare_pass();
        $this->page_access();
        $this->site['user']     = $user;

        $this->site['title']    = 'Nuevo Lote';
        $this->site['ref']      = 'lote_alta';
        $this->site['footer'][] = get_vue('deposito', 'vue_lote', 'lote_alta');

        View2::render('deposito', 'lote', $this->site, 'lote_alta');
    }
    function lote_edicion()
    {
        $user                   = get_user($this->var, URL . 'gestion/login');
        $this->locked();
        $this->compare_pass();
        $this->page_access();
        $this->site['user']     = $user;

        $this->site['title']    = 'Editar Lote';
        $this->site['ref']      = 'lote_edicion';
        $this->site['footer'][] = get_vue('deposito', 'vue_lote', 'lote_edicion');

        View2::render('deposito', 'lote', $this->site, 'lote_edicion');
    }
    function personal() # Version Nueva
    {
        $user                   = get_user($this->var, URL . 'gestion/login');
        $this->locked();
        $this->compare_pass();
        $this->page_access();

        $this->site['user']     = $user;
        $this->site['title']    = 'Pagina Personal';
        $this->site['ref']      = 'personal';

        array_push($this->site['footer'], get_vue('staff', 'vue_personal', 'personal'));

        View2::render('staff', 'personal', $this->site, 'personal');
    }
    function links() # Version Nueva
    {
        $user                   = get_user($this->var, URL . 'gestion/login');
        $this->locked();
        $this->compare_pass();
        $this->page_access();
        $this->site['user']     = $user;

        $this->site['title']    = 'Pagina Links';
        $this->site['ref']      = 'links';

        array_push($this->site['footer'], get_vue('links', 'vue_links', 'edit'));

        View2::render('links', 'links', $this->site, 'edit');
    }
    function sucursal() # Version Nueva
    {
        $user                   = get_user($this->var, URL . 'gestion/login');
        $this->locked();
        $this->compare_pass();
        $this->page_access();

        $this->site['user']     = $user;
        $this->site['title']    = 'Pagina Sucursal';
        $this->site['ref']      = 'sucursal';

        array_push($this->site['footer'], get_vue('stores', 'vue_store', 'sucursal'));

        View2::render('stores', 'store', $this->site, 'sucursal');
    }

    function login($profile = null)
    {
        # Si el usuario ya esta iniciado lo envia a su index
        if (Auth::validate($this->var)) {
            Redirect::to($this->site['home']);
        }
        # Configuracion Personalizada
        $this->site['title']            = 'Iniciar Sesion';
        $this->site['ref']              = 'login';
        $this->site['logo_local']       = IMAGES . '_local.png';
        $this->site['stores']           = $this->store();
        $this->site['profiles']         = $this->profiles();
        $this->site['insert_inputs']    = insert_inputs();
        $this->site['action']           = URL . 'gestion/access';
        $this->del_appjs();

        array_push($this->site['footer'], $this->THEME . '/js/pages/password-addon.init.js');
        View2::render('gestion', 'login', $this->site, 'login');
    }
    function administrador($profile = null)
    {
        # Si el usuario ya esta iniciado lo envia a su index
        if (Auth::validate($this->var)) {
            Redirect::to($this->site['home']);
        }

        # Configuracion Personalizada
        $this->site['title']            = 'Iniciar Sesion';
        $this->site['ref']              = 'login';
        $this->site['stores']           = $this->store();
        $this->site['insert_inputs']    = insert_inputs();
        $this->site['action']           = URL . 'gestion/access';
        $this->site['profiles']         = '<option value="profile_administrator">Administrador</option>';
        $this->del_appjs();

        array_push($this->site['footer'], $this->THEME . '/js/pages/password-addon.init.js');
        View2::render('gestion', 'login', $this->site, 'login');
    }
    # =============== fin vistas html ======================

    # Modo Desarrollo
    public function variables()
    {
        $user = get_user($this->var, URL . 'gestion/login');
        debug($user, true);
    }

    # Privates
    private function del_appjs()
    # Borramos el archivo apps.js porque el nav-bar no esta disponible
    {
        $app = array_search($this->THEME . 'js/app.js', $this->site['footer']);
        unset($this->site['footer'][$app]);
    }
    private function compare_pass()
    {
        # Funcion para verificar que el usuario cambie la contraseña por defecto (DNI)
        $us        = get_user($this->var, $this->site['login']);
        try {
            $pass_us = $us->user;
            $pass_db = $us->pass;

            if (password_verify($pass_us . AUTH_SALT, $pass_db)) {
                Flasher::new('Debe crear una nueva contraseña para utilizar el sistema', 'danger');
                $this->reset();
            }
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    private function page_access()
    {
        # Revisa que el usuario tenga acceso al methodo solicitado
        $us     = get_user($this->var, $this->site['login']);
        $pages  = get_pages($us->profile);

        if (METHOD === 'index') {
            Redirect::to($this->controller . $pages['index']);
        }

        if (!in_array(METHOD, $pages['pages'])) {
            Flasher::new('No posee acceso al recurso solicitado ' . METHOD, 'danger');
            Redirect::to($this->controller . $pages['index']);
        }
    }
    private function create_html()
    # Solo cuando incluye app.js
    {
        $html_data['lang']                  = 'es';
        $html_data['data-layout']           = 'vertical';
        $html_data['data-topbar']           = 'dark';
        $html_data['data-sidebar']          = 'dark';
        $html_data['data-sidebar-size']     = 'sm';
        $html_data['data-sidebar-image']    = 'none';
        $html_data['data-preloader']        = 'disable';
        $html_data['data-layout-mode']      = 'light';
        $html_data['data-layout-width']     = 'fluid';
        $html_data['data-layout-position']  = 'fixed';
        $html_data['data-layout-style']     = 'default';

        $html                               = '';
        foreach ($html_data as $ix => $vl) {
            $html .= $ix . '="' . $vl . '" ';
        }
        return '<html ' . $html . '>';
    }
    private function profiles()
    {
        $profiles           = new persons_accessModel();
        $profiles           = $profiles->not_all();
        $option_profiles    = '';
        foreach ($profiles as $profile) {
            $option_profiles .= sprintf('<option value="%s">%s</option>', $profile['person_profile_id'], $profile['person_profile_text']);
        }
        return $option_profiles;
    }
    private function store()
    {
        $stores         = public_stores();
        $option_store   = '';
        foreach ($stores as $store) {
            $option_store .= sprintf('<option value="%s">%s</option>', $store['store_id'], $store['store_name']);
        }
        return $option_store;
    }


    #================ UNICAMENTE PARA DESARROLLO =====================
    private function float($number)
    {
        if (strpos($number, ',') !== false) {
            $number = str_replace(".", "", $number);
            $number = str_replace(",", ".", $number);
        }

        $number = abs(floatval($number));
        return $number;
    }
    private function dates($date)
    {
        if (strlen($date) >= 6) {
            $datex = explode("/", $date);
            $day   = $datex[0];
            $month = $datex[1];
            $year  = $datex[2];

            // Valida si la fecha es válida con checkdate()
            return checkdate($month, $day, $year) ? "$year-$month-$day" : null;
        }
        return null;
    }

    public function importar_gsm()
    {
        check_csrf();
        # Ruta al archivo CSV
        $csv_file       = FILES . 'history.csv';
        $array          = [];
        $gsm_actual     = '';
        $lote_actual    = 0;
        $linea          = 0;
        # Creamos el array a partir del CSV
        if (($handle = fopen($csv_file, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                # Primero cargamos la linea completa
                $nro_gsm                = $data[1];
                $nro_denuncia           = $data[2];
                $nro_alot               = $data[3];
                $nro_sigea              = $data[4];
                $fecha_ingreso          = $data[5];
                $deposito               = $data[6];
                $judicializado          = $data[7];
                $operacion              = $data[8];
                $organismo_secuestro    = $data[9];
                $observaciones          = $data[10];
                $aforo                  = $data[11];
                $aforo_fecha            = $data[12];
                $ncm                    = $data[13];
                $derecho_impexp         = $data[14];
                $tasa_estadistica       = $data[15];
                $impuesto_interno       = $data[16];
                $rubro                  = $data[17];
                $descripcion            = $data[18];
                $u_medida               = $data[19];
                $estado_ingreso         = $data[20];
                $cantidad               = $data[21];
                $m3_total               = $data[22];
                $unitario_usd           = $data[23];
                $momento_imponible      = $data[24];
                $cotizacion_dolar       = $data[25];
                $valor_aduana           = $data[26];
                $pesos_derechos         = $data[27];
                $pesos_estadistica      = $data[28];
                $arancel_despachante    = $data[29];
                $iva_general            = $data[30];
                $iva_adicional          = $data[31];
                $anticipo_ganancias     = $data[32];
                $pesos_iva              = $data[33];
                $pesos_adicional        = $data[34];
                $pesos_ganancias        = $data[35];
                $pesos_impuestos        = $data[36];
                $valor_plaza            = $data[37];
                $resolucion_fecha       = $data[38];
                $resolucion_nro         = $data[39];
                $disposicion_nro        = $data[40];
                $disposicion_tipo       = $data[41];
                $disposicion_fecha      = $data[42];
                $acta_fecha             = $data[43];

                $lotes = new lotesModel;
                # Luego le comprobamos si es un lote ya cargado o uno nuevo
                if ($gsm_actual != $nro_gsm) {
                    # Si no hay registro del gsm. Lo creamos y lo asignamos como actual
                    $gsm_actual = $nro_gsm;

                    $lotes->fecha_ingreso           = $this->dates($fecha_ingreso);
                    $lotes->nro_gsm                 = mb_strtoupper($nro_gsm);
                    $lotes->nro_denuncia            = mb_strtolower($nro_denuncia);
                    $lotes->nro_alot                = mb_strtolower($nro_alot);
                    $lotes->nro_sigea               = mb_strtolower($nro_sigea);
                    $lotes->deposito                = mb_strtolower($deposito);
                    $lotes->judicializado           = ($judicializado != 'si') ? 'no' : 'si';
                    $lotes->operacion               = mb_strtolower($operacion);
                    $lotes->organismo_secuestro     = mb_strtolower($organismo_secuestro);
                    $lotes->lote_observation        = mb_strtolower($observaciones);
                    $lotes->resolucion_fecha        = mb_strtolower($resolucion_fecha);
                    $lotes->resolucion_nro          = mb_strtolower($resolucion_nro);
                    $lotes->lote_condition          = 1;
                    $lotes->lote_store              = 1;

                    $lote_actual                    = $lotes->lote_add();

                    $lotes->lote_item_bound         = $lote_actual;
                    $lotes->picture                 = '_nodisponible.jpg';
                    $lotes->aforo                   = ($aforo != 'si') ? 'no' : 'si';
                    $lotes->aforo_fecha             = $this->dates($aforo_fecha);
                    $lotes->ncm                     = $ncm;
                    $lotes->rubro                   = mb_strtolower($rubro);
                    $lotes->descripcion             = mb_strtolower($descripcion);
                    $lotes->u_medida                = mb_strtolower($u_medida);
                    $lotes->estado_ingreso          = ($estado_ingreso != 'si') ? 'usado' : 'nuevo';
                    $lotes->cantidad                = $this->float($cantidad);
                    $lotes->m3_total                = $this->float($m3_total);
                    $lotes->unitario_usd            = $this->float($unitario_usd);
                    $lotes->momento_imponible       = $this->dates($momento_imponible);
                    $lotes->derecho_impexp          = $this->float($derecho_impexp);
                    $lotes->derecho_impexp          = $this->float($derecho_impexp);
                    $lotes->tasa_estadistica        = $this->float($tasa_estadistica);
                    $lotes->impuesto_interno        = $this->float($impuesto_interno);
                    $lotes->anticipo_ganancias      = $this->float($anticipo_ganancias);
                    $lotes->cotizacion_dolar        = $this->float($cotizacion_dolar);
                    $lotes->valor_aduana            = $this->float($valor_aduana);
                    $lotes->pesos_derechos          = $this->float($pesos_derechos);
                    $lotes->pesos_estadistica       = $this->float($pesos_estadistica);
                    $lotes->arancel_despachante     = $this->float($arancel_despachante);
                    $lotes->iva_general             = $this->float($iva_general);
                    $lotes->iva_adicional           = $this->float($iva_adicional);
                    $lotes->pesos_iva               = $this->float($pesos_iva);
                    $lotes->pesos_adicional         = $this->float($pesos_adicional);
                    $lotes->pesos_ganancias         = $this->float($pesos_ganancias);
                    $lotes->pesos_impuestos         = $this->float($pesos_impuestos);
                    $lotes->valor_plaza             = $this->float($valor_plaza);
                    $lotes->disposicion_tipo        = mb_strtolower($disposicion_tipo);
                    $lotes->disposicion_nro         = mb_strtolower($disposicion_nro);
                    $lotes->disposicion_fecha       = $this->dates($disposicion_fecha);
                    $lotes->acta_fecha              = $this->dates($acta_fecha);
                    $lotes->informado               = 'no';
                    $lotes->gestionado              = 'no';
                    $lotes->item_add();
                } else {
                    $lotes->lote_item_bound         = $lote_actual;
                    $lotes->picture                 = '_nodisponible.jpg';
                    $lotes->aforo                   = ($aforo != 'si') ? 'no' : 'si';
                    $lotes->aforo_fecha             = $this->dates($aforo_fecha);
                    $lotes->ncm                     = $ncm;
                    $lotes->rubro                   = mb_strtolower($rubro);
                    $lotes->descripcion             = mb_strtolower($descripcion);
                    $lotes->u_medida                = mb_strtolower($u_medida);
                    $lotes->estado_ingreso          = ($estado_ingreso != 'si') ? 'usado' : 'nuevo';
                    $lotes->cantidad                = $this->float($cantidad);
                    $lotes->m3_total                = $this->float($m3_total);
                    $lotes->unitario_usd            = $this->float($unitario_usd);
                    $lotes->momento_imponible       = $this->dates($momento_imponible);
                    $lotes->derecho_impexp          = $this->float($derecho_impexp);
                    $lotes->derecho_impexp          = $this->float($derecho_impexp);
                    $lotes->tasa_estadistica        = $this->float($tasa_estadistica);
                    $lotes->impuesto_interno        = $this->float($impuesto_interno);
                    $lotes->anticipo_ganancias      = $this->float($anticipo_ganancias);
                    $lotes->cotizacion_dolar        = $this->float($cotizacion_dolar);
                    $lotes->valor_aduana            = $this->float($valor_aduana);
                    $lotes->pesos_derechos          = $this->float($pesos_derechos);
                    $lotes->pesos_estadistica       = $this->float($pesos_estadistica);
                    $lotes->arancel_despachante     = $this->float($arancel_despachante);
                    $lotes->iva_general             = $this->float($iva_general);
                    $lotes->iva_adicional           = $this->float($iva_adicional);
                    $lotes->pesos_iva               = $this->float($pesos_iva);
                    $lotes->pesos_adicional         = $this->float($pesos_adicional);
                    $lotes->pesos_ganancias         = $this->float($pesos_ganancias);
                    $lotes->pesos_impuestos         = $this->float($pesos_impuestos);
                    $lotes->valor_plaza             = $this->float($valor_plaza);
                    $lotes->disposicion_tipo        = mb_strtolower($disposicion_tipo);
                    $lotes->disposicion_nro         = mb_strtolower($disposicion_nro);
                    $lotes->disposicion_fecha       = $this->dates($disposicion_fecha);
                    $lotes->acta_fecha              = $this->dates($acta_fecha);
                    $lotes->informado               = 'no';
                    $lotes->gestionado              = 'no';

                    $lotes->item_add();
                }
                $linea++;
                debug($lotes);
                logger('GM: ' . $nro_gsm, 'debug');
            }
            fclose($handle);
        } else {
            echo "Error al abrir el archivo CSV.";
        }
    }
}
