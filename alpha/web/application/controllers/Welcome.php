<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->category = array("Учитель года", "Педагогический дебют (учитель)", "Воспитатель года", "Педагог-психолог года", "Учитель-дефектолог", "Молодой руководитель", "Классный руководитель", "Мастер", "Педагогический дебют (воспитатель)");
		$this->grocery_view = $this->config->item('grocery_view');
		$this->token = 'v3';
		$this->max_vote = 5;
	}
	public function index() {
		$this->show();
	}
	public function stream() {
		
		$this->load->library(array('design_lib'));
		$data = array();
		$data['participant'] = $this->db->get('participant')->result('array');
		foreach($data['participant'] as $key => $participant){
			$data['participant'][$key]['participant_category_name'] = $this->category[$participant['participant_category']];
			if(!empty($participant['participant_photo'] )){
				$data['participant'][$key]['participant_photo'] = "assets/uploads/participant/$participant[participant_photo]";
			}else{
				$data['participant'][$key]['participant_photo'] = "assets/files/photo/{$participant['participant_category']}/{$participant['participant_number']}.jpg";
				
			}
		}
		//$data['participant'] = array();
		$this->db->order_by('expert_position','ASC');
		$data['expert'] = $this->db->get('expert')->result('array');
		$nophoto = array();
		foreach($data['expert'] as $key => $expert){
			$data['expert'][$key]['expert_category_name'] = $this->category[$expert['expert_category']];
			
			if(!in_array($expert['expert_id'], $nophoto)){
				if(!empty($expert['expert_photo'] )){
					$data['expert'][$key]['expert_photo'] = "assets/uploads/expert/$expert[expert_photo]";
				}else{
					// $data['expert'][$key]['expert_photo'] = 'assets/templates/teacher2020/images/photo_expert/'.$expert['expert_id'].'.jpg';
					$file_name = 'assets/templates/teacher2020/images/photo_expert/'.$expert['expert_id'].'.jpg';
					if(file_exists($file_name)) {
						$data['expert'][$key]['expert_photo'] = $file_name;
					} else {
						$data['expert'][$key]['expert_photo'] = null;
					}
				}
				if($data['expert'][$key]['expert_photo'] == null) {
					$img = '';
				} else {
					$img = '<div class="single-testimonial text-center"><div class="client-thumb" style="margin:0"><img src="'.$data['expert'][$key]['expert_photo'].'" alt="thumb"></div></div>';
				}
				$data['expert'][$key]['expert_img'] = $img;
			}else{
				$data['expert'][$key]['expert_img'] = ''; 
			}
		}
		$data['header'] = $this->parser->parse('welcome/header', array(), true);
		$data['footer'] = $this->parser->parse('welcome/footer', array(), true);
		$data['map'] = $this->parser->parse('welcome/map', array(), true);
		$data['roadmap_main'] = $this->parser->parse('welcome/roadmap_main', array(), true); 
		//$data['roadmap_main'] = $this->parser->parse('welcome/roadmap5', array(), true); 
		$data['roadmap1'] = $this->parser->parse('welcome/roadmap3', array(), true); 
		//$data['roadmap1'] = '';
		$data['roadmap2'] = $this->parser->parse('welcome/roadmap4', array(), true);
		$data['org'] = $this->parser->parse('welcome/org', array(), true);
		$data['org'] = '';
		$data['map2'] = $this->parser->parse('welcome/stream', array(), true);
		
		$data['docs'] = $this->db->get('participant_doc')->result('array');
		
		
		$this->html .= $this->parser->parse('welcome/index', $data, true);

		$this->design_lib->show(); 
	}
	function ajax_r_stream($datetime =''){
		$this->db->order_by('datetime', 'DESC');
		$events = $this->db->get('event')->result('array');
		foreach($events as $key => $event){
			$date = date_create($event['datetime']);
			$events[$key]['datetime'] = date_format($date, 'd.m.Y H:i');
			if(!empty($event['photo_1']))
				$events[$key]['photo_1_small'] = $this->_thumb_name($event['photo_1']);
			if(!empty($event['photo_2']))
				$events[$key]['photo_2_small'] = $this->_thumb_name($event['photo_2']);
			if(!empty($event['photo_3']))
				$events[$key]['photo_3_small'] = $this->_thumb_name($event['photo_3']);
		}
		echo json_encode($events);	
	}
	function _thumb_name($name = ''){
		if(!empty($name)){
			$part_name = explode('.', $name);
			$ext = $part_name[count($part_name)-1];
			unset($part_name[count($part_name)-1]);
			$new_name = implode('.', $part_name) . '_thumb.' . $ext;
			return $new_name;
		}else{
			return '';
		}
	}
	public function participant($id = '') {
		if(!empty($id)){
			$this->load->library(array('design_lib'));
			$data = array();
			$data = $this->db->get_where('participant', array('participant_id' => $id))->row_array();
			if(empty($data)){
				redirect(404);
			}
			$data['participant_category_name'] = $this->category[$data['participant_category']];
			if(!empty($data['participant_photo'] )){
				$data['participant_photo'] = "assets/uploads/participant/$data[participant_photo]";
			}else{
				$data['participant_photo'] = "assets/files/photo/{$data['participant_category']}/{$data['participant_number']}.jpg";
				
			}
			$this->html = $this->parser->parse('welcome/participant', $data, true);
			// $this->html = $this->parser->parse('welcome/index', $data, true);

			$this->design_lib->show(); 
			
		}else{
			redirect(404);
		}
	}
	public function show($token = '') {
		
		$this->load->library(array('design_lib'));
		$data = array();
		$data['participant'] = $this->db->get('participant')->result('array');
		foreach($data['participant'] as $key => $participant){
			if(isset($this->category[$participant['participant_category']])) {
				$data['participant'][$key]['participant_category_name'] = $this->category[$participant['participant_category']];
				
				if(!empty($participant['participant_photo'] )) {
					$data['participant'][$key]['participant_photo'] = "assets/uploads/participant/$participant[participant_photo]";
				} else {
					$data['participant'][$key]['participant_photo'] = "assets/files/photo/{$participant['participant_category']}/{$participant['participant_number']}.jpg";
				}
			}
		}
		//$data['participant'] = array();
		$this->db->order_by('expert_position','ASC');
		$data['expert'] = $this->db->get('expert')->result('array');
		$nophoto = array(); 
		foreach($data['expert'] as $key => $expert){
			$data['expert'][$key]['expert_category_name'] = $this->category[$expert['expert_category']];
			
			if(!in_array($expert['expert_id'], $nophoto)){
				if(!empty($expert['expert_photo'] )){
					$data['expert'][$key]['expert_photo'] = "assets/uploads/expert/$expert[expert_photo]";
				}else{
					// $data['expert'][$key]['expert_photo'] = 'assets/templates/teacher2020/images/photo_expert/'.$expert['expert_id'].'.jpg';
					$file_name = 'assets/templates/teacher2020/images/photo_expert/'.$expert['expert_id'].'.jpg';
					if(file_exists($file_name)) {
						$data['expert'][$key]['expert_photo'] = $file_name;
					} else {
						$data['expert'][$key]['expert_photo'] = null;
					}
				}
				if($data['expert'][$key]['expert_photo'] == null) {
					$img = '';
				} else {
					$img = '<div class="single-testimonial text-center"><div class="client-thumb" style="margin:0"><img src="'.$data['expert'][$key]['expert_photo'].'" alt="thumb"></div></div>';
				}
				$data['expert'][$key]['expert_img'] = $img;
			}else{
				$data['expert'][$key]['expert_img'] = ''; 
			}
		}
		$data['header'] = $this->parser->parse('welcome/header', array(), true);
		$data['footer'] = $this->parser->parse('welcome/footer', array(), true);
		$data['map'] = $this->parser->parse('welcome/map', array(), true);
		$data['roadmap_main'] = $this->parser->parse('welcome/roadmap_main', array(), true); 
		//$data['roadmap_main'] = $this->parser->parse('welcome/roadmap5', array(), true); 
		$data['roadmap1'] = $this->parser->parse('welcome/roadmap3', array(), true); 
		//$data['roadmap1'] = '';
		$data['roadmap2'] = $this->parser->parse('welcome/roadmap4', array(), true);
		$data['org'] = $this->parser->parse('welcome/org', array(), true);
		$data['org'] = '';
		$data['map2'] = $this->parser->parse('welcome/stream', array(), true);
		$this->db->order_by('doc_day', 'ASC');
		$data['docs'] = $this->db->get('participant_doc')->result('array');		
		$this->db->order_by('category', 'ASC');
        $data['video'] = $this->db->get('video')->result('array');	
		
		$this->html .= $this->parser->parse('welcome/index', $data, true);

		$this->design_lib->show(); 
	}	
	public function register5($token = '') {
		
		$this->load->library(array('design_lib'));
		$data = array();
		$this->db->join('location', 'location.location_id = point.location_id');
		$this->db->order_by('point.location_id','ASC');
		if($this->login){
			$data['user_email'] = $this->user_email;
		}else{
			$data['user_email'] = '';
			$this->design_lib->msg(array('title' => 'Необходимо зарегистрироваться на сайте', 'url' => "register"));
			exit;
		}
		$data['point'] = $this->db->get('point')->result('array_id', 'location_id', TRUE);
		$data['region'] = array();
		$point_user = $this->db->get('point_user')->result('array_id', 'point_id', TRUE);
		
		foreach($data['point'] as $key => $reg){
			
			$tmp = array(); 
			$tmp['content'] = '';  
			foreach($reg as $key2 => $point){
				
				$tmp['name'] = $point['location_name'];
				$busy_quotas = isset($point_user[$point['point_id']])?count($point_user[$point['point_id']]):0;

				$free_quotas = (int)$point['point_quotas'] - $busy_quotas;
				if($free_quotas<=0){
					$btn = '';
				}else{
					
				}
				$btn = '<button class="btn btn-primary btn-gradient btn-fsmall col-md-4 col-4 col-xl-3" type="button" data-toggle="modal" data-target="#registerModal" data-point_id="'.$point['point_id'].'" data-point_name="'.$point['point_name'].'">Регистрация</button>';
				$btn2 = '<button class="btn btn-primary btn-gradient btn-fsmall col-md-4 col-4 col-xl-3" type="button" data-toggle="modal" data-target="#locationModal" data-address="'.$point['point_address'].'" data-point_name="'.$point['point_name'].'">Показать на карте</button>';
				//$tmp['content'] .= "<h4>$point[point_name] $btn</h4><p>Доступно мест: (<b>$free_quotas</b>/$point[point_quotas])<br>$point[point_address] </p><p></p><hr/>" ;
				
				$tmp['content'] .= '<div class="container broken_grid_container"><div style="margin-top: 18px;" class="row display-flex align-center broken_row_container margin-zero-auto">';
				$tmp['content'] .= "<h4  class=\"col-md-6 col-5 col-xl-7\">$point[point_name] </h4>$btn</div>";
				$tmp['content'] .= "<div style=\"margin-top: 10px;\" class=\"row display-flex align-center broken_row_container margin-zero-auto\">";
				$tmp['content'] .= "<p class=\"col-md-6 col-5 col-xl-7 margin-bottom-zero\">$point[point_address] </p>$btn2</div><hr></div>";
			}
			$data['region'][] = $tmp;
		}
		$data['header'] = $this->parser->parse('welcome/header', array(), true);
		$data['footer'] = $this->parser->parse('welcome/footer', array(), true);

		$this->html .= $this->parser->parse('welcome/5register', $data, true);

		$this->design_lib->show();
	}
	public function action($section =''){
		switch ($section){
			case 'register':				
				$this->load->library(array('design_lib', 'p_lib', 'email'));
				$this->p_lib->load(array('email_pat'));
				
				$email = $this->input->post('user_email');
				$words = array('АВТОРИТАРНОСТЬ', 'АККРЕДИТАЦИЯ', 'АКМЕОЛОГИЯ', 'АКСЕЛЕРАЦИЯ', 'АКСИОЛОГИЯ', 'АКТУАЛИЗАЦИЯ', 'АЛГОРИТМ', 'АЛЬТРУИЗМ', 'АМБИДЕКСТРИЯ', 'АМБИЦИЯ', 'АНДРОГОГИКА', 'АНДРОГИНИЯ', 'АНКЕТА', 'АННОТАЦИЯ', 'АНОМАЛИЯ', 'АНТРОПОКОСМИЗМ', 'АНТРОПОЛОГИЗМ', 'АПАТИЯ', 'АПРОБАЦИЯ', 'АРИСТОТЕЛЬ', 'АРТТЕРАПИЯ', 'АРХЕТИП', 'АТТИТЮД', 'АТТРАКЦИЯ', 'БЕРДЯЕВ', 'БЕСЕДА', 'БИБЛИОТЕРАПИЯ', 'БИХЕВИОРИЗМ', 'ВАЛЕОЛОГИЯ', 'ВАЛИДНОСТЬ', 'ВДОХНОВЕНИЕ', 'ВЕНТЦЕЛЬ', 'ВЕРА', 'ВЕРБАЛИЗАЦИЯ', 'ВЕРИФИКАЦИЯ', 'ВЛИЯНИЕ', 'ВОЛЯ', 'ВООБРАЖЕНИЕ', 'ВОСПИТАНИЕ', 'ВУНДЕРКИНД', 'ГЕГЕЛЬ', 'ГЕДОНИЗМ', 'ГЕЛЬВЕЦИЙ', 'ГЕНИАЛЬНОСТЬ', 'ГЕНОТИП', 'ГЕРМЕНЕВТИКА', 'ГЕРОГОГИКА', 'ГЕШТАЛЬТ', 'ГИМНАЗИЯ', 'ГИПНОПЕДИЯ', 'ГИПОТЕЗА', 'ГЛОССАРИЙ', 'ГОСПИТАЛИЗМ', 'ГРАЖДАНСТВЕННОСТЬ', 'ГРАМОТНОСТЬ', 'ГУМАНИЗАЦИЯ', 'ГУМАНИЗМ', 'ГУМАНИТАРНЫЙ', 'ГУМАННОСТЬ', 'ДЕЙСТВИЯ', 'ДЕМОКРИТ', 'ДЕТСТВО', 'ДЕЯТЕЛЬНОСТЬ', 'ДИДАКТИКА', 'ДУХОВНОСТЬ', 'ДУША', 'ЖИЗНЕДЕЯТЕЛЬНОСТЬ', 'ЗАДАТКИ', 'ЗАДАЧА', 'ЗАКОН', 'ЗНАНИЕ', 'ИГРА', 'ИДЕАЛИЗАЦИЯ', 'ИДЕАЛ', 'ИНДИВИД', 'ИНДУКЦИЯ', 'ИНТЕЛЛЕКТ', 'ИНТЕРЕС', 'ИНТЕРФЕРЕНЦИЯ', 'ИНФОРМАЦИЯ', 'ИСКУССТВО', 'ИССЛЕДОВАНИЕ', 'КЛАСС', 'КОМПЕТЕНТНОСТЬ', 'КОНКРЕТИЗАЦИЯ', 'КОНСУЛЬТАЦИЯ', 'КОНТЕКСТ', 'КОУЧ', 'КРЕАТИВНОСТЬ', 'КРИТЕРИЙ', 'КУЛЬТУРА', 'КУРС', 'ЛЕКЦИЯ', 'ЛИЧНОСТЬ', 'МЕТОД', 'МОДУЛЬ', 'МОРАЛЬ', 'МОТИВ', 'МЫШЛЕНИЕ', 'НАВЫК');
				$code = $words[array_rand($words)];
				// Шлем письмо с временноый ссылкой для смены пароля
				$this->config->load('email', TRUE);
				
				$email_config = $this->config->item('email');
	
				$this->email->initialize($this->config->item('email'));
				$this->email->from($email_config['smtp_user'], $this->config->item('project_name_short'));
				$this->email->to($email);
				$user_themes = $this->input->post('user_themes');
				if(is_array($user_themes)){
					$user_themes = implode('|',$user_themes);
				}
				$user_id_sys = null;
				if($this->login){
					$user_id_sys = $this->user_id;
				}
				$this->db->insert('point_user', array(
					'point_id' => $this->input->post('point_id'),
					'user_id_sys' => $user_id_sys,
					'user_name' => $this->input->post('user_name'),
					'code' => $code,
					'user_email' => $this->input->post('user_email'),
					'user_job' => $this->input->post('user_job'),
					'user_themes' => $user_themes,
					'location_id' => $this->input->post('location_id'),
					'institution_id' => $this->input->post('institution_id'),
				));
				$this->email->subject('Кодовое слово | ' . $this->config->item('project_name_short'));
				$this->design_lib->set_template('email');

				$this->html = $this->h_lib->_content(array(
					$this->p_lib->email_pat->title(array(
						'content' => 'Ваше кодовое слово:'
					)),
					$this->p_lib->email_pat->text(array(
						'content' => '«<b>'.$code.'</b>»'
					))
				));

				$out = $this->design_lib->show('', array(), FALSE);
			
				$this->email->message($out);

				if (!$this->email->send()) {
					echo 'Ошибка';
					exit;
				} else {
					$this->html = '';
					$this->load->library(array('design_lib'));
					$this->design_lib->msg(array('title' => 'На вашу электронную почту отправлен код доступа', 'url' => "lk"));
				}
				
			break;
		}
	}
	public function program($token = '') {
		if($token != $this->token){
			exit;
		}
		$this->load->library(array('design_lib', 'Grocery_CRUD'));
		$crud = new grocery_CRUD();
		$this->design_lib->set_template('lk');
		$crud->set_table('program')
			;
		
		$this->html .= $this->parser->parse($this->grocery_view, $crud->render(), TRUE);

		$this->design_lib->show();
	}
	public function ajax_r_votelist($pid = ''){ 
		if(!empty($pid)){
			$this->db->select('user_auth.user_email, vote.participant_id, vote.user_id');
			$this->db->join('user_auth', 'user_auth.user_id = vote.user_id');
			$vote = $this->db->get_where('vote', array('participant_id' => $pid))->result('array_id', 'user_id', TRUE);
			$data = array();
			foreach($vote as $v){
				$email = $v[0]['user_email'];
				$email = substr_replace($email, '*****', 0, 5);
				$data[$email] = count($v);
			}
			echo json_encode(array('success'=>$data));
		}else{
			echo json_encode(array('error'=>1));
		}
	}
	public function vote() {
		
		$this->load->library(array('design_lib'));
		$data = array();

		if($this->login){
			$vote = $this->db->get_where('vote', array('user_id' => $this->user_id))->result('array_id', 'participant_id', TRUE);
			$vote_total = $this->db->get_where('vote', array('user_id' => $this->user_id))->result('array');
		}else{
			$vote_total = $vote = array();
		}
		$votes = $this->db->get('vote')->result('array_id', 'participant_id', TRUE);
		$left_bals = $this->max_vote - count($vote_total);
		$data['participant'] = $this->db->get('participant')->result('array');
		foreach($data['participant'] as $key => $participant){
			$data['participant'][$key]['participant_category_name'] = $this->category[$participant['participant_category']];
			$vote_status = '';
				
			if(isset($vote[$participant['participant_id']])){
				$data['participant'][$key]['participant_bals'] = count($vote[$participant['participant_id']]);
				$vote_status .= '<p><button class="btn btn-primary btn-gradient btn-fsmall col-md-10 col-10 col-xl-10" type="button" data-toggle="modal" data-target="#voteModal" data-pid="'.$participant['participant_id'].'">Голосов: <b>' . count($votes[$participant['participant_id']]) . '</b></button></p>';
			}else{
				$data['participant'][$key]['participant_bals'] = 'Голосовать';
			}
			if(!empty($participant['participant_photo'] )){
				$data['participant'][$key]['participant_photo'] = "assets/uploads/participant/$participant[participant_photo]";
			}else{
				$data['participant'][$key]['participant_photo'] = "assets/files/photo/{$participant['participant_category']}/{$participant['participant_number']}.jpg";				
			}

			$data['participant'][$key]['vote_status'] = $vote_status;
		}
		$data['header'] = $this->parser->parse('welcome/header', array(), true);
		if($this->login){
			$data['introtext'] = "<p>Ваша регистрация прошла успешно. Вы можете отдать свои баллы одному педагогу или распределить их по нескольким участникам. Ваши голоса принесут участникам радость и уверенность в себе.</p><p><b>Осталось баллов:</b> <span id='total_bals'>$left_bals</span></p>";
			
		}else{
			$data['introtext'] = '<p>Для голосования за участников конкурса «Педагог года» вам необходимо <a href="register" class="btn btn-primary btn-gradient btn-fsmall">пройти регистрацию</a> или <a href="login" class="btn btn-primary btn-gradient btn-fsmall">авторизоваться</a> если вы уже были зарегистрированы.</p>';
		}
		$data['footer'] = $this->parser->parse('welcome/footer', array(), true);

		$this->html .= $this->parser->parse('welcome/vote', $data, true);

		$this->design_lib->show();
	}
	function ajax_r_progam($point_id = '') {
		if(empty($point_id)){
			echo 'Ошибка';
			exit;
		}
		$this->db->order_by('program_day','ASC'); 
		$program = $this->db->get('program')->result('array_id', 'program_day', TRUE);
		
		$program_id = $this->db->get('program')->result('array_id', 'program_id', FALSE);
		
		$days_title = array(17 => '17 Августа', 18 => 'Трек «Качество» 18 Августа', 19 => 'Трек «Личностный Рост» 19 Августа', 20 => 'Трек «Цифра» 20 Августа', 21 => 'Трек «Воспитание» 21 Августа');
		$out_html = '';
		
		$point = $this->db->get_where('point', array('point_id' => $point_id))->row_array();
		$point_user = $this->db->get_where('point_user', array('point_id' => $point_id))->result('array');

		
		foreach($point_user as $key => $user){
			
			$programs_arr = explode('|', $user['user_themes']);
			foreach($programs_arr as $key2 => $p){
				if(!isset($program_id[$p]['users'])){
					$program_id[$p]['users'] = 1;
				}else{
					$program_id[$p]['users']++;
				}
			}
		}			
		
		foreach($program as $key => $days){
		
			$out_html .= "<optgroup label=\"$days_title[$key]\">";
			foreach($days as $prog){
				$disabled = '';
				if(isset($program_id[$prog['program_id']]['users'])){
					$quat = $point['point_quotas'] - $program_id[$prog['program_id']]['users'];
					if($quat<=0){
						$quat = 0;
						$disabled = 'disabled';
					}
				}else{
					$quat = $point['point_quotas'];
				}
				
				$quatas = "<b>(Доступно мест: $quat/$point[point_quotas])</b>";
				
				$out_html .= "<option $disabled value=\"$prog[program_id]\">$prog[program_name] $quatas</option> ";
				
			}
			$out_html .= "</optgroup>";
		}
		echo json_encode(array('success'=>$out_html));
		
	}
	/**
	 * AJAX голосование
	 */
	function ajax_r_vote($direction, $pid) {
		//END
		//echo json_encode(array('success' => '', 'error' => 'Голосование завершилось!', 'left' => 0));
		//exit;
		$error = '';
		$left_bals = $this->max_vote;
		$success = FALSE;
		if($this->login){
			$vote = $this->db->get_where('vote', array('user_id' => $this->user_id))->result('array');
			$left_bals = $this->max_vote - count($vote);
			if(!empty($pid)){
				if($direction == 1) {// PLUS
					if(count($vote) < $this->max_vote){
						$this->db->insert('vote', array('user_id' => $this->user_id, 'participant_id' => $pid));
						$success = TRUE;
						$left_bals--;
					}else{
						$error = 'Нет баллов для голосования';
					}
				}elseif($direction == -1){ // MINUS
					$vote_item = $this->db->get_where('vote', array('user_id' => $this->user_id, 'participant_id' => $pid))->row_array();
					if(!empty($vote_item)){
						$this->db->delete('vote', array('vote_id' => $vote_item['vote_id']));
						$success = TRUE;
						$left_bals++;
					}
				}else{
					$error = 'Ошибка. (Direction)';
				}
			}else{
				$error = 'Ошибка. (Pid)';
			}
			
			
		}else{
			$error = 'Для голосования нужна регистрация на сайте';
		}
		 
		echo json_encode(array('success' => $success, 'error' => $error, 'left' => $left_bals));
	}
	
}
