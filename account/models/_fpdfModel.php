<?php

/* Modelo para generar FPDFS */

class _fpdfModel extends Model

{
    public $logo_picture    = null;
    public $store_data      = [];
    public $person_data     = [];
    public $articles_data   = [];
    public $totals_data     = [];
    public $message_data    = [];

    public $data;
    # Globals
    function invoce($pdf_output = null)
    {
        header('Content-Type: text/html; charset=utf-8');

        #$d              = $this->example_invoce();
        $d              = $this->data;
        $fpdf           = new FPDF('P', 'mm', 'A4');
        $fpdf->AddPage();

        $fpdf->AddFont('core', '', 'core35.php');
        $fpdf->AddFont('coreb', '', 'core65b.php');
        $page_width     = $fpdf->GetPageWidth();

        if ($d['store_picture'] != '') {
            $posXLogo = $page_width - 35;
            $fpdf->Image($d['store_picture'], $posXLogo, 10, 30);
        }
        $fpdf->SetFont('coreb', '', 18);
        $fpdf->SetTextColor(73, 80, 87);
        $fpdf->Cell(65, 10, $d['afip_business'], 0, 0, 'L');
        $fpdf->SetFont('coreb', '', 18);
        $fpdf->Cell(65, 5, $d['bill_type'], 0, 0, 'C');
        #
        $fpdf->Ln();
        $fpdf->SetFont('coreb', '', 8);
        $fpdf->Cell(65, 10, '', 0, 0);
        $fpdf->Cell(65, 5, $d['bill_code'], 0, 0, 'C');
        #
        $fpdf->Ln(10);
        $fpdf->SetFont('core', '', 10);
        $fpdf->Cell(30, 5, 'CUIT:', 0, 0, 'R');
        $fpdf->Cell(60, 5, $d['afip_cuit'], 0, 0, 'L');
        $fpdf->Cell(105, 5, $d['store_address'], 0, 0, 'R');
        #
        $fpdf->Ln();
        $fpdf->SetFont('core', '', 10);
        $fpdf->Cell(30, 5, 'IB:', 0, 0, 'R');
        $fpdf->Cell(60, 5, $d['afip_gross_income'], 0, 0, 'L');
        $fpdf->Cell(105, 5, $d['store_email'], 0, 0, 'R');
        #
        $fpdf->Ln();
        $fpdf->SetFont('core', '', 10);
        $fpdf->Cell(30, 5, 'Inicio Actividad:', 0, 0, 'R');
        $fpdf->Cell(60, 5, $d['afip_start_activity'], 0, 0, 'L');
        $fpdf->Cell(105, 5, $d['store_web'], 0, 0, 'R');
        #
        $fpdf->Ln();
        $fpdf->SetFont('core', '', 10);
        $fpdf->Cell(30, 5, 'Comprobante Nro:', 0, 0, 'R');
        $fpdf->Cell(60, 5, $d['bill_number'], 0, 0, 'L');
        $fpdf->Cell(105, 5, $d['store_phonenumber'], 0, 0, 'R');
        #
        $fpdf->Ln();
        $fpdf->SetFont('core', '', 10);
        $fpdf->Cell(30, 5, 'Comprobante Fecha:', 0, 0, 'R');
        $fpdf->Cell(60, 5, $d['bill_date'], 0, 0, 'L');
        #
        $fpdf->Ln();
        $y = $fpdf->GetY();
        $x = $page_width - 5;
        $fpdf->Line(10, $y, $x, $y);
        #
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->Cell(97, 5, 'DATOS PROFESIONAL', 0, 0);
        #$fpdf->Cell(97, 5, 'TOTAL A PAGAR', 0, 0, 'R');
        #
        $fpdf->Ln();
        $fpdf->SetFont('core', '', 10);
        $fpdf->Cell(97, 5, iconv('UTF-8', 'windows-1252', $d['person_name'] . ' ' . $d['person_lastname']), 0, 0);
        #$fpdf->SetFont('coreb', '', 24);
        #$fpdf->Cell(97, 10, '$' . $d['bill_total'], 0, 0, 'R');
        #
        $fpdf->SetY($fpdf->GetY() + 5); # Toma el valor actual y le suma la celda de nombre
        $fpdf->SetFont('core', '', 10);
        $fpdf->Cell(97, 5, iconv('UTF-8', 'windows-1252', $d['person_taxpayer']), 0, 0);
        $fpdf->Ln();
        $fpdf->Cell(97, 5, $d['person_cuit'], 0, 0);
        #$fpdf->Cell(97, 5, $d['bill_subnote'], 0, 0, 'R');
        $fpdf->Ln();
        #
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->SetFillColor(243, 246, 249);
        $fpdf->Cell(10, 5, '#', 0, 0, 'C', true);
        $fpdf->Cell(90, 5, 'Detalle', 0, 0, 'C', true);
        $fpdf->Cell(25, 5, 'Unitario', 0, 0, 'C', true);
        $fpdf->Cell(20, 5, 'Cantidad', 0, 0, 'C', true);
        $fpdf->Cell(20, 5, 'Iva', 0, 0, 'C', true);
        $fpdf->Cell(30, 5, 'Subtotal', 0, 0, 'C', true);
        #
        $fpdf->SetFont('core', '', 10);
        foreach ($d['bill_items'] as $ix_items => $vl_item) {
            $fpdf->Ln();
            $fpdf->Cell(10, 5, $ix_items + 1, 0, 0, 'C');
            $fpdf->Cell(90, 5, iconv('UTF-8', 'windows-1252', $vl_item['details']), 0, 0, 'L');
            $fpdf->Cell(25, 5, '$' . $vl_item['unit_price'], 0, 0, 'R');
            $fpdf->Cell(20, 5, $vl_item['count'], 0, 0, 'C');
            $fpdf->Cell(20, 5, $vl_item['unit_tax'], 0, 0, 'C');
            $fpdf->Cell(30, 5, '$' . $vl_item['unit_total'], 0, 0, 'R');
            if ($vl_item['note'] != '') {
                $fpdf->Ln();
                $fpdf->Cell(10, 5, '', 0, 0, 'C');
                $fpdf->Cell(90, 5, iconv('UTF-8', 'windows-1252', $vl_item['note']), 0, 0, 'L');
            }
        }
        #
        $fpdf->Ln(10);
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->SetX(130);
        $fpdf->Cell(40, 5, 'Subtotal: ', 0, 0, 'L');
        $fpdf->Cell(30, 5, '$' . $d['bill_subtotal'], 0, 0, 'R');
        #
        $fpdf->Ln();
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->SetX(130);
        $fpdf->Cell(40, 5, 'Iva 21%: ', 0, 0, 'L');
        $fpdf->Cell(30, 5, '$' . $d['bill_tax'], 0, 0, 'R');
        $fpdf->Ln();
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->SetX(130);
        $fpdf->Cell(40, 5, 'Exento: ', 0, 0, 'L');
        $fpdf->Cell(30, 5, '$' . $d['bill_exempt'], 0, 0, 'R');
        #
        /* $fpdf->Ln();
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->SetX(130);
        $fpdf->Cell(40, 5, 'Exento: ', 0, 0, 'L');
        $fpdf->Cell(30, 5, '$' . $d['bill_extax'], 0, 0, 'R');
        #
        $fpdf->Ln();
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->SetX(130);
        $fpdf->Cell(40, 5, 'Descuento %: ', 0, 0, 'L');
        $fpdf->Cell(30, 5, '$' . $d['bill_discount'], 0, 0, 'R'); */
        #
        $fpdf->Ln();
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->SetX(130);
        $fpdf->Cell(40, 5, 'Total a Pagar: ', 0, 0, 'L');
        $fpdf->Cell(30, 5, '$' . $d['bill_total'], 0, 0, 'R');
        #
        if ($d['bill_note'] != '') {
            $fpdf->Ln(10);
            $fpdf->SetFont('core', '', 10);
            $fpdf->SetFillColor(234, 250, 252);
            $fpdf->Cell(197, 10, $d['bill_note'], 0, 0, 'L', true);
        }
        if ($d['bill_note2'] != '') {
            $fpdf->Ln(10);
            $fpdf->SetFont('core', '', 6);
            $fpdf->SetFillColor(255, 255, 255);
            $fpdf->Cell(197, 10, $d['bill_note2'], 0, 0, 'L', true);
        }
        #
        if ($d['bill_qr'] != '') {
            $y = $fpdf->GetPageHeight() - 50;
            $fpdf->Image($d['bill_qr'], 10, $y, 30);
            $fpdf->Image($d['bill_logo'], 40, $y, 40);
            $fpdf->SetY($y);
            $fpdf->SetX(130);
            $fpdf->SetFont('coreb', '', 10);
            $fpdf->Cell(70, 10, 'CAE: ' . $d['bill_cae'], 0, 0, 'R');
            $fpdf->Ln(5);
            $fpdf->SetX(130);
            $fpdf->Cell(70, 10, 'Vencimiento: ' . $d['bill_expiration'], 0, 0, 'R');
            $fpdf->SetY($y + 10);
            $fpdf->SetX(40);
            $fpdf->SetFont('coreb', '', 12);
            $fpdf->Cell(70, 10, 'Comprobante Autorizado', 0, 0, 'L');
            $fpdf->SetY($y + 15);
            $fpdf->SetX(40);
            $fpdf->SetFont('core', '', 8);
            $fpdf->Cell(50, 10,  iconv('UTF-8', 'windows-1252', 'Esta Administración Federal no se responsabiliza por los datos ingresados en el detalle de la operación'), 0, 0, 'L');
        }
        if ($pdf_output != null) {
            $fpdf->Output($pdf_output, "F");
        } else {
            $fpdf->Output();
        }
    }
    /* function buy_book($pdf_output = null)
    {
        header('Content-Type: text/html; charset=utf-8');

        $d              = $this->data;
        $fpdf           = new FPDF('l', 'mm', 'A4');
        $fpdf->AddPage();

        $fpdf->AddFont('core', '', 'core35.php');
        $fpdf->AddFont('coreb', '', 'core65b.php');
        $page_width     = $fpdf->GetPageWidth();

        # Incrustamos el logo de la firma
        if ($d['store_picture'] != '') {
            $posXLogo = $page_width - 35;
            $fpdf->Image($d['store_picture'], $posXLogo, 10, 30);
        }
        # Titulo
        $fpdf->SetFont('coreb', '', 16);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(65, 10, 'Libro Iva - Compras', 0, 0, 'L');
        $fpdf->Ln();
        $fpdf->Ln();
        # Primera linea
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetFillColor(243, 246, 249);
        $fpdf->Cell(60, 5, 'Detalle', 1, 0, 'L', true);
        $fpdf->SetFont('coreb', '', 6);
        $fpdf->Cell(15, 5, 'No Gravado', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Grav. 10.5%', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Grav. 21%', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Grav. 27%', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Iva 10.5%', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Iva 21%', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Iva 27%', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Exento', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'SIRCREB', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Monotributo', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Perc. Iva', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Ing Bruto', 1, 0, 'C', true);
        $fpdf->Cell(15, 5, 'Otros Trib', 1, 0, 'C', true);
        $fpdf->Cell(20, 5, 'Totales', 1, 0, 'C', true);


        $fpdf->Ln();
        foreach ($d['book'] as $book) {
            $fpdf->Ln();
            $fpdf->SetFont('core', '', 10);
            $fpdf->Cell(60, 5, $book['taxbook_person_name'], 1, 0, 'L');
            $fpdf->SetFont('core', '', 8);
            $fpdf->Cell(15, 5, '$' . $book['taxbook_not_taxed'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_taxed105'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_taxed21'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_taxed27'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_vat105'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_vat21'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_vat27'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_exempt'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_sircreb'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_monotribute'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_vat_perception'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_gross_income'], 1, 0, 'C');
            $fpdf->Cell(15, 5, '$' . $book['taxbook_other_taxes'], 1, 0, 'C');
            $fpdf->Cell(20, 5, '$' . $book['taxbook_totals'], 1, 0, 'C');
            $fpdf->Ln();
            $fpdf->SetFont('core', '', 10);
            $fpdf->Cell(60, 5, $book['box_concept_name'], 1, 0, 'L');
            $fpdf->Cell(45, 5, 'F' . $book['taxbook_letter'] . ' Nro: ' . str_pad($book['taxbook_point'], 4, "0", STR_PAD_LEFT) . '-' . str_pad($book['taxbook_number'], 8, "0", STR_PAD_LEFT), 1, 0, 'L');
            $fpdf->Cell(45, 5, $book['taxbook_sub_category'], 1, 0, 'L');
            $fpdf->Cell(125, 5, 'Observacion: ' . $book['taxbook_observations'], 1, 0, 'L');
            $fpdf->Ln();
        }

        if ($pdf_output != null) {
            $fpdf->Output($pdf_output, "F");
        } else {
            $fpdf->Output();
        }
    } */
    function book($pdf_output = null)
    {
        header('Content-Type: text/html; charset=utf-8');

        $d              = $this->data;
        $fpdf           = new FPDF('l', 'mm', 'A4');
        $fpdf->AddPage();

        $fpdf->AddFont('core', '', 'core35.php');
        $fpdf->AddFont('coreb', '', 'core65b.php');
        $page_width     = $fpdf->GetPageWidth();

        # Incrustamos el logo de la firma
        if ($d['store_picture'] != '') {
            $posXLogo = $page_width - 35;
            $fpdf->Image($d['store_picture'], $posXLogo, 10, 30);
        }
        # Titulo
        $fpdf->SetX(5);
        $fpdf->SetFont('coreb', '', 16);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(65, 10, 'Libro Iva - Compras', 0, 0, 'L');
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->Cell(65, 10, 'Periodo: ' . $d['taxbook_date_from'] . ' - ' . $d['taxbook_date_end'], 0, 0, 'L');
        $fpdf->Ln();
        $fpdf->Ln();
        # Primera linea
        $fpdf->SetX(5);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetFillColor(243, 246, 249);
        $fpdf->SetFont('coreb', '', 6);
        $fpdf->Cell(14, 5, 'Fecha', 0, 0, 'C', true);
        $fpdf->Cell(5, 5, 'Tipo', 0, 0, 'C', true);
        $fpdf->Cell(2, 5, 'L', 0, 0, 'C', true);
        $fpdf->Cell(19, 5, 'Nro', 0, 0, 'C', true);
        $fpdf->Cell(29, 5, 'Proveedores', 0, 0, 'C', true);
        $fpdf->Cell(5, 5, 'IVA', 0, 0, 'C', true);
        $fpdf->Cell(15, 5, 'CUIT', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'No Gravado', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Grav. 10.5%', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Grav. 21%', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Grav. 27%', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Iva 10.5%', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Iva 21%', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Iva 27%', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Exento', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'SIRCREB', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Monotributo', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Perc. Iva', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Ing Bruto', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Otros Trib', 0, 0, 'C', true);
        $fpdf->Cell(14, 5, 'Totales', 0, 0, 'C', true);



        foreach ($d['book'] as $book) {
            $fpdf->Ln();
            $fpdf->SetX(5);
            $fpdf->SetFont('core', '', 6);
            $fpdf->SetFillColor(243, 246, 249);
            $fpdf->Cell(14, 5, $book['taxbook_date'], 0, 0, 'C');
            $fpdf->Cell(5, 5, 'FC', 0, 0, 'C');
            $fpdf->Cell(2, 5, $book['taxbook_letter'], 0, 0, 'C');
            $fpdf->Cell(19, 5, $book['taxbook_point'] .'-'.$book['taxbook_number'], 0, 0, 'C');
            $fpdf->Cell(29, 5, $book['taxbook_person_name'], 0, 0, 'L');
            $fpdf->Cell(5, 5, 'RI', 0, 0, 'C');
            $fpdf->Cell(15, 5, $book['taxbook_person_cuit'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_not_taxed'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_taxed105'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_taxed21'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_taxed27'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_vat105'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_vat21'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_vat27'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_exempt'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_sircreb'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_monotribute'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_vat_perception'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_gross_income'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_other_taxes'], 0, 0, 'C');
            $fpdf->Cell(14, 5, '$ ' . $book['taxbook_totals'], 0, 0, 'C');
        }

        # Totales
        $totals = $d['book_totals'];
        $fpdf->Ln();
        $fpdf->SetX(5);
        $fpdf->SetFont('coreb', '', 6);
        $fpdf->Cell(89, 5, 'TOTALES', 0, 0, 'R', true);
        $fpdf->SetFont('core', '', 6);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_not_taxed'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_taxed105'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_taxed21'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_taxed27'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_vat105'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_vat21'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_vat27'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_exempt'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_sircreb'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_monotribute'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_vat_perception'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_gross_income'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_other_taxes'], 0, 0, 'C', true);
        $fpdf->Cell(14, 5, '$ ' . $totals['taxbook_totals'], 0, 0, 'C', true);

        $fpdf->AddPage();

        # Incrustamos el logo de la firma
        if ($d['store_picture'] != '') {
            $posXLogo = $page_width - 35;
            $fpdf->Image($d['store_picture'], $posXLogo, 10, 30);
        }
        # Titulo
        $fpdf->SetX(5);
        $fpdf->SetFont('coreb', '', 16);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(65, 10, 'Libro Iva - Compras', 0, 0, 'L');
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->Cell(65, 10, 'Periodo: ' . $d['taxbook_date_from'] . ' - ' . $d['taxbook_date_from'], 0, 0, 'L');
        $fpdf->Ln();
        $fpdf->Ln();
        # Primera linea
        $fpdf->SetX(5);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetFillColor(243, 246, 249);
        $fpdf->SetFont('coreb', '', 7);
        $fpdf->Cell(33, 5, 'Tipos Comprobante', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'No Gravado', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Grav. 10.5%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Grav. 21%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Grav. 27%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Iva 10.5%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Iva 21%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Iva 27%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Exento', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'SIRCREB', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Monotributo', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Perc. Iva', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Ing Bruto', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Otros Trib', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Totales', 0, 0, 'C', true);

        $array = array(
            array(
                'facturas'                  => 'Facturas',
                'taxbook_not_taxed'         => '0.00',
                'taxbook_taxed105'          => '0.00',
                'taxbook_taxed21'           => '7.500',
                'taxbook_taxed27'           => '0.00',
                'taxbook_vat105'            => '0.00',
                'taxbook_vat21'             => '1575',
                'taxbook_vat27'             => '0.00',
                'taxbook_exempt'            => '0.00',
                'taxbook_sircreb'           => '0.00',
                'taxbook_monotribute'       => '0.00',
                'taxbook_vat_perception'    => '0.00',
                'taxbook_gross_income'      => '0.00',
                'taxbook_other_taxes'       => '0.00',
                'taxbook_totals'            => '9075',
            ),
            array(
                'facturas'                  => 'Recibos',
                'taxbook_not_taxed'         => '0.00',
                'taxbook_taxed105'          => '0.00',
                'taxbook_taxed21'           => '7.500',
                'taxbook_taxed27'           => '0.00',
                'taxbook_vat105'            => '0.00',
                'taxbook_vat21'             => '1575',
                'taxbook_vat27'             => '0.00',
                'taxbook_exempt'            => '0.00',
                'taxbook_sircreb'           => '0.00',
                'taxbook_monotribute'       => '0.00',
                'taxbook_vat_perception'    => '0.00',
                'taxbook_gross_income'      => '0.00',
                'taxbook_other_taxes'       => '0.00',
                'taxbook_totals'            => '9075',
            ),
        );

        foreach ($array as $book) {
            $fpdf->Ln();
            $fpdf->SetX(5);
            $fpdf->SetFont('core', '', 7);
            $fpdf->SetFillColor(243, 246, 249);
            $fpdf->Cell(33, 5, $book['facturas'], 0, 0, 'C', true);
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_not_taxed'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed105'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed21'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed27'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat105'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat21'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat27'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_exempt'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_sircreb'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_monotribute'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat_perception'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_gross_income'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_other_taxes'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_totals'], 0, 0, 'C');
        }
        $totals =
            [
                'taxbook_not_taxed'         => '0.00',
                'taxbook_taxed105'          => '0.00',
                'taxbook_taxed21'           => '7.500',
                'taxbook_taxed27'           => '0.00',
                'taxbook_vat105'            => '0.00',
                'taxbook_vat21'             => '1575',
                'taxbook_vat27'             => '0.00',
                'taxbook_exempt'            => '0.00',
                'taxbook_sircreb'           => '0.00',
                'taxbook_monotribute'       => '0.00',
                'taxbook_vat_perception'    => '0.00',
                'taxbook_gross_income'      => '0.00',
                'taxbook_other_taxes'       => '0.00',
                'taxbook_totals'            => '9075',
            ];
        # Totales
        $fpdf->Ln();
        $fpdf->SetX(5);
        $fpdf->SetFont('coreb', '', 6);
        $fpdf->Cell(33, 5, 'TOTALES', 0, 0, 'R', true);
        $fpdf->SetFont('core', '', 6);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_not_taxed'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed105'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed21'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed27'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat105'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat21'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat27'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_exempt'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_sircreb'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_monotribute'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat_perception'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_gross_income'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_other_taxes'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_totals'], 0, 0, 'C', true);


        if ($pdf_output != null) {
            $fpdf->Output($pdf_output, "F");
        } else {
            $fpdf->Output();
        }
    }
    function sales($pdf_output = null)
    {
        header('Content-Type: text/html; charset=utf-8');

        $d              = $this->data;
        $fpdf           = new FPDF('l', 'mm', 'A4');
        $fpdf->AddPage();

        $fpdf->AddFont('core', '', 'core35.php');
        $fpdf->AddFont('coreb', '', 'core65b.php');
        $page_width     = $fpdf->GetPageWidth();

        # Incrustamos el logo de la firma
        if ($d['store_picture'] != '') {
            $posXLogo = $page_width - 35;
            $fpdf->Image($d['store_picture'], $posXLogo, 10, 30);
        }
        # Titulo
        $fpdf->SetX(5);
        $fpdf->SetFont('coreb', '', 16);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(65, 10, 'Libro Iva - Ventas', 0, 0, 'L');
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->Cell(65, 10, 'Periodo: ' . $d['taxbook_date_from'] . ' - ' . $d['taxbook_date_end'], 0, 0, 'L');
        $fpdf->Ln();
        $fpdf->Ln();
        # Primera linea
        $fpdf->SetX(5);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetFillColor(243, 246, 249);
        $fpdf->SetFont('coreb', '', 6);
        $fpdf->Cell(14, 5, 'Fecha', 0, 0, 'C', true);
        $fpdf->Cell(5, 5, 'Tipo', 0, 0, 'C', true);
        $fpdf->Cell(2, 5, 'L', 0, 0, 'C', true);
        $fpdf->Cell(19, 5, 'Nro', 0, 0, 'C', true);
        $fpdf->Cell(29, 5, 'Razon Social', 0, 0, 'C', true);
        $fpdf->Cell(5, 5, 'IVA', 0, 0, 'C', true);
        $fpdf->Cell(15, 5, 'CUIT', 0, 0, 'C', true);
        $fpdf->Cell(28, 5, 'No Gravado', 0, 0, 'C', true);
        $fpdf->Cell(28, 5, 'Grav. 10.5%', 0, 0, 'C', true);
        $fpdf->Cell(28, 5, 'Grav. 21%', 0, 0, 'C', true);
        $fpdf->Cell(28, 5, 'Iva 10.5%', 0, 0, 'C', true);
        $fpdf->Cell(28, 5, 'Iva 21%', 0, 0, 'C', true);
        $fpdf->Cell(28, 5, 'Exento', 0, 0, 'C', true);
        $fpdf->Cell(28, 5, 'Totales', 0, 0, 'C', true);



        foreach ($d['book'] as $book) {
            $fpdf->Ln();
            $fpdf->SetX(5);
            $fpdf->SetFont('core', '', 6);
            $fpdf->SetFillColor(243, 246, 249);
            $fpdf->Cell(14, 5, $book['taxbook_date'], 0, 0, 'C');
            $fpdf->Cell(5, 5, 'FC', 0, 0, 'C');
            $fpdf->Cell(2, 5, $book['taxbook_letter'], 0, 0, 'C');
            $fpdf->Cell(19, 5, $book['taxbook_point'] .'-'.$book['taxbook_number'], 0, 0, 'C');
            $fpdf->Cell(29, 5, $book['person_lastname'].' '.$book['person_name'], 0, 0, 'L');
            $fpdf->Cell(5, 5, 'RI', 0, 0, 'C');
            $fpdf->Cell(15, 5, $book['taxbook_person_cuit'], 0, 0, 'R');
            $fpdf->Cell(28, 5, '$ ' . $book['taxbook_not_taxed'], 0, 0, 'R');
            $fpdf->Cell(28, 5, '$ ' . $book['taxbook_taxed105'], 0, 0, 'R');
            $fpdf->Cell(28, 5, '$ ' . $book['taxbook_taxed21'], 0, 0, 'R');
            $fpdf->Cell(28, 5, '$ ' . $book['taxbook_vat105'], 0, 0, 'R');
            $fpdf->Cell(28, 5, '$ ' . $book['taxbook_vat21'], 0, 0, 'R');
            $fpdf->Cell(28, 5, '$ ' . $book['taxbook_exempt'], 0, 0, 'R');
            $fpdf->Cell(28, 5, '$ ' . $book['taxbook_totals'], 0, 0, 'R');
        }

        # Totales
        /* $totals = $d['book_totals'];
        $fpdf->Ln();
        $fpdf->SetX(5);
        $fpdf->SetFont('coreb', '', 6);
        $fpdf->Cell(89, 5, 'TOTALES', 0, 0, 'R', true);
        $fpdf->SetFont('core', '', 6);
        $fpdf->Cell(28, 5, '$ ' . $totals['taxbook_not_taxed'], 0, 0, 'R', true);
        $fpdf->Cell(28, 5, '$ ' . $totals['taxbook_taxed105'], 0, 0, 'R', true);
        $fpdf->Cell(28, 5, '$ ' . $totals['taxbook_taxed21'], 0, 0, 'R', true);
        $fpdf->Cell(28, 5, '$ ' . $totals['taxbook_vat105'], 0, 0, 'R', true);
        $fpdf->Cell(28, 5, '$ ' . $totals['taxbook_vat21'], 0, 0, 'R', true);
        $fpdf->Cell(28, 5, '$ ' . $totals['taxbook_exempt'], 0, 0, 'R',true);
        $fpdf->Cell(28, 5, '$ ' . $totals['taxbook_totals'], 0, 0, 'R', true);

        $fpdf->AddPage(); */

        # Incrustamos el logo de la firma
        /* if ($d['store_picture'] != '') {
            $posXLogo = $page_width - 35;
            $fpdf->Image($d['store_picture'], $posXLogo, 10, 30);
        }
        # Titulo
        $fpdf->SetX(5);
        $fpdf->SetFont('coreb', '', 16);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(65, 10, 'Libro Iva - Compras', 0, 0, 'L');
        $fpdf->SetFont('coreb', '', 10);
        $fpdf->Cell(65, 10, 'Periodo: ' . $d['taxbook_date_from'] . ' - ' . $d['taxbook_date_from'], 0, 0, 'L');
        $fpdf->Ln();
        $fpdf->Ln();
        # Primera linea
        $fpdf->SetX(5);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetFillColor(243, 246, 249);
        $fpdf->SetFont('coreb', '', 7);
        $fpdf->Cell(33, 5, 'Tipos Comprobante', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'No Gravado', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Grav. 10.5%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Grav. 21%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Iva 10.5%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Iva 21%', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Exento', 0, 0, 'C', true);
        $fpdf->Cell(18, 5, 'Totales', 0, 0, 'C', true);

        $array = array(
            array(
                'facturas'                  => 'Facturas',
                'taxbook_not_taxed'         => '0.00',
                'taxbook_taxed105'          => '0.00',
                'taxbook_taxed21'           => '7.500',
                'taxbook_taxed27'           => '0.00',
                'taxbook_vat105'            => '0.00',
                'taxbook_vat21'             => '1575',
                'taxbook_vat27'             => '0.00',
                'taxbook_exempt'            => '0.00',
                'taxbook_totals'            => '9075',
            ),
            array(
                'facturas'                  => 'Recibos',
                'taxbook_not_taxed'         => '0.00',
                'taxbook_taxed105'          => '0.00',
                'taxbook_taxed21'           => '7.500',
                'taxbook_taxed27'           => '0.00',
                'taxbook_vat105'            => '0.00',
                'taxbook_vat21'             => '1575',
                'taxbook_vat27'             => '0.00',
                'taxbook_totals'            => '9075',
            ),
        );

        foreach ($array as $book) {
            $fpdf->Ln();
            $fpdf->SetX(5);
            $fpdf->SetFont('core', '', 7);
            $fpdf->SetFillColor(243, 246, 249);
            $fpdf->Cell(33, 5, $book['facturas'], 0, 0, 'C', true);
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_not_taxed'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed105'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed21'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed27'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat105'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat21'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat27'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_exempt'], 0, 0, 'C');
            $fpdf->Cell(18, 5, '$ ' . $book['taxbook_totals'], 0, 0, 'C');
        }
        $totals =
            [
                'taxbook_not_taxed'         => '0.00',
                'taxbook_taxed105'          => '0.00',
                'taxbook_taxed21'           => '7.500',
                'taxbook_taxed27'           => '0.00',
                'taxbook_vat105'            => '0.00',
                'taxbook_vat21'             => '1575',
                'taxbook_vat27'             => '0.00',
                'taxbook_exempt'            => '0.00',
                'taxbook_totals'            => '9075',
            ];
        # Totales
        $fpdf->Ln();
        $fpdf->SetX(5);
        $fpdf->SetFont('coreb', '', 6);
        $fpdf->Cell(33, 5, 'TOTALES', 0, 0, 'R', true);
        $fpdf->SetFont('core', '', 6);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_not_taxed'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed105'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed21'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_taxed27'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat105'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat21'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_vat27'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_exempt'], 0, 0, 'C', true);
        $fpdf->Cell(18, 5, '$ ' . $book['taxbook_totals'], 0, 0, 'C', true); */


        if ($pdf_output != null) {
            $fpdf->Output($pdf_output, "F");
        } else {
            $fpdf->Output();
        }
    }
    # Privates

    # Protects 
    protected function example_invoce()
    {
        $invoce =
            [
                'afip_cuit'             => '30-71654109-2',
                'afip_business'         => 'Instituto Alear SRL',
                'afip_gross_income'     => '30-71654109/2',
                'afip_start_activity'   => '01/06/2019',
                'store_picture'         => 'http://localhost/alear/account/dist/assets/images/logo.png',
                'store_address'         => 'La Rioja 429, E3100 Parana, Entre Rios',
                'store_email'           => 'institutoalear@gmail.com',
                'store_web'             => 'https://institutoalear.ar',
                'store_phonenumber'     => '+549 3434462423',


                'person_name'           => 'Julieta Micaela',
                'person_lastname'       => 'Fratoni',
                'person_taxpayer'       => 'Monotributista',
                'person_cuit'           => '27-39042011-4',

                'bill_type'             => 'A',
                'bill_code'             => '(cod 05)',
                'bill_number'           => '0001-12345678',
                'bill_date'             => '06/12/2023',
                'bill_cae'              => '51651561654313',
                'bill_expiration'       => '31/12/2023',
                'bill_qr'               => 'http://localhost/alear/account/dist/assets/images/qr_example.png',
                'bill_logo'             => 'http://localhost/alear/account/dist/assets/images/afip.png',
                'bill_note'             => 'Recuerde que la fecha de vencimiento es limite de pago',
                'bill_subtotal'         => 15173.55,
                'bill_tax'              => 3186.45,
                'bill_extax'            => 0,
                'bill_discount'         => 0,
                'bill_total'            => 18360,
                'bill_subnote'          => 'Vencimiento: 10/12/2023',
                'bill_items'            =>
                [
                    [
                        'count'         => 1,
                        'details'       => 'Módulo de 28 horas semanales (112 mensuales)',
                        'note'          => 'Horas Extra: $0.00',
                        'unit_price'    => 18360,
                        'unit_tax'      => '21%',
                        'unit_total'    => 18360,
                    ],
                    [
                        'count'         => 1,
                        'details'       => 'Aporte para internet',
                        'note'          => '',
                        'unit_price'    => 232,
                        'unit_tax'      => '21%',
                        'unit_total'    => 232,
                    ],
                ],
            ];
        return $invoce;
    }
}
