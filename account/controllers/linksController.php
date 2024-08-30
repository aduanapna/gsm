<?php

class linksController extends Controller
{
    public $var         = var_gestion;
    public $controller  = 'links';
    public $THEME       = STYLES . 'default/';

    function __construct()
    {
        parent::__construct();

        # Configuracion inicial
        $this->site['title']            = title_account;
        $this->site['name']             = title_account;
        $this->site['author']           = title_account;
        $this->site['description']      = '';
        $this->site['logo']             = links_logo;
        $this->site['about']            = 'https://imaginedesign.ar';
        $this->site['favicon']          = links_logo;
        # Modulo dependiente de enlaces 
        $this->site['controller']       = $this->controller;
        # Enlaces
        $this->site['home']             = URL . 'links/index';
        $this->site['index']            = URL . 'links/index';
        $this->site['team_cover']       = IMAGES . '_local.png';
        $this->site['html']             = $this->create_html();
        $this->site['body_class']       = '';
        $this->site['body_style']       = '';

        $this->site['head'][]           = $this->THEME . 'css/bootstrap.min.css';
        $this->site['head'][]           = $this->THEME . 'css/app.min.css';
        $this->site['head'][]           = $this->THEME . 'css/icons.min.css';
        $this->site['head'][]           = $this->THEME . 'custom/fonts.css';

        $this->site['head'][]           = LIBS . 'sweetalert2/sweetalert2.min.js';
        $this->site['head'][]           = LIBS . 'sweetalert2/sweetalert2-toast.js';
        $this->site['head'][]           = LIBS . 'sweetalert2/sweetalert2.css';
        $this->site['head'][]           = LIBS . 'toastify-js/toastify.js';
        $this->site['head'][]           = LIBS . 'vue/vue.js';
        $this->site['head'][]           = LIBS . 'axios/axios.min.js';
        $this->site['head'][]           = LIBS . 'fleximvc/app.js';
        $this->site['footer']           = [];
    }
    # Global
    function data()
    {
        check_csrf();
        try {
            $send = [];
            json_response(200, $send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    # Vista HTML
    function index()
    {
        $_SESSION['link_page'] = 'index';
        $this->view_page();
    }
    function view_page()
    {
        $this->site['footer'][] = get_vue('links', 'vue_index', 'view');
        View2::render('links', 'index', $this->site, 'view');
    }
    # Protects
    protected function create_html()
    {
        $html                               = '';
        # Solo cuando incluye app.js
        $html_data['lang']                  = 'es';
        $html_data['data-layout']           = 'horizontal';
        $html_data['data-topbar']           = 'dark';
        $html_data['data-sidebar']          = 'dark';
        $html_data['data-sidebar-size']     = 'sm';
        $html_data['data-sidebar-image']    = 'none';
        $html_data['data-preloader']        = 'false';
        $html_data['data-layout-mode']      = 'light';
        $html_data['data-layout-width']     = 'fluid';
        $html_data['data-layout-position']  = 'fixed';
        $html_data['data-layout-style']     = 'default';

        foreach ($html_data as $ix => $vl) {
            $html .= $ix . '="' . $vl . '" ';
        }
        return '<html ' . $html . '>';
    }
}
