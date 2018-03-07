<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_controller extends CI_Controller {
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
	}

	//Vista del formulario de activacion
	public function active_form($id){
		$data['data_user'] = $this->admin_database->data_user($id);
		$this->load->view('header');
		$this->load->view('admin_bars');
		$this->load->view('admin_page_activate', $data);
		$this->load->view('footer');
	}

	//Activar usuario
	public function activate_user(){
		$this->form_validation->set_rules('id', 'Id', 'trim|required|xss_clean');
		$this->form_validation->set_rules('database_name', 'Database_name', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			redirect('login', 'refresh');
		}
		else{
			$data = array(
				'id' => $this->input->post('id'),
				'name_db' => $this->input->post('database_name'),
				'active' => '1'
				);
			$password = substr(md5(uniqid()), 0, 10); //Password aleatoria
			$result = $this->admin_database->activate_insert($data, $password);
			if(!$result){
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_db'] = "¡ERROR!: Nombre de la base de datos repetido.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}else{
				//Enviamos un email con la contraseña
				//cargamos la libreria email de ci
				$this->load->library("email");

				//configuracion para gmail
				$configMail = array(
					'protocol' => 'smtp',
					//'smtp_host' => 'ssl://in-v3.mailjet.com',
					'smtp_host' => 'ssl://smtp.gmail.com',
					'smtp_port' => 465,
					'smtp_user' => 'widsoporte@gmail.com',//'65edf6fe937597e09afb14b41fd92a14',
					'smtp_pass' => 'cwNvyt6l',//'b5b2823a87a192c36f2d4e3d493dc1cb',
					'mailtype' => 'html',
					'charset' => 'utf-8',
					'newline' => "\r\n",
					'wordwrap' => TRUE
				);

				//Enviamos un email con la nueva contraseña 
				$to = 'widsoporte@gmail.com';
				$from = 'widsoporte@gmail.com';
				$asunto = "Datos de ".$data['name_db'];
				$cuerpo = " 
					<html> 
					<head> 
					   <title>Se ha activado la base de datos ".$data['name_db']."</title> 
					</head> 
					<body> 
					<h1>La base de datos ha sido activada</h1> 
					<p> 
					<b>El nombre de usuario es ".$data['name_db']."_user y la contraseña es ".$password.".
					</p> 
					</body> 
					</html> 
					";

				$this->email->initialize($configMail);
				$this->email->from($from);
				$this->email->to($to);
				$this->email->subject($asunto);
				$this->email->message($cuerpo);
				$this->email->send();



				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_ok'] = "¡BIEN HECHO!: El usuario ha sido activado correctamente.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}
		}
	}

	//Vista de registro de admin
	public function admin_registration(){
		$this->load->view('header');
		$this->load->view('admin_bars');
		$this->load->view('admin_registration_form');
		$this->load->view('footer');
	}

	//Registrar nuevo administrador
	public function new_admin_registration(){
		//Validamos usuario y contraseña
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length[6]');
		if ($this->form_validation->run() == FALSE) {
			$data['msg_db'] = 'Error en el formulario';
			$this->load->view('header');
			$this->load->view('admin_bars');
			$this->load->view('admin_registration_form', $data);
			$this->load->view('footer');
		} else {
			$data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => $this->input->post('password')
				);
			$result = $this->admin_database->registration_admin_insert($data);
			if ($result == TRUE) {
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_ok'] = 'OK: Se ha registrado correctamente';


				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			} else {
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_db'] = 'Ese nombre de usuario ya existe';
				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}
		}
	}

	//Vista de todos los usuarios activos
	public function show_users(){
		$data['all_active_users'] = $this->admin_database->all_active_users();

		$this->load->view('header');
		$this->load->view('admin_bars');
		$this->load->view('admin_users_form', $data);
		$this->load->view('footer');
	}

	//Vista del formulario de edicion
	public function edit_form($id){
		$data['data_user'] = $this->admin_database->data_user($id);
		$this->load->view('header');
		$this->load->view('admin_bars');
		$this->load->view('admin_page_edit', $data);
		$this->load->view('footer');
	}

	//Modificar la base de datos asignada a un usuario
	public function edit_datebase(){
		$this->form_validation->set_rules('id', 'Id', 'trim|required|xss_clean');
		$this->form_validation->set_rules('database_name', 'Database_name', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			redirect('login', 'refresh');
		}
		else{
			$data = array(
				'id' => $this->input->post('id'),
				'name_db' => $this->input->post('database_name')
				);
			$result = $this->admin_database->user_edit($data);
			if(!$result){
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_db'] = "¡ERROR!: ID erronea.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}else{
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_ok'] = "¡BIEN HECHO!: Base de datos actualizada correctamente.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}
		}
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
			$result = $this->admin_database->user_edit($data);
			if(!$result){
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_db'] = "¡ERROR!: ID erronea.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}else{
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_ok'] = "¡BIEN HECHO!: Rol actualizado correctamente.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}
		}
	}

	//Modificar el password de un usuario
	public function edit_password(){
		$this->form_validation->set_rules('id', 'Id', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length[6]');
		if ($this->form_validation->run() == FALSE) {
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_db'] = "Error en el formulario.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
		}
		else{
			$data = array(
				'id' => $this->input->post('id'),
				'password' => $this->input->post('password')
				);
			$result = $this->login_database->change_password($data);
			if(!$result){
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_db'] = "¡ERROR!: ID erronea.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}else{
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_ok'] = "¡BIEN HECHO!: Contraseña actualizada correctamente.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
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
				'name_db' => '',
				'active' => 0
				);
			$result = $this->admin_database->user_edit($data);
			if(!$result){
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_db'] = "¡ERROR!: ID erronea.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}else{
				$data['inactive_admin_client_users'] = $this->admin_database->inactive_admin_client_users();
				$data['msg_ok'] = "¡BIEN HECHO!: Usuario desactivado.";

				$this->load->view('header');
				$this->load->view('admin_bars');
				$this->load->view('admin_page', $data);
				$this->load->view('footer');
			}
		}
	}



}