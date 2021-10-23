<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit_measurements extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->user) {
            redirect('login');
        }
    }

    public function index()
    {
        $this->view_data['units_measurements'] = Unit_measurement::all();
        $this->content_view = 'unit_measurement/view';
    }

    public function add()
    {
        try {
            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d H:i:s");
            $_POST['created_date'] = $date;
            $user = Unit_measurement::create($_POST);
            redirect("unit_measurements", "refresh");
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function edit($id = FALSE)
    {
        try {
            if ($_POST) {
                $unit = Unit_measurement::find($id);
                $unit->update_attributes($_POST);
                redirect("unit_measurements", "refresh");
            } else {
                $this->view_data['unit_measurement'] = Unit_measurement::find($id);
                $this->content_view = 'unit_measurement/edit';
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $unit = Unit_measurement::find($id);
            $unit->delete();
            redirect("unit_measurements", "refresh");
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
