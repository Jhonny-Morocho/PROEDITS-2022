<?php
namespace App\Traits;

use App\Models\ClienteMembresia;
use App\Models\Membresia;
use App\Models\Pedido;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;
trait TemplateCorreo {
    //productos cuando compra productos de la tienda
    public static function templateFacturaPaypalProductos($idFactura){
        $tabla="";
        $total=0.0;
        $pedido=Pedido::where('id_transaccion',$idFactura)->first();
        //convierto el string a objeto
        $factura=json_decode($pedido->form_factura, true);
        $productos=$factura['productos'];


        foreach ($productos as $key => $value) {
          $precioUnitario=$value['precio'];
          $total=$value['precio']+$total;
          $precioUnitario=sprintf("%.2f", $precioUnitario);

                $tabla.='<tr>
                <th
                    style="line-height: 24px;
                    font-size: 16px; margin: 0;"
                    align="left">
                    '.$value['id'].'
                </th>
                <td
                    style="border-spacing: 0px;
                    border-collapse: collapse;
                    line-height: 24px;
                    font-size: 16px;
                    border-top-width: 1px;
                    border-top-color: #dee2e6;
                    border-top-style: solid;
                    margin: 0; padding: 12px;"
                    align="left"
                    valign="top">

                    '.$value['url_directorio'].'

                </td>
                <td
                    style="border-spacing: 0px;
                    border-collapse: collapse;
                    line-height: 24px;
                    font-size: 16px;
                    border-top-width: 1px;
                    border-top-color: #dee2e6;
                    border-top-style: solid;
                    margin: 0;
                    padding: 12px;"
                    align="left"
                    valign="top">

                    $'.$precioUnitario.'
                </td>


              </tr>';
        }

        $header='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
              <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

                <style type="text/css">
                  .ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:150%}a{text-decoration:none}body,td,input,textarea,select{margin:unset;font-family:unset}input,textarea,select{font-size:unset}@media screen and (max-width: 600px){table.row th.col-lg-1,table.row th.col-lg-2,table.row th.col-lg-3,table.row th.col-lg-4,table.row th.col-lg-5,table.row th.col-lg-6,table.row th.col-lg-7,table.row th.col-lg-8,table.row th.col-lg-9,table.row th.col-lg-10,table.row th.col-lg-11,table.row th.col-lg-12{display:block;width:100% !important}.d-mobile{display:block !important}.d-desktop{display:none !important}.w-lg-25{width:auto !important}.w-lg-25>tbody>tr>td{width:auto !important}.w-lg-50{width:auto !important}.w-lg-50>tbody>tr>td{width:auto !important}.w-lg-75{width:auto !important}.w-lg-75>tbody>tr>td{width:auto !important}.w-lg-100{width:auto !important}.w-lg-100>tbody>tr>td{width:auto !important}.w-lg-auto{width:auto !important}.w-lg-auto>tbody>tr>td{width:auto !important}.w-25{width:25% !important}.w-25>tbody>tr>td{width:25% !important}.w-50{width:50% !important}.w-50>tbody>tr>td{width:50% !important}.w-75{width:75% !important}.w-75>tbody>tr>td{width:75% !important}.w-100{width:100% !important}.w-100>tbody>tr>td{width:100% !important}.w-auto{width:auto !important}.w-auto>tbody>tr>td{width:auto !important}.p-lg-0>tbody>tr>td{padding:0 !important}.pt-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-top:0 !important}.pr-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-right:0 !important}.pb-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-bottom:0 !important}.pl-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-left:0 !important}.p-lg-1>tbody>tr>td{padding:0 !important}.pt-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-top:0 !important}.pr-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-right:0 !important}.pb-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-bottom:0 !important}.pl-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-left:0 !important}.p-lg-2>tbody>tr>td{padding:0 !important}.pt-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-top:0 !important}.pr-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-right:0 !important}.pb-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-bottom:0 !important}.pl-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-left:0 !important}.p-lg-3>tbody>tr>td{padding:0 !important}.pt-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-top:0 !important}.pr-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-right:0 !important}.pb-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-bottom:0 !important}.pl-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-left:0 !important}.p-lg-4>tbody>tr>td{padding:0 !important}.pt-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-top:0 !important}.pr-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-right:0 !important}.pb-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-bottom:0 !important}.pl-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-left:0 !important}.p-lg-5>tbody>tr>td{padding:0 !important}.pt-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-top:0 !important}.pr-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-right:0 !important}.pb-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-bottom:0 !important}.pl-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-left:0 !important}.p-0>tbody>tr>td{padding:0 !important}.pt-0>tbody>tr>td,.py-0>tbody>tr>td{padding-top:0 !important}.pr-0>tbody>tr>td,.px-0>tbody>tr>td{padding-right:0 !important}.pb-0>tbody>tr>td,.py-0>tbody>tr>td{padding-bottom:0 !important}.pl-0>tbody>tr>td,.px-0>tbody>tr>td{padding-left:0 !important}.p-1>tbody>tr>td{padding:4px !important}.pt-1>tbody>tr>td,.py-1>tbody>tr>td{padding-top:4px !important}.pr-1>tbody>tr>td,.px-1>tbody>tr>td{padding-right:4px !important}.pb-1>tbody>tr>td,.py-1>tbody>tr>td{padding-bottom:4px !important}.pl-1>tbody>tr>td,.px-1>tbody>tr>td{padding-left:4px !important}.p-2>tbody>tr>td{padding:8px !important}.pt-2>tbody>tr>td,.py-2>tbody>tr>td{padding-top:8px !important}.pr-2>tbody>tr>td,.px-2>tbody>tr>td{padding-right:8px !important}.pb-2>tbody>tr>td,.py-2>tbody>tr>td{padding-bottom:8px !important}.pl-2>tbody>tr>td,.px-2>tbody>tr>td{padding-left:8px !important}.p-3>tbody>tr>td{padding:16px !important}.pt-3>tbody>tr>td,.py-3>tbody>tr>td{padding-top:16px !important}.pr-3>tbody>tr>td,.px-3>tbody>tr>td{padding-right:16px !important}.pb-3>tbody>tr>td,.py-3>tbody>tr>td{padding-bottom:16px !important}.pl-3>tbody>tr>td,.px-3>tbody>tr>td{padding-left:16px !important}.p-4>tbody>tr>td{padding:24px !important}.pt-4>tbody>tr>td,.py-4>tbody>tr>td{padding-top:24px !important}.pr-4>tbody>tr>td,.px-4>tbody>tr>td{padding-right:24px !important}.pb-4>tbody>tr>td,.py-4>tbody>tr>td{padding-bottom:24px !important}.pl-4>tbody>tr>td,.px-4>tbody>tr>td{padding-left:24px !important}.p-5>tbody>tr>td{padding:48px !important}.pt-5>tbody>tr>td,.py-5>tbody>tr>td{padding-top:48px !important}.pr-5>tbody>tr>td,.px-5>tbody>tr>td{padding-right:48px !important}.pb-5>tbody>tr>td,.py-5>tbody>tr>td{padding-bottom:48px !important}.pl-5>tbody>tr>td,.px-5>tbody>tr>td{padding-left:48px !important}.s-lg-1>tbody>tr>td,.s-lg-2>tbody>tr>td,.s-lg-3>tbody>tr>td,.s-lg-4>tbody>tr>td,.s-lg-5>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-0>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-1>tbody>tr>td{font-size:4px !important;line-height:4px !important;height:4px !important}.s-2>tbody>tr>td{font-size:8px !important;line-height:8px !important;height:8px !important}.s-3>tbody>tr>td{font-size:16px !important;line-height:16px !important;height:16px !important}.s-4>tbody>tr>td{font-size:24px !important;line-height:24px !important;height:24px !important}.s-5>tbody>tr>td{font-size:48px !important;line-height:48px !important;height:48px !important}}@media yahoo{.d-mobile{display:none !important}.d-desktop{display:block !important}.w-lg-25{width:25% !important}.w-lg-25>tbody>tr>td{width:25% !important}.w-lg-50{width:50% !important}.w-lg-50>tbody>tr>td{width:50% !important}.w-lg-75{width:75% !important}.w-lg-75>tbody>tr>td{width:75% !important}.w-lg-100{width:100% !important}.w-lg-100>tbody>tr>td{width:100% !important}.w-lg-auto{width:auto !important}.w-lg-auto>tbody>tr>td{width:auto !important}.p-lg-0>tbody>tr>td{padding:0 !important}.pt-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-top:0 !important}.pr-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-right:0 !important}.pb-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-bottom:0 !important}.pl-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-left:0 !important}.p-lg-1>tbody>tr>td{padding:4px !important}.pt-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-top:4px !important}.pr-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-right:4px !important}.pb-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-bottom:4px !important}.pl-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-left:4px !important}.p-lg-2>tbody>tr>td{padding:8px !important}.pt-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-top:8px !important}.pr-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-right:8px !important}.pb-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-bottom:8px !important}.pl-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-left:8px !important}.p-lg-3>tbody>tr>td{padding:16px !important}.pt-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-top:16px !important}.pr-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-right:16px !important}.pb-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-bottom:16px !important}.pl-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-left:16px !important}.p-lg-4>tbody>tr>td{padding:24px !important}.pt-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-top:24px !important}.pr-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-right:24px !important}.pb-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-bottom:24px !important}.pl-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-left:24px !important}.p-lg-5>tbody>tr>td{padding:48px !important}.pt-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-top:48px !important}.pr-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-right:48px !important}.pb-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-bottom:48px !important}.pl-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-left:48px !important}.s-lg-0>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-lg-1>tbody>tr>td{font-size:4px !important;line-height:4px !important;height:4px !important}.s-lg-2>tbody>tr>td{font-size:8px !important;line-height:8px !important;height:8px !important}.s-lg-3>tbody>tr>td{font-size:16px !important;line-height:16px !important;height:16px !important}.s-lg-4>tbody>tr>td{font-size:24px !important;line-height:24px !important;height:24px !important}.s-lg-5>tbody>tr>td{font-size:48px !important;line-height:48px !important;height:48px !important}}
                </style>
            </head>
          <body style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border: 0;" bgcolor="#ffffff">
            <table valign="top" class="bg-light body" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin: 0; padding: 0; border: 0;" bgcolor="#f8f9fa">
                <tbody>
                <tr>
                    <td valign="top" style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; margin: 0;" align="left" bgcolor="#f8f9fa">

                <table class="card" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: separate !important; border-radius: 4px; width: 100%; overflow: hidden; border: 1px solid #dee2e6;" bgcolor="#ffffff">
            <tbody>
                <tr>
                <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">
                    <div>
                <table class="card-body" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; width: 100%; margin: 0; padding: 20px;" align="left">
                    <div>
                    <table class="container " border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                    <td align="center" style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; margin: 0; padding: 0 16px;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%; max-width: 600px; margin: 0 auto;">
                    <tbody>
                        <tr>
                        <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; margin: 0;" align="left">

                        <table class="row" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin-right: -15px; margin-left: -15px; table-layout: fixed; width: 100%;">
                    <thead>
                <tr>

                        <th class="col-7" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 58.333333%; margin: 0;">
                        <img class="img-fluid" width="450" height="200" src="'.getenv('APP_URL').'/Recursos/logos-pagina/logo-red-black.png" alt="Some Image" style="height: auto; line-height: 100%; outline: none; text-decoration: none; width: 100%; max-width: 100%; border: 0 none;">
                        <div style="margin-top: 20px;">
                            <p
                                style="line-height: 24px;
                                font-size: 16px;
                                width: 100%;
                                margin: 0;"
                                align="left"><b>Fecha:</b>
                                '.Carbon::now().'
                            </p>

                            <p
                                style="line-height: 24px;
                                font-size: 16px;
                                width: 100%;
                                margin: 0;"
                                align="left"><b>Id Orden:</b>
                                '.$idFactura.'
                            </p>

                            <p
                                style="line-height: 24px;
                                font-size: 16px;
                                width: 100%;
                                margin: 0;"
                                align="left"><b>Método de pago:</b>
                                '.$pedido->metodo_compra.'
                            </p>

                        </div>



                            <div class="hr " style="width: 100%; margin: 20px 0; border: 0;">
                <table border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
                    <tbody>
                    <tr>
                        <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; border-top-width: 1px; border-top-color: #dddddd; border-top-style: solid; height: 1px; width: 100%; margin: 0;" align="left"></td>
                    </tr>
                    </tbody>
                </table>
            </div>


            </th>


                        <th class="col-5" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 41.666667%; margin: 0;">

                            <p class="h3 text-warning"
                                style="line-height: 33.6px;
                                font-size: 28px;
                                font-weight: 500;
                                vertical-align:
                                baseline; width: 100%;
                                color: #8d897f;
                                 margin: 0;"
                                 align="left"><b>Factura #</b>

                                 '.$pedido->id_transaccion.'

                            </p>
                            <div class="hr " style="width: 100%; margin: 20px 0; border: 0;">
            <table border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
                <tbody>
                <tr>
                    <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; border-top-width: 1px; border-top-color: #dddddd; border-top-style: solid; height: 1px; width: 100%; margin: 0;" align="left"></td>
                </tr>
                </tbody>
            </table>
            </div>

                            <p
                                style="line-height:
                                    24px; font-size:
                                    16px; width: 100%;
                                    margin: 0;"
                                    align="left"><b>Cliente:</b>'.$pedido->nombre.'
                                    '.$pedido->apellido.'
                            </p>

                            <p
                                style="line-height: 24px;
                                        font-size: 16px;
                                        width: 100%;
                                        margin: 0;"
                                        align="left"><b>Documento:</b>
                                '.$pedido->documento_identidad.'
                            </p>

                            <p
                                style="line-height: 24px;
                                font-size: 16px;
                                width: 100%;
                                margin: 0;"
                                align="left"><b>Teléfono:</b>
                                '.$pedido->telefono.'
                            </p>

                            <p
                                style="line-height: 24px;
                                       font-size: 16px;
                                       width: 100%;
                                       margin: 0;"
                                       align="left"
                                       ><b>Email:</b>
                                '.$pedido->correo.'
                            </p>

                            <p
                                style="line-height: 24px;
                                        font-size: 16px;
                                        width: 100%;
                                        margin: 0;"
                                        align="left"><b>Dirección:</b>
                                '.$pedido->direccion.'
                            </p>


            </th>


                </tr>
            </thead>
            </table>


                    <div style="padding-top: 5px;">
                        <table class="row" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin-right: -15px; margin-left: -15px; table-layout: fixed; width: 100%;">
            <thead>
                <tr>

                            <th class="col-12" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 100%; margin: 0;">

                                <table class="table" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%; max-width: 100%;">
                                <thead style="background: #a5dba4ab;">
                                    <tr>
                                        <th
                                            style="line-height: 24px;
                                                  font-size: 16px;
                                                  border-bottom-width: 2px;
                                                  border-bottom-color: #dee2e6;
                                                  border-bottom-style: solid;
                                                  border-top-width: 1px;
                                                  border-top-color: #dee2e6;
                                                  border-top-style: solid;
                                                  margin: 0; padding: 12px;"
                                                  align="left"
                                                  valign="top">
                                                #
                                        </th>
                                        <th
                                            style="line-height: 24px;
                                            font-size: 16px;
                                            border-bottom-width: 2px;
                                            border-bottom-color: #dee2e6;
                                            border-bottom-style: solid;
                                            border-top-width: 1px;
                                            border-top-color: #dee2e6;
                                            border-top-style: solid;
                                            margin: 0;
                                            padding: 12px;"
                                            align="left"
                                            valign="top">

                                            Producto

                                        </th>

                                        <th
                                            style="line-height: 24px;
                                            font-size: 16px;
                                            border-bottom-width: 2px;
                                            border-bottom-color: #dee2e6;
                                            border-bottom-style: solid;
                                            border-top-width: 1px;
                                            border-top-color: #dee2e6;
                                            border-top-style: solid;
                                            margin: 0;
                                            padding: 12px;"
                                            align="left"
                                            valign="top">

                                          Precio
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>


                                  '.$tabla.'



                                </tbody>
                                </table>
                                <div class="hr " style="width: 100%; margin: 20px 0; border: 0;">
            <table border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
                <tbody>
                <tr>
                    <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; border-top-width: 1px; border-top-color: #dddddd; border-top-style: solid; height: 1px; width: 100%; margin: 0;" align="left"></td>
                </tr>
                </tbody>
            </table>
            </div>


                                <table class="row" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin-right: -15px; margin-left: -15px; table-layout: fixed; width: 100%;">
            <thead>
                <tr>

                                    <th class="col-10" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 83.333333%; margin: 0;">
            <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left"><b>Total: </b></p>
            </th>

                                    <th class="col-2" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 16.666667%; margin: 0;">
            $'.sprintf("%.2f", $total).'
            </th>


                </tr>
            </thead>
            </table>

                                <div class="hr " style="width: 100%; margin: 20px 0; border: 0;">
            <table border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
                <tbody>
                <tr>
                    <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; border-top-width: 1px; border-top-color: #dddddd; border-top-style: solid; height: 1px; width: 100%; margin: 0;" align="left"></td>
                </tr>
                </tbody>
            </table>
            </div>


            </th>


                </tr>
            </thead>
            </table>

                    </div>

                        </td>
                        </tr>
                    </tbody>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <![endif]-->
                </td>
                </tr>
            </tbody>
            </table>

                    <table class="row" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin-right: -15px; margin-left: -15px; table-layout: fixed; width: 100%;">
            <thead>
                <tr>

                        <div class="text-center" style="" align="center">¡ Gracias por tu compra. !</div>
                        <div class="text-center" style="" align="center"><b>www.proeditsclub.com</b></div>

                </tr>
            </thead>
            </table>

                </div>
                </td>
                </tr>
            </tbody>
            </table>

                </div>
                </td>
                </tr>
            </tbody>
            </table>


                </td>
                </tr>
            </tbody>
            </table>
          </body>
        </html>
        ';
        return $header;
    }
    //compra membresia con paypal
    public static function templateFacturaPaypalMembresia($idFactura){
        $tabla="";
        $pedido=ClienteMembresia::where('id_transaccion',$idFactura)->first();
        $membresia=Membresia::where('id',$pedido->id_membresia)->first();
        //convierto el string a objeto
        $factura=json_decode($pedido->form_factura, true);

        $tabla.='
        <tr>
            <th
                style="line-height: 24px;
                font-size: 16px; margin: 0;"
                align="left">
                1
            </th>
            <td
                style="border-spacing: 0px;
                border-collapse: collapse;
                line-height: 24px;
                font-size: 16px;
                border-top-width: 1px;
                border-top-color: #dee2e6;
                border-top-style: solid;
                margin: 0; padding: 12px;"
                align="left"
                valign="top">

                '.$membresia->nombre.'

            </td>
            <td
                style="border-spacing: 0px;
                border-collapse: collapse;
                line-height: 24px;
                font-size: 16px;
                border-top-width: 1px;
                border-top-color: #dee2e6;
                border-top-style: solid;
                margin: 0;
                padding: 12px;"
                align="left"
                valign="top">

                $'.$pedido->precio_venta.'
            </td>
        </tr>';


        $header='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
              <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

                <style type="text/css">
                  .ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:150%}a{text-decoration:none}body,td,input,textarea,select{margin:unset;font-family:unset}input,textarea,select{font-size:unset}@media screen and (max-width: 600px){table.row th.col-lg-1,table.row th.col-lg-2,table.row th.col-lg-3,table.row th.col-lg-4,table.row th.col-lg-5,table.row th.col-lg-6,table.row th.col-lg-7,table.row th.col-lg-8,table.row th.col-lg-9,table.row th.col-lg-10,table.row th.col-lg-11,table.row th.col-lg-12{display:block;width:100% !important}.d-mobile{display:block !important}.d-desktop{display:none !important}.w-lg-25{width:auto !important}.w-lg-25>tbody>tr>td{width:auto !important}.w-lg-50{width:auto !important}.w-lg-50>tbody>tr>td{width:auto !important}.w-lg-75{width:auto !important}.w-lg-75>tbody>tr>td{width:auto !important}.w-lg-100{width:auto !important}.w-lg-100>tbody>tr>td{width:auto !important}.w-lg-auto{width:auto !important}.w-lg-auto>tbody>tr>td{width:auto !important}.w-25{width:25% !important}.w-25>tbody>tr>td{width:25% !important}.w-50{width:50% !important}.w-50>tbody>tr>td{width:50% !important}.w-75{width:75% !important}.w-75>tbody>tr>td{width:75% !important}.w-100{width:100% !important}.w-100>tbody>tr>td{width:100% !important}.w-auto{width:auto !important}.w-auto>tbody>tr>td{width:auto !important}.p-lg-0>tbody>tr>td{padding:0 !important}.pt-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-top:0 !important}.pr-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-right:0 !important}.pb-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-bottom:0 !important}.pl-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-left:0 !important}.p-lg-1>tbody>tr>td{padding:0 !important}.pt-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-top:0 !important}.pr-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-right:0 !important}.pb-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-bottom:0 !important}.pl-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-left:0 !important}.p-lg-2>tbody>tr>td{padding:0 !important}.pt-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-top:0 !important}.pr-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-right:0 !important}.pb-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-bottom:0 !important}.pl-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-left:0 !important}.p-lg-3>tbody>tr>td{padding:0 !important}.pt-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-top:0 !important}.pr-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-right:0 !important}.pb-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-bottom:0 !important}.pl-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-left:0 !important}.p-lg-4>tbody>tr>td{padding:0 !important}.pt-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-top:0 !important}.pr-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-right:0 !important}.pb-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-bottom:0 !important}.pl-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-left:0 !important}.p-lg-5>tbody>tr>td{padding:0 !important}.pt-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-top:0 !important}.pr-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-right:0 !important}.pb-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-bottom:0 !important}.pl-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-left:0 !important}.p-0>tbody>tr>td{padding:0 !important}.pt-0>tbody>tr>td,.py-0>tbody>tr>td{padding-top:0 !important}.pr-0>tbody>tr>td,.px-0>tbody>tr>td{padding-right:0 !important}.pb-0>tbody>tr>td,.py-0>tbody>tr>td{padding-bottom:0 !important}.pl-0>tbody>tr>td,.px-0>tbody>tr>td{padding-left:0 !important}.p-1>tbody>tr>td{padding:4px !important}.pt-1>tbody>tr>td,.py-1>tbody>tr>td{padding-top:4px !important}.pr-1>tbody>tr>td,.px-1>tbody>tr>td{padding-right:4px !important}.pb-1>tbody>tr>td,.py-1>tbody>tr>td{padding-bottom:4px !important}.pl-1>tbody>tr>td,.px-1>tbody>tr>td{padding-left:4px !important}.p-2>tbody>tr>td{padding:8px !important}.pt-2>tbody>tr>td,.py-2>tbody>tr>td{padding-top:8px !important}.pr-2>tbody>tr>td,.px-2>tbody>tr>td{padding-right:8px !important}.pb-2>tbody>tr>td,.py-2>tbody>tr>td{padding-bottom:8px !important}.pl-2>tbody>tr>td,.px-2>tbody>tr>td{padding-left:8px !important}.p-3>tbody>tr>td{padding:16px !important}.pt-3>tbody>tr>td,.py-3>tbody>tr>td{padding-top:16px !important}.pr-3>tbody>tr>td,.px-3>tbody>tr>td{padding-right:16px !important}.pb-3>tbody>tr>td,.py-3>tbody>tr>td{padding-bottom:16px !important}.pl-3>tbody>tr>td,.px-3>tbody>tr>td{padding-left:16px !important}.p-4>tbody>tr>td{padding:24px !important}.pt-4>tbody>tr>td,.py-4>tbody>tr>td{padding-top:24px !important}.pr-4>tbody>tr>td,.px-4>tbody>tr>td{padding-right:24px !important}.pb-4>tbody>tr>td,.py-4>tbody>tr>td{padding-bottom:24px !important}.pl-4>tbody>tr>td,.px-4>tbody>tr>td{padding-left:24px !important}.p-5>tbody>tr>td{padding:48px !important}.pt-5>tbody>tr>td,.py-5>tbody>tr>td{padding-top:48px !important}.pr-5>tbody>tr>td,.px-5>tbody>tr>td{padding-right:48px !important}.pb-5>tbody>tr>td,.py-5>tbody>tr>td{padding-bottom:48px !important}.pl-5>tbody>tr>td,.px-5>tbody>tr>td{padding-left:48px !important}.s-lg-1>tbody>tr>td,.s-lg-2>tbody>tr>td,.s-lg-3>tbody>tr>td,.s-lg-4>tbody>tr>td,.s-lg-5>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-0>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-1>tbody>tr>td{font-size:4px !important;line-height:4px !important;height:4px !important}.s-2>tbody>tr>td{font-size:8px !important;line-height:8px !important;height:8px !important}.s-3>tbody>tr>td{font-size:16px !important;line-height:16px !important;height:16px !important}.s-4>tbody>tr>td{font-size:24px !important;line-height:24px !important;height:24px !important}.s-5>tbody>tr>td{font-size:48px !important;line-height:48px !important;height:48px !important}}@media yahoo{.d-mobile{display:none !important}.d-desktop{display:block !important}.w-lg-25{width:25% !important}.w-lg-25>tbody>tr>td{width:25% !important}.w-lg-50{width:50% !important}.w-lg-50>tbody>tr>td{width:50% !important}.w-lg-75{width:75% !important}.w-lg-75>tbody>tr>td{width:75% !important}.w-lg-100{width:100% !important}.w-lg-100>tbody>tr>td{width:100% !important}.w-lg-auto{width:auto !important}.w-lg-auto>tbody>tr>td{width:auto !important}.p-lg-0>tbody>tr>td{padding:0 !important}.pt-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-top:0 !important}.pr-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-right:0 !important}.pb-lg-0>tbody>tr>td,.py-lg-0>tbody>tr>td{padding-bottom:0 !important}.pl-lg-0>tbody>tr>td,.px-lg-0>tbody>tr>td{padding-left:0 !important}.p-lg-1>tbody>tr>td{padding:4px !important}.pt-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-top:4px !important}.pr-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-right:4px !important}.pb-lg-1>tbody>tr>td,.py-lg-1>tbody>tr>td{padding-bottom:4px !important}.pl-lg-1>tbody>tr>td,.px-lg-1>tbody>tr>td{padding-left:4px !important}.p-lg-2>tbody>tr>td{padding:8px !important}.pt-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-top:8px !important}.pr-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-right:8px !important}.pb-lg-2>tbody>tr>td,.py-lg-2>tbody>tr>td{padding-bottom:8px !important}.pl-lg-2>tbody>tr>td,.px-lg-2>tbody>tr>td{padding-left:8px !important}.p-lg-3>tbody>tr>td{padding:16px !important}.pt-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-top:16px !important}.pr-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-right:16px !important}.pb-lg-3>tbody>tr>td,.py-lg-3>tbody>tr>td{padding-bottom:16px !important}.pl-lg-3>tbody>tr>td,.px-lg-3>tbody>tr>td{padding-left:16px !important}.p-lg-4>tbody>tr>td{padding:24px !important}.pt-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-top:24px !important}.pr-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-right:24px !important}.pb-lg-4>tbody>tr>td,.py-lg-4>tbody>tr>td{padding-bottom:24px !important}.pl-lg-4>tbody>tr>td,.px-lg-4>tbody>tr>td{padding-left:24px !important}.p-lg-5>tbody>tr>td{padding:48px !important}.pt-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-top:48px !important}.pr-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-right:48px !important}.pb-lg-5>tbody>tr>td,.py-lg-5>tbody>tr>td{padding-bottom:48px !important}.pl-lg-5>tbody>tr>td,.px-lg-5>tbody>tr>td{padding-left:48px !important}.s-lg-0>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}.s-lg-1>tbody>tr>td{font-size:4px !important;line-height:4px !important;height:4px !important}.s-lg-2>tbody>tr>td{font-size:8px !important;line-height:8px !important;height:8px !important}.s-lg-3>tbody>tr>td{font-size:16px !important;line-height:16px !important;height:16px !important}.s-lg-4>tbody>tr>td{font-size:24px !important;line-height:24px !important;height:24px !important}.s-lg-5>tbody>tr>td{font-size:48px !important;line-height:48px !important;height:48px !important}}
                </style>
            </head>
          <body style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border: 0;" bgcolor="#ffffff">
            <table valign="top" class="bg-light body" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin: 0; padding: 0; border: 0;" bgcolor="#f8f9fa">
                <tbody>
                <tr>
                    <td valign="top" style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; margin: 0;" align="left" bgcolor="#f8f9fa">

                <table class="card" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: separate !important; border-radius: 4px; width: 100%; overflow: hidden; border: 1px solid #dee2e6;" bgcolor="#ffffff">
            <tbody>
                <tr>
                <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">
                    <div>
                <table class="card-body" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; width: 100%; margin: 0; padding: 20px;" align="left">
                    <div>
                    <table class="container " border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                    <td align="center" style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; margin: 0; padding: 0 16px;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%; max-width: 600px; margin: 0 auto;">
                    <tbody>
                        <tr>
                        <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; margin: 0;" align="left">

                        <table class="row" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin-right: -15px; margin-left: -15px; table-layout: fixed; width: 100%;">
                    <thead>
                <tr>

                        <th class="col-7" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 58.333333%; margin: 0;">
                        <img class="img-fluid" width="450" height="200" src="'.getenv('APP_URL').'/Recursos/logos-pagina/logo-red-black.png" alt="Some Image" style="height: auto; line-height: 100%; outline: none; text-decoration: none; width: 100%; max-width: 100%; border: 0 none;">
                        <div style="margin-top: 20px;">
                            <p
                                style="line-height: 24px;
                                font-size: 16px;
                                width: 100%;
                                margin: 0;"
                                align="left"><b>Fecha:</b>
                                '.Carbon::now().'
                            </p>

                            <p
                                style="line-height: 24px;
                                font-size: 16px;
                                width: 100%;
                                margin: 0;"
                                align="left"><b>Id Orden:</b>
                                '.$pedido->id.'
                            </p>

                            <p
                                style="line-height: 24px;
                                font-size: 16px;
                                width: 100%;
                                margin: 0;"
                                align="left"><b>Método de pago:</b>
                                '.$pedido->metodo_compra.'
                            </p>

                        </div>



                            <div class="hr " style="width: 100%; margin: 20px 0; border: 0;">
                <table border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
                    <tbody>
                    <tr>
                        <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; border-top-width: 1px; border-top-color: #dddddd; border-top-style: solid; height: 1px; width: 100%; margin: 0;" align="left"></td>
                    </tr>
                    </tbody>
                </table>
            </div>


            </th>


                        <th class="col-5" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 41.666667%; margin: 0;">

                            <p class="h3 text-warning"
                                style="line-height: 33.6px;
                                font-size: 28px;
                                font-weight: 500;
                                vertical-align:
                                baseline; width: 100%;
                                color: #8d897f;
                                 margin: 0;"
                                 align="left"><b>Factura #</b>

                                 '.$pedido->id_transaccion.'

                            </p>
                            <div class="hr " style="width: 100%; margin: 20px 0; border: 0;">
            <table border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
                <tbody>
                <tr>
                    <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; border-top-width: 1px; border-top-color: #dddddd; border-top-style: solid; height: 1px; width: 100%; margin: 0;" align="left"></td>
                </tr>
                </tbody>
            </table>
            </div>

                            <p
                                style="line-height:
                                    24px; font-size:
                                    16px; width: 100%;
                                    margin: 0;"
                                    align="left"><b>Cliente:</b>'.$pedido->nombre.'
                                    '.$pedido->apellido.'
                            </p>

                            <p
                                style="line-height: 24px;
                                        font-size: 16px;
                                        width: 100%;
                                        margin: 0;"
                                        align="left"><b>Documento:</b>
                                '.$pedido->documento_identidad.'
                            </p>

                            <p
                                style="line-height: 24px;
                                font-size: 16px;
                                width: 100%;
                                margin: 0;"
                                align="left"><b>Teléfono:</b>
                                '.$pedido->telefono.'
                            </p>

                            <p
                                style="line-height: 24px;
                                       font-size: 16px;
                                       width: 100%;
                                       margin: 0;"
                                       align="left"
                                       ><b>Email:</b>
                                '.$pedido->correo.'
                            </p>

                            <p
                                style="line-height: 24px;
                                        font-size: 16px;
                                        width: 100%;
                                        margin: 0;"
                                        align="left"><b>Dirección:</b>
                                '.$pedido->direccion.'
                            </p>


            </th>


                </tr>
            </thead>
            </table>


                    <div style="padding-top: 5px;">
                        <table class="row" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin-right: -15px; margin-left: -15px; table-layout: fixed; width: 100%;">
            <thead>
                <tr>

                            <th class="col-12" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 100%; margin: 0;">

                                <table class="table" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%; max-width: 100%;">
                                <thead style="background: #a5dba4ab;">
                                    <tr>
                                        <th
                                            style="line-height: 24px;
                                                  font-size: 16px;
                                                  border-bottom-width: 2px;
                                                  border-bottom-color: #dee2e6;
                                                  border-bottom-style: solid;
                                                  border-top-width: 1px;
                                                  border-top-color: #dee2e6;
                                                  border-top-style: solid;
                                                  margin: 0; padding: 12px;"
                                                  align="left"
                                                  valign="top">
                                                #
                                        </th>
                                        <th
                                            style="line-height: 24px;
                                            font-size: 16px;
                                            border-bottom-width: 2px;
                                            border-bottom-color: #dee2e6;
                                            border-bottom-style: solid;
                                            border-top-width: 1px;
                                            border-top-color: #dee2e6;
                                            border-top-style: solid;
                                            margin: 0;
                                            padding: 12px;"
                                            align="left"
                                            valign="top">

                                            Producto

                                        </th>

                                        <th
                                            style="line-height: 24px;
                                            font-size: 16px;
                                            border-bottom-width: 2px;
                                            border-bottom-color: #dee2e6;
                                            border-bottom-style: solid;
                                            border-top-width: 1px;
                                            border-top-color: #dee2e6;
                                            border-top-style: solid;
                                            margin: 0;
                                            padding: 12px;"
                                            align="left"
                                            valign="top">

                                          Precio
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>


                                  '.$tabla.'



                                </tbody>
                                </table>
                                <div class="hr " style="width: 100%; margin: 20px 0; border: 0;">
            <table border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
                <tbody>
                <tr>
                    <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; border-top-width: 1px; border-top-color: #dddddd; border-top-style: solid; height: 1px; width: 100%; margin: 0;" align="left"></td>
                </tr>
                </tbody>
            </table>
            </div>


                                <table class="row" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin-right: -15px; margin-left: -15px; table-layout: fixed; width: 100%;">
            <thead>
                <tr>

                                    <th class="col-10" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 83.333333%; margin: 0;">
            <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left"><b>Total: </b></p>
            </th>

                                    <th class="col-2" align="left" valign="top" style="line-height: 24px; font-size: 16px; min-height: 1px; padding-right: 15px; padding-left: 15px; font-weight: normal; width: 16.666667%; margin: 0;">
            $'.sprintf("%.2f", $pedido->precio_venta).'
            </th>


                </tr>
            </thead>
            </table>

                                <div class="hr " style="width: 100%; margin: 20px 0; border: 0;">
            <table border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; width: 100%;">
                <tbody>
                <tr>
                    <td style="border-spacing: 0px; border-collapse: collapse; line-height: 24px; font-size: 16px; border-top-width: 1px; border-top-color: #dddddd; border-top-style: solid; height: 1px; width: 100%; margin: 0;" align="left"></td>
                </tr>
                </tbody>
            </table>
            </div>


            </th>


                </tr>
            </thead>
            </table>

                    </div>

                        </td>
                        </tr>
                    </tbody>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <![endif]-->
                </td>
                </tr>
            </tbody>
            </table>

                    <table class="row" border="0" cellpadding="0" cellspacing="0" style="font-family: Helvetica, Arial, sans-serif; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-spacing: 0px; border-collapse: collapse; margin-right: -15px; margin-left: -15px; table-layout: fixed; width: 100%;">
            <thead>
                <tr>

                        <div class="text-center" style="" align="center">¡ Gracias por tu compra. !</div>
                        <div class="text-center" style="" align="center"><b>www.proeditsclub.com</b></div>

                </tr>
            </thead>
            </table>

                </div>
                </td>
                </tr>
            </tbody>
            </table>

                </div>
                </td>
                </tr>
            </tbody>
            </table>


                </td>
                </tr>
            </tbody>
            </table>
          </body>
        </html>
        ';
        return $header;
    }
    public static function enviarCorreo($templateHtml,$para,$tituloCorreo){
        try {
            $mail=new PHPMailer();
            $mail->CharSet='UTF-8';
            $mail->isMail();
            $mail->setFrom(getenv("CORREO"),'Proeditsclub');
            $mail->addReplyTo(getenv("CORREO_ADMIN"),'Proeditsclub');
            $mail->Subject=($tituloCorreo);
            $mail->addAddress($para);
            // $mail->Port= 587;
            $mail->msgHTML($templateHtml);
            $envio=$mail->Send();
            if ($envio==true) {
            return TRUE;
            }else{
                return FALSE;
            }
        } catch (\Throwable $th) {
            return  $respuestaMensaje=$th->getMessage();
        }
    }

    public static function templateRecuperarPassword($usuario,$passowrd){
        return'
        <html>
             <head>
             <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
             <style>
                 /* Add custom classes and styles that you want inlined here */
             </style>
             </head>
             <body class="bg-light">
             <div class="container">
                 <div class="card my-5">
                 <div class="card-body">
                     <img width="300"
                         height="100"
                         src="'.getenv("APP_URL").'/Recursos/logos-pagina/logo-red-black.png" alt="LOGO-PROEDITS" />
                     <div class="h6 text-muted mt-3">PROEDITSCLUB.COM</div>
                     <h5 class=" mt-2"><b>SOLICITUD DE RECUPERACIÓN DE CONTRASEÑA </b></h5>
                     <hr>
                     <div class="container">
                         <p class=" mt-2">
                             Estimado/a '.$usuario.'
                         </p>
                         <p class=" mt-2">
                             '.'
                         </p>
                         <p class=" mt-2">
                            Su contraseña ha sido actulizada con exito por favor ingrese con su nueva contraseña
                            <b>'.$passowrd.'</b>

                         </p>
                 </div>
                     <a
                         style="
                         padding: .6rem 2rem;
                         color: #fff;
                         border-radius: .25rem;
                         background-color: #0c2342;
                         text-decoration: none;"

                             href="'.getenv("APP_URL").'" style="background:#0c2342 !important;">
                         Acceder
                     </a>
                         <p class="mt-3">Saludos Cordiales</p>

                 </div>
                 </div>
             </div>
             </body>
       </html>';
    }

}
