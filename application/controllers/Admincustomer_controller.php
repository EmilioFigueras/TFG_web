<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admincustomer_controller extends CI_Controller {
	public function __construct() {
		parent::__construct();

		// Load form helper library
		$this->load->helper('form');

		// Load form validation library
		$this->load->library('form_validation');

		// Load session library
		$this->load->library('session');

		// Load database
		$this->load->model('login_database');
		$this->load->model('admincustomer_database');
	}

	private function delete_mark($cadena){
	    //Codificamos la cadena en formato utf8 en caso de que nos de errores
	    //$cadena = utf8_encode($cadena);
	 
	    //Ahora reemplazamos las letras
	    $cadena = str_replace(
	        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
	        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
	        $cadena
	    );
	 
	    $cadena = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
	        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
	        $cadena );
	 
	    $cadena = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
	        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
	        $cadena );
	 
	    $cadena = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
	        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
	        $cadena );
	 
	    $cadena = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
	        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
	        $cadena );

	    $cadena = str_replace("\n", " ", $cadena);
	 
	    return $cadena;
	}


	//Vista de registro de un nuevo usuario para el cliente
	public function user_registration(){
		$this->load->view('header');
		$this->load->view('admin_customer_bars');
		$this->load->view('user_registration_form');
		$this->load->view('footer');
	}

	//Registrar nuevo usuario
	public function new_user_registration(){
		//Validamos usuario y contraseña
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length[6]');
		if ($this->form_validation->run() == FALSE) {
			$data['msg_db'] = 'Error en el formulario';
			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('user_registration_form', $data);
			$this->load->view('footer');
		} else {
			$data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => $this->input->post('password')
				);
			$result = $this->admincustomer_database->registration_user_customer_insert($data);
			if ($result == TRUE) {
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_ok'] = 'OK: Se ha registrado correctamente';


				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			} else {
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_db'] = 'Ese nombre de usuario ya existe';
				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			}
		}
	}

	//Vista del formulario de edicion
	public function edit_form($id){
		$data['data_user'] = $this->admincustomer_database->data_user($id);
		$this->load->view('header');
		$this->load->view('admin_customer_bars');
		$this->load->view('admin_customer_page_edit', $data);
		$this->load->view('footer');
	}

	//Modificar el rol de un usuario
	public function edit_role(){
		$this->form_validation->set_rules('id', 'Id', 'trim|required|xss_clean');
		$this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			redirect('login', 'refresh');
		}
		else{
			$data = array(
				'id' => $this->input->post('id'),
				'role' => $this->input->post('role')
				);
			$result = $this->admincustomer_database->user_edit($data);
			if(!$result){
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_db'] = "¡ERROR!: ID erronea.";

				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			}else{
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_ok'] = "¡BIEN HECHO!: Rol actualizado correctamente.";

				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			}
		}
	}

	//Modificar el password de un usuario
	public function edit_password(){
		$this->form_validation->set_rules('id', 'Id', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length[6]');
		if ($this->form_validation->run() == FALSE) {
			$data['all_active_users'] = $this->admincustomer_database->all_active_users();
			$data['msg_db'] = "Error en el formulario.";

			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('admin_customer_page', $data);
			$this->load->view('footer');
		}
		else{
			$data = array(
				'id' => $this->input->post('id'),
				'password' => $this->input->post('password')
				);
			$result = $this->login_database->change_password($data);
			if(!$result){
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_db'] = "¡ERROR!: ID erronea.";

				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			}else{
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_ok'] = "¡BIEN HECHO!: Contraseña actualizada correctamente.";

				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			}
		}
	}

	//Desactivar a un usuario
	public function disable_user(){
		$this->form_validation->set_rules('id', 'Id', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			redirect('login', 'refresh');
		}
		else{
			$data = array(
				'id' => $this->input->post('id'),
				'active' => 0
				);
			$result = $this->admincustomer_database->user_edit($data);
			if(!$result){
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_db'] = "¡ERROR!: ID erronea.";

				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			}else{
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_ok'] = "¡BIEN HECHO!: Usuario desactivado.";

				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			}
		}
	}

	//Elimina para siempre a un usuario
	public function delete_user(){
		$this->form_validation->set_rules('id', 'Id', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			redirect('login', 'refresh');
		}
		else{
			$data = array(
				'id' => $this->input->post('id')
				);
			$result = $this->admincustomer_database->user_delete($data);
			if(!$result){
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_db'] = "¡ERROR!: ID erronea.";

				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			}else{
				$data['all_active_users'] = $this->admincustomer_database->all_active_users();
				$data['msg_ok'] = "¡BIEN HECHO!: Usuario eliminado.";

				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('admin_customer_page', $data);
				$this->load->view('footer');
			}
		}
	}

	//Carga la vista de las opciones sobre los comentarios
	public function comments_options(){
		//Obtenemos el nombre de las columnas
		$data['structure'] = $this->admincustomer_database->get_structure_table();

		$this->load->view('header');
		$this->load->view('admin_customer_bars');
		$this->load->view('comments_options', $data);
		$this->load->view('footer');

	}

	//Carga la vista de la valoracion de comentarios
	public function comments_rate(){
		if(strcmp($this->input->post('comments_rate'), 'valued') == 0){
			$data['comments'] = $this->admincustomer_database->comments_value(true);
		}else{
			$data['comments'] = $this->admincustomer_database->comments_value(false);
		}

		$this->load->view('header');
		$this->load->view('admin_customer_bars');
		$this->load->view('comments_rate', $data);
		$this->load->view('footer');
	}

	//Inserta o edita las puntuaciones
	public function insert_rate(){
		for($i=1; $i<=10; $i++){
			$this->form_validation->set_rules('comments_rate_'.$i, 'Comments_rate_'.$i, 'trim|required|xss_clean');
			$this->form_validation->set_rules('comments_idComment_'.$i, 'Comments_idComment_'.$i, 'trim|required|xss_clean');
		}
		$this->form_validation->set_rules('offset', 'Offset', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) { //Si falla
			//Volvemos a la pagina anterior
			for($i=1; $i<=10; $i++){
				$data['comments'][$i]['autoid'] = $this->input->post('comments_idComment_'.$i);
				$data['comments'][$i]['personalRating'] = $this->input->post('comments_rate_'.$i);
				$data['comments'][$i]['comment'] = $this->input->post('comments_comment_'.$i);
			}
			$data['offset'] = $this->input->post('offset');
			$data['msg_db'] = "¡ERROR!: Una o más lineas no han sido volaradas.";

			$data = json_decode(json_encode($data), FALSE); //Lo convertimos en un objeto

			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('comments_rate', $data);
			$this->load->view('footer');
		}else{
			for($i=1; $i<=10; $i++){
				$data_db = array(
					'idComment' => $this->input->post('comments_idComment_'.$i),
					'personalRating' => $this->input->post('comments_rate_'.$i)
					);
				$result = $this->admincustomer_database->insert_rate($data_db);
				if(!$result)
					$data['msg_db'] = "¡ERROR!: Algo ha ido mal...";
			}
			if(!isset($data['msg_db']))
				$data['msg_ok'] = "OK: Todo correcto.";

			$data['offset'] = $this->input->post('offset') + 10;
			if(strcmp($result, 'Update') == 0){ //Comentarios valorados
				$data['comments'] = $this->admincustomer_database->comments_value(true, $data['offset']);
			}else if(strcmp($result, 'Insert') == 0){ //Comentarios nunca valorados
				$data['comments'] = $this->admincustomer_database->comments_value(false);
			}

			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('comments_rate', $data);
			$this->load->view('footer');


		}
	}

	//Descarga los comentarios dado unos datos de entrada y genera un Excel para que el usuario se los descargue
	public function comments_download(){
		$this->form_validation->set_rules('comments_download', 'Comments_download', 'trim|required|xss_clean');
		$this->form_validation->set_rules('words', 'Words', 'trim|required|xss_clean');
		$this->form_validation->set_rules('products_active', 'Products_active', 'trim|xss_clean');
		$this->form_validation->set_rules('products_text', 'Products_text', 'trim|xss_clean');
		$this->form_validation->set_rules('comments_active', 'Comments_active', 'trim|xss_clean');
		$this->form_validation->set_rules('comments_text', 'Comments_text', 'trim|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			redirect('login', 'refresh');
		}
		else{
			//Preparamos el array de condiciones segun los datos recibidos
			$conditions = array();

			//Comentarios valorados o no valorados
			if(strcmp($this->input->post('comments_download'), "not_valued") === 0){ //Comentarios no valorados
				$conditions = array_merge($conditions, array("a.personalRating is null" => null));
			}else{ //Comentarios sí valorados
				$conditions = array_merge($conditions, array("a.personalRating is not null" => null));
			}

			//Restriccion de productos
			if(!empty($this->input->post('products_active')) && strcmp($this->input->post('products_text'), "") !== 0){ //Se ha activado los productos
				
				$cond = "p.".$this->input->post('products_field')." ".$this->input->post('products_symbol');
				if(strpos($this->input->post('products_symbol'), "like") !== false){ //Si es un like
					$text = "%".$this->input->post('products_text')."%"; //Le agregamos los %
				}else{
					$text = $this->input->post('products_text');
				}

				$conditions = array_merge($conditions, array($cond => $text));
			}

			//Restriccion de comentarios
			if(!empty($this->input->post('comments_active')) && strcmp($this->input->post('comments_text'), "") !== 0){ //Se ha activado los comentarios
				
				$cond = "c.".$this->input->post('comments_field')." ".$this->input->post('comments_symbol');
				if(strpos($this->input->post('comments_symbol'), "like") !== false){ //Si es un like
					$text = "%".$this->input->post('comments_text')."%"; //Le agregamos los %
				}else{
					$text = $this->input->post('comments_text');
				}

				$conditions = array_merge($conditions, array($cond => $text));
			}


			$result = $this->admincustomer_database->get_comments_download($conditions);
			$result = json_decode(json_encode($result), TRUE); //Lo convertimos en un array


			//Eliminar tildes y poner todo en minusculas
			foreach ($result as $key => $value) {
				$result[$key]['comment'] = trim(strtolower($this->delete_mark($result[$key]['comment'])));
			}

			//Eliminar las palabras prohibidas que aparezcan
			if(strcmp($this->input->post('words'), "Seperar por comas (los espacios serán tenidos en cuenta)") !== 0){ //Se ha utilizado las palabras prohibidas
				$words = explode(",", strtolower($this->delete_mark($this->input->post('words'))));
				//Bucle que recorra resultados, foreach que recorra palabras prohibidas y eliminarlas
				foreach ($result as $key => $value) {
					//$result[$key]['comment'] = strtolower($this->delete_mark($result[$key]['comment']));
					foreach ($words as $key2 => $value2) {
						$result[$key]['comment'] = str_replace($value2, " ", $result[$key]['comment']);
					}
				}
			}

			//Creamos el fichero Excel
			//Cargamos la libreria PHPExcel
			$this->load->library('excel');
			//Activamos la Primera Hoja
			$this->excel->setActiveSheetIndex(0);
			//Le damos un nombre
			$this->excel->getActiveSheet()->setTitle('KnowledgeAnalysis');


			//Nombre del fichero
			$filename='ToKnowledgeAnalysis_'.date('Y_m_d_H:i:s').'.xlsx';

			//Nombre de columnas
			//Fijas
			$this->excel->getActiveSheet()->setCellValue('A1', 'Product')
			->setCellValue('B1', 'Comment')
			->setCellValue('C1', 'PersonalRating');

			$index = 'D';

			//Agregamos el resto de columnas extras
			$keys = array_keys($result[0]);
			foreach ($keys as $key => $value) {
				if(strcasecmp("name", $value) != 0 && strcasecmp("comment", $value) != 0 && strcasecmp("personalRating", $value) != 0){
					$this->excel->getActiveSheet()->setCellValue($index.'1', $value);
					$index++;
				}
			}

			//Rellenamos el Excel
			$index_num = '2';
			foreach ($result as $key => $value) {
				//Datos fijos
				$this->excel->getActiveSheet()->setCellValue('A'.$index_num, trim($value['name']))
				->setCellValue('B'.$index_num, wordwrap($value['comment'], 40, "\n", FALSE))
				->setCellValue('C'.$index_num, trim($value['personalRating']));

				//Buscamos datos de columnas extras
				$index = 'D';
				foreach ($keys as $key2 => $value2) {
					if(is_numeric($value[$value2])){
						$result[$key][$keys[$key2]] = str_replace(",", ".", $value[$value2]);
						//$this->excel->getActiveSheet()->getStyle($index.$index_num)->getNumberFormat()->setFormatCode('#,##0.00');
					}
					if(strcasecmp("name", $value2) != 0 && strcasecmp("comment", $value2) != 0 && strcasecmp("personalRating", $value2) != 0){
						$this->excel->getActiveSheet()->setCellValue($index.$index_num, trim($result[$key][$keys[$key2]]));
						$index++;
					}
				}
				$index_num++;
			}

			//Redimensionamos las columnas
			foreach(range('A', $index) as $columnID) {
				if(strcasecmp("B", $columnID) != 0)
			    	$this->excel->getActiveSheet()->getColumnDimension($columnID)
			        	->setAutoSize(true);
			}

			//Cabeceras
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0'); 
			            
			//Formato xls => 'Excel5' o formato xlsx => 'Excel2007'
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
			//Sacamos la ventana de descarga por pantalla
			$objWriter->save('php://output');


		}
	}

	//Vista inicial de los informes
	public function reports_form(){
		$data['all_reports'] = $this->admincustomer_database->all_reports();

		$this->load->view('header');
		$this->load->view('admin_customer_bars');
		$this->load->view('reports_form', $data);
		$this->load->view('footer');
	}

	//Formulario de edicion de informe
	public function edit_report_form($id){
		$data['data_report'] = $this->admincustomer_database->data_report($id);
		$data['structure'] = $this->admincustomer_database->get_structure_table();


		$this->load->view('header');
		$this->load->view('admin_customer_bars');
		$this->load->view('edit_report_form', $data);
		$this->load->view('footer');
	}

	//Edicion de informe
	public function edit_report($id){
		//Validamos los datos
		$this->form_validation->set_rules('title_report', 'Title_report', 'trim|required|xss_clean');
		$this->form_validation->set_rules('description_report', 'Description_report', 'trim|required|xss_clean');
		$this->form_validation->set_rules('type_report', 'Type_report', 'trim|required|xss_clean');
		$this->form_validation->set_rules('active_report', 'Active_report', 'trim|xss_clean');
		$this->form_validation->set_rules('query_report_select', 'Query_report_select', 'trim|required');
		$this->form_validation->set_rules('query_report_where', 'Query_report_where', 'trim');
		$this->form_validation->set_rules('query_report_groupby_active', 'Query_report_groupby_active', 'trim|xss_clean');
		$this->form_validation->set_rules('query_report_groupby', 'Query_report_groupby', 'trim|xss_clean');
		$this->form_validation->set_rules('query_report_orderby_active', 'Query_report_orderby_active', 'trim|xss_clean');
		$this->form_validation->set_rules('query_report_orderby', 'Query_report_orderby', 'trim|xss_clean');


		if ($this->form_validation->run() == FALSE) {
			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('user_registration_form');
			$this->load->view('footer');
		} else {
			//Creamos el array con los datos requeridos para insertar
			$data = array(
				'title' => $this->input->post('title_report'),
				'description' => $this->input->post('description_report'),
				'type' => $this->input->post('type_report'),
				'query_select' => $this->input->post('query_report_select')
				);

			//Ahora agregamos al array los datos opcionales
			if(!empty($this->input->post('query_report_groupby_active')) && strcmp($this->input->post('query_report_groupby'), "") !== 0){ //Se ha activado el group by
				$data['query_groupby'] = $this->input->post('query_report_groupby');
			}else{
				$data['query_groupby'] = NULL;
			}
			if(!empty($this->input->post('query_report_orderby_active')) && strcmp($this->input->post('query_report_orderby'), "") !== 0){ //Se ha activado el order by
				$data['query_orderby'] = $this->input->post('query_report_orderby');
			}else{
				$data['query_orderby'] = NULL;
			}
			if(strcmp($this->input->post('query_report_where'), "") !== 0){ //Hay un where
				$data['query_where'] = $this->input->post('query_report_where');
			}else{
				$data['query_where'] = NULL;
			}
			if(!empty($this->input->post('active_report'))){ //Se ha activado el informe
				$data['active'] = '1';
			}else{
				$data['active'] = '0';
			}


			$result = $this->admincustomer_database->edit_report($id, $data);
			if ($result == TRUE) {
				$data['all_reports'] = $this->admincustomer_database->all_reports();
				$data['msg_ok'] = 'OK: Se ha editado correctamente';


				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('reports_form', $data);
				$this->load->view('footer');
			} else {
				$data['all_reports'] = $this->admincustomer_database->all_reports();
				$data['msg_db'] = 'ERROR: Error al editar el informe';
				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('reports_form', $data);
				$this->load->view('footer');
			}
		}
	}

	//Llamar a la vista de crear un nuevo informe
	public function new_report_form(){
		$data['structure'] = $this->admincustomer_database->get_structure_table();

		$this->load->view('header');
		$this->load->view('admin_customer_bars');
		$this->load->view('new_report_form', $data);
		$this->load->view('footer');
	}

	//Crear un nuevo informe
	public function new_report(){
		//Validamos los datos
		$this->form_validation->set_rules('title_report', 'Title_report', 'trim|required|xss_clean');
		$this->form_validation->set_rules('description_report', 'Description_report', 'trim|required|xss_clean');
		$this->form_validation->set_rules('type_report', 'Type_report', 'trim|required|xss_clean');
		$this->form_validation->set_rules('active_report', 'Active_report', 'trim|xss_clean');
		$this->form_validation->set_rules('query_report_select', 'Query_report_select', 'trim|required|xss_clean');
		$this->form_validation->set_rules('query_report_where', 'Query_report_where', 'trim|xss_clean');
		$this->form_validation->set_rules('query_report_groupby_active', 'Query_report_groupby_active', 'trim|xss_clean');
		$this->form_validation->set_rules('query_report_groupby', 'Query_report_groupby', 'trim|xss_clean');
		$this->form_validation->set_rules('query_report_orderby_active', 'Query_report_orderby_active', 'trim|xss_clean');
		$this->form_validation->set_rules('query_report_orderby', 'Query_report_orderby', 'trim|xss_clean');


		if ($this->form_validation->run() == FALSE) {
			$data['all_reports'] = $this->admincustomer_database->all_reports();
			$data['msg_db'] = 'ERROR: Los datos introducidos son incorrectos.';


			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('reports_form', $data);
			$this->load->view('footer');
		} else {
			//Creamos el array con los datos requeridos para insertar
			$data = array(
				'title' => $this->input->post('title_report'),
				'description' => $this->input->post('description_report'),
				'type' => $this->input->post('type_report'),
				'query_select' => $this->input->post('query_report_select')
				);

			//Ahora agregamos al array los datos opcionales
			if(!empty($this->input->post('query_report_groupby_active')) && strcmp($this->input->post('query_report_groupby'), "") !== 0){ //Se ha activado el group by
				$data['query_groupby'] = $this->input->post('query_report_groupby');
			}
			if(!empty($this->input->post('query_report_orderby_active')) && strcmp($this->input->post('query_report_orderby'), "") !== 0){ //Se ha activado el order by
				$data['query_orderby'] = $this->input->post('query_report_orderby');
			}
			if(strcmp($this->input->post('query_report_where'), "") !== 0){ //Hay un where
				$data['query_where'] = $this->input->post('query_report_where');
			}
			if(!empty($this->input->post('active_report'))){ //Se ha activado el informe
				$data['active'] = '1';
			}


			$result = $this->admincustomer_database->insert_report($data);
			if ($result == TRUE) {
				$data['all_reports'] = $this->admincustomer_database->all_reports();
				$data['msg_ok'] = 'OK: Se ha creado correctamente';


				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('reports_form', $data);
				$this->load->view('footer');
			} else {
				$data['all_reports'] = $this->admincustomer_database->all_reports();
				$data['msg_db'] = 'ERROR: Error al crear el informe';
				$this->load->view('header');
				$this->load->view('admin_customer_bars');
				$this->load->view('reports_form', $data);
				$this->load->view('footer');
			}
		}
	}


	//Muestra el grafico del informe seleccionado
	public function show_report($id){
		$data['report_sel'] = $this->admincustomer_database->data_report($id);

		if($data['report_sel']){
			//Formamos la consulta del informe
			$select = $data['report_sel'][0]->query_select;
			$where = $data['report_sel'][0]->query_where;
			$groupby = $data['report_sel'][0]->query_groupby;
			$orderby = $data['report_sel'][0]->query_orderby;

			$data['results'] = $this->admincustomer_database->exec_query($select, $where, $groupby, $orderby);
			$data['title'] = $data['report_sel'][0]->title;
			$data['type'] = $data['report_sel'][0]->type;
			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('show_report', $data);
			$this->load->view('footer');


		}else{
			$data['all_reports'] = $this->admincustomer_database->all_reports();
			$data['msg_db'] = 'ERROR: Error al mostrar el informe';
			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('reports_form', $data);
			$this->load->view('footer');
		}
	}

	//Elimina un informe
	public function delete_report($id){
		$result = $this->admincustomer_database->delete_report($id);

		if($result == TRUE){
			$data['all_reports'] = $this->admincustomer_database->all_reports();
			$data['msg_ok'] = 'ELIMINADO: Informe eliminado correctamente.';
			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('reports_form', $data);
			$this->load->view('footer');
		}else{
			$data['all_reports'] = $this->admincustomer_database->all_reports();
			$data['msg_db'] = 'ERROR: Error al eliminar el informe.';
			$this->load->view('header');
			$this->load->view('admin_customer_bars');
			$this->load->view('reports_form', $data);
			$this->load->view('footer');
		}

		
	}





}