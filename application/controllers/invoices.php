<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'third_party/Stripe/Stripe.php');

class Invoices extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_model', 'invoice');
        $this->user = $this->session->userdata('user_id') ? User::find_by_id($this->session->userdata('user_id')) : FALSE;
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
        $this->lang->load($lang, $lang);
        $this->register = $this->session->userdata('register') ? $this->session->userdata('register') : FALSE;

        $this->setting = Setting::find(1);
    }

    public function ajax_list()
    {
        $list = $this->invoice->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $invoice) {
            $document = Parameter::find('all', array(
                'conditions' => array(
                    'parameter_class = ? AND parameter_value = ? AND parameter_status = ?',
                    1000,
                    $invoice->typedocument_id,
                    1
                )
            ));

            $PayMethode = explode('~', $invoice->paidmethod);
            $methodPayment = Parameter::find('all', array(
                'conditions' => array(
                    'parameter_class = ? AND parameter_code = ? AND parameter_status = ?',
                    1001,
                    $PayMethode[0],
                    1
                )
            ));

            $no++;
            $row = array();
            $row[] = $document[0]->parameter_description;
            $row[] = sprintf("%08d", $invoice->id);
            $row[] = $invoice->clientname;
            // $row[] = $invoice->tax;
            // $row[] = $invoice->discount;
            $row[] = number_format((float)$invoice->total, $this->setting->decimals, '.', '');
            $row[] = $methodPayment[0]->parameter_description;
            $row[] = $invoice->created_by;
            $row[] = date("d/m/Y H:i", strtotime($invoice->created_at));
            $row[] = $invoice->totalitems;

            switch ($invoice->status) {
                case 1: // case Credit Card
                    $satus = 'unpaid';
                    break;
                case 2: // case ckeck
                    $satus = 'Partiallypaid';
                    break;
                default:
                    $satus = 'paid';
            }
            if ($invoice->canceled == 1) {
                $satus = 'CanceledStatus';
            }
            $row[] = '<span class="' . $satus . '">' . label($satus) . '<span>';

            // add html for action
            if ($this->user->role === "admin")
                $row[] = '<div class="btn-group"><a class="btn btn-primary" href="javascript:void(0)" style="padding: 6px 0px;" dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-cog fa-fw"></i></a><a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="fa fa-caret-down" title="Toggle dropdown menu"></span></a><ul class="dropdown-menu"><li><a href="javascript:void(0)" onclick="Edit_Sale(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i> ' . label("Edit") . '</a></li><li><a href="javascript:void(0)" onclick="payaments(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-credit-card-alt fa-fw" aria-hidden="true"></i> ' . label("Payements") . '</a></li><li><a href="javascript:void(0)" onclick="showInvoice(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-sticky-note" aria-hidden="true"></i> ' . label("invoice") . '</a></li><li><a href="javascript:void(0)" onclick="showTicket(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-ticket fa-fw" aria-hidden="true"></i> ' . label("Receipt") . '</a></li><li class="divider"></li><li><a href="javascript:void(0)" onclick="canceled_invoice(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i> ' . label("Canceled") . '</a></li><li><a href="javascript:void(0)" onclick="delete_invoice(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> ' . label("Delete") . '</a></li></ul></div>';
            else
                $row[] = '<div class="btn-group"><a class="btn btn-primary" href="javascript:void(0)" style="padding: 6px 0px;" dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-cog fa-fw"></i></a><a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="fa fa-caret-down" title="Toggle dropdown menu"></span></a><ul class="dropdown-menu"><li><a href="javascript:void(0)" onclick="Edit_Sale(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i> ' . label("Edit") . '</a></li><li><a href="javascript:void(0)" onclick="payaments(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-credit-card-alt fa-fw" aria-hidden="true"></i> ' . label("Payements") . '</a></li><li><a href="javascript:void(0)" onclick="showInvoice(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-sticky-note" aria-hidden="true"></i> ' . label("invoice") . '</a></li><li><a href="javascript:void(0)" onclick="showTicket(' . "'" . $invoice->id . "'" . ')"><i class="fa fa-ticket fa-fw" aria-hidden="true"></i> ' . label("Receipt") . '</a></li></ul></div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->invoice->count_all(),
            "recordsFiltered" => $this->invoice->count_filtered(),
            "data" => $data
        );
        // output to json format
        echo json_encode($output);
    }

    public function ajax_delete($id)
    {
        $this->invoice->delete_by_id($id);
        $posales = Sale_item::delete_all(array(
            'conditions' => array(
                'sale_id = ?',
                $id
            )
        ));
        echo json_encode(array(
            "status" => TRUE
        ));
    }

    public function ajax_canceled($id)
    {
        Sale::update_all(array(
            'set' => array(
                'canceled' => 1,
                'canceled_by' => $this->user->id
            ),
            'conditions' => array(
                'id = ?',
                $id
            )
        ));

        Sale_item::update_all(array(
            'set' => array(
                'canceled' => 1
            ),
            'conditions' => array(
                'sale_id = ?',
                $id
            )
        ));

        $data = array('canceled' => 1);
        $this->db->where('sale_id', $id);
        $this->db->update('zarest_sale_items', $data);
        echo json_encode(array(
            "status" => TRUE
        ));
    }

    public function ShowTicket($id)
    {
        try {
            $sale = Sale::find($id);
            $posales = Sale_item::find('all', array(
                'conditions' => array(
                    'sale_id = ?',
                    $id
                )
            ));

            $waiterName = $sale->waiter_id > 0 ? Waiter::find($sale->waiter_id)->name : label('withoutWaiter');

            $register = Register::find($this->register);
            $store = Store::find($register->store_id);

            $document = Parameter::find('all', array(
                'conditions' => array(
                    'parameter_class = ? AND parameter_value = ? AND parameter_status = ?',
                    1000,
                    $sale->typedocument_id,
                    1
                )
            ));

            $PayMethode = explode('~', $sale->paidmethod);
            $methodPayment = Parameter::find('all', array(
                'conditions' => array(
                    'parameter_class = ? AND parameter_code = ? AND parameter_status = ?',
                    1001,
                    $PayMethode[0],
                    1
                )
            ));

            $nameTypeDocument = strtoupper($document[0]->parameter_description);

            $dateFormat = strtotime($sale->created_at);
            $newDateFormat = date('d/m/Y H:i', $dateFormat);

            $ticket = '<input type="hidden" name="idSale" id="idSale" value="' . base64_encode($sale->id) . '"><div class="col-md-12"><div class="text-center"><img src="files/Setting/' . $this->setting->logo . '" alt="" width="80px" style="margin-bottom: 10px"/></div><div class="text-center">' . $this->setting->receiptheader . '</div><div style="clear:both;"><h4 style="text-align: center;"><b>' . $nameTypeDocument . ' : ' . sprintf("%08d", $sale->id) . '</b></h4> <p style="text-align: center;margin: 0;">----------------------------------- </p><div style="clear:both;"></div><span class="float-left">' . label("DateHourEmition") . ': ' . $newDateFormat . '</span><div style="clear:both;"><span class="float-left">' . label("CustomerTicket") . ': ' . $sale->clientname . '</span><div style="clear:both;"></div><table class="table" cellspacing="0" border="0"><thead><tr><th><em>#</em></th><th>' . label("Product") . '</th><th>' . label("Quantity") . '</th><th>' . label("SubTotal") . '</th></tr></thead><tbody>';

            $i = 1;
            foreach ($posales as $posale) {
                $ticket .= '<tr><td style="text-align:center; width:30px;">' . $i . '</td><td style="text-align:left; width:180px;">' . $posale->name . '</td><td style="text-align:center; width:50px;">' . $posale->qt . '</td><td style="text-align:right; width:70px; ">' . $this->setting->currency . ' ' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') . '</td></tr>';
                $i++;
            }

            // barcode codding type
            $bcs = 'code128';
            $height = 20;
            $width = 3;
            $ticket .= '</tbody></table><table class="table" cellspacing="0" border="0" style="margin-bottom:8px;"><tbody>';
            // if there is a discount it will be displayed
            if (intval($sale->discount) >= 0)
                $ticket .= '<tr><td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("Discount") . '</td><td colspan="2" style=" padding-top:5px; text-align:right; font-weight:bold;">' . $this->setting->currency . ' ' . number_format((float)$sale->discount, $this->setting->decimals, '.', '') . '</td></tr><tr>';
                $ticket .= '<tr><td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("SubTotal") . '</td><td colspan="2" style=" padding-top:5px; text-align:right; font-weight:bold;">' . $this->setting->currency . ' ' . number_format((float)$sale->subtotal, $this->setting->decimals, '.', '') . '</td></tr><tr>';
            // same for the order tax
            if (intval($sale->tax)  >= 0)
                $ticket .= '<tr><td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("tax") . '(' . $sale->tax . ')</td><td colspan="2" style=" padding-top:5px; text-align:right; font-weight:bold;">'. $this->setting->currency . ' ' . number_format((float)$sale->total - (float)$sale->subtotal, $this->setting->decimals, '.', '') . '</td></tr><tr>';

            $ticket .= '<tr><td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("GrandTotal") . '</td><td colspan="2" style=" padding-top:5px; text-align:right; font-weight:bold;">' . $this->setting->currency . ' ' . number_format((float)$sale->total, $this->setting->decimals, '.', '') . '</td></tr><tr>';

            $PayMethode = explode('~', $sale->paidmethod);
            // switch ($PayMethode[0]) {
                // case '1': // case Credit Card
                //     $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("CreditCard") . '</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;"> ' . $PayMethode[1] . '</td></tr>';
                //     break;
                // case '2': // case ckeck
                //     $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("Yape") . '</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $PayMethode[1] . '</td></tr>';
                //     break;
                // default:
                //     $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("Paid") . ' (' . label("Cash") . ')</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $this->setting->currency . ' ' . number_format((float)$sale->firstpayement, $this->setting->decimals, '.', '') . '</td></tr>';
            // }

            $payements = Payement::find('all', array('conditions' => array('sale_id = ?', $id)));
            if ($payements) {
                $ticket .= '<tr>';
                foreach ($payements as $pay) {
                    $PayMethode = explode('~', $pay->paidmethod);
                    // switch ($PayMethode[0]) {
                    //     case '1': // case Credit Card
                    //         $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("CreditCard") . ' ' . $PayMethode[1] . '</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $this->setting->currency . ' ' . number_format((float)$pay->paid, $this->setting->decimals, '.', '') . '</td></tr>';
                    //         break;
                    //     case '2': // case ckeck
                    //         $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("Yape") . ' (' . $PayMethode[1] . ')</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $this->setting->currency . ' ' . number_format((float)$pay->paid, $this->setting->decimals, '.', '') . '</td></tr>';
                    //         break;
                    //     default:
                    //         $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("Paid") . ' (' . label("Cash") . ')</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $this->setting->currency . ' ' . number_format((float)$pay->paid, $this->setting->decimals, '.', '') . '</td></tr>';
                    // }
                }
            } else {
                $ticket .= '</tbody></table>';
            }

            $ticket .= '<div style="border-top:1px solid #000; padding-top:10px;"><span class="float-left">Mozo (a): </span><span class="float-right">' . $waiterName . '</span><div style="clear:both;"><span class="float-left">Método de Pago: </span><span class="float-right">' . $methodPayment[0]->parameter_description . '</span><center><img style="margin-top:30px" src="' . site_url('pos/GenerateBarcode/' . sprintf("%05d", $sale->id) . '/' . $bcs . '/' . $height . '/' . $width) . '" alt="' . $sale->id . '" /></center><p class="text-center" style="margin:0 auto;margin-top:10px;">' . $store->footer_text . '</p><div class="text-center" style="background-color:#000;padding:5px;width:85%;color:#fff;margin:0 auto;border-radius:3px;margin-top:20px;">' . $this->setting->receiptfooter . '</div></div><br><br>';

            echo $ticket;
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function showInvoice($id)
    {
        $sale = Sale::find($id);
        $posales = Sale_item::find('all', array(
            'conditions' => array(
                'sale_id = ?',
                $id
            )
        ));
        $client = Customer::find('first', array(
            'conditions' => array(
                'id = ?',
                $sale->client_id
            )
        ));
        $ClientData = $client ? 'Customer: ' . $client->name . '<br>' . $client->phone . '<br>' . $client->email : label('WalkinCustomer');
        $document = Parameter::find('all', array(
            'conditions' => array(
                'parameter_class = ? AND parameter_value = ? AND parameter_status = ?',
                1000,
                $sale->typedocument_id,
                1
            )
        ));

        $nameTypeDocument = strtoupper($document[0]->parameter_description);
        $dateFormat = strtotime($sale->created_at);
        $newDateFormat = date('d/m/Y H:i', $dateFormat);

        $ticket = '<div class="col-sm-12"><table width="100%"><tr><td align="left"><span class="float-left">' . $this->setting->companyname . '<br>' . label("Tel") . ' ' . $this->setting->phone . '</span></td><td align="right"><img src="files/Setting/' . $this->setting->logo . '" alt="" width="100px" Style="margin:15px;float:right;"/></td></tr></table></div><div style="clear:both;"></div><h4 class="float-left"></h4> <div style="clear:both;"></div><span style="font-size:24px;font-weight:600;padding:5px;background-color:#415472;color:#fff;">' . $nameTypeDocument . ' : ' . sprintf("%08d", $sale->id) . '</span><br><br><br><div style="clear:both;"></div><table width="100%"><tr><td align="left"><span class="float-left">' . label("Fecha Emisión") . ': ' . $newDateFormat . '</span></td><td align="right"><span Style="margin-bottom:15px;float:right;width:100%;text-align:right">Señor(es): ' . $ClientData . '</span></td></tr></table></br><div style="clear:both;"></div><div style="clear:both;"></div><table class="table" cellspacing="0" border="0"><thead><tr style="background-color:#555;color:#fff;font-weight:600"><th><em>#</em></th><th>' . label("Product") . '</th><th>' . label("Quantity") . '</th><th>' . label("SubTotal") . '</th></tr></thead><tbody>';

        $i = 1;
        foreach ($posales as $posale) {
            $ticket .= '<tr><td style="text-align:center; width:30px;">' . $i . '</td><td style="text-align:left; width:180px;">' . $posale->name . '</td><td style="text-align:center; width:50px;">' . $posale->qt . '</td><td style="text-align:right; width:70px; ">' . $this->setting->currency . ' ' . number_format((float)($posale->qt * $posale->price), $this->setting->decimals, '.', '') . '</td></tr>';
            $i++;
        }

        $bcs = 'code128';
        $height = 20;
        $width = 3;
        $ticket .= '</tbody></table><div class="col-xs-4  col-xs-offset-8"><table class="table table-striped" cellspacing="0" border="0" style="margin:20px 0 30px 0;"><thead><tr><td style="text-align:left; padding:3px;">' . label("TotalItems") . '</td><td style="text-align:right; padding:3px; padding-right:1.5%;font-weight:bold;">' . $sale->totalitems . '</td></tr></thead><tbody><tr><td style="text-align:left; padding:3px;">' . label("Total") . '</td><td style="text-align:right; padding:3px; padding-right:1.5%;font-weight:bold;">' . $this->setting->currency . ' ' . number_format((float)$sale->subtotal, $this->setting->decimals, '.', '') . '</td></tr>';

        if (intval($sale->discount))
            $ticket .= '<tr><td style="text-align:left; padding:3px;">' . label("Discount") . '</td><td style="text-align:right; padding:3px; padding-right:1.5%;font-weight:bold;">' . $sale->discount . '</td></tr>';
        if (intval($sale->tax))
            $ticket .= '<tr><td style="text-align:left; padding:3px; padding-left:1.5%;">' . label("tax") . '</td><td style="text-align:right; padding:3px;font-weight:bold;">' . $sale->tax . '</td></tr>';
        $ticket .= '<tr style="background-color:#415472;color:#fff;font-weight:600;font-size:20px"><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $this->setting->currency . ' ' . number_format((float)$sale->total, $this->setting->decimals, '.', '') . '</td></tr><tr>';

        $PayMethode = explode('~', $sale->paidmethod);
        switch ($PayMethode[0]) {
            case '1': // case Credit Card
                $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("CreditCard") . '</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $PayMethode[1] . '</td></tr></tbody></table></div>';
                break;
            case '2': // case ckeck
                $ticket .= '<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">' . label("Yape") . '</td><td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;">' . $PayMethode[1] . '</td></tr></tbody></table></div>';
                break;
            default:
                $ticket .= '</tbody></table></div>';
        }
        $ticket .= '<div class="text-center" style="clear:both;padding-bottom:10px; padding-top:10px; width:100%; background-color:#eee"><span style="font-size:9px;text-transform:uppercase;letter-spacing: 4px;">' . $this->setting->companyname . '<br>' . $this->setting->phone . '</span></div>';

        echo $ticket;
    }

    public function Edit_Ajax($id)
    {
        $customers = Customer::find('all');
        $sale = Sale::find($id);
        switch ($sale->status) {
            case 1: // case Credit Card
                $satus = 'unpaid';
                break;
            case 2: // case ckeck
                $satus = 'Partiallypaid';
                break;
            default:
                $satus = 'paid';
        }
        $change = ($sale->total - $sale->paid) > 0 ? ($sale->total - $sale->paid) : '';
        $content = '<div class="row"><div class="col-md-12"><h4><b>' . label("Total") . ': </b> ' . $this->setting->currency . ' ' . $sale->total . ' <b>&emsp;' . label("Paid") . ': </b> ' . $this->setting->currency . ' ' . $sale->paid . ' <b> &emsp;' . label("Change") . ': </b> ' . $this->setting->currency . ' ' . ($sale->total - $sale->paid) . '</h4><div class="form-group"><label for="customerSelect">' . label("changeClient") . '</label><select class="form-control" id="customerSelect"><option value="0" >' . label("WalkinCustomer") . '</option>';

        foreach ($customers as $customer) {
            $Selected = $customer->id == $sale->client_id ? 'selected' : '';
            $content .= '<option value="' . $customer->id . '" ' . $Selected . '>' . $customer->name . ' ' . $customer->lastname . '</option>';
        }

        $content .= '</select></div><div class="form-group"><label for="changeStatus">' . label("changeStatus") . ' <span class="' . $satus . '">' . label($satus) . '<span></label><select class="form-control" id="changeStatus"><option value="' . $sale->status . '" >' . label("changeStatus") . '</option><option value="0" >' . label("paid") . '</option><option value="1" >' . label("unpaid") . '</option><option value="2" >' . label("Partiallypaid") . '</option></select></div></div><input type="hidden" id="ClientId" value="' . $id . '" />';

        echo $content;
    }

    public function Update_Sale($id)
    {
        $sale = Sale::find($id);
        date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d H:i:s");
        $sale->update_attributes(array(
            'client_id' => $this->input->post('customerId'),
            'clientname' => $this->input->post('customer'),
            'status' => $this->input->post('Status'),
            'modified_at' => $date
        ));
    }

    public function payaments($id)
    {

        $sale = Sale::find($id);
        $change = ($sale->total - $sale->paid) > 0 ? ($sale->total - $sale->paid) : '';
        $content = '<div class="row"><div class="col-md-12"><h4><b>' . label("Total") . '</b> ' . $this->setting->currency . ' ' . number_format((float)$sale->total, $this->setting->decimals, '.', '') . ' <b>&emsp;' . label("Paid") . ' :</b> ' . $this->setting->currency . ' ' . number_format((float)$sale->paid, $this->setting->decimals, '.', '') . '<b> &emsp;' . label("Change") . ' :</b> ' . $this->setting->currency . ' ' . number_format((float)($sale->total - $sale->paid), $this->setting->decimals, '.', '') . '</h4></div></div>';

        $content .= '<div class="col-md-12"><table class="table"><thead><tr><th width="20%">' . label("Date") . '</th><th width="30%">' . label("Createdby") . '</th><th width="20%">' . label("Amount") . '</th><th width="20%">' . label("Method") . '</th><th width="10%"> </th></tr></thead><tbody class="itemslist">';

        $PayMethode = explode('~', $sale->paidmethod);
        $dateFormat = strtotime($sale->created_at);
        $newDateFormat = date('d/m/Y H:i', $dateFormat);
        $content .= '<tr><td>' . $newDateFormat . '</td>
      <td>' . $sale->created_by . '</td>
      <td>' . number_format((float)$sale->firstpayement, $this->setting->decimals, '.', '') . '</td>
      <td>' . ($PayMethode[0] !== '1' ? ($PayMethode[0] !== '2' ? label("Cash") : label("Cheque")) : label("CreditCard")) . '</td>
      <td> </td></tr>';
        $payements = Payement::find('all', array('conditions' => array('sale_id = ?', $id)));
        if ($payements) {
            foreach ($payements as $pay) {
                $PayMethode = explode('~', $pay->paidmethod);
                $content .= '<tr><td>' . $pay->date->format('d-m-Y') . '</td>
            <td>' . $pay->created_by . '</td>
            <td>' . number_format((float)$pay->paid, $this->setting->decimals, '.', '') . '</td>
            <td>' . ($PayMethode[0] !== '1' ? ($PayMethode[0] !== '2' ? label("Cash") : label("Cheque")) : label("CreditCard")) . '</td>
            <td><a href="javascript:void(0)" onclick="deletepayement(' . $pay->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';
            }
        }
        $content .= '  </tbody>
        </table></div> <button class="btn btn-add col-md-12" onclick="addpymntBtn()" style="margin-bottom:0">' . label("AddPayement") . '</button>';
        echo $content;
    }

    public function Addpayament($type)
    {

        date_default_timezone_set($this->setting->timezone);
        $date = date("Y-m-d H:i:s");
        $_POST['date'] = $date;
        $_POST['register_id'] = $this->register;
        $register = Register::find($this->register);
        $store = Store::find($register->store_id);
        if ($type == 2) {
            try {
                Stripe::setApiKey($this->setting->stripe_secret_key);
                $myCard = array(
                    'number' => $this->input->post('ccnum'),
                    'exp_month' => $this->input->post('ccmonth'),
                    'exp_year' => $this->input->post('ccyear'),
                    "cvc" => $this->input->post('ccv')
                );
                $charge = Stripe_Charge::create(array(
                    'card' => $myCard,
                    'amount' => (floatval($this->input->post('paid')) * 100),
                    'currency' => $this->setting->currency
                ));
                echo "<p class='bg-success text-center'>" . label('saleStripesccess') . '</p>';
            } catch (Stripe_CardError $e) {
                // Since it's a decline, Stripe_CardError will be caught
                $body = $e->getJsonBody();
                $err = $body['error'];
                echo "<p class='bg-danger text-center'>" . $err['message'] . '</p>';
            }
        }
        unset($_POST['ccnum']);
        unset($_POST['ccmonth']);
        unset($_POST['ccyear']);
        unset($_POST['ccv']);
        Payement::create($_POST);
        $sale = Sale::find($this->input->post('sale_id'));

        $sale->paid = $sale->paid + $this->input->post('paid');
        $statu = $sale->paid - $sale->total;
        $sale->status = $statu >= 0 ? '0' : '2';
        $sale->save();

        echo json_encode(array(
            "status" => TRUE
        ));
    }

    public function deletepayement($id, $sale_id)
    {
        $payement = Payement::find($id);

        $sale = Sale::find($sale_id);
        $sale->paid = $sale->paid - $payement->paid;
        $statu = $sale->paid - $sale->total;
        $sale->status = $statu === -floatval($sale->total) ? '1' : '2';
        $sale->save();

        $payement->delete();
    }

    public function generateXlsSales()
    {
        // load excel library
        $this->load->library('excel');
        $listInfo = Sale::all();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'REPORTE DE VENTAS');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(18);
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Tipo de comprobante');
        $objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Número');
        $objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Cliente');
        $objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Fecha');
        $objPHPExcel->getActiveSheet()->SetCellValue('E3', 'Total');
        $objPHPExcel->getActiveSheet()->SetCellValue('F3', 'Método de Pago');
        $objPHPExcel->getActiveSheet()->SetCellValue('G3', 'Registrado Por');
        $objPHPExcel->getActiveSheet()->SetCellValue('H3', 'Total Items');
        $objPHPExcel->getActiveSheet()->SetCellValue('I3', 'Estado');

        foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }
        // set Row
        $rowCount = 4;
        $estado = '';
        foreach ($listInfo as $list) {
            switch ($list->status) {
                case 1: // case Credit Card
                    $estado = 'No Pagado';
                    break;
                case 2: // case ckeck
                    $estado = 'Parcialmente Pagado';
                    break;
                default:
                    $estado = 'Pagado';
            }
            if ($list->canceled == 1) {
                $estado = 'Cancelado';
            }
            $document = Parameter::find('all', array(
                'conditions' => array(
                    'parameter_class = ? AND parameter_value = ? AND parameter_status = ?',
                    1000,
                    $list->typedocument_id,
                    1
                )
            ));
            $PayMethode = explode('~', $list->paidmethod);
            $methodPayment = Parameter::find('all', array(
                'conditions' => array(
                    'parameter_class = ? AND parameter_code = ? AND parameter_status = ?',
                    1001,
                    $PayMethode[0],
                    1
                )
            ));

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount,  strtoupper($document[0]->parameter_description));
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, sprintf("%08d", $list->id));
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $list->clientname);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, date("d/m/Y H:i", strtotime($list->created_at)));
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $list->total);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $methodPayment[0]->parameter_description);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $list->created_by);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $list->totalitems);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $estado);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
            $rowCount++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Ventas');
        $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('0088cc');
        $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
        $filename = "ventas_" . date("YmdHis") . ".xls";
        $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $object_writer->save('php://output');
    }
}
