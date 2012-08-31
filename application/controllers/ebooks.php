<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ebooks extends CI_Controller {

function __construct() {
	parent::__construct();
	$this->load->helper( array('menutool') );
}

public function index() {
	header('location: /ebooks/lists');
}

public function lists($page = 1) {
	$this->load->model('Ebooks_model');
	$itemsInPage = 8;
	
	$data['itemsInPage'] = $itemsInPage;
	$data['thisLink'] = '/ebooks/lists/';
	$data['thisPage'] = $page;
	$data['totalPages'] = ceil($this->Ebooks_model->getEbookNum()/$itemsInPage);
	
	$data['ebooks'] = $this->Ebooks_model->getEbook(array(
		'sortBy' => 'create_time',
		'sortDirection' => 'desc',
		'offset' => ($page - 1) * $itemsInPage, 
		'limit' => $itemsInPage,
		'select' => 'eid, title'
	));
	
	$this->load->model('SuggessEbooks_model');
	$sugg_ebook_ids = $this->SuggessEbooks_model->get(array('limit' => 5));
	$ebook_ids = array();
	if($sugg_ebook_ids) {
		foreach ($sugg_ebook_ids as $sugg_ebook_id) {
			array_push($ebook_ids, $sugg_ebook_id->ebook_id);
		}
	
		$data['suggestebooks'] = $this->Ebooks_model->getEbook(array(
			'eid' => $ebook_ids,
			'select' => 'eid, title, description, clicks',
			'sortDirection' => 'desc', 
			'multi' => true
		));
	} else {
		$data['suggestebooks'] = array();
	}
	
	$this->load->model('VideoCatalogs_model');
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time'));
	$menu['func'] = 'ebooks';
	$menu['menu'] = _makeMenu($menuitems);

	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('ebooks/list_view', $data);
	$this->load->view('footer_view');
	
}
	
public function read($eid = null) {
	if(!$eid) {
		header('location: /');
		return;
	}

	$this->load->model('Ebooks_model');
	$ebook = $this->Ebooks_model->getEbook(array('eid' => $eid));
	if(!$ebook) {
		header('location: /');
		return;
	}
	$data['ebook'] = $ebook;
	$ebook->clicks += 1; 
	$this->Ebooks_model->updateEbook(array('eid' => $eid, 'clicks' => $ebook->clicks));
	
	$this->load->model('SuggessEbooks_model');
	$sugg_ebook_ids = $this->SuggessEbooks_model->get(array('limit' => 5));
	$ebook_ids = array();
	if($sugg_ebook_ids) {
		foreach ($sugg_ebook_ids as $sugg_ebook_id) {
			array_push($ebook_ids, $sugg_ebook_id->ebook_id);
		}
		$data['suggestebooks'] = $this->Ebooks_model->getEbook(array(
			'eid' => $ebook_ids,
			'select' => 'eid, title, clicks',
			'sortDirection' => 'desc', 
			'multi' => true
		));	
	} else {
		$data['suggestebooks'] = array();
	}
	
	$this->load->model('VideoCatalogs_model');
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time'));
	$menu['func'] = 'ebooks';
	$menu['menu'] = _makeMenu($menuitems);

	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('ebooks/read_view', $data);
	$this->load->view('footer_view');
}
	
public function thumbnail($eid, $isTiny = false, $redirect = false)
{
	$this->load->model('Ebooks_model');
	$location_src = 'images/ebooks/'. $eid;
	if($isTiny) {
		$location = $location_src. '_tiny.jpg';
	} else {
		$location = $location_src. '_list.jpg';
	}
	
	if(file_exists($location)) {
		if($redirect) {
			header('location: /'. $location);
		} else {
			header('Content-type: image/jpeg');
			
			$file = fopen($location, 'r');
			echo fread($file,filesize($location));
			fclose($file);
		}
	} else {
		
		if($isTiny) {
			if(file_exists($location_src.'_list.jpg')) {
				header('Content-type: image/jpeg');
				$source = imagecreatefromjpeg($location_src.'_list.jpg');
				$thumb = imagecreatetruecolor(73, 102);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 73, 102, 148, 208);
				imagejpeg($thumb, $location, 75);
				imagejpeg($thumb, NULL , 75);
				return;
			}
			header('Content-type: image/png');
			$im = @imagecreate(73, 102);
		} else {
			header('Content-type: image/png');
			$im = @imagecreate(148, 208);
		}
		$background_color = imagecolorallocate($im, 238, 238, 238);
		$text_color = imagecolorallocate($im, 0, 0, 0);
		imagestring($im, 1, 5, 5, "Not found!", $text_color);
		imagepng($im);
		imagedestroy($im);
		/*
		header('Content-type: image/jpeg');
		
		$data = $this->Ebooks_model->getEbookThumbnail(array('eid' => $eid), $isTiny);
		$file = fopen($location, 'w');
		if($isTiny) {
			if($data->ebook_thumbnail_tiny) {
				echo $data->ebook_thumbnail_tiny;
				fwrite($file, $data->ebook_thumbnail_tiny);
			} else {
				$data = $this->Ebooks_model->getEbookThumbnail(array('eid' => $eid));
				$source = imagecreatefromstring($data->ebook_thumbnail);
				
				$thumb = imagecreatetruecolor(73, 102);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 73, 102, 148, 208);

				ob_start();
				imagejpeg($thumb, NULL, 75);
				$thumb_data = ob_get_contents(); 
				ob_end_clean();
				
				$data = $this->Ebooks_model->updateEbook(array('eid' => $eid,
					'ebook_thumbnail_tiny' => $thumb_data
				));
				
				echo $thumb_data;
				fwrite($file, $thumb_data);
			}
		} else {
			echo $data->ebook_thumbnail;
			fwrite($file, $data->ebook_thumbnail);
		}
		fclose($file);
		*/
	}
}

public function download($eid = null) {
	if(!$eid) {
		header('location: /ebooks/lists');
		return;
	}
	
	$this->load->model('Ebooks_model');
	$ebook = $this->Ebooks_model->getEbook(array('eid' => $eid));

	if($ebook) {
		$location = 'images/ebooks/'.$ebook->eid.'.pdf';
		if(file_exists($location)) {
			$file = fopen($location, 'rb');
			if($file) {
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment;filename="'.($ebook->title).'.pdf"');
				#echo fread($file, filesize($location));
				while (!feof($file)) { 
					echo fread($file, 8192); 
				} 
			}
			fclose($file);
		} else {
			$msg['error_title'] = '錯誤';
			$msg['error_message'] = '電子書PDF不存在!';
			
			$this->load->view('header_view');
			$this->load->view('error_view', $msg);
			$this->load->view('footer_view');
		}
	} else {
		header('location: /ebooks/lists');
	}
}
	
}
/* End of file ebooks.php */
/* Location: ./application/controllers/ebooks.php */