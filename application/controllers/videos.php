<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videos extends CI_Controller {

function __construct() {
	parent::__construct();
	$this->load->helper( array('menutool') );
}

public function index() {
	header('location: /videos/more');
}


public function search($page = 1) {
	if(!$this->input->post('keyword')) {
		if($this->session->userdata('keyword')) {
			$keyword = $this->session->userdata('keyword');
		} else {
			header('location: /videos/more');
			retrun;
		}
	} else {
		$keyword = $this->input->post('keyword');
		$this->session->set_userdata(array('keyword' => $keyword));
	}
	
	$this->load->model('Videos_model');
	$itemsInPage = 16;
	$numResults = $this->Videos_model->getVideo(array('keyword' => $keyword, 'counts'=> true));
	
	$data['itemsInPage'] = $itemsInPage;
	$data['thisLink'] = '/videos/search';
	$data['vcid'] = -1;
	$data['thisPage'] = $page;
	$data['totalPages'] = ceil($numResults/$itemsInPage);
	
	$data['keyword'] = $keyword;
	$data['numResults'] = $numResults;
	
	$data['videos'] = $this->Videos_model->getVideo(array(
		'keyword' => $keyword,
		'sortBy' => 'create_time',
		'sortDirection' => 'desc',
		'offset' => ($page - 1) * $itemsInPage, 
		'limit' => $itemsInPage,
		'select' => 'vid, title, create_time'
	));
	
	$this->load->model('VideoCatalogs_model');
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array(
		'sortBy' => 'create_time',
	));
	$menu['func'] = 'videos';
	$menu['keyword'] = $keyword;
	$menu['menu'] = _makeMenu($menuitems);
	$data['title'] = '搜尋結果';
	//$data['vcatalog'] = _makeVCHash($menuitems);
	
	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('videos/list_view', $data);
	$this->load->view('footer_view');
}

public function advsearch($page = 1) {
	if(!$this->input->post('keyword')) {
		if($this->session->userdata('keyword')) {
			$keyword = $this->session->userdata('keyword');
			$datefrom = $this->session->userdata('datefrom');
			$dateto = $this->session->userdata('dateto');
		} else {
			header('location: /videos/more');
			retrun;
		}
	} else {
		$keyword = $this->input->post('keyword');
		$datefrom = $this->input->post('datefrom');
		$dateto = $this->input->post('dateto');
		$this->session->set_userdata(array(
			'keyword' => $keyword,
			'datefrom' => $datefrom,
			'dateto' => $dateto
		));
	};
	
	$this->load->model('Videos_model');
	$itemsInPage = 16;
	
	$search_array = array('keyword' => $keyword, 'counts' => true);
	if($datefrom) $search_array['date_from'] = $datefrom;
	if($dateto) $search_array['date_to'] = $dateto;
	$numResults = $this->Videos_model->getVideo($search_array);
	
	$data['itemsInPage'] = $itemsInPage;
	$data['thisLink'] = '/videos/advsearch';
	$data['vcid'] = -1;
	$data['thisPage'] = $page;
	$data['totalPages'] = ceil($numResults/$itemsInPage);
	
	$data['keyword'] = $keyword;
	$data['numResults'] = $numResults;
	
	unset($search_array['counts']);
	$search_array['sortBy'] = 'create_time';
	$search_array['sortDirection'] = 'desc';
	$search_array['offset'] = ($page - 1) * $itemsInPage;
	$search_array['limit'] = $itemsInPage;
	$search_array['select'] = 'vid, title, create_time';
	$data['videos'] = $this->Videos_model->getVideo($search_array);
	
	$this->load->model('VideoCatalogs_model');
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array(
		'sortBy' => 'create_time',
	));
	$menu['func'] = 'videos';
	$menu['keyword'] = $keyword;
	$menu['datefrom'] = $datefrom;
	$menu['dateto'] = $dateto;
	$menu['menu'] = _makeMenu($menuitems);
	$data['title'] = '搜尋結果';
	//$data['vcatalog'] = _makeVCHash($menuitems);
	
	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('videos/list_view', $data);
	$this->load->view('footer_view');
}

public function more($page = 1) {
	$this->load->model('Videos_model');
	$itemsInPage = 16;
	
	$data['itemsInPage'] = $itemsInPage;
	$data['thisLink'] = '/videos/more/';
	$data['vcid'] = -1;
	$data['thisPage'] = $page;
	$data['totalPages'] = 1;//ceil($this->Videos_model->getNumInVC(-1)/$itemsInPage);
	
	$data['videos'] = $this->Videos_model->getVideo(array(
		'sortBy' => 'create_time',
		'sortDirection' => 'desc',
		'offset' => ($page - 1) * $itemsInPage, 
		'limit' => $itemsInPage,
		'select' => 'vid, title, create_time'
	));
	
	$this->load->model('VideoCatalogs_model');
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array(
		'sortBy' => 'create_time',
	));
	$menu['func'] = 'videos';
	$menu['menu'] = _makeMenu($menuitems);
	$data['title'] = '最新影音';
	//$data['vcatalog'] = _makeVCHash($menuitems);
	
	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('videos/list_view', $data);
	$this->load->view('footer_view');
}

public function lists($videocatalog_id = 1, $page = 1) {
	$this->load->model('Videos_model');
	$itemsInPage = 16;
	
	$data['itemsInPage'] = $itemsInPage;
	$data['thisLink'] = '/videos/lists/'.$videocatalog_id.'/';
	$data['vcid'] = $videocatalog_id;
	$data['thisPage'] = $page;
	$data['totalPages'] = ceil($this->Videos_model->getNumInVC($videocatalog_id)/$itemsInPage);
	
	$data['videos'] = $this->Videos_model->getVideo(array(
		'videocatalog_id' => $videocatalog_id, 
		'sortBy' => 'create_time',
		'sortDirection' => 'desc',
		'offset' => ($page - 1) * $itemsInPage, 
		'limit' => $itemsInPage,
		'select' => 'vid, title, create_time'
	));
	
	$this->load->model('VideoCatalogs_model');
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array(
		'sortBy' => 'create_time',
	));
	$menu['func'] = 'videos';
	$menu['menu'] = _makeMenu($menuitems);
	$videoCatalog = $this->VideoCatalogs_model->getCatalogs(array('vcid' => $videocatalog_id));
	$data['title'] = $videoCatalog ? $videoCatalog->vcname : '無分類';
	//$data['vcatalog'] = _makeVCHash($menuitems);
	
	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('videos/list_view', $data);
	$this->load->view('footer_view');
}

public function bigimg($vid, $redirect = false)
{
	$location_src = 'images/videos/'. $vid;
	$location = $location_src. '_big.jpg';
	
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
		header('Content-type: image/png');
		$im = @imagecreate(650, 262);
		
		$background_color = imagecolorallocate($im, 238, 238, 238);
		$text_color = imagecolorallocate($im, 0, 0, 0);
		imagestring($im, 1, 5, 5, "Not found!", $text_color);
		imagepng($im);
		imagedestroy($im);
	}
}

public function thumbnail($vid, $isTiny = false, $redirect = false)
{
//	$this->load->model('Videos_model');
	$location_src = 'images/videos/'. $vid;
	if($isTiny) {
		$location = $location_src. '_tiny.jpg';
	} else {
		$location = $location_src. '.jpg';
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
			if(file_exists($location_src.'.jpg')) {
				header('Content-type: image/jpeg');
				$source = imagecreatefromjpeg($location_src.'.jpg');
				$thumb = imagecreatetruecolor(80, 52);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 80, 52, 197, 132);
				imagejpeg($thumb, $location, 75);
				imagejpeg($thumb, NULL , 75);
				return;
			}
			header('Content-type: image/png');
			$im = @imagecreate(80, 52);
		} else {
			header('Content-type: image/png');
			$im = @imagecreate(197, 132);
		}
		
		$background_color = imagecolorallocate($im, 238, 238, 238);
		$text_color = imagecolorallocate($im, 0, 0, 0);
		imagestring($im, 1, 5, 5, "Not found!", $text_color);
		imagepng($im);
		imagedestroy($im);
		
		/*
		header('Content-type: image/jpeg');
		
		$data = $this->Videos_model->getVideoThumbnail(array('vid' => $vid), $isTiny);
		$file = fopen($location, 'w');
		if($isTiny) {
			if($data->video_thumbnail_tiny) {
				echo $data->video_thumbnail_tiny;
				fwrite($file, $data->video_thumbnail_tiny);
			} else {
				$data = $this->Videos_model->getVideoThumbnail(array('vid' => $vid));
				$source = imagecreatefromstring($data->video_thumbnail);
				
				$thumb = imagecreatetruecolor(80, 52);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 80, 52, 197, 132);

				ob_start();
				imagejpeg($thumb, NULL, 75);
				$thumb_data = ob_get_contents(); 
				ob_end_clean();
				
				$data = $this->Videos_model->updateVideo(array('vid' => $vid,
					'video_thumbnail_tiny' => $thumb_data
				));
				
				echo $thumb_data;
				fwrite($file, $thumb_data);
			}
		} else {
			echo $data->video_thumbnail;
			fwrite($file, $data->video_thumbnail);
		}
		fclose($file);
		*/
	}
}
	
public function play($vid = null) {
	if(!$vid) {
		header('location: /');
		return;
	}

	$this->load->model('Videos_model');
	$video = $this->Videos_model->getVideo(array('vid' => $vid));
	if(!$video) {
		header('location: /');
		return;
	}
	$data['video'] = $video;
	$video->clicks += 1; 
	$this->Videos_model->updateVideo(array('vid' => $vid, 'clicks' => $video->clicks));
	
	$this->load->model('VideoCatalogs_model');
	$videoCatalog = $this->VideoCatalogs_model
		->getCatalogs(array('vcid' => $data['video']->videocatalog_id));
	$data['vcname'] = $videoCatalog ? $videoCatalog->vcname : '無分類';
		
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time'));
	$menu['func'] = 'videos';
	$menu['menu'] = _makeMenu($menuitems);
	$menu['nosearch'] = true;
	
	$this->load->model('VideoStatics_model');
	$this->VideoStatics_model->plusOne($vid);

	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('videos/play_view', $data);
	$this->load->view('footer_view');
}
	
}
/* End of file videos.php */
/* Location: ./application/controllers/videos.php */