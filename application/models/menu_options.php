<?php

class Menu_Options extends CI_Model
{

   var $table = 'zarest_menu_options';

   public function __construct()
   {
      parent::__construct();
      $this->load->database();
   }

   function ObtenerMenuPorRolUsuario($idRol)
   {
      $this->db->select('T1.*');
      $this->db->from('zarest_menu_options AS T1');
      $this->db->join('zarest_rol_permissions AS T2', 'T1.id = T2.menu_id');
      $this->db->where('T2.rol_id', $idRol);
      $this->db->where('T1.active', 1);
      $this->db->where('T2.active', 1);
      $this->db->where('T1.isMenu', 1);
      $this->db->order_by('T1.id', 'T1.order');
      $query = $this->db->get();
      return $query->result();
   }

   function ObtenerSubMenuPorIdPadre($rol_id, $idPadre)
   {
      $this->db->select('T1.*');
      $this->db->from('zarest_menu_options AS T1');
      $this->db->join('zarest_rol_permissions AS T2', 'T1.id = T2.menu_id AND T2.rol_id = ' . $rol_id);
      $this->db->where('T1.father', $idPadre);
      $this->db->where('T1.active', 1);
      $this->db->where('T2.active', 1);
      $this->db->where('T1.isMenu', 1);
      $this->db->order_by('T1.id', 'T1.order');
      $query = $this->db->get();
      return $query->result();
   }

   function getById($id, $rol_id)
   {
      $this->db->select('IFNULL(T2.active,0) AS `active`', false);
      $this->db->from('zarest_menu_options AS T1');
      $this->db->join('zarest_rol_permissions AS T2', 'T1.id = T2.menu_id AND T2.rol_id = ' . $rol_id);
      $this->db->where('T1.id', $id);
      $query = $this->db->get();
      return $query->row();
   }

   function ObtenerMenuPermisosPorRol($idRol)
   {
      $this->db->select('T1.*,IFNULL(T2.active,0) AS `activemenu`', false);
      $this->db->from('zarest_menu_options AS T1');
      $this->db->join('zarest_rol_permissions AS T2', 'T1.id = T2.menu_id AND T2.active = 1 AND T2.rol_id = ' . $idRol, 'left');
      $this->db->where('T1.active', 1);
      $this->db->order_by('T1.id', 'T1.order');
      $query = $this->db->get();
      return $query->result();
   }

   function EliminarPermisosPorRol($idRol)
   {
      $this->db->delete('zarest_rol_permissions', array('rol_id' => $idRol));
   }

   function ContarPermisoPorRol($idRol, $idMenu)
   {
      $this->db->select('T1.*');
      $this->db->from('zarest_rol_permissions AS T1');
      $this->db->where('T1.rol_id', $idRol);
      $this->db->where('T1.menu_id', $idMenu);
      $query = $this->db->get();
      return $query->result();
   }

   function ActualizarPermisoPorRol($idRol, $idMenu, $valor)
   {
      $data = array(
         'active' => $valor
      );

      $this->db->where('rol_id', $idRol);
      $this->db->where('menu_id', $idMenu);
      $this->db->update('zarest_rol_permissions', $data);
   }

   function InsertarPermisoRol($idRol, $idMenu, $valor)
   {
      $data = array(
         'menu_id' => $idMenu,
         'rol_id' => $idRol,
         'created_date' => date("Y-m-d H:i:s"),
         'active' => $valor
      );

      $this->db->insert('zarest_rol_permissions', $data);
   }
}
