<?php

class _lotesController extends Controller

{
    public static $user;
    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login', true);
    }

    # ===== Global

    /** Informacion para enviar al front lote*/
    function data()
    {
        try {

            $send_data['lotes']            = $this->lotes_all();
            $send_data['lotes_condition']  = $this->lote_conditions();

            json_response(200, $send_data);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    /** Informacion por defecto para una nueva orden */
    function lote_new()
    {
        $lote_data['lote_id']               = null;
        $lote_data['fecha_ingreso']         = date_js();
        $lote_data['nro_gsm']               = '';
        $lote_data['nro_denuncia']          = '';
        $lote_data['nro_sumario']           = '';
        $lote_data['nro_alot']              = '';
        $lote_data['nro_sigea']             = '';
        $lote_data['deposito']              = 'ADUANA';
        $lote_data['judicializado']         = 'NO';
        $lote_data['operacion']             = 'IMPORTACION';
        $lote_data['organismo_secuestro']   = 'DGA';
        $lote_data['lote_observation']      = '';
        $lote_data['lote_condition']        = 1;
        $lote_data['lote_store']            = self::$user->store_id;
        $lote_data['lote_items']            = [];

        try {
            json_response(200, $lote_data, 'Orden por defecto');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage() . ' new_lote');
        }
    }
    /** Agrega orden takeaway o delivey */
    function lote_add()
    {
        $lote_form                                  = check_form('lote_form');
        $lote_items                                 = $lote_form['lote_items'];
        # Seteamos variables que usaremos mas de una vez
        $lote                                       = new lotesModel;
        $lote->fecha_ingreso                        = get_form($lote_form, 'fecha_ingreso', ['date']);
        $lote->nro_gsm                              = get_form($lote_form, 'nro_gsm', ['notnull', 'strtolower']);
        $lote->nro_denuncia                         = get_form($lote_form, 'nro_denuncia', ['strtolower']);
        $lote->nro_sumario                          = get_form($lote_form, 'nro_sumario', ['strtolower']);
        $lote->nro_alot                             = get_form($lote_form, 'nro_alot', ['strtolower']);
        $lote->nro_sigea                            = get_form($lote_form, 'nro_sigea', ['strtolower']);
        $lote->deposito                             = get_form($lote_form, 'deposito', ['notnull']);
        $lote->judicializado                        = get_form($lote_form, 'judicializado', ['strtolower']);
        $lote->operacion                            = get_form($lote_form, 'operacion', ['strtolower']);
        $lote->organismo_secuestro                  = get_form($lote_form, 'organismo_secuestro', ['strtolower']);
        $lote->lote_observation                     = get_form($lote_form, 'lote_observation', ['strtolower']);
        $lote->lote_condition                       = 1;
        $lote->lote_store                           = self::$user->store_id;

        $lote_id                                    = $lote->lote_add();
        # Cargamos item x item
        $lote_item                                  = new lotesModel;
        # Borramos todos los items
        $lote_item->item_deletes();

        foreach ($lote_items as $item) {
            $lote_item->lote_item_bound             = $lote_id;
            $lote_item->estado_ingreso              = get_form($item, 'estado_ingreso', ['notnull'], 'nuevo');
            $lote_item->picture                     = get_form($item, 'picture', ['image'], '');
            $lote_item->rubro                       = get_form($item, 'rubro', ['strtolower']);
            $lote_item->posible_destino             = get_form($item, 'posible_destino', ['strtolower']);
            $lote_item->cantidad                    = get_form($item, 'cantidad', []);
            $lote_item->u_medida                    = get_form($item, 'u_medida', []);
            $lote_item->descripcion                 = get_form($item, 'descripcion', ['strtolower']);
            $lote_item->m3_length                   = get_form($item, 'm3_length', ['notnull', 'positive'], 0);
            $lote_item->m3_width                    = get_form($item, 'm3_width', ['notnull', 'positive'], 0);
            $lote_item->m3_height                   = get_form($item, 'm3_height', ['notnull', 'positive'], 0);
            $lote_item->m3_unit                     = get_form($item, 'm3_unit', ['notnull', 'positive'], 0);
            $lote_item->m3_total                    = get_form($item, 'm3_total', ['notnull', 'positive'], 0);
            $lote_item->aforo                       = get_form($item, 'aforo', ['strtolower'], 'no');
            $lote_item->aforo_fecha                 = get_form($item, 'aforo_fecha', []);
            $lote_item->ncm                         = get_form($item, 'ncm', []);
            $lote_item->momento_imponible           = get_form($item, 'momento_imponible', []);
            $lote_item->cotizacion_dolar            = get_form($item, 'cotizacion_dolar', ['notnull', 'positive'], 0);
            $lote_item->unitario_usd                = get_form($item, 'unitario_usd', ['notnull', 'positive'], 0);
            $lote_item->valor_aduana                = get_form($item, 'valor_aduana', ['notnull', 'positive'], 0);
            $lote_item->iva_general                 = get_form($item, 'iva_general', ['notnull', 'positive'], 0);
            $lote_item->iva_adicional               = get_form($item, 'iva_adicional', ['notnull', 'positive'], 0);
            $lote_item->tasa_estadistica            = get_form($item, 'tasa_estadistica', ['notnull', 'positive'], 0);
            $lote_item->impuesto_interno            = get_form($item, 'impuesto_interno', ['notnull', 'positive'], 0);
            $lote_item->anticipo_ganancias          = get_form($item, 'anticipo_ganancias', ['notnull', 'positive'], 0);
            $lote_item->derecho_impexp              = get_form($item, 'derecho_impexp', ['notnull', 'positive'], 0);
            $lote_item->arancel_despachante         = get_form($item, 'arancel_despachante', ['notnull', 'positive'], 0);
            $lote_item->pesos_iva                   = get_form($item, 'pesos_iva', ['notnull', 'positive'], 0);
            $lote_item->pesos_adicional             = get_form($item, 'pesos_adicional', ['notnull', 'positive'], 0);
            $lote_item->pesos_estadistica           = get_form($item, 'pesos_estadistica', ['notnull', 'positive'], 0);
            $lote_item->pesos_impuestos             = get_form($item, 'pesos_impuestos', ['notnull', 'positive'], 0);
            $lote_item->pesos_ganancias             = get_form($item, 'pesos_ganancias', ['notnull', 'positive'], 0);
            $lote_item->pesos_derechos              = get_form($item, 'pesos_derechos', ['notnull', 'positive'], 0);
            $lote_item->valor_plaza                 = get_form($item, 'valor_plaza', ['notnull', 'positive'], 0);
            $lote_item->intervencion_inal           = get_form($item, 'intervencion_inal', ['strtolower'], 'no');
            $lote_item->intervencion_seguridad      = get_form($item, 'intervencion_seguridad', ['strtolower'], 'no');
            $lote_item->intervencion_juguete        = get_form($item, 'intervencion_juguete', ['strtolower'], 'no');
            $lote_item->disposicion_fecha           = get_form($item, 'disposicion_fecha', ['date']);
            $lote_item->disposicion_tipo            = get_form($item, 'disposicion_tipo', ['strtolower']);
            $lote_item->disposicion_nro             = get_form($item, 'disposicion_nro', ['strtolower']);
            $lote_item->acta_fecha                  = get_form($item, 'acta_fecha', ['date']);
            $lote_item->acta_nro                    = get_form($item, 'acta_nro', ['strtolower']);
            $lote_item->informado                   = get_form($item, 'informado', ['strtolower']);
            $lote_item->gestionado                  = get_form($item, 'gestionado', ['strtolower']);
            $lote_item->item_add();
        }
        json_response(200, null, 'Orden creada correctamente');
    }
    function lote_update()
    {
        $form_lote                                     = check_form('lote_form');
        # Seteamos variables que usaremos mas de una vez
        $lote                                          = new lotesModel;
        $lote->lote_id                                  = get_form($form_lote, 'lote_id', ['notnull']);
        $lote->fecha_ingreso                           = get_form($form_lote, 'fecha_ingreso', ['date']);
        $lote->nro_gsm                                 = get_form($form_lote, 'nro_gsm', ['notnull']);
        $lote->nro_denuncia                            = get_form($form_lote, 'nro_denuncia', []);
        $lote->nro_sumario                             = get_form($form_lote, 'nro_sumario', []);
        $lote->nro_alot                                = get_form($form_lote, 'nro_alot', []);
        $lote->nro_sigea                               = get_form($form_lote, 'nro_sigea', []);
        $lote->deposito                                = get_form($form_lote, 'deposito', ['notnull']);
        $lote->judicializado                           = get_form($form_lote, 'judicializado', []);
        $lote->operacion                               = get_form($form_lote, 'operacion', []);
        $lote->organismo_secuestro                     = get_form($form_lote, 'organismo_secuestro', []);
        $lote->lote_observation                       = get_form($form_lote, 'lote_observation', []);
        $lote->lote_condition                         = get_form($form_lote, 'lote_condition', []);
        $lote->lote_store                             = self::$user->store_id;
        $lote->lote_update();

        json_response(200, null, 'Orden modificada correctamente');
    }
    function item_update()
    {
        $lote_items                                     = check_form('lote_items');
        $lote_deletes                                   = check_form('lote_deletes');
        # Seteamos variables que usaremos mas de una vez
        $lote_item                                      = new lotesModel;
        # Borramos los items
        foreach ($lote_deletes as $item_delete) {
            $lote_item->lote_item_id = $item_delete['lote_item_id'];
            $lote_item->item_delete();
        }
        foreach ($lote_items as $item) {
            $lote_item_id                               = get_form($item, 'lote_item_id', ['notnull']);
            $lote_item->lote_item_id                    = $lote_item_id;
            $lote_item->lote_item_bound                 = get_form($item, 'lote_item_bound', ['notnull']);
            $lote_item->picture                         = get_form($item, 'picture', ['image'], '');
            $lote_item->rubro                           = get_form($item, 'rubro', ['strtolower']);
            $lote_item->posible_destino                 = get_form($item, 'posible_destino', ['strtolower']);
            $lote_item->cantidad                        = get_form($item, 'cantidad', []);
            $lote_item->u_medida                        = get_form($item, 'u_medida', []);
            $lote_item->descripcion                     = get_form($item, 'descripcion', ['strtolower']);
            $lote_item->m3_length                       = get_form($item, 'm3_length', ['notnull', 'positive'], 0);
            $lote_item->m3_width                        = get_form($item, 'm3_width', ['notnull', 'positive'], 0);
            $lote_item->m3_height                       = get_form($item, 'm3_height', ['notnull', 'positive'], 0);
            $lote_item->m3_unit                         = get_form($item, 'm3_unit', ['notnull', 'positive'], 0);
            $lote_item->m3_total                        = get_form($item, 'm3_total', ['notnull', 'positive'], 0);
            $lote_item->aforo                           = get_form($item, 'aforo', ['strtolower'], 'no');
            $lote_item->aforo_fecha                     = get_form($item, 'aforo_fecha', []);
            $lote_item->ncm                             = get_form($item, 'ncm', []);
            $lote_item->momento_imponible               = get_form($item, 'momento_imponible', []);
            $lote_item->cotizacion_dolar                = get_form($item, 'cotizacion_dolar', ['notnull', 'positive'], 0);
            $lote_item->unitario_usd                    = get_form($item, 'unitario_usd', ['notnull', 'positive'], 0);
            $lote_item->valor_aduana                    = get_form($item, 'valor_aduana', ['notnull', 'positive'], 0);
            $lote_item->iva_general                     = get_form($item, 'iva_general', ['notnull', 'positive'], 0);
            $lote_item->iva_adicional                   = get_form($item, 'iva_adicional', ['notnull', 'positive'], 0);
            $lote_item->tasa_estadistica                = get_form($item, 'tasa_estadistica', ['notnull', 'positive'], 0);
            $lote_item->impuesto_interno                = get_form($item, 'impuesto_interno', ['notnull', 'positive'], 0);
            $lote_item->anticipo_ganancias              = get_form($item, 'anticipo_ganancias', ['notnull', 'positive'], 0);
            $lote_item->derecho_impexp                  = get_form($item, 'derecho_impexp', ['notnull', 'positive'], 0);
            $lote_item->arancel_despachante             = get_form($item, 'arancel_despachante', ['notnull', 'positive'], 0);
            $lote_item->pesos_iva                       = get_form($item, 'pesos_iva', ['notnull', 'positive'], 0);
            $lote_item->pesos_adicional                 = get_form($item, 'pesos_adicional', ['notnull', 'positive'], 0);
            $lote_item->pesos_estadistica               = get_form($item, 'pesos_estadistica', ['notnull', 'positive'], 0);
            $lote_item->pesos_impuestos                 = get_form($item, 'pesos_impuestos', ['notnull', 'positive'], 0);
            $lote_item->pesos_ganancias                 = get_form($item, 'pesos_ganancias', ['notnull', 'positive'], 0);
            $lote_item->pesos_derechos                  = get_form($item, 'pesos_derechos', ['notnull', 'positive'], 0);
            $lote_item->valor_plaza                     = get_form($item, 'valor_plaza', ['notnull', 'positive'], 0);
            $lote_item->intervencion_inal               = get_form($item, 'intervencion_inal', ['strtolower'], 'no');
            $lote_item->intervencion_seguridad          = get_form($item, 'intervencion_seguridad', ['strtolower'], 'no');
            $lote_item->intervencion_juguete            = get_form($item, 'intervencion_juguete', ['strtolower'], 'no');
            $lote_item->intervencion_inal_resol         = get_form($item, 'intervencion_inal_resol', ['strtolower']);
            $lote_item->intervencion_seguridad_resol    = get_form($item, 'intervencion_seguridad_resol', ['strtolower']);
            $lote_item->intervencion_juguete_resol      = get_form($item, 'intervencion_juguete_resol', ['strtolower']);
            $lote_item->intervencion_chas               = get_form($item, 'intervencion_chas', ['strtolower']);
            $lote_item->disposicion_fecha               = get_form($item, 'disposicion_fecha', ['date']);
            $lote_item->disposicion_tipo                = get_form($item, 'disposicion_tipo', ['strtolower']);
            $lote_item->disposicion_nro                 = get_form($item, 'disposicion_nro', ['strtolower']);
            $lote_item->acta_fecha                      = get_form($item, 'acta_fecha', ['date']);
            $lote_item->acta_nro                        = get_form($item, 'acta_nro', ['strtolower']);
            $lote_item->informado                       = get_form($item, 'informado', ['strtolower']);
            $lote_item->gestionado                      = get_form($item, 'gestionado', ['strtolower']);
            if ($lote_item_id != 0) {
                $lote_item->item_update();
            } else {
                $lote_item->item_add();
            }
        }

        json_response(200, null, 'Orden modificada correctamente');
    }
    function lote_delete()
    {
        $form_lote         = check_form('lote_form');
        # Seteamos variables que usaremos mas de una vez
        $lote              = new lotesModel;
        $lote->lote_id    = get_form($form_lote, 'lote_id', ['notnull']);

        $lote->lote_delete();

        json_response(200, null, 'Orden eliminada correctamente');
    }
    /** Traer datos del lote */
    function lote_view()
    {
        $nro_gsm = get_form($_POST, 'lote_number', ['notnull']);

        # Consultamos lote por GSM
        $lotes             = new lotesModel;
        $lotes->lote_id   = $nro_gsm;
        $lote_data         = $lotes->lote_one();

        if ($lote_data != []) {
            $lote_items                = to_array($lote_data['lote_items']);
            foreach ($lote_items as $ix_items => $vl_items) {
                $lote_items[$ix_items]['picture']  = IMAGES . $vl_items['picture'];
                $lote_items[$ix_items]['keywords'] = generate_keywords([$vl_items['descripcion'], $vl_items['rubro']]);
            }
            $lote_data['lote_items']  = $lote_items;

            json_response(200, $lote_data);
        }
        json_response(201, null, 'Lote no encontrado');
    }
    function lotes_all()
    {
        $lotes         = new lotesModel;
        $lotes_data    = $lotes->lote_all();

        return $this->lote_list($lotes_data);
    }
    function lotes_available()
    {
        $lotes                          = new lotesModel;
        $lotes_data                     = $lotes->lotes_available();

        $send_data['lotes']             = $this->lote_list($lotes_data);
        $send_data['lotes_condition']   = $this->lote_conditions();

        json_response(200, $send_data);
    }

    protected function lote_list($lotes_data = [])
    {

        foreach ($lotes_data as $ix_lote => $vl_lote) {
            $lote_items = to_array($vl_lote['lote_items']);
            foreach ($lote_items as $ix_items => $vl_items) {
                $lote_items[$ix_items]['picture']   = IMAGES . $vl_items['picture'];
            }
            $lotes_data[$ix_lote]['valor_plaza']    = round($vl_lote['valor_plaza'] ?? 0, 2);
            $lotes_data[$ix_lote]['m3_total']       = round($vl_lote['m3_total'] ?? 0, 6);
            $lotes_data[$ix_lote]['lote_items']     = $lote_items;
            $lotes_data[$ix_lote]['keywords']       = generate_keywords(['#' . $vl_lote['nro_gsm'], $vl_lote['nro_denuncia'], $vl_lote['nro_sumario'], $vl_lote['nro_alot'], $vl_lote['nro_sigea']]);
        }
        return $lotes_data;
    }
    protected function lote_conditions()
    {
        $lotes         = new lotesModel;
        $lotes_data    = $lotes->lotes_condition();
        return $lotes_data;
    }
}
