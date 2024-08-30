<?php

class storesModel extends Model
{
    public $store_id;
    public $store_picture;
    public $store_name;
    public $store_description;
    public $store_address;
    public $store_phonenumber;
    public $store_facebook;
    public $store_instagram;
    public $store_email;
    public $store_web;
    public $store_open_am;
    public $store_close_am;
    public $store_open_pm;
    public $store_close_pm;
    public $store_cash;
    public $afip_cuit;
    public $afip_business;
    public $afip_gross_income;
    public $afip_start_activity;
    public $afip_vat_condition;
    public $afip_crt;
    public $afip_key;
    public $afip_expiration;
    public $afip_folder;
    public $afip_production;
    public $afip_point_sale;
    public $afip_invoice_type;
    public $store_condition;
    public $store_open;

    /**  Agrega una sucursal con los datos basicos */
    public function add()
    {
        $sql = "INSERT INTO `stores` SET ";

        $params =
            [
                'store_name'        => $this->store_name,
                'store_address'     => $this->store_address,
                'store_condition'   => $this->store_condition,
                'store_open'        => $this->store_open,
            ];

        $sql    = generate_bind($sql, $params);

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Actualiza todos los datos de la sucursal */
    public function update()
    {
        $sql = "UPDATE `stores` SET ";

        $params =
            [
                'store_id'              => $this->store_id,
                'store_picture'         => $this->store_picture,
                'store_name'            => $this->store_name,
                'store_description'     => $this->store_description,
                'store_address'         => $this->store_address,
                'store_phonenumber'     => $this->store_phonenumber,
                'store_instagram'       => $this->store_instagram,
                'store_facebook'        => $this->store_facebook,
                'store_email'           => $this->store_email,
                'store_web'             => $this->store_web,
                'store_open_am'         => $this->store_open_am,
                'store_close_am'        => $this->store_close_am,
                'store_open_pm'         => $this->store_open_pm,
                'store_close_pm'        => $this->store_close_pm,
                'store_open'            => $this->store_open,
                'store_cash'            => $this->store_cash,
            ];

        $sql    = generate_bind($sql, $params);
        $sql = $sql . " WHERE store_id=:store_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Actualiza todos los datos relevantes de FACTURACION */
    public function update_afip()
    {
        $sql = "UPDATE `stores` SET ";

        $params =
            [
                'afip_cuit'             => $this->afip_cuit,
                'afip_business'         => $this->afip_business,
                'afip_gross_income'     => $this->afip_gross_income,
                'afip_start_activity'   => $this->afip_start_activity,
                'afip_vat_condition'    => $this->afip_vat_condition,
                'afip_crt'              => $this->afip_crt,
                'afip_key'              => $this->afip_key,
                'afip_expiration'       => $this->afip_expiration,
                'afip_folder'           => $this->afip_folder,
                'afip_production'       => $this->afip_production,
                'afip_point_sale'       => $this->afip_point_sale,
                'afip_invoice_type'     => $this->afip_invoice_type,
                'store_id'              => $this->store_id,
            ];

        $sql    = generate_bind($sql, $params);
        $sql = $sql . " WHERE store_id=:store_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Trae los datos de la sucursal seleccionada */
    public function one()
    {
        $sql = "SELECT *
        FROM `stores`
        WHERE store_id=:store_id OR store_name=:store_id LIMIT 1";
        $params = ['store_id' => $this->store_id];

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Trae todas las sucursales */
    public function alls()
    {
        $sql = "SELECT * FROM `stores`";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function access_all()
    {
        $sql = "SELECT store_id,store_name FROM `stores`";
        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Trae los horarios de la sucursal */
    public function store_attention()
    {
        $sql = "SELECT store_open_am,store_close_am,store_open_pm,store_close_pm FROM `stores` 
        WHERE store_id=:store_id LIMIT 1";
        $params = ['store_id' => $this->store_id];

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**  Verifica si la sucursal esta abierta */
    public function store_open()
    {
        $sql = "SELECT * FROM `stores` WHERE store_id=:store_id AND store_open=1";
        $params = ['store_id' => $this->store_id];
        try {
            return (parent::query($sql, $params) ? true : false);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Trae todas las sucursales menos la seleccionada */
    public function not_all()
    {
        $sql = "SELECT * FROM `stores` WHERE store_id!=:store_id";
        $params = ['store_id' => $this->store_id];
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function afip_data()
    {
        $sql = "SELECT afip_cuit,afip_business,afip_gross_income,afip_start_activity,afip_vat_condition,afip_crt,afip_key,afip_folder,afip_production,afip_point_sale,afip_invoice_type 
        FROM `stores` 
        WHERE store_id=:store_id LIMIT 1";
        $params = ['store_id' => $this->store_id];

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
