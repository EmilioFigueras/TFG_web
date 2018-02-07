<?php
	class Admincustomer_database extends CI_Model {

		//Registra a un nuevo administrador global
		public function registration_user_customer_insert($data){
			//Comprobamos si el nombre de usuario existe
			$condition = "username =" . "'" . $data['username'] . "'";
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();

			//Si no existe el nombre de usuario
			if ($query->num_rows() == 0 && !empty($this->session->userdata['logged_in']['customer']))  {
				//Codificamos la contraseña
				$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
				$data['role'] = 3;
				$data['active'] = 1;
				$data['customer'] = $this->session->userdata['logged_in']['customer'];
				$data['name_db'] = $this->session->userdata['logged_in']['name_db'];

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

		//Obtiene los datos de los usuarios activos del cliente actual
		public function all_active_users(){
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				$this->db->select('u.*, r.rolename as rol');
				$this->db->from('users as u');
				$this->db->join('roles as r', 'r.id = u.role');
				$this->db->where("u.active = '1'");
				$this->db->where("u.name_db = '".$this->session->userdata['logged_in']['name_db']."'");
				$query = $this->db->get();
				if($query->num_rows() > 0)
					return $query->result();
				else 
					return false;
			}
			
		}

		//Obtiene los datos de un usuario
		public function data_user($id){
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				$this->db->select('u.*, r.rolename as rol');
				$this->db->from('users as u');
				$this->db->join('roles as r', 'r.id = u.role');
				$this->db->where("u.id = '".$id."'");
				$this->db->where("u.name_db = '".$this->session->userdata['logged_in']['name_db']."'");
				$query = $this->db->get();
				if($query->num_rows() > 0)
					return $query->row();
				else 
					return false;
			}
		}

		//Editar un usuario
		public function user_edit($data){
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Comprobamos si el nombre de la base de datos existe
				$condition = "id =" . "'" . $data['id'] . "'";
				$this->db->select('*');
				$this->db->from('users');
				$this->db->where($condition);
				$this->db->where("name_db = '".$this->session->userdata['logged_in']['name_db']."'");
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

		//Elimina a un usuario
		public function user_delete($data){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Comprobamos si el nombre de la base de datos existe
				$condition = "id =" . "'" . $data['id'] . "'";
				$this->db->select('*');
				$this->db->from('users');
				$this->db->where($condition);
				$this->db->where("name_db = '".$this->session->userdata['logged_in']['name_db']."'");
				$this->db->limit(1);
				$query = $this->db->get();

				//Si existe el id
				if ($query->num_rows() == 1) {
					$this->db->where('id', $data['id']);
					$this->db->delete('users');
					if ($this->db->affected_rows() > 0)
						return true;
					else 
						return false;
				}else
					return false;
			}
		}

		//Obtiene comentarios para valorar
		/*
			$valued = true si son comentarios valorados, false si son comentarios sin valorar
			$offset = Indica el offset donde empezar a descargar para comentarios valorados
		*/
		public function comments_value($valued, $offset=0){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);


				if($valued){ //Comentarios valorados
					$this->personal_db->select('c.*, p.*, a.personalRating');
					$this->personal_db->from('comments as c');
					$this->personal_db->join('products as p', 'c.idProduct = p.id', 'inner');
					$this->personal_db->join('analysis as a', 'c.autoid = a.idComment', 'inner');
					$this->personal_db->limit(10, $offset);
				}else{ //Comentarios no valorados
					$this->personal_db->select('c.*, p.*, a.personalRating');
					$this->personal_db->from('comments as c');
					$this->personal_db->join('products as p', 'c.idProduct = p.id', 'inner');
					$this->personal_db->join('analysis as a', 'c.autoid = a.idComment', 'left');
					$this->personal_db->where('a.personalRating is null');
					$this->personal_db->limit(10);
				}

				$query = $this->personal_db->get();
				if($query->num_rows() > 0)
					return $query->result();
				else 
					return false;
			}

		} 

		//Inserta o edita una valoracion
		public function insert_rate($data){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta
				$this->personal_db = $this->load->database('personal', TRUE);

				//Comprobamos si el comentario ya ha sido valorado
				$condition = "idComment =" . "'" . $data['idComment'] . "'";
				$this->personal_db->select('*');
				$this->personal_db->from('analysis');
				$this->personal_db->where($condition);
				$this->personal_db->limit(1);
				$query = $this->personal_db->get();

				//Si ya existe pues editamos
				if ($query->num_rows() == 1){
					$this->personal_db->where('idComment', $data['idComment']);
					$this->personal_db->update('analysis', $data);
					return "Update";

				}else{ //Si no existe pues insertamos
					//Insertamos en la base de datos
					$this->personal_db->insert('analysis', $data);
					if ($this->personal_db->affected_rows() > 0)
						return "Insert";
					else 
						return false;
				}
				
			}

		}

		//Obtenemos el nombre de las columnas de la base de datos
		public function get_structure_table(){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);

				$structure['comments'] = $this->personal_db->list_fields('comments');
				$structure['products'] = $this->personal_db->list_fields('products');

				if(!empty($structure['comments']) && !empty($structure['products'])){
					return $structure;
				}else
					return false;			
			}
		}

		//Obtiene los datos de los comentarios que se descargaran en un Excel
		/*
			$conditions = condiciones establecidas por el usuario
		*/
		public function get_comments_download($conditions){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);

				$this->personal_db->select('c.*, p.*, a.personalRating');
				$this->personal_db->from('comments as c');
				$this->personal_db->join('products as p', 'c.idProduct = p.id', 'inner');
				$this->personal_db->join('analysis as a', 'c.autoid = a.idComment', 'left');
				$this->personal_db->where($conditions);

				$query = $this->personal_db->get();
				//var_dump($query->result());die;
				if($query->num_rows() > 0)
					return $query->result();
				else 
					return false;
						
			}

		}

		//Obtiene todos los informes
		public function all_reports(){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);

				$this->personal_db->select('*');
				$this->personal_db->from('reports');

				$query = $this->personal_db->get();
				//var_dump($query->result());die;
				if($query->num_rows() > 0)
					return $query->result();
				else 
					return false;
						
			}
		}

		//Dada una ID obtiene el informe
		public function data_report($id){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);

				$this->personal_db->select('*');
				$this->personal_db->from('reports');
				$this->personal_db->where("autoid = '".$id."'");

				$query = $this->personal_db->get();
				//var_dump($query->result());die;
				if($query->num_rows() > 0)
					return $query->result();
				else 
					return false;
						
			}
		}

		//Inserta un nuevo informe
		public function insert_report($data){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);
				
				//Insertamos en la base de datos
				$this->personal_db->insert('reports', $data);
				if ($this->personal_db->affected_rows() > 0)
					return TRUE;
				else 
					return false;
				
			}
		}

		public function edit_report($id, $data){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);

				//Comprobamos si el nombre de la base de datos existe
				$this->personal_db->select('*');
				$this->personal_db->from('reports');
				$this->personal_db->where("autoid = '".$id."'");

				$query = $this->personal_db->get();

				//Si existe el id
				if ($query->num_rows() == 1) {
					$this->personal_db->where('autoid', $id);
					$this->personal_db->update('reports', $data);
					if ($this->personal_db->affected_rows() > 0)
						return true;
					else 
						return false;
				}else
					return false;
			}
		}

		public function delete_report($id){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);

				//Comprobamos si el nombre de la base de datos existe
				$this->personal_db->select('*');
				$this->personal_db->from('reports');
				$this->personal_db->where("autoid = '".$id."'");

				$query = $this->personal_db->get();

				//Si existe el id
				if ($query->num_rows() == 1) {
					$this->personal_db->where('autoid', $id);
					$this->personal_db->delete('reports');
					if ($this->personal_db->affected_rows() > 0)
						return true;
					else 
						return false;
				}else
					return false;
			}
		}
		
		//Ejecuta la query obtenida de la base de datos para obtener los resultados
		public function exec_query($select, $where=NULL, $groupby=NULL, $orderby=NULL){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);

				$this->personal_db->select($select);
				$this->personal_db->from('comments as c');
				$this->personal_db->join('products as p', 'c.idProduct = p.id', 'left');
				$this->personal_db->join('analysis as a', 'c.autoid = a.idComment', 'left');
				if(!empty($groupby)){
					$this->personal_db->group_by($groupby);
					if(!empty($where))
						$this->personal_db->having($where);
				}else if(!empty($where))
					$this->personal_db->where($where);
				if(!empty($orderby))
					$this->personal_db->order_by($orderby);

				$query = $this->personal_db->get();
				//var_dump($query->result());die;
				if($query->num_rows() > 0)
					return $query->result();
				else 
					return false;



			}
		}



	}

?>