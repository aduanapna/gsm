<?php

/**
 * Clase para generar QRS  - Requiere plugin qr - Ultima actualizacion 11/02/2024
 */
class _qrModel extends Model
{
    public $url_qr;

    public $size    = 6;
    public $level   = 'L';
    public $frame   = 3;


    public function create_qr($url_qr)
    {
        $qr_file    = generate_ref() . '.png';
        $qr         = FOLDER_QR . $qr_file;
        $size       = $this->size;
        $level      = $this->level;
        $frame      = $this->frame;
        try {
            Qrcode::png(
                $url_qr,
                $qr,
                $level,
                $size,
                $frame
            );
            return $qr_file;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
