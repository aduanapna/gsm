<?php

/**
 * 
 */
class _excelModel extends Model

{
  public $array_header;
  public $array_data;
  public $name_file = null;


  function __construct()
  {
  }
  # ===== Globals
  function to_cvs()
  {
    $array_header = array($this->array_header);
    $array_data   = $this->array_data;
    $name_file    = $this->name_file;

    if ($this->name_file == null) {
      $name_file = rand(100);
    }
    $filename = $name_file . '.csv';
    $file     = FOLDER_FILES . $filename;

    try {
      # Crear un archivo temporal para guardar el contenido CSV
      $file = fopen($file, 'w');

      # Escribir los datos en el archivo CSV
      foreach ($array_header as $row_header) {
        fputcsv($file, $row_header,';');
      }

      foreach ($array_data as $row_data) {
        fputcsv($file, $row_data,';');
      }

      # Cerrar el archivo temporal
      fclose($file);

      return FILES . $filename;
    } catch (Exception $e) {
      return 'Error al generar archivo. Msg: ' . $e;
    }
  }
}
