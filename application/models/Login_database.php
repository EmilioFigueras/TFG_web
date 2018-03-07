<?php
	class Login_database extends CI_Model {

		//Insertar registro en la base de datos
		public function registration_insert($data) {

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
				$data['role'] = 2;
				//Insertamos en la base de datos
				$this->db->insert('users', $data);
				if ($this->db->affected_rows() > 0) {
					return true;
				}
			}else {
				return false; //Si el nombre de usuario ya existe
			}
		}

		//Comprueba que es correcto
		public function login($data) {

			$condition = "username =" . "'" . $data['username'] . "' and active = '1'";
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();

			if ($query->num_rows() == 1) {
				$res = $query->row();
				$hash = $res->password;
				return password_verify($data['password'], $hash);
			} else {
				return false; //No existe el usuario
			}
		}

		//Datos del usuario
		public function read_user_information($username) {
			$condition = "username =" . "'" . $username . "'";
			$this->db->select('users.*, roles.rolename');
			$this->db->from('users');
			$this->db->where($condition);
			$this->db->join('roles', 'users.role = roles.id');
			$this->db->limit(1);
			$query = $this->db->get();

			if ($query->num_rows() == 1) {
				return $query->result();
			} else {
				return false;
			}
		}

		//Cambiar contraseña
		public function change_password($data){
			//Comprobamos si el nombre de la base de datos existe
			$condition = "id =" . "'" . $data['id'] . "'";
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();

			//Si existe el id
			if ($query->num_rows() == 1) {
				$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
				$this->db->where('id', $data['id']);
				$this->db->update('users', $data);
				if ($this->db->affected_rows() > 0)
					return true;
				else 
					return false;
			}else
				return false;
		}

		//Comprueba que el nombre de usuario y correo son reales
		public function check_data($data){
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where("username", $data['username']);
			$this->db->where("email", $data["email"]);
			$query = $this->db->get();

			//Si existe el id
			if ($query->num_rows() == 1) {
				return $query->row();

			}else
				return false;

		}

	}

?>