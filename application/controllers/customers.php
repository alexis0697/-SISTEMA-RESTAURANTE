<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customers extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (! $this->user) {
            redirect('login');
        }
    }

    public function index()
    {
        $this->view_data['customers'] = Customer::all();
        $this->content_view = 'customer/view';
    }

    public function add()
    {
        try {
            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d H:i:s");
            $_POST['created_at'] = $date;
            $source = ($_POST['source']) ? $_POST['source'] : 0;
            unset($_POST['source']);
            $customer = Customer::create($_POST);
            if ($source == 1) {
                redirect("", "refresh");
            }
            redirect("customers", "refresh");
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function edit($id = FALSE)
    {
        if ($_POST) {
            $customer = Customer::find($id);
            $customer->update_attributes($_POST);
            redirect("customers", "refresh");
        } else {
            $this->view_data['customer'] = Customer::find($id);
            $this->content_view = 'customer/edit';
        }
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        redirect("customers", "refresh");
    }
}
