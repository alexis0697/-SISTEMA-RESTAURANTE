<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->user) {
            redirect('login');
        }
        if ($this->user->role !== "admin") {
            redirect('');
        }
    }

    public function index()
    {
        try {
            $this->view_data['warehouses'] = Warehouse::all();
            $this->view_data['Users'] = User::all();
            $this->view_data['stores'] = Store::all();
            $this->view_data['roles'] = Rol::all();
            $this->view_data['Timezones'] = $this->tz_list();
            $this->content_view = 'setting/setting';
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        unlink('./files/Avatars/' . $user->avatar);
        $user->delete();
        redirect("/settings?tab=users", "location");
    }

    public function addUser()
    {
        try {
            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d H:i:s");
            $config['upload_path'] = './files/Avatars/';
            $config['encrypt_name'] = TRUE;
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_width'] = '1000';
            $config['max_height'] = '1000';
            $idRol = 0;
            if ($_POST['role'] == 'admin') $idRol = 1;
            if ($_POST['role'] == 'sales') $idRol = 2;
            if ($_POST['role'] == 'waiter') $idRol = 3;
            if ($_POST['role'] == 'kitchen') $idRol = 4;

            $this->load->library('upload', $config);
            if ($this->upload->do_upload()) {
                $data = array(
                    'upload_data' => $this->upload->data()
                );
                $image = $data['upload_data']['file_name'];
                $_POST['avatar'] = $image;
                $_POST['created_at'] = $date;
                unset($_POST['PasswordRepeat']);
                $user = User::create($_POST);
                $dataRol['user_id'] = $user->id;
                $dataRol['rol_id'] = $idRol;
                $dataRol['created_date'] = $date;
                $rolUser = Rol_User::create($dataRol);
                redirect("/settings?tab=users", "location");
            } else {
                $_POST['created_at'] = $date;
                unset($_POST['PasswordRepeat']);
                $user = User::create($_POST);
                $dataRol['user_id'] = $user->id;
                $dataRol['rol_id'] = $idRol;
                $dataRol['created_date'] = $date;
                $rolUser = Rol_User::create($dataRol);
                redirect("/settings?tab=users", "location");
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function editUser($id = FALSE)
    {
        try {
            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d H:i:s");
            if ($_POST) {
                $config['upload_path'] = './files/Avatars/';
                $config['encrypt_name'] = TRUE;
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_width'] = '1000';
                $config['max_height'] = '1000';

                $idRol = 0;
                if ($_POST['role'] == 'admin') $idRol = 1;
                if ($_POST['role'] == 'sales') $idRol = 2;
                if ($_POST['role'] == 'waiter') $idRol = 3;
                if ($_POST['role'] == 'kitchen') $idRol = 4;

                $user = User::find($id);
                $rolUser = Rol_User::find('all', array('conditions' => array('user_id = ?', $id)));

                $this->load->library('upload', $config);
                if ($this->upload->do_upload()) {
                    $data = array(
                        'upload_data' => $this->upload->data()
                    );
                    $image = $data['upload_data']['file_name'];
                    unlink('./files/Avatars/' . $user->avatar);
                    $_POST['avatar'] = $image;
                    $_POST['created_at'] = $date;
                    unset($_POST['PasswordRepeat']);
                    if ($_POST['password'] === '')
                        unset($_POST['password']);
                    $user->update_attributes($_POST);

                    //ACTUALIZO ROL
                    Rol_User::update_all(array('set' => array('rol_id' => $idRol), 'conditions' => array('user_id = ?', $id)));

                    redirect("/settings?tab=users", "location");
                } else {
                    $_POST['created_at'] = $date;
                    unset($_POST['PasswordRepeat']);
                    if ($_POST['password'] === '')
                        unset($_POST['password']);
                    $user->update_attributes($_POST);

                    Rol_User::update_all(array('set' => array('rol_id' => $idRol), 'conditions' => array('user_id = ?', $id)));
                    redirect("/settings?tab=users", "location");
                }
            } else {

                $this->view_data['roles'] = Rol::all();
                $this->view_data['stores'] = Store::all();
                $this->view_data['user'] = User::find($id);
                $this->content_view = 'setting/modifyUser';
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    // Settings
    public function updateSettings()
    {
        $config['upload_path'] = './files/Setting/';
        $config['encrypt_name'] = TRUE;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_width'] = '1000';
        $config['max_height'] = '1000';

        $setting = Setting::find(1);

        $this->load->library('upload', $config);
        if ($this->upload->do_upload()) {
            $data = array(
                'upload_data' => $this->upload->data()
            );
            $image = $data['upload_data']['file_name'];
            unlink('./files/Setting/' . $setting->logo);
            $_POST['logo'] = $image;
            $setting->update_attributes($_POST);
            redirect("/settings?tab=setting", "location");
        } else {
            $setting->update_attributes($_POST);
            redirect("/settings?tab=setting", "location");
        }
    }

    public function addRol()
    {
        try {
            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d H:i:s");
            $_POST['created_date'] = $date;
            $_POST['store_id'] = 1;
            $rol = Rol::create($_POST);
            redirect("/settings?tab=roles", "location");
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function editRol($id = FALSE)
    {
        try {
            date_default_timezone_set($this->setting->timezone);
            $date = date("Y-m-d H:i:s");
            if ($_POST) {
                $rol = Rol::find($id);
                $_POST['created_date'] = $date;
                $rol->update_attributes($_POST);
                redirect("/settings?tab=roles", "location");
            } else {
                $this->view_data['rol'] = Rol::find($id);
                $this->content_view = 'setting/modifyRol';
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function deleteRol($id)
    {
        $rol = Rol::find($id);
        $rol->delete();
        redirect("/settings?tab=roles", "location");
    }
}
