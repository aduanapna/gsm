<?php

class lotesModel extends Model
{
    #lotes
    public $lote_id;
    public $fecha_ingreso;
    public $nro_gsm;
    public $nro_denuncia;
    public $nro_sumario;
    public $nro_alot;
    public $nro_sigea;
    public $deposito;
    public $judicializado;
    public $operacion;
    public $organismo_secuestro;
    public $lote_observation;
    public $resolucion_fecha;
    public $resolucion_nro;
    public $lote_condition;
    public $lote_store;
    # lotes_item
    public $lote_item_id;
    public $lote_item_bound;
    public $estado_ingreso;
    public $picture;
    public $posible_destino;
    public $rubro;
    public $cantidad;
    public $u_medida;
    public $descripcion;
    public $intervencion_inal;
    public $intervencion_inal_resol;
    public $intervencion_seguridad;
    public $intervencion_seguridad_resol;
    public $intervencion_juguete;
    public $intervencion_juguete_resol;
    public $intervencion_chas;
    public $m3_length;
    public $m3_width;
    public $m3_height;
    public $m3_unit;
    public $m3_total;
    public $aforo;
    public $aforo_fecha;
    public $ncm;
    public $momento_imponible;
    public $cotizacion_dolar;
    public $unitario_usd;
    public $valor_aduana;
    public $iva_general;
    public $iva_adicional;
    public $tasa_estadistica;
    public $impuesto_interno;
    public $anticipo_ganancias;
    public $derecho_impexp;
    public $arancel_despachante;
    public $pesos_iva;
    public $pesos_adicional;
    public $pesos_estadistica;
    public $pesos_impuestos;
    public $pesos_ganancias;
    public $pesos_derechos;
    public $valor_plaza;
    public $estado_actual;
    public $disposicion_fecha;
    public $disposicion_tipo;
    public $disposicion_nro;
    public $acta_fecha;
    public $acta_nro;
    public $informado;
    public $gestionado;

    public function lote_add()
    {
        $params['fecha_ingreso']        = $this->fecha_ingreso;
        $params['nro_gsm']              = $this->nro_gsm;
        $params['nro_denuncia']         = $this->nro_denuncia;
        $params['nro_sumario']          = $this->nro_sumario;
        $params['nro_alot']             = $this->nro_alot;
        $params['nro_sigea']            = $this->nro_sigea;
        $params['deposito']             = $this->deposito;
        $params['judicializado']        = $this->judicializado;
        $params['operacion']            = $this->operacion;
        $params['organismo_secuestro']  = $this->organismo_secuestro;
        $params['lote_observation']     = $this->lote_observation;
        $params['resolucion_nro']       = $this->resolucion_nro;
        $params['resolucion_nro']       = $this->resolucion_nro;
        $params['lote_condition']       = $this->lote_condition;
        $params['lote_store']           = $this->lote_store;

        $sql    = "INSERT INTO `lotes` SET ";
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function lote_update()
    {
        $params['lote_id']             = $this->lote_id;
        $params['fecha_ingreso']        = $this->fecha_ingreso;
        $params['nro_gsm']              = $this->nro_gsm;
        $params['nro_denuncia']         = $this->nro_denuncia;
        $params['nro_sumario']          = $this->nro_sumario;
        $params['nro_alot']             = $this->nro_alot;
        $params['nro_sigea']            = $this->nro_sigea;
        $params['deposito']             = $this->deposito;
        $params['judicializado']        = $this->judicializado;
        $params['operacion']            = $this->operacion;
        $params['resolucion_nro']       = $this->resolucion_nro;
        $params['organismo_secuestro']  = $this->organismo_secuestro;
        $params['lote_observation']    = $this->lote_observation;
        $params['lote_condition']      = $this->lote_condition;
        $params['lote_store']          = $this->lote_store;

        $sql    = "UPDATE `lotes` SET ";
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE lote_id=:lote_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function lote_delete()
    {
        $params = ['lote_id' => $this->lote_id];

        $sql = "DELETE lotes, lotes_item
                FROM lotes
                LEFT JOIN lotes_item ON lote_item_bound=lote_id
                WHERE lote_id=:lote_id;";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Lista las ordenes segun criterio lote_id, lote_number */
    public function lote_one()
    {
        $params = ['lote_id' => $this->lote_id];

        $sql = "SELECT lote_id,fecha_ingreso,DATE_FORMAT(fecha_ingreso, '%d/%m/%Y %H:%i') AS fecha,
        nro_gsm,nro_denuncia,nro_sumario,nro_alot,nro_sigea,deposito,judicializado,
        operacion,organismo_secuestro,resolucion_nro,lote_observation,lote_condition,lote_store,
        IFNULL(SUM(valor_plaza),0) as valor_plaza,
        IFNULL(SUM(m3_total),0) as m3_total,
        lote_condition_name,
        (SELECT GROUP_CONCAT(
            JSON_OBJECT(
                'lote_item_id',lote_item_id,
                'lote_item_bound',lote_item_bound,
                'estado_ingreso',estado_ingreso,
                'picture',picture,
                'posible_destino',posible_destino,
                'rubro',rubro,
                'cantidad',cantidad,
                'u_medida',u_medida,
                'descripcion',descripcion,
                'm3_length',m3_length,
                'm3_height',m3_height,
                'm3_width',m3_width,
                'm3_unit',m3_total,
                'm3_total',m3_total,
                'aforo',aforo,
                'aforo_fecha',aforo_fecha,
                'ncm',ncm,
                'momento_imponible',momento_imponible,
                'cotizacion_dolar',cotizacion_dolar,
                'unitario_usd',unitario_usd,
                'valor_aduana',valor_aduana,
                'iva_general',iva_general,
                'iva_adicional',iva_adicional,
                'tasa_estadistica',tasa_estadistica,
                'impuesto_interno',impuesto_interno,
                'anticipo_ganancias',anticipo_ganancias,
                'derecho_impexp',derecho_impexp,
                'arancel_despachante',arancel_despachante,
                'pesos_iva',pesos_iva,
                'pesos_adicional',pesos_adicional,
                'pesos_estadistica',pesos_estadistica,
                'pesos_impuestos',pesos_impuestos,
                'pesos_ganancias',pesos_ganancias,
                'pesos_derechos',pesos_derechos,
                'valor_plaza',valor_plaza,
                'estado_actual',estado_actual,
                'intervencion_inal',intervencion_inal,
                'intervencion_inal_resol',intervencion_inal_resol,
                'intervencion_seguridad',intervencion_seguridad,
                'intervencion_seguridad_resol',intervencion_seguridad_resol,
                'intervencion_juguete',intervencion_juguete,
                'intervencion_juguete_resol',intervencion_juguete_resol,
                'intervencion_chas',intervencion_chas,
                'disposicion_fecha',disposicion_fecha,
                'disposicion_tipo',disposicion_tipo,
                'disposicion_nro',disposicion_nro,
                'acta_fecha',acta_fecha,
                'acta_nro',acta_nro,
                'informado',informado,
                'gestionado',gestionado
            ) SEPARATOR ',')
            FROM `lotes_item`
            WHERE lote_item_bound = lote_id) AS lote_items
        FROM `lotes`
        INNER JOIN `lotes_condition` ON lote_condition_id=lote_condition
        LEFT JOIN `lotes_item` ON lote_item_bound=lote_id
        WHERE lote_id=:lote_id OR nro_gsm=:lote_id
        GROUP BY lote_id";

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Lista todas las ordenes sin criterio particular*/
    public function lote_all()
    {
        $params = [];

        $sql = "SELECT lote_id,fecha_ingreso,DATE_FORMAT(fecha_ingreso, '%d/%m/%Y %H:%i') AS fecha,
        nro_gsm,nro_denuncia,nro_sumario,nro_alot,nro_sigea,deposito,judicializado,
        operacion,organismo_secuestro,resolucion_nro,lote_observation,lote_condition,lote_store,
        IFNULL(SUM(valor_plaza),0) as valor_plaza,
        IFNULL(SUM(m3_total),0) as m3_total,
        lote_condition_name,
        (SELECT GROUP_CONCAT(
            JSON_OBJECT(
                'lote_item_id',lote_item_id,
                'lote_item_bound',lote_item_bound,
                'estado_ingreso',estado_ingreso,
                'picture',picture,
                'posible_destino',posible_destino,
                'rubro',rubro,
                'cantidad',cantidad,
                'u_medida',u_medida,
                'descripcion',descripcion,
                'm3_length',m3_length,
                'm3_height',m3_height,
                'm3_width',m3_width,
                'm3_unit',m3_unit,
                'm3_total',m3_total,
                'aforo',aforo,
                'aforo_fecha',aforo_fecha,
                'ncm',ncm,
                'momento_imponible',momento_imponible,
                'cotizacion_dolar',cotizacion_dolar,
                'unitario_usd',unitario_usd,
                'valor_aduana',valor_aduana,
                'iva_general',iva_general,
                'iva_adicional',iva_adicional,
                'tasa_estadistica',tasa_estadistica,
                'impuesto_interno',impuesto_interno,
                'anticipo_ganancias',anticipo_ganancias,
                'derecho_impexp',derecho_impexp,
                'arancel_despachante',arancel_despachante,
                'pesos_iva',pesos_iva,
                'pesos_adicional',pesos_adicional,
                'pesos_estadistica',pesos_estadistica,
                'pesos_impuestos',pesos_impuestos,
                'pesos_ganancias',pesos_ganancias,
                'pesos_derechos',pesos_derechos,
                'valor_plaza',valor_plaza,
                'estado_actual',estado_actual,
                'intervencion_inal',intervencion_inal,
                'intervencion_inal_resol',intervencion_inal_resol,
                'intervencion_seguridad',intervencion_seguridad,
                'intervencion_seguridad_resol',intervencion_seguridad_resol,
                'intervencion_juguete',intervencion_juguete,
                'intervencion_juguete_resol',intervencion_juguete_resol,
                'intervencion_chas',intervencion_chas,
                'disposicion_fecha',DATE_FORMAT(disposicion_fecha, '%d/%m/%Y %H:%i'),
                'disposicion_tipo',disposicion_tipo,
                'disposicion_nro',disposicion_nro,
                'acta_fecha',DATE_FORMAT(acta_fecha, '%d/%m/%Y %H:%i'),
                'acta_nro',acta_nro,
                'informado',informado,
                'gestionado',gestionado
            ) SEPARATOR ',')
            FROM `lotes_item`
            WHERE lote_item_bound = lote_id) AS lote_items
        FROM `lotes`
        INNER JOIN `lotes_condition` ON lote_condition_id=lote_condition
        LEFT JOIN `lotes_item` ON lote_item_bound=lote_id
        GROUP BY lote_id
        ORDER BY nro_gsm";

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function lotes_available()
    {
        $params = [];

        $sql = "SELECT lote_id,fecha_ingreso,DATE_FORMAT(fecha_ingreso, '%d/%m/%Y %H:%i') AS fecha,
        nro_gsm,nro_denuncia,nro_sumario,nro_alot,nro_sigea,deposito,judicializado,
        operacion,organismo_secuestro,resolucion_nro,lote_observation,lote_condition,lote_store,
        IFNULL(SUM(valor_plaza),0) as valor_plaza,
        IFNULL(SUM(m3_total),0) as m3_total,
        lote_condition_name,
        (SELECT GROUP_CONCAT(
            JSON_OBJECT(
                'lote_item_id',lote_item_id,
                'lote_item_bound',lote_item_bound,
                'estado_ingreso',estado_ingreso,
                'picture',picture,
                'posible_destino',posible_destino,
                'rubro',rubro,
                'cantidad',cantidad,
                'u_medida',u_medida,
                'descripcion',descripcion,
                'm3_length',m3_length,
                'm3_height',m3_height,
                'm3_width',m3_width,
                'm3_unit',m3_unit,
                'm3_total',m3_total,
                'aforo',aforo,
                'aforo_fecha',aforo_fecha,
                'ncm',ncm,
                'momento_imponible',momento_imponible,
                'cotizacion_dolar',cotizacion_dolar,
                'unitario_usd',unitario_usd,
                'valor_aduana',valor_aduana,
                'iva_general',iva_general,
                'iva_adicional',iva_adicional,
                'tasa_estadistica',tasa_estadistica,
                'impuesto_interno',impuesto_interno,
                'anticipo_ganancias',anticipo_ganancias,
                'derecho_impexp',derecho_impexp,
                'arancel_despachante',arancel_despachante,
                'pesos_iva',pesos_iva,
                'pesos_adicional',pesos_adicional,
                'pesos_estadistica',pesos_estadistica,
                'pesos_impuestos',pesos_impuestos,
                'pesos_ganancias',pesos_ganancias,
                'pesos_derechos',pesos_derechos,
                'valor_plaza',valor_plaza,
                'estado_actual',estado_actual,
                'intervencion_inal',intervencion_inal,
                'intervencion_inal_resol',intervencion_inal_resol,
                'intervencion_seguridad',intervencion_seguridad,
                'intervencion_seguridad_resol',intervencion_seguridad_resol,
                'intervencion_juguete',intervencion_juguete,
                'intervencion_juguete_resol',intervencion_juguete_resol,
                'intervencion_chas',intervencion_chas,
                'disposicion_fecha',DATE_FORMAT(disposicion_fecha, '%d/%m/%Y %H:%i'),
                'disposicion_tipo',disposicion_tipo,
                'disposicion_nro',disposicion_nro,
                'acta_fecha',DATE_FORMAT(acta_fecha, '%d/%m/%Y %H:%i'),
                'acta_nro',acta_nro,
                'informado',informado,
                'gestionado',gestionado
            ) SEPARATOR ',')
            FROM `lotes_item`
            WHERE lote_item_bound = lote_id) AS lote_items
        FROM `lotes`
        INNER JOIN `lotes_condition` ON lote_condition_id=lote_condition
        LEFT JOIN `lotes_item` ON lote_item_bound=lote_id
        WHERE lote_condition != 8
        GROUP BY lote_id
        ORDER BY nro_gsm";

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Lista todas las ordenes segun criterio lote_condition */
    public function lote_status()
    {
        $sql = "SELECT lote_id,TIME_FORMAT(lote_detail_preparation_time, '%H:%i') AS lote_preparation_time 
        FROM `lotes` 
        INNER JOIN `lotes_detail` ON lote_detail_lote = lote_id
        WHERE lote_condition=2 AND lote_store=:lote_store";
        $params = ['lote_store' => $this->lote_store];

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Establece valores en campo lote_condition */
    public function lote_change()
    {
        $params['lote_id']         = $this->lote_id;
        $params['lote_condition']  = $this->lote_condition;

        $sql = "UPDATE `lotes` SET lote_condition=:lote_condition WHERE lote_id=:lote_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function item_add()
    {
        $params['lote_item_bound']              = $this->lote_item_bound;
        $params['estado_ingreso']               = $this->estado_ingreso;
        $params['picture']                      = $this->picture;
        $params['rubro']                        = $this->rubro;
        $params['posible_destino']              = $this->posible_destino;
        $params['cantidad']                     = $this->cantidad;
        $params['u_medida']                     = $this->u_medida;
        $params['descripcion']                  = $this->descripcion;
        $params['m3_length']                    = $this->m3_length;
        $params['m3_width']                     = $this->m3_width;
        $params['m3_height']                    = $this->m3_height;
        $params['m3_unit']                      = $this->m3_unit;
        $params['m3_total']                     = $this->m3_total;
        $params['aforo']                        = $this->aforo;
        $params['aforo_fecha']                  = $this->aforo_fecha;
        $params['ncm']                          = $this->ncm;
        $params['momento_imponible']            = $this->momento_imponible;
        $params['cotizacion_dolar']             = $this->cotizacion_dolar;
        $params['unitario_usd']                 = $this->unitario_usd;
        $params['valor_aduana']                 = $this->valor_aduana;
        $params['iva_general']                  = $this->iva_general;
        $params['iva_adicional']                = $this->iva_adicional;
        $params['tasa_estadistica']             = $this->tasa_estadistica;
        $params['impuesto_interno']             = $this->impuesto_interno;
        $params['anticipo_ganancias']           = $this->anticipo_ganancias;
        $params['derecho_impexp']               = $this->derecho_impexp;
        $params['arancel_despachante']          = $this->arancel_despachante;
        $params['pesos_iva']                    = $this->pesos_iva;
        $params['pesos_adicional']              = $this->pesos_adicional;
        $params['pesos_estadistica']            = $this->pesos_estadistica;
        $params['pesos_impuestos']              = $this->pesos_impuestos;
        $params['pesos_ganancias']              = $this->pesos_ganancias;
        $params['pesos_derechos']               = $this->pesos_derechos;
        $params['valor_plaza']                  = $this->valor_plaza;
        $params['intervencion_inal']            = $this->intervencion_inal;
        $params['intervencion_seguridad']       = $this->intervencion_seguridad;
        $params['intervencion_juguete']         = $this->intervencion_juguete;
        $params['intervencion_chas']            = $this->intervencion_chas;

        $params['disposicion_fecha']            = $this->disposicion_fecha;
        $params['disposicion_tipo']             = $this->disposicion_tipo;
        $params['disposicion_nro']              = $this->disposicion_nro;
        $params['acta_fecha']                   = $this->acta_fecha;
        $params['acta_nro']                     = $this->acta_nro;
        $params['informado']                    = $this->informado;
        $params['gestionado']                   = $this->gestionado;

        $sql    = "INSERT INTO `lotes_item` SET ";
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function item_update()
    {
        $params['lote_item_id']                 = $this->lote_item_id;
        $params['estado_ingreso']               = $this->estado_ingreso;
        $params['picture']                      = $this->picture;
        $params['rubro']                        = $this->rubro;
        $params['posible_destino']              = $this->posible_destino;
        $params['cantidad']                     = $this->cantidad;
        $params['u_medida']                     = $this->u_medida;
        $params['descripcion']                  = $this->descripcion;
        $params['m3_length']                    = $this->m3_length;
        $params['m3_width']                     = $this->m3_width;
        $params['m3_height']                    = $this->m3_height;
        $params['m3_unit']                      = $this->m3_unit;
        $params['m3_total']                     = $this->m3_total;
        $params['aforo']                        = $this->aforo;
        $params['aforo_fecha']                  = $this->aforo_fecha;
        $params['ncm']                          = $this->ncm;
        $params['momento_imponible']            = $this->momento_imponible;
        $params['cotizacion_dolar']             = $this->cotizacion_dolar;
        $params['unitario_usd']                 = $this->unitario_usd;
        $params['valor_aduana']                 = $this->valor_aduana;
        $params['iva_general']                  = $this->iva_general;
        $params['iva_adicional']                = $this->iva_adicional;
        $params['tasa_estadistica']             = $this->tasa_estadistica;
        $params['impuesto_interno']             = $this->impuesto_interno;
        $params['anticipo_ganancias']           = $this->anticipo_ganancias;
        $params['derecho_impexp']               = $this->derecho_impexp;
        $params['arancel_despachante']          = $this->arancel_despachante;
        $params['pesos_iva']                    = $this->pesos_iva;
        $params['pesos_adicional']              = $this->pesos_adicional;
        $params['pesos_estadistica']            = $this->pesos_estadistica;
        $params['pesos_impuestos']              = $this->pesos_impuestos;
        $params['pesos_ganancias']              = $this->pesos_ganancias;
        $params['pesos_derechos']               = $this->pesos_derechos;
        $params['valor_plaza']                  = $this->valor_plaza;
        $params['intervencion_inal']            = $this->intervencion_inal;
        $params['intervencion_inal_resol']      = $this->intervencion_inal_resol;
        $params['intervencion_seguridad']       = $this->intervencion_seguridad;
        $params['intervencion_seguridad_resol'] = $this->intervencion_seguridad_resol;
        $params['intervencion_juguete']         = $this->intervencion_juguete;
        $params['intervencion_juguete_resol']   = $this->intervencion_juguete_resol;
        $params['intervencion_chas']            = $this->intervencion_chas;

        $params['disposicion_fecha']            = $this->disposicion_fecha;
        $params['disposicion_tipo']             = $this->disposicion_tipo;
        $params['disposicion_nro']              = $this->disposicion_nro;
        $params['acta_fecha']                   = $this->acta_fecha;
        $params['acta_nro']                     = $this->acta_nro;
        $params['informado']                    = $this->informado;
        $params['gestionado']                   = $this->gestionado;

        $sql    = "UPDATE `lotes_item` SET ";
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE lote_item_id=:lote_item_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function item_delete()
    {
        $params['lote_item_id'] = $this->lote_item_id;
        $sql = "DELETE FROM `lotes_item` WHERE lote_item_id=:lote_item_id LIMIT 1";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function item_deletes()
    {
        $params['lote_item_bound'] = $this->lote_item_bound;
        $sql = "DELETE FROM `lotes_item` WHERE lote_item_bound=:lote_item_bound";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Listas condiciones */
    public function lotes_condition()
    {
        $params = [];

        $sql = "SELECT * FROM `lotes_condition`";

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
