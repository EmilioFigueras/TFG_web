<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
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
		$this->load->model('admin_database');
		$this->load->model('admincustomer_database');
		$this->load->model('usercustomer_database');

	}

	//Pagina de login
	public function index(){
		$this->load->view('header');
		$this->load->view('login_form');
		$this->load->view('footer');

	}

	//Pagina de registro
	public function registration(){
		$this->load->view('header');
		$this->load->view('registration_form');
		$this->load->view('footer');
	}

	//Vista de recuperacion de la contraseña
	public function forgot_form(){
		$this->load->view('header');
		$this->load->view('forgot_form');
		$this->load->view('footer');
	}

	//Cambio de la contraseña 
	public function forgot_pass(){
		//Validamos usuario y contraseña
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$data['message_display'] = 'Error';
			$this->load->view('header');
			$this->load->view('forgot_form', $data);
			$this->load->view('footer');
		} else {
			$data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				);
			$result = $this->login_database->check_data($data);
			if ($result == TRUE) {
				//Creamos una nueva contraseña aleatoria
				$newpass = substr(md5(uniqid()), 0, 10);

				//Convertimos el objeto a array
				$newdata = json_decode(json_encode($result), True);
				$newdata['password'] = $newpass;

				//Cambiamos la contraseña
				$res = $this->login_database->change_password($newdata);
				if($res){
					$data['message_display'] = 'La contraseña ha sido modificada correctamente';

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

					//Enviamos un email con la nueva contraseña 
					$to = $data['email'];
					$from = 'figmare@hotmail.com';
					$asunto = "Cambio de contraseña";
					$cuerpo = ' 
						<html> 
						<head> 
						   <title>Se ha procedido a cambiarle la contraseña</title> 
						</head> 
						<body> 
						<h1>Nueva contraseña</h1> 
						<p> 
						<b>Su nueva contraseña es: '.$newpass.'. Puedes modificarla una vez inicie sesión.
						</p> 
						</body> 
						</html> 
						';

					$this->email->initialize($configMail);
					$this->email->from($from);
					$this->email->to($to);
					$this->email->subject($asunto);
					$this->email->message($cuerpo);
					$this->email->send();

					$this->load->view('header');
					$this->load->view('forgot_form', $data);
					$this->load->view('footer');
				}else{
					$data['message_display'] = 'Error. Inténtelo de nuevo más tarde';
					$this->load->view('header');
					$this->load->view('forgot_form', $data);
					$this->load->view('footer');
				}
			} else {
				$data['message_display'] = 'Datos incorrectos';
				$this->load->view('header');
				$this->load->view('forgot_form', $data);
				$this->load->view('footer');
			}
		}

	}

	//Validacion y registro
	public function new_user_registration(){
		//Validamos usuario y contraseña
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
		$this->form_validation->set_rules('customer', 'Customer', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('header');
			$this->load->view('registration_form');
			$this->load->view('footer');
		} else {
			$data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'customer' => $this->input->post('customer'),
				'password' => $this->input->post('password')
				);
			$result = $this->login_database->registration_insert($data);
			if ($result == TRUE) {
				$data['message_display'] = 'Se ha registrado correctamente';

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

				//Enviamos un email de confirmacion 
				$to = $data['email'];
				$from = 'figmare@hotmail.com';
				$asunto = "Registro en TFG";
				$cuerpo = ' 
					<html> 
					<head> 
					   <title>Registro en TFG</title> 
					</head> 
					<body> 
					<h1>¡Bienvenido!</h1> 
					<p> 
					<b>Su registro se ha completado con éxito.</b> Pronto nos pondremos en contacto con usted para enviarle la aplicación con la información correspondiente.
					</p> 
					</body> 
					</html> 
					';

				$this->email->initialize($configMail);
				$this->email->from($from);
				$this->email->to($to);
				$this->email->subject($asunto);
				$this->email->message($cuerpo);
				$this->email->send();

				$this->load->view('header');
				$this->load->view('login_form', $data);
				$this->load->view('footer');
			} else {
				$data['message_display'] = 'Nombre de usuario erróneo';
				$this->load->view('header');
				$this->load->view('registration_form', $data);
				$this->load->view('footer');
			}
		}

	}

	//Comprobar y cargar la información del usuario logeado
	public function user_login_process(){
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE) {
			if(isset($this->session->userdata['logged_in'])){
				switch($this->session->userdata['logged_in']['role']){
					case 'admin':{
						$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();

						$this->load->view('header');
						$this->load->view('admin_bars');
						$this->load->view('admin_page', $data);
						$this->load->view('footer');
						break;
					};
					case 'admin_customer':{
						$data['all_active_users'] = $this->admincustomer_database->all_active_users();

						$this->load->view('header');
						$this->load->view('admin_customer_bars');
						$this->load->view('admin_customer_page', $data);
						$this->load->view('footer');
						break;
					};
					case 'user_customer':{
						$data['all_reports'] = $this->usercustomer_database->all_reports_active();
						$this->load->view('header');
						$this->load->view('user_customer_bars');
						$this->load->view('user_customer_page', $data);
						$this->load->view('footer');
						break;
					};
					default:{
						$this->load->view('header');
						$this->load->view('login_form');
						$this->load->view('footer');
						break;
					}
				}

			}else{
				$this->load->view('header');
				$this->load->view('login_form');
				$this->load->view('footer');
			}
		} else {
			$data = array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password')
				);
			$result = $this->login_database->login($data);
			if ($result == TRUE) {
				$username = $this->input->post('username');
				$result = $this->login_database->read_user_information($username);
				if ($result != false) {
					$session_data = array(
						'id' => $result[0]->id,
						'username' => $result[0]->username,
						'email' => $result[0]->email,
						'customer' => $result[0]->customer,
						'name_db' => $result[0]->name_db,
						'role' => $result[0]->rolename, 
						'active' => $result[0]->active
						);
					// Add user data in session
					$this->session->set_userdata('logged_in', $session_data);

					switch($this->session->userdata['logged_in']['role']){
						case 'admin':{
							$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();

							$this->load->view('header');
							$this->load->view('admin_bars');
							$this->load->view('admin_page', $data);
							$this->load->view('footer');
							break;
						};
						case 'admin_customer':{
							$data['all_active_users'] = $this->admincustomer_database->all_active_users();

							$this->load->view('header');
							$this->load->view('admin_customer_bars');
							$this->load->view('admin_customer_page', $data);
							$this->load->view('footer');
							break;
						};
						case 'user_customer':{
							$data['all_reports'] = $this->usercustomer_database->all_reports_active();

							$this->load->view('header');
							$this->load->view('user_customer_bars');
							$this->load->view('user_customer_page', $data);
							$this->load->view('footer');
							break;
						};
						default:{
							$this->load->view('header');
							$this->load->view('login_form');
							$this->load->view('footer');
							break;
						}
					}
				}
			} else {
				$data = array(
					'error_message' => 'Usuario o contraseña incorrecto.'
					);
				$this->load->view('header');
				$this->load->view('login_form', $data);
				$this->load->view('footer');
			}
		}
	}


	//Configuración del usuario logueado
	public function settings(){
		$data['data_user'] = $this->session->userdata()['logged_in'];

		switch($this->session->userdata['logged_in']['role']){
			case 'admin':{
				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('settings', $data);
				$this->load->view('footer');
				break;
			};
			case 'admin_customer':{
				$this->load->view('header');
				$this->load->view('admin_customer_bars'); 
				$this->load->view('settings', $data);
				$this->load->view('footer');
				break;
			};
			case 'user_customer':{
				$this->load->view('header');
				$this->load->view('user_customer_bars');
				$this->load->view('settings', $data);
				$this->load->view('footer');
				break;
			};
			default:{
				$this->load->view('header');
				$this->load->view('login_form');
				$this->load->view('footer');
				break;
			}
		}
		/*$this->load->view('header');
		$this->load->view('admin_bars');
		$this->load->view('settings', $data);
		$this->load->view('footer');*/
	}

	//Modificar el password de un usuario
	public function edit_password(){
		$this->form_validation->set_rules('id', 'Id', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password2', 'Password2', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			redirect('login', 'refresh');
		}
		else{
			$data['data_user'] = $this->session->userdata()['logged_in'];
			if(strcmp($this->input->post('password'), $this->input->post('password2')) !== 0){
				$data['msg_db'] = "¡ERROR!: Has introducido mal la contraseña.";

				$this->load->view('header');
				switch($this->session->userdata['logged_in']['role']){
					case 'admin':{
						$this->load->view('admin_bars');
						break;
					};
					case 'admin_customer':{
						$this->load->view('admin_customer_bars'); 
						break;
					};
					case 'user_customer':{
						$this->load->view('user_customer_bars');
						break;
					};
				
				}
				$this->load->view('settings', $data);
				$this->load->view('footer');
			}else{
				$data_send = array(
					'id' => $this->input->post('id'),
					'password' => $this->input->post('password')
					);
				$result = $this->login_database->change_password($data_send);
				if(!$result){
					$data['msg_db'] = "¡ERROR!: ID erronea.";

				$this->load->view('header');
					switch($this->session->userdata['logged_in']['role']){
						case 'admin':{
							$this->load->view('admin_bars');
							break;
						};
						case 'admin_customer':{
							$this->load->view('admin_customer_bars'); 
							break;
						};
						case 'user_customer':{
							$this->load->view('user_customer_bars');
							break;
						};
					
					}
					$this->load->view('settings', $data);
					$this->load->view('footer');
				}else{
					$data['msg_ok'] = "¡BIEN HECHO!: Contraseña actualizada correctamente.";

					$this->load->view('header');
					switch($this->session->userdata['logged_in']['role']){
						case 'admin':{
							$this->load->view('admin_bars');
							break;
						};
						case 'admin_customer':{
							$this->load->view('admin_customer_bars'); 
							break;
						};
						case 'user_customer':{
							$this->load->view('user_customer_bars');
							break;
						};
					
					}
					$this->load->view('settings', $data);
					$this->load->view('footer');
				}
			}
		}
	}

	//Cerrar sesión
	public function logout() {

		//Borramos los datos de la sesion
		$sess_array = array(
			'username' => ''
		);
		$this->session->unset_userdata('logged_in', $sess_array);
		$data['message_display'] = 'Bye Bye!';
		$this->load->view('header');
		$this->load->view('login_form', $data);
		$this->load->view('footer');
	}

}