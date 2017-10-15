<?php
	class Admin_database extends CI_Model {


		//Obtiene los administradores de los clientes inactivos
		public function inactive_admin_client_users(){
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where("role = '2'");
			$this->db->where("active = '0'");
			$query = $this->db->get();
			if($query->num_rows() > 0)
				return $query->result();
			else 
				return false;
		}


		//Obtiene los datos de un usuario
		public function data_user($id){
			$this->db->select('u.*, r.rolename as rol');
			$this->db->from('users as u');
			$this->db->join('roles as r', 'r.id = u.role');
			$this->db->where("u.id = '".$id."'");
			$query = $this->db->get();
			if($query->num_rows() > 0)
				return $query->row();
			else 
				return false;
		}

		//Activa a un usuario
		public function activate_insert($data){
			//Comprobamos si el nombre de la base de datos existe
			$condition = "name_db =" . "'" . $data['name_db'] . "'";
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();

			//Si no existe el nombre de usuario
			if ($query->num_rows() == 0) {
				$this->db->where('id', $data['id']);
				$this->db->update('users', $data);
				if ($this->db->affected_rows() > 0)
					return true;
				else 
					return false;
			}else
				return false;
				
		}

		//Registra a un nuevo administrador global
		public function registration_admin_insert($data){
			//Comprobamos si el nombre de usuario existe
			$condition = "username =" . "'" . $data['username'] . "'";
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();

			//Si no existe el nombre de usuario
			if ($query->num_rows() == 0) {
				//Codificamos la contraseña
				$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
				$data['role'] = 1;
				$data['active'] = 1;

				//Insertamos en la base de datos
				$this->db->insert('users', $data);
				if ($this->db->affected_rows() > 0) {
					return true;
				}else
					return false;
			}else {
				return false; //Si el nombre de usuario ya existe
			}
		}


		//Obtiene los datos de los usuarios activos
		public function all_active_users(){
			$this->db->select('u.*, r.rolename as rol');
			$this->db->from('users as u');
			$this->db->join('roles as r', 'r.id = u.role');
			$this->db->where("u.active = '1'");
			$query = $this->db->get();
			if($query->num_rows() > 0)
				return $query->result();
			else 
				return false;
		}


		//Editar un usuario
		public function user_edit($data){
			//Comprobamos si el nombre de la base de datos existe
			$condition = "id =" . "'" . $data['id'] . "'";
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();

			//Si existe el id
			if ($query->num_rows() == 1) {
				$this->db->where('id', $data['id']);
				$this->db->update('users', $data);
				if ($this->db->affected_rows() > 0)
					return true;
				else 
					return false;
			}else
				return false;
		}



	}

?>