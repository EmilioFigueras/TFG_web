<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usercustomer_controller extends CI_Controller {
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
		$this->load->model('usercustomer_database');
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

	//Muestra el grafico del informe seleccionado
	public function show_report($id){
		$data['report_sel'] = $this->usercustomer_database->data_report($id);

		if($data['report_sel']){
			//Formamos la consulta del informe
			$select = $data['report_sel'][0]->query_select;
			$where = $data['report_sel'][0]->query_where;
			$groupby = $data['report_sel'][0]->query_groupby;
			$orderby = $data['report_sel'][0]->query_orderby;

			$data['results'] = $this->usercustomer_database->exec_query($select, $where, $groupby, $orderby);
			$data['title'] = $data['report_sel'][0]->title;
			$data['type'] = $data['report_sel'][0]->type;
			$this->load->view('header');
			$this->load->view('user_customer_bars');
			$this->load->view('show_report', $data);
			$this->load->view('footer');


		}else{
			$data['msg_db'] = 'ERROR: Error al mostrar el informe';
			$data['all_reports'] = $this->usercustomer_database->all_reports_active();
			$this->load->view('header');
			$this->load->view('user_customer_bars');
			$this->load->view('user_customer_page', $data);
			$this->load->view('footer');
		}
	}

	//Muestra el formulario de envio de email
	public function report_error_form($id_report){
		$data['report_sel'] = $this->usercustomer_database->data_report($id_report);

		$this->load->view('header');
		$this->load->view('user_customer_bars');
		$this->load->view('report_error_form', $data);
		$this->load->view('footer');
	}

	public function report_error(){
		$this->form_validation->set_rules('description_report', 'Description_report', 'xss_clean');
		$this->form_validation->set_rules('title_report', 'Title_report', 'trim|required|xss_clean');
		$this->form_validation->set_rules('id_report', 'Id_report', 'trim|required|xss_clean');


		if ($this->form_validation->run() == FALSE) {
			$data['msg_db'] = 'ERROR: Datos incorrectos en la descripción dada';
			$data['all_reports'] = $this->usercustomer_database->all_reports_active();
			$this->load->view('header');
			$this->load->view('user_customer_bars');
			$this->load->view('user_customer_page', $data);
			$this->load->view('footer');
		} else {
			$title = $this->input->post('title_report');
			$id = $this->input->post('id_report');
			$description = $this->input->post('description_report');
			$username = $this->input->post('username_report');
			$email = $this->input->post('email_report');
			//cargamos la libreria email de ci
			$this->load->library("email");

			//configuracion para gmail
			$configMail = array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://in-v3.mailjet.com',
				'smtp_port' => 465,
				'smtp_user' => '65edf6fe937597e09afb14b41fd92a14',
				'smtp_pass' => 'b5b2823a87a192c36f2d4e3d493dc1cb',
				'mailtype' => 'html',
				'charset' => 'utf-8',
				'newline' => "\r\n"
			);

			//Enviamos el email
			$get_emails = $this->usercustomer_database->get_admin_email();//Obtenemos el correo electronico del administrador
			if(!$get_emails){
				$data['msg_db'] = 'ERROR: Email del administrador no encontrado';
				$data['all_reports'] = $this->usercustomer_database->all_reports_active();
				$this->load->view('header');
				$this->load->view('user_customer_bars');
				$this->load->view('user_customer_page', $data);
				$this->load->view('footer');
			}else{
				$description = str_replace("\n", "<br>", $description);

				$emails = $get_emails[0]->email;
				for ($i=1; $i < count($get_emails); $i++) { 
					$emails .= ",".$value->email;
				}

				$to = $emails; 

				$from = 'figmare@hotmail.com';
				$asunto = "Incidencia con el informe ".$id;
				$cuerpo = ' 
					<html> 
					<head> 
					   <title>Reporte del informe '.$id.'</title> 
					</head> 
					<body> 
					<h1>'.$title.'</h1> 
					<br> 
					El usuario <b>'.$username.'</b> ha reportado el informe indicado por el siguiente motivo: <br>
					<br>'.$description.'<br>
					Puedes contactar con el usuario a través de su dirección de email: '.$email.'
					</body> 
					</html> 
					';

				$this->email->initialize($configMail);
				$this->email->from($from);
				$this->email->to($to);
				$this->email->subject($asunto);
				$this->email->message($cuerpo);
				$this->email->send();
				
				$data['all_reports'] = $this->usercustomer_database->all_reports_active();
				$data['msg_ok'] = 'OK: Email enviado correctamente.';
				$this->load->view('header');
				$this->load->view('user_customer_bars');
				$this->load->view('user_customer_page', $data);
				$this->load->view('footer');

			}


		}

	}

	//Carga la vista de las descargas sobre los comentarios
	public function comments_options(){
		//Obtenemos el nombre de las columnas
		$data['structure'] = $this->usercustomer_database->get_structure_table();

		$this->load->view('header');
		$this->load->view('user_customer_bars');
		$this->load->view('user_comments_options', $data);
		$this->load->view('footer');

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


			$result = $this->usercustomer_database->get_comments_download($conditions);
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


}