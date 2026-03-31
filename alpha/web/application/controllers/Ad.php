<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ad extends CI_Controller {

	function __construct() {
		parent::__construct();

		// Доступ только для менеджеров
		if (!$this->manager) {
			$this->load->library(array('telegram_lib'));
			$this->telegram_lib->send('Нарушение безопасности! Попытка входа в панель администратора');
			redirect(404);
		}
		$this->token = 'v3';
		$this->grocery_view = $this->config->item('grocery_view');
	}
	function demoaccess($crud){
		if($this->demo_user()){
			$crud->unset_delete();
			$crud->unset_add();
			$crud->unset_edit();                
		}
		return $crud;
	}
	function demo_user(){
		return $this->user_id==1;
	}
	function no_access(){
		$this->load->library(array('design_lib'));
		$this->design_lib->set_template('admin');
		$this->html .= '<h2>В данный раздел доступ ограничен</h2>';
		$this->design_lib->show();
		exit;
	}
	function index() {
		$this->load->library(array('design_lib'));
		$this->design_lib->set_template('admin');

		// Текущее время
		$data['date'] = $this->h_lib->card(array(
			'class'	 => array('text-center'),
			'body'	 => $this->h_lib->div(array(
				'id'	 => 'xsCurDateTime',
				'class'	 => array('xs-curdatetime'),
				'data'	 => array(
					$this->lang_lib->date(array(
						'date'	 => time(),
						'format' => 'sdmy',
						'time'	 => TRUE
					))
				)
			))
		));

		// Погода
		$data['weather'] = '';
		
		
		$dashboards['dash1'] = $this->parser->parse('ad/dashboard_r1',array(), true);
		$dashboards['dash2'] = $this->parser->parse('ad/dashboard_r2',array(), true);
		$dashboards['dash3'] = $this->parser->parse('ad/dashboard_r3',array(), true);
		$dashboards['dash4'] = $this->parser->parse('ad/dashboard_r4',array(), true);
		
		$this->design_lib->show('ad/dashboard',$dashboards);
		//$this->design_lib->show('ad/index', $data);
	}
	public function event() {
		
		$this->load->library(array('design_lib', 'Grocery_CRUD'));
		$crud = new grocery_CRUD();
		$this->design_lib->set_template('lk');
		$crud->set_table('event')
			;
		$crud->set_field_upload('photo_1','assets/uploads/news');
		$crud->set_field_upload('photo_2','assets/uploads/news');
		$crud->set_field_upload('photo_3','assets/uploads/news');
		$crud->callback_after_upload(array($this,'resize_callback_after_upload'));
		$crud->set_relation('location_id', 'location', 'location_name');
		$this->html .= $this->parser->parse($this->grocery_view, $crud->render(), TRUE);

		$this->design_lib->show();
	}

	function resize_callback_after_upload($uploader_response,$field_info, $files_to_upload)
	{
		$this->load->library('image_moo');	
		$file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name; 	 
		$this->image_moo->load($file_uploaded)->resize(1920,1280)->save($file_uploaded,true);
		$part_name = explode('.', $uploader_response[0]->name);
		$ext = $part_name[count($part_name)-1];
		unset($part_name[count($part_name)-1]);
		$new_name = implode('.', $part_name) . '_thumb.' . $ext;
		$thumb_name = $field_info->upload_path.'/'.$new_name; 	 
		$this->image_moo->load($file_uploaded)->resize(69,64)->save($thumb_name,true);			
		return true;
	}	
    public function video() {		
		$this->load->library(array('design_lib', 'Grocery_CRUD'));
		$crud = new grocery_CRUD();
		$this->design_lib->set_template('lk');
		$crud->set_table('video') 
			;
		
		
		$this->html .= $this->parser->parse($this->grocery_view, $crud->render(), TRUE);

		$this->design_lib->show();
	}
	public function participant_doc() {		
		$this->load->library(array('design_lib', 'Grocery_CRUD'));
		$crud = new grocery_CRUD();
		$this->design_lib->set_template('lk');
		$crud->set_table('participant_doc') 
			;
		$crud->set_field_upload('doc_url','assets/uploads/participant_doc');
		
		$this->html .= $this->parser->parse($this->grocery_view, $crud->render(), TRUE);

		$this->design_lib->show();
	}
	public function participant($token = '') {
		if($token != $this->token){
			exit;
		}
		$this->load->library(array('design_lib', 'Grocery_CRUD'));
		$crud = new grocery_CRUD();
		$this->design_lib->set_template('lk');

		$columns	 = array('participant_id', 'participant_number', 'participant_name', 'participant_description', 'participant_location', 'participant_location_id', 'participant_category', 'participant_photo', 'participant_site', 'participant_institution');
		$edit_fields = array('participant_id', 'participant_number', 'participant_name', 'participant_description', 'participant_location', 'participant_location_id', 'participant_category', 'participant_photo', 'participant_site', 'participant_institution');

		$crud->set_table('participant')
			->columns($columns)
			->edit_fields($edit_fields)
			->display_as('participant_id', 'ID')
			->display_as('participant_number', 'Номер')
			->display_as('participant_name', 'Ф.И.О.')
			->display_as('participant_description', 'Описание')
			->display_as('participant_location', 'Адрес')
			->display_as('participant_location_id', 'ID Адреса')
			->display_as('participant_category', 'Категория участинка')
			->display_as('participant_photo', 'Фото')
			->display_as('participant_site', 'Сайт участника')
			->display_as('participant_institution', 'Учреждение-участник');

		$crud->set_field_upload('participant_photo','assets/uploads/participant');
		$crud->set_relation('participant_location_id', 'location', 'location_name');

		$this->design_lib->show($this->grocery_view, $crud->render());

		// $crud->set_table('participant')
		// 	;
		// $this->html .= $this->parser->parse($this->grocery_view, $crud->render(), TRUE);

		// $this->design_lib->show();
	}
	public function point($token = '') {
		if($token != $this->token){
			exit;
		}
		$this->load->library(array('design_lib', 'Grocery_CRUD'));
		$crud = new grocery_CRUD();
		$this->design_lib->set_template('lk');
		$crud->set_table('point')
			;
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->set_relation('location_id', 'location', 'location_name');
		$this->html .= $this->parser->parse($this->grocery_view, $crud->render(), TRUE);

		$this->design_lib->show();
	}
	public function expert($token = '') {
		if($token != $this->token){
			exit;
		}
		$this->load->library(array('design_lib', 'Grocery_CRUD'));
		$crud = new grocery_CRUD();
		$this->design_lib->set_template('lk');
		$crud->set_table('expert')
			;
		$crud->set_field_upload('expert_photo','assets/uploads/expert');
		
		$this->html .= $this->parser->parse($this->grocery_view, $crud->render(), TRUE);

		$this->design_lib->show();
	}	

	// Access
	function access() {
		// Доступ только для администраторов
		if (!$this->admin) {
			redirect(404);
		}
                if($this->demo_user()){
                    $this->no_access();
                }
		$this->load->library(array('design_lib', 'Grocery_CRUD'));
		$this->design_lib->set_template('admin');

		$this->_check_controller_files();

		$crud = new grocery_CRUD();

		$columns	 = array('controller', 'controller_url', 'access_login', 'access_system', 'access_reason');
		$edit_fields = array('controller', 'controller_url', 'access_login', 'access_system', 'access_reason');

		$crud->set_table('access')
			->set_relation('controller', 'controller', '<b>{controller}</b> <small>{controller_name}</small>')
			->columns($columns)
			->edit_fields($edit_fields)
			->unset_add()
			->unset_delete()
			->display_as('controller', 'Controller')
			->display_as('controller_url', 'Controller URL ()')
			->display_as('access_login', 'Login')
			->display_as('access_system', 'System')
			->display_as('access_reason', 'Reason');

		$this->design_lib->show($this->grocery_view, $crud->render());
	}

	function controllers() {
		// Доступ только для администраторов
		if (!$this->admin) {
			redirect(404);
		}
                if($this->demo_user()){
                    $this->no_access();
                }
		$this->load->library(array('design_lib', 'Grocery_CRUD'));
		$this->design_lib->set_template('admin');

		$this->_check_controller_files();

		$crud = new grocery_CRUD();

		$columns	 = array('controller', 'controller_name', 'controller_enabled', 'controller_beta');
		$edit_fields = array('controller', 'controller_name', 'controller_description', 'controller_enabled', 'controller_beta');

		$crud->set_table('controller')
			//->set_relation('user_id', 'user_auth', 'user_email')
			->columns($columns)
			->edit_fields($edit_fields)
			->unset_add()
			->unset_delete()
			->display_as('controller', 'Controller')
			->display_as('controller_name', 'Name')
			->display_as('controller_enabled', 'Enabled')
			->display_as('controller_beta', 'BETA');

		$this->design_lib->show($this->grocery_view, $crud->render());
	}

	function _check_controller_files() {
		$this->db->select('controller');
		$controller_db		 = $this->db->get('controller')->result('array_id', 'controller');
		$controller_db_keys	 = array_keys($controller_db);

		$this->db->select('controller');
		$this->db->where(array('controller_url' => NULL));
		$access_db		 = $this->db->get('access')->result('array_id', 'controller');
		$access_db_keys	 = array_keys($access_db);

		$controller_file_names	 = array();
		$files					 = array_diff(scandir('application/controllers'), array('.', '..'));
		foreach ($files as $file_name) {
			$file_name_arr = explode('.', $file_name);

			// Если PHP файл
			if (isset($file_name_arr[1]) && $file_name_arr[1] == 'php') {
				$controller = strtolower($file_name_arr[0]);

				// Если нет в таблице контроллеров, то добавляем в таблицу
				if (!in_array($controller, $controller_db_keys)) {
					$controller_file_names[] = strtolower($file_name_arr[0]);

					$this->db->insert('controller', array(
						'controller' => $controller
					));
				}

				// Если нет в таблице access, то добавляем в таблицу
				if (!in_array($controller, $access_db_keys)) {
					$controller_file_names[] = strtolower($file_name_arr[0]);

					$this->db->insert('access', array(
						'controller' => $controller
					));
				}
			}
		}
	}

	function users($type = NULL) {
		// Доступ только для администраторов (BETA)
		if (!$this->admin || !$this->beta) {
			redirect(404);
		}
                if($this->demo_user()){
                    $this->no_access();
                }
		if (is_null($type)) {
			redirect('ad/users/alpha');
		}

		$this->load->library(array('design_lib', 'ad_lib', 'Grocery_CRUD'));
		$this->design_lib->set_template('admin');

		$crud = new grocery_CRUD();

		$columns = array('user_id');

		$crud->set_table('lab_user')
			->set_relation('user_id', 'user_auth', 'user_email')
			->unset_add()
			->unset_delete()
			->unset_edit()
			->columns($columns)
			->display_as('user_id', 'E-mail');

		$this->html .= $this->parser->parse($this->grocery_view, $crud->render(), TRUE);

		$this->design_lib->show();
	}

	function config($type = NULL) {
		// Доступ только для администраторов (BETA)
		if (!$this->admin || !$this->beta) {
			redirect(404);
		}
                if($this->demo_user()){
                    $this->no_access();
                }
		if (is_null($type)) {
			redirect('ad/config/beta');
		}

		$this->load->library(array('design_lib', 'ad_lib'));
		$this->design_lib->set_template('admin');

		$this->html .= $this->ad_lib->config(array('type' => $type));

		$this->design_lib->show();
	}

	// Control
	function control($section = NULL) {
		$lang_data = $this->lang_lib->load('admin');
                if($this->demo_user()){
                    $this->no_access();
                }
		switch ($section) {
			case 'session':
				$this->load->library(array('design_lib'));
				$this->design_lib->set_template('admin');

				$this->html .= $this->h_lib->card(array(
					'header' => $lang_data['admin_control_session_reset_header'],
					'body'	 => $this->h_lib->_content(array(
						$this->h_lib->radio(array(
							'form_group' => array(
								'label' => 'Site'
							),
							'checked'	 => array('beta'),
							'data'		 => array(
								array(
									'id'	 => 'reset_site_b',
									'name'	 => 'reset_site',
									'value'	 => 'beta',
									'title'	 => 'Beta'
								),
								array(
									'id'	 => 'reset_site_a',
									'name'	 => 'reset_site',
									'value'	 => 'alpha',
									'title'	 => 'Alpha'
								)
							)
						))
					)),
					'footer' => array(
						'content'	 => $this->h_lib->button(array(
							'content'	 => $lang_data['admin_control_session_reset_submit'],
							'onclick'	 => 'xAdmin.control.session.reset()'
						)),
						'class'		 => array('text-center')
					),
				));

				$this->design_lib->show();
				break;

			default:
				break;
		}
	}

	function ajax_constrol_session_reset() {
		$data['status'] = FALSE;

		$site = $this->input->post('site');

		if ($site == 'alpha') {
			$this->db = $this->load->database('alpha', TRUE);
		}

		$this->db->select('user_id');
		$user_ids = array_keys($this->db->get('user')->result('array_id', 'user_id'));

		foreach ($user_ids as $user_id) {
			$this->db->insert('system_orders', array(
				'user_id'	 => $user_id,
				'order_type' => 'destroy_session',
				'order_date' => date('Y-m-d H:i:s')
			));
		}

		$data['status'] = 'success';

		echo json_encode($data);
	}

	// Languages
	function lang($language = '', $lang_file = '') {
		// Доступ только для администраторов и переводчиков (BETA)
		if (!$this->admin && !$this->translator) {
			redirect(404);
		}
                if($this->demo_user()){
                    $this->no_access();
                }
		$this->load->library(array('design_lib', 'p_lib'));
		$this->p_lib->load(array('ad_pat'));
		$this->design_lib->set_template('admin');

		if (empty($language)) {
			// Список языков
			$lang_list	 = array();
			$trans_langs = array();

			if ($this->admin) {
				$trans_langs = $this->lang_lib->languages;
			} else {
				if ($this->translator) {
					$trans_langs = $this->access_lib->translators_lang[$this->user_id];
				}
			}

			foreach ($trans_langs as $menu_key => $menu_item) {
				if (is_int($menu_key)) {
					$menu_key	 = $menu_item;
					$menu_item	 = $this->lang_lib->languages[$menu_key];
				}
				$lang_list[] = array(
					'content'	 => "$menu_key - $menu_item",
					'url'		 => "ad/lang/$menu_key"
				);
			}

			$this->html .= $this->h_lib->listgroup(array('data' => $lang_list));
		} else {
			// Список языковых файлов
			if (empty($lang_file)) {
				$lang_files	 = array();
				$path		 = $this->lang_lib->lang_path . $this->lang_lib->languages_dir[$this->lang_lib->default_lang];
				$files		 = array_diff(scandir($path), array('.', '..'));

				$lang_list = array();

				foreach ($files as $file_key => $file) {
					$file_name = explode('.', $file);
					if ($file_name [1] != 'html') {
						$lang_file_trans_data = $this->lang_lib->compare($file_name[0], $language);

						$list_item = array(
							'content'	 => $file_name[0],
							'url'		 => "ad/lang/$language/" . $file_name[0]
						);
						if ($lang_file_trans_data['percentage'] < 100) {
							$list_item['count'] = "$lang_file_trans_data[words_count] words - $lang_file_trans_data[percentage] %";

							$theme = 'warning';
							if ($lang_file_trans_data['percentage'] < 50) {
								$theme = 'danger';
							}

							$list_item['count_theme'] = $theme;
						}

						$lang_list[] = $list_item;
					}
				}
				$this->html .= $this->h_lib->listgroup(array('data' => $lang_list));
			} else {
				// Выбран конкретный файл
				$path_def_lang_file	 = $this->lang_lib->lang_path . $this->lang_lib->languages_dir[$this->lang_lib->default_lang] . '/' . $lang_file . '.php';
				$path_lang_file		 = $this->lang_lib->lang_path . $this->lang_lib->languages_dir[$language] . '/' . $lang_file . '.php';

				include $path_def_lang_file;
				$default_lang	 = $lang;
				$lang			 = NULL;

				if (file_exists($path_lang_file)) {
					include $path_lang_file;
				} else {
					$lang = array();
				}

				$pat_data = array();

				foreach ($default_lang as $lang_key => $lang_translate) {
					$lang_val			 = isset($lang[$lang_key]) ? $lang[$lang_key] : '';
					$pat_data[$lang_key] = array(
						'value'		 => $lang_val,
						'form_group' => array(
							'label' => htmlspecialchars($lang_translate)
						)
					);
				}

				$this->html .= $this->p_lib->ad_pat->lang_form(array(
					'lang_pref'	 => $language,
					'lang_file'	 => $path_lang_file,
					'data'		 => $pat_data
				));
			}
		}

		$this->design_lib->show();
	}

	function action() {
		switch ($this->input->post('service')) {
			case 'integration':
				
				$this->load->library(array('api_lib','design_lib'));
				$intagration_name = $this->input->post('name');
				$result = $this->api_lib->integration(array('name'=> $intagration_name));
				if($result===TRUE){
					$this->design_lib->msg(array(
						'title'		 => 'Успешно',
						'content'	 => 'создана интеграция',
						'url'		 => "ad/integrations",
						'redirect'	 => TRUE
					));
					exit;
				}else{
					$this->design_lib->msg(array(
						'title'		 => 'Ошибка',
						'content'	 => $result,
						'url'		 => "ad/integrations",
						'redirect'	 => FALSE
					));
				}
				break;
			// Подтверждение обновления/создания контракта
			case 'contract_check':
				$catalog_moderation_id	 = $this->input->post('catalog_moderation_id');
				$contract_title			 = $this->input->post('contract_title');
				$contract_intro			 = $this->input->post('contract_intro');
				$contract_description	 = $this->input->post('contract_description');
				$contract_abi			 = $this->input->post('contract_abi');
				$contract_code			 = $this->input->post('contract_code');

				$catalog_moderation_status	 = $this->input->post('catalog_moderation_status');
				$catalog_moderation_comment	 = $this->input->post('catalog_moderation_comment');

				if (!empty($catalog_moderation_id) && !empty($contract_title) && !empty($contract_intro) && !empty($contract_description)) {
					$this->db->where(array('catalog_moderation_id' => $catalog_moderation_id));
					$catalog_moderation_q = $this->db->get('catalog_moderation')->row_array();

					if (!empty($catalog_moderation_q)) {
						$contract_id = $catalog_moderation_q['contract_id'];
						$user_id	 = $catalog_moderation_q['user_id'];

						$contract_data = array(
							'user_id'				 => $user_id,
							'contract_title'		 => $contract_title,
							'contract_intro'		 => $contract_intro,
							'contract_description'	 => $contract_description,
							'contract_abi'			 => $contract_abi,
							'contract_code'			 => $contract_code,
							'datetime'				 => date('Y-m-d H:i:s')
						);

						$moderation_data								 = $contract_data;
						$moderation_data['contract_id']					 = $contract_id;
						$moderation_data['catalog_moderation_status']	 = $catalog_moderation_status;
						$moderation_data['catalog_moderation_comment']	 = $catalog_moderation_comment;

						switch ($catalog_moderation_status) {
							// Опубликовано
							case 'publish':
								$moderation_data['catalog_moderation_comment'] = NULL;

								$this->db->update('catalog', $contract_data, array(
									'contract_id'	 => $contract_id,
									'user_id'		 => $user_id
								));
								break;

							// На доработке
							case 'rework':

								break;

							// Удалено
							case 'delete':

								break;

							// Снято с публикации
							case 'unpublish':

								break;
						}

						$this->db->update('catalog_moderation', $moderation_data, array(
							'contract_id'			 => $contract_id,
							'user_id'				 => $user_id,
							'catalog_moderation_id'	 => $catalog_moderation_id
						));
					}
				}

				redirect('ad/contract');

				break;

			case 'lang':
				// Доступ только для администраторов и переводчиков (BETA)
				if (!$this->admin && !$this->translator) {
					redirect(404);
				}

				$this->load->library(array('lang_lib'));
				$path_lang_file	 = $this->input->post('file');
				$lang_pref		 = $this->input->post('pref');

				$path_arr	 = explode('/', $path_lang_file);
				unset($path_arr[count($path_arr) - 1]);
				$path_new	 = implode('/', $path_arr);

				// Если не существует папки языка
				if (!is_dir($path_new)) {
					mkdir($path_new);
				}

				unset($_POST['pref']);
				unset($_POST['file']);
				unset($_POST['service']);

				$new_lang	 = array();
				$fp			 = fopen($path_lang_file, 'w');
				$out		 = '<?php ' . PHP_EOL;
				foreach ($_POST as $lang_key => $lang_item) {
					$lt = htmlspecialchars($this->input->post($lang_key), ENT_QUOTES);
					if ($lang_pref == $this->lang_lib->default_lang || !empty($lt)) {
						$out .= '$lang[\'' . $lang_key . '\'] = \'' . $lt . '\';' . PHP_EOL;
					}
				}
				fwrite($fp, $out);
				fclose($fp);

				// JS файл
				$file_name	 = explode('/', $path_lang_file);
				$file_name	 = $file_name[count($file_name) - 1];
				if ($file_name == 'js.php') {
					$this->load->library(array('lang_lib'));
					$this->lang_lib->gen_js_file($lang_pref);
				}

				redirect('ad/lang');

				break;

			default:
				redirect(404);
				break;
		}
	}

}
