<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permissions extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('menu_options', 'menu_options');
        $this->user = $this->session->userdata('user_id') ? User::find_by_id($this->session->userdata('user_id')) : FALSE;
        $lang = $this->session->userdata("lang") == null ? "english" : $this->session->userdata("lang");
        $this->lang->load($lang, $lang);
        $this->register = $this->session->userdata('register') ? $this->session->userdata('register') : FALSE;
    }

    function obtenerPermisosPorRol(){
        $idRol = $this->input->post('idRol');
        $opciones = $this->menu_options->ObtenerMenuPermisosPorRol($idRol);
        $htmlOpciones = '';
        $checked = '';
        $padding = '';
        $disabled = '';
        foreach ($opciones as $option) {
            if ($option->activemenu == 1 || ($option->father == 0 && $option->isMenu == 1)) $checked = 'checked';else $checked = '';
            if ($option->father == 0 && $option->isMenu == 1) $disabled = 'disabled';else $disabled = '';
            if ($option->level == 1){
                $padding = 'padding-left: 20px;';
            }elseif ($option->level == 2) {
                $padding = 'padding-left: 35px';
            }elseif ($option->level == 3) {
                $padding = 'padding-left: 50px';
            }else{
                $padding = 'padding-left: 35px;';
            }
            $htmlOpciones .= '<div class="row">';
            $htmlOpciones .= ' <div class="form-check" style="' . $padding . '">';
            $htmlOpciones .= '  <input ' . $disabled . ' style="display: inline-block;" ' . $checked .' class="form-check-input" type="checkbox" value="" id="chkOption_' . $option->id . '">';
            $htmlOpciones .= '  <label style="cursor: pointer;" class="form-check-label" for="chkOption_' . $option->id . '">' . $option->name . '</label>';
            $htmlOpciones .= '  </div>';
            $htmlOpciones .= '</div>';
        }
        echo ($htmlOpciones);
    }

    function guardarPermisos(){
        $idRol = $this->input->post('idRol');
        $permisos = json_decode($this->input->post('permisos'));
        // $this->menu_options->EliminarPermisosPorRol($idRol);
        foreach($permisos as $permiso)
        {
            $idOption = explode('_',$permiso->id);
            if(count(($this->menu_options->ContarPermisoPorRol($idRol,$idOption[1]))) > 0){
                $this->menu_options->ActualizarPermisoPorRol($idRol,$idOption[1],$permiso->value);
            }else{
                $this->menu_options->InsertarPermisoRol($idRol,$idOption[1],$permiso->value);
            }
        }
        echo 1;
    }

}
