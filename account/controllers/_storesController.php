<?php
# Controlador de sucursales
class _storesController extends Controller

{
    public static $user;
    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login');
    }
    function one()
    {
        try {
            $store              = new storesModel();
            $store->store_id    = self::$user->store_id;
            $data_store         = $store->one();
            if ($data_store != []) {
                $data_store['store_picture']    = IMAGES . $data_store['store_picture'];
                $data_store['store_open']       = boolean_return($data_store['store_open']);
                $data_store['printer_token']    = $this->token_pr($data_store['store_id'], $data_store['printer_id']);
                $data_store['zones']            = to_array($data_store['zones']);
            } else {
                json_response(404, null, 'Error al cargar sucursal');
            }

            json_response(200, $data_store);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function update()
    {
        $form                              = check_form('form');

        try {
            # Actulizamos campos de sucursal
            $store                          = new storesModel();

            $store->store_id                = get_form($form, 'store_id', ['notnull']);
            $store->store_picture           = get_form($form, 'store_picture', ['image']);
            $store->store_name              = get_form($form, 'store_name', ['notnull']);
            $store->store_description       = get_form($form, 'store_description', []);
            $store->store_address           = get_form($form, 'store_address', []);
            $store->store_phonenumber       = get_form($form, 'store_phonenumber', []);
            $store->store_instagram         = get_form($form, 'store_instagram', []);
            $store->store_facebook          = get_form($form, 'store_facebook', []);
            $store->store_email             = get_form($form, 'store_email', []);
            $store->store_web               = get_form($form, 'store_web', []);
            $store->afip_cuit               = get_form($form, 'afip_cuit', []);
            $store->afip_business           = get_form($form, 'afip_business', []);
            $store->afip_gross_income       = get_form($form, 'afip_gross_income', []);
            $store->afip_start_activity     = get_form($form, 'afip_start_activity', []);
            $store->afip_vat_condition      = get_form($form, 'afip_vat_condition', []);
            $store->afip_crt                = get_form($form, 'afip_crt', []);
            $store->afip_key                = get_form($form, 'afip_key', []);
            $store->afip_expiration         = get_form($form, 'afip_expiration', [], date_js());
            $store->afip_folder             = get_form($form, 'afip_folder', []);
            $store->afip_production         = get_form($form, 'afip_production', []);
            $store->afip_point_sale         = get_form($form, 'afip_point_sale', []);
            $store->afip_invoice_type       = get_form($form, 'afip_invoice_type', []);

            $store->store_open              = get_form($form, 'store_open', ['boolean']);
            $store->update();

            json_response(200, null, 'Datos actulizados');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function update_afip()
    {
        $form                              = check_form('form');

        try {
            # Actulizamos campos de sucursal
            $store                          = new storesModel();

            $store->afip_cuit               = get_form($form, 'afip_cuit', ['']);
            $store->afip_business           = get_form($form, 'afip_business', ['']);
            $store->afip_gross_income       = get_form($form, 'afip_gross_income', ['']);
            $store->afip_start_activity     = get_form($form, 'afip_start_activity', ['']);
            $store->afip_vat_condition      = get_form($form, 'afip_vat_condition', []);
            $store->afip_crt                = get_form($form, 'afip_crt', []);
            $store->afip_key                = get_form($form, 'afip_key', []);
            $store->afip_expiration         = get_form($form, 'afip_expiration', ['notnull'], date_js());
            $store->afip_folder             = get_form($form, 'afip_folder', []);
            $store->afip_production         = get_form($form, 'afip_production', []);
            $store->afip_point_sale         = get_form($form, 'afip_point_sale', []);
            $store->afip_invoice_type       = get_form($form, 'afip_invoice_type', []);
            $store->store_id                = get_form($form, 'store_id', []);

            $store->update_afip();

            json_response(200, null, 'Datos actulizados');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    # Privates
    private function token_pr($store_id, $printer_id)
    # Token para usar impresora desde aplicativo IDPrinter
    {
        $token['store_id']              = $store_id;
        $token['printer_id']            = $printer_id;
        $token                          = to_json($token);
        $token                          = iD_encrypt($token);
        return $token;
    }

}
