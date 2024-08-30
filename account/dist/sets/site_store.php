<?php
/** Consulta sucursal */
function public_store($store_id)
{
    $stores             = new storesModel();
    $stores->store_id   = $store_id;
    $stores_data        = $stores->one();
    $store_return       = [];
    if ($stores_data != []) {
        $store_return['store_picture']      = IMAGES . $stores_data['store_picture'];
        $store_return['store_name']         = ucwords($stores_data['store_name']);
        $store_return['store_address']      = $stores_data['store_address'];
        $store_return['store_phonenumber']  = $stores_data['store_phonenumber'];
        $store_return['store_open']         = $stores_data['store_open'];
        $store_return['store_id']           = $stores_data['store_id'];
    }
    return $store_return;
}
/** Consulta locales de venta */
function public_stores()
{
    $stores         = new storesModel();
    $stores_data    = $stores->alls();
    $store_return   = [];
    if ($stores_data != []) {
        foreach ($stores_data as $ix_store => $vl_store) {
                $store_return[$ix_store]['store_picture']      = IMAGES . $vl_store['store_picture'];
                $store_return[$ix_store]['store_name']         = $vl_store['store_name'];
                $store_return[$ix_store]['store_address']      = $vl_store['store_address'];
                $store_return[$ix_store]['store_phonenumber']  = $vl_store['store_phonenumber'];
                $store_return[$ix_store]['store_open']         = $vl_store['store_open'];
                $store_return[$ix_store]['store_id']           = $vl_store['store_id'];
        }
    }
    return $store_return;
}