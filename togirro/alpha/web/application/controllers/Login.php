<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login
 *
 * Авторизация пользователей
 *
 * @author		Alex Strakhov <astrahov92@me.com>
 * @package		Auth
 */
class Login extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->load->library(array('auth_lib'));

		if ($this->login) {
			redirect($this->auth_lib->login_redirect_base_controller);
			exit;
		}

		$this->load->library(array('design_lib', 'p_lib'));
		$this->p_lib->load(array('auth_pat'));
		$this->design_lib->set_template('auth');

		$this->html .= $this->auth_lib->render_login_form();

		$this->design_lib->show();
	}

	function ajax_r_login() {
		$this->load->library(array('auth_lib'));

		echo $this->auth_lib->login(array(
			'email'		 => $this->input->post('email'),
			'password'	 => $this->input->post('password'),
			'recaptcha'	 => $this->input->post('recaptcha'),
			'code'		 => $this->input->post('code') // For TFA
		));
	}

	function out() {
		$site_lang = $this->session->userdata('site_lang');

		$this->session->sess_destroy();

		redirect("lang/change_r/$site_lang");
	}

	function email_confirm($email_confirm_key = NULL) {
		$this->load->library(array('auth_lib'));

		if ($this->login) {
			redirect($this->config->item('auth_login_redirect_base_controller'));
			exit;
		}

		$this->load->library(array('design_lib', 'p_lib'));
		$this->p_lib->load(array('auth_pat'));
		$this->design_lib->set_template('auth');

		$this->lang_data = $this->lang_lib->load('auth');

		if (!empty($email_confirm_key)) {
			$this->db->where(array('user_email_confirm_key' => $email_confirm_key));
			$user = $this->db->get('user_auth')->row_array();

			if (!empty($user)) {
				$this->db->where(array('user_id' => $user['user_id']));
				$this->db->update('user_auth', array(
					'user_email_confirm'	 => 1,
					'user_email_confirm_key' => NULL
				));

				$this->auth_lib->auth_success(array(
					'email'		 => $user['user_email'],
					'user_id'	 => $user['user_id']
				));
				redirect('login');
			} else {
				redirect(404);
			}
		} else {
			$user_email = $this->session->userdata('user_email');

			if (empty($user_email)) {
				redirect('login');
			}

			$this->db->select(array('user_email', 'user_email_confirm_last_date'));
			$this->db->where(array('user_email' => $user_email));
			$user = $this->db->get('user_auth')->row_array();

			$this->html .= $this->p_lib->auth_pat->email_confirm_form(array('user' => $user));

			$this->design_lib->show();
		}
	}

	function ajax_r_send_email_confirm() {
		$email = $this->session->userdata('user_email');

		$lang_data = $this->lang_lib->load('auth', FALSE);

		if (!empty($email)) {
			$this->load->library(array('design_lib', 'p_lib', 'email'));
			$this->p_lib->load(array('email_pat'));

			$this->db->select(array('user_email', 'user_email_confirm_last_date'));
			$this->db->where(array('user_email' => $email));
			$user = $this->db->get('user_auth')->row_array();

			if (empty($user['user_email_confirm_last_date']) || strtotime($user['user_email_confirm_last_date']) + 5 * 60 < time()) {
				$email_confirm_key = md5(uniqid(rand(), 1));

				// Записываем уникальный ключ подтверждения E-mail в БД
				$this->db->where(array('user_email' => $email));
				$this->db->update('user_auth', array(
					'user_email_confirm_last_date'	 => date('Y-m-d H:i:s'),
					'user_email_confirm_key'		 => $email_confirm_key
				));

				// Шлем письмо с временноый ссылкой для смены пароля
				$this->config->load('email', TRUE);

				$email_config = $this->config->item('email');

				$this->email->initialize($this->config->item('email'));
				$this->email->from($email_config['smtp_user'], $this->config->item('project_name'));
				$this->email->to($email);

				$this->email->subject($lang_data['email_confirm_email_title'] . ' | ' . $this->config->item('project_name_short'));
				$this->design_lib->set_template('email');

				$this->html = $this->h_lib->_content(array(
					$this->p_lib->email_pat->title(array(
						'content' => $lang_data['email_confirm_email_template_title']
					)),
					$this->p_lib->email_pat->text(array(
						'content' => $lang_data['email_confirm_email_template_content_1']
					)),
					$this->p_lib->email_pat->panel(array(
						'content' => $this->h_lib->anchor(array(
							'content'	 => site_url("login/email_confirm/$email_confirm_key"),
							'url'		 => site_url("login/email_confirm/$email_confirm_key"),
							'btn'		 => FALSE
						))
					)),
					$this->p_lib->email_pat->text(array(
						'content' => $lang_data['email_confirm_email_template_content_2']
					))
				));

				$out = $this->design_lib->show('', array(), FALSE);

				$this->email->message($out);

				if (!$this->email->send()) {
					echo 'Ошибка';
					exit;
				}
			}
		}

		$this->html = '';
	}

	function forgot($forgot_key = NULL) {
		$this->load->library(array('auth_lib'));

		if ($this->login) {
			redirect($this->config->item('auth_login_redirect_base_controller'));
			exit;
		}

		$this->load->library(array('design_lib', 'p_lib'));
		$this->p_lib->load(array('auth_pat'));
		$this->design_lib->set_template('auth');

		$this->lang_data = $this->lang_lib->load('auth');

		if (!empty($forgot_key)) {
			if ($forgot_key == 'continue') {
				// Успешная отправка письма
				$email = $this->session->userdata('user_email');

				$this->db->where(array(
					'user_email'					 => $email,
					'user_password_forgot_key !='	 => NULL
				));
				$user = $this->db->get('user_auth')->row_array();

				if (!empty($user)) {
					$this->html .= $this->p_lib->auth_pat->forgot_sended();

					$this->design_lib->show();
				} else {
					redirect('forgot');
				}
			} else {
				// Пользователь перешел по ссылке из письма
				$this->db->where(array('user_password_forgot_key' => $forgot_key));
				$user = $this->db->get('user_auth')->row_array();

				if (!empty($user)) {
					$this->html .= $this->p_lib->auth_pat->forgot_form_continue(array('user_forgot_key' => $forgot_key));

					$this->design_lib->show();
				} else {
					redirect(404);
				}
			}
		} else {
			$this->html .= $this->p_lib->auth_pat->forgot_form();

			$this->design_lib->show();
		}
	}

	function action() {
		switch ($this->input->post('service')) {
			case 'forgot':
				$email = $this->input->post('forgot_email');

				if ($this->db->exist('user_auth', array('user_email' => $email))) {
					$this->load->library(array('design_lib', 'p_lib', 'email'));
					$this->p_lib->load(array('email_pat'));

					$lang_data = $this->lang_lib->load('auth', FALSE);

					// Записываем в сессию e-mail
					$this->session->set_userdata(array('user_email' => $email));

					$forgot_key = md5(uniqid(rand(), 1));

					// Записываем уникальный ключ подтверждения E-mail в БД
					$this->db->where(array('user_email' => $email));
					$this->db->update('user_auth', array('user_password_forgot_key' => $forgot_key));

					// Шлем письмо с временноый ссылкой для смены пароля
					$this->config->load('email', TRUE);

					$email_config = $this->config->item('email');
					$this->email->initialize($this->config->item('email'));
					$this->email->set_newline("\r\n");
					$this->email->from($email_config['smtp_user'], $this->config->item('project_name'));
					$this->email->to($email);

					$this->email->subject($lang_data['forgot_email_title'] . ' | ' . $this->config->item('project_name_short'));
					$this->design_lib->set_template('email');

					$this->html = $this->h_lib->_content(array(
						$this->p_lib->email_pat->title(array(
							'content' => $lang_data['forgot_email_template_title']
						)),
						$this->p_lib->email_pat->text(array(
							'content' => $lang_data['forgot_email_template_content_1']
						)),
						$this->p_lib->email_pat->panel(array(
							'content' => $this->h_lib->anchor(array(
								'content'	 => site_url("login/forgot/$forgot_key"),
								'url'		 => site_url("login/forgot/$forgot_key"),
								'btn'		 => FALSE
							))
						)),
						$this->p_lib->email_pat->text(array(
							'content' => $lang_data['forgot_email_template_content_2']
						))
					));

					$out = $this->design_lib->show('', array(), FALSE);

					$this->email->message($out);

					if (!$this->email->send()) {
						echo $this->email->print_debugger();
						echo 'Ошибка';
						exit;
					} else {
						redirect('login/forgot/continue');
					}
				}

				break;

			case 'forgot_end':
				$this->load->library(array('design_lib', 'auth_lib', 'email'));

				$forgot_key = $this->input->post('user_forgot_key');

				$this->db->where(array('user_password_forgot_key' => $forgot_key));
				$user = $this->db->get('user_auth')->row_array();

				if (!empty($user)) {
					$new_password			 = $this->input->post('new_password');
					$new_password_confirm	 = $this->input->post('new_password_confirm');

					$password_validate = $this->auth_lib->password_validate($new_password, 'forgot');

					if ($password_validate === TRUE && $new_password == $new_password_confirm) {
						$this->db->where(array('user_password_forgot_key' => $forgot_key));
						$this->db->update('user_auth', array(
							'user_password_forgot_key'	 => NULL,
							'user_password'				 => md5($new_password . $this->config->item('auth_password_key'))
						));

						$this->auth_lib->auth_success(array(
							'email'		 => $user['user_email'],
							'user_id'	 => $user['user_id']
						));
						redirect('login');
					} else {
						// @todo Ошибка в паролях!!!
					}
				} else {
					// @todo Пользователь не существует, ошибка в востановлении пароля
				}
				break;
		}
	}

}
