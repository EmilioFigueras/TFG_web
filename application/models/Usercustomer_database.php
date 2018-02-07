<?php
	class Usercustomer_database extends CI_Model {

		//Obtiene todos los informes
		public function all_reports_active(){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				//Accedemos a la base de datos correcta 
				$this->personal_db = $this->load->database('personal', TRUE);
				$this->db->select('*');
				$this->db->from('reports');
				$this->db->where('active', '1');
				$this->db->order_by('title', 'ASC');

				$query = $this->db->get();
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
				$this->db->select('*');
				$this->db->from('reports');
				$this->db->where("autoid = '".$id."'");

				$query = $this->db->get();
				//var_dump($query->result());die;
				if($query->num_rows() > 0)
					return $query->result();
				else 
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

				$this->db->select($select);
				$this->db->from('comments as c');
				$this->db->join('products as p', 'c.idProduct = p.id', 'left');
				$this->db->join('analysis as a', 'c.autoid = a.idComment', 'left');
				if(!empty($groupby)){
					$this->db->group_by($groupby);
					$this->db->having($where);
				}else if(!empty($where))
					$this->db->where($where);
				if(!empty($orderby))
					$this->db->order_by($orderby);

				$query = $this->db->get();
				//var_dump($query->result());die;
				if($query->num_rows() > 0)
					return $query->result();
				else 
					return false;



			}
		}

		public function get_admin_email(){
			//Obtenemos la base de datos que debemos consultar
			if(empty($this->session->userdata['logged_in']['name_db']))
				return false;
			else{
				$this->db->select('email');
				$this->db->from('users');
				$this->db->where('name_db', $this->session->userdata['logged_in']['name_db']);
				$this->db->where('active', 1);
				$this->db->where('role', 2);

				$query = $this->db->get();
				//var_dump($query->result());die;
				if($query->num_rows() > 0)
					return $query->result();
				else 
					return false;

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

				$structure['comments'] = $this->db->list_fields('comments');
				$structure['products'] = $this->db->list_fields('products');

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

				$this->db->select('c.*, p.*, a.personalRating');
				$this->db->from('comments as c');
				$this->db->join('products as p', 'c.idProduct = p.id', 'inner');
				$this->db->join('analysis as a', 'c.autoid = a.idComment', 'left');
				$this->db->where($conditions);

				$query = $this->db->get();
				//var_dump($query->result());die;
				if($query->num_rows() > 0)
					return $query->result();
				else 
					return false;
						
			}

		}

	}

?>