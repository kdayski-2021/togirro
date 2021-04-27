<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lk extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->load->library(array('design_lib', 'crypto_lib'));
		$this->design_lib->set_template('lk');
		$this->db->select('point_email, point_name');
		$points = $this->db->get('point')->result('array');
		$point_email = array();
		foreach($points as $point){
			$point_email[] = strtolower($point['point_email']);
		}
        $this->db->order_by('category', 'ASC');
        $data['video'] = $this->db->get('video')->result('array');	
		$data['adminblock'] = '';
		$email  = strtolower($this->user_email);
		if(in_array($email, $point_email)){
			$point = $this->db->get_where('point', array('point_email' => $email))->row_array();
			if(!empty($point)){
				$data['adminblock'] = $this->parser->parse('lk/adminblock', array('point_name' => $point['point_name'], 'point_code' => $point['point_code']), true);;
			}
		}
		$this->design_lib->show('lk/index', $data);
	}
	function video() {
		$this->load->library(array('design_lib', 'crypto_lib'));
		$this->design_lib->set_template('lk');
        $data['video'] = $this->db->get('video')->result('array');
		$this->design_lib->show('lk/video', $data);
	}
	function lk2() {
		$this->load->library(array('design_lib', 'crypto_lib'));
		$this->design_lib->set_template('lk');
		$this->db->select('point_email, point_name');
		$points = $this->db->get('point')->result('array');
		$point_email = array();
		foreach($points as $point){
			$point_email[] = $point['point_email'];
		}
		$data['adminblock'] = '';
		$email  = $this->user_email;
		$email  = 'pinigin82@yandex.ru';
		if(in_array($email, $point_email)){
			$point = $this->db->get_where('point', array('point_email' => $email))->row_array();
			if(!empty($point)){
				$data['adminblock'] = $this->parser->parse('lk/adminblock', array('point_name' => $point['point_name'], 'point_code' => $point['point_code']), true);;
			}			
		}
		$this->design_lib->show('lk/index', $data);
	}
	function lists($day = 17) {
		
		$program = $this->db->get_where('program', array('program_day' => $day))->result('array');
		$program_ids = array();
		foreach($program as $pr){
			$program_ids[] = $pr['program_id']; 
		}
		
		$point = $this->db->get_where('point', array('point_email' => $this->user_email))->row_array();
		if(!empty($point)){
			$out_html = '<table cellpadding="4" cellspacing="1" border="1" style="text-align:left;">';
			$this->db->order_by('code', 'ASC');
			$users = $this->db->get_where('point_user', array('point_id' => $point['point_id']))->result('array');
			$send_users  = array();
			foreach($users as $user){
				$themes = explode('|', $user['user_themes']);
				$finded = FALSE; 
				foreach ($themes as $theme){
					if(!$finded){
						if(in_array($theme, $program_ids)){  
							$send_users[] = $user;
							$finded = TRUE;
						}
					}
				}
			}
			
			$out_html .= "<tr><td>#</td><td>Слово</td><td>Ф.И.О.</td><td>Явка*</td></tr>";
			if(count($send_users)){
				foreach($send_users as $user){
					$user_name = preg_replace('/\s/', ' ', $user['user_name']);
					$user_name_arr = explode(' ', trim ($user_name));
					
					$un = '';
					if(!empty($user_name_arr[0]))
						$un .= $user_name_arr[0];
					if(!empty($user_name_arr[1]))
						$un .= ' ' . mb_substr ($user_name_arr[1], 0, 1,'UTF-8') . '.';
					if(!empty($user_name_arr[2]))
						$un .= ' ' . mb_substr ($user_name_arr[2], 0, 1,'UTF-8') . '.'; 
					$out_html .= "<tr><td>[$user[user_id]]</td><td>$user[code]</td><td>$un</td><td></td></tr>";
				}
				$out_html .= '</table><p>* - заполните поле "Явка" отметив крестиком или галочкой если человек пришел.</p>';
				
				$this->xxx($point, $out_html, "assets/files/list/$day/"); 
				
		
			}  
		}else{
			redirect(404);
		}
		
			
		
	}
	function xxx($point, $income_html, $path){
		$this->load->library('tcpdf_lib');
		$pdf = new TCPDF_lib(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Fanil');
		$pdf->SetTitle('Список '. $point['point_id']);
		$pdf->SetSubject('Forum');
		$pdf->SetKeywords('Forum');

		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Список участников - https://forum.togirro.ru', 'Форум «Тюменское образование - 2020»');

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$pdf->SetFont('dejavusans', '', 10);


		$pdf->AddPage();


		$html = "<h1>[{$point['point_id']}] $point[point_name]</h1>";
		$html .= "";
		$html .= $income_html;
		$pdf->writeHTML($html, true, false, true, false, '');


		$pdf->lastPage();

		$pdf->Output("$path/list_$point[point_id].pdf", 'I');
	}
	function docs() {
		$this->load->library(array('design_lib', 'crypto_lib'));
		$this->design_lib->set_template('lk');
		
		$data = array();
        $this->db->order_by('doc_day', 'ASC');
        
		$data['docs'] = $this->db->get('participant_doc')->result('array');		
		$this->design_lib->show('lk/docs', $data);
	}

}
