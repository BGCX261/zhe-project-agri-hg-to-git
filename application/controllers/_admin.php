<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

function __construct() {
	parent::__construct();
	$this->load->model('Users_model');
	//$this->load->library('encrypt');
}

function index() {
	if($this->session->userdata('login')) {
		$menu['menu'] = 'home';
		$lmenu['left'] = 'sugg';
		$sugg['message'] = '';
		
		$this->load->model('SuggessVideoMonthly_model');
		$sugg_video_mon = $this->SuggessVideoMonthly_model->getLatest();
		$sugg['svmid'] = $sugg_video_mon->svmid;
		$sugg['svm_title'] = $sugg_video_mon->svm_title;
		$sugg['svmdate'] = $sugg_video_mon->svmdate;
		$svm_title = $this->input->post('svm_title');
		if($svm_title) {
			$svmid = $this->input->post('svmid');
			if($svmid) {
				if($this->SuggessVideoMonthly_model->update(array ('svmid' => $svmid, 'svm_title' => $svm_title))) {
					$sugg['message'] .= '推薦名稱更新成功!';
					$sugg['svm_title'] = $svm_title;
				} else {
					$sugg['message'] .= '推薦名稱未更新!';
				}
			}
		}
		
		$this->load->model('SuggessVideos_model');
		$sugg['videos'] = $this->SuggessVideos_model->getVideos($sugg_video_mon->svmid);
		
		$this->load->helper( array('menutool') );
		$this->load->model('VideoCatalogs_model');
		$sugg['vcatalog'] = _makeVCHash($this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time')));

		$this->load->view('admin/header_view');
		$this->load->view('admin/menu_view', $menu);
		$this->load->view('admin/home_left_view', $lmenu);
		$this->load->view('admin/thismonvideo_view', $sugg);
		$this->load->view('admin/footer_view');
		return;
	}
	
	//$encrypted_string = $this->encrypt->encode($msg, $key);
	//$this->session->set_userdata('login', 0);
	$message = array('message' => '');
 
	if(!(isset($_POST['username'])&&isset($_POST['password']))) {
		$this->load->view('admin/login_view', $message);
		return;
	}

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$data = $this->Users_model->getUser(array('user_name' => $username));
	if(!$data || $password !== $this->encrypt->decode($data->user_pass)) {
		$message['message'] = "帳號或密碼錯誤!";
		$this->load->view('admin/login_view', $message);
		return;
	}
	
	$userdata = array(
		'username' => $username,
		//'userprem' => $
		'usernote' => $data->user_note,
		'login' => 1,
		'last_activity' => $this->session->_get_time()
	);
	
	
	$this->session->set_userdata($userdata);
	#header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
	header('location:/admin');
}

function logout() {
	$this->session->set_userdata('login', 0);
	header('location:/admin');
}

function deleVideoSugg($svid = null) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	if($svid) {
		$this->load->model('SuggessVideos_model');
		$this->SuggessVideos_model->delete(array('svid' => $svid));
		header('location:/admin');
	}
}

// -- Banner

function addBanner() {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	$link = $this->input->post('link');
	$link = $link ? $link : '';
	
	$this->load->model('Banner_Model');
	$sort = $this->Banner_Model->maxSort();
	$sort = $sort ? $sort + 1 : 1;
	
	$newid = $this->Banner_Model->add(array(
		'link' => $link, 'sort' => $sort,
	));
	
	if($newid) {
		$uploadPath = 'images/';
		$config['upload_path'] = $uploadPath;
		$config['allowed_types'] = 'jpg';
		//$confug['overwrite'] = TRUE;
		//$config['file_name'] = $newid;
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		
		if($this->upload->do_upload('jpgfile')){
			$jpgfile_src = $uploadPath.'ban'.$newid.'.jpg';
			$upload_data = $this->upload->data();
			$file_src = $uploadPath.$upload_data['file_name'];
			list($width, $height) = getimagesize($file_src);
			if(($width != 222)||($height != 62)) {
				$source = imagecreatefromjpeg($file_src);
				$thumb = imagecreatetruecolor(222, 62);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 222, 62, $width, $height);
				imagejpeg($thumb, $jpgfile_src, 75);
				@unlink($file_src);
			} else {
				@unlink($jpgfile_src);
				rename($file_src, $jpgfile_src);
			}
		}
	}
	
	header('location:/admin/mngBanner');
	return;
}

function deleBanner($bid = null) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	$this->load->model('Banner_Model');
	$sort = $this->Banner_Model->delete(array('bid' => $bid));
	
	header('location:/admin/mngBanner');
	return;
}

function moveBanner($bid = null, $sort = null) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	if($bid&&$sort) {
		$this->load->model('Banner_Model');
		$this->Banner_Model->sort(array('bid' => $bid, 'sort' => $sort));
	}
	
	header('location:/admin/mngBanner');
	return;
}

function editBanner($bid = null) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	$menu['menu'] = 'home';
	$lmenu['left'] = 'banner';
	
	$da = $this->input->post('da');
	if($da) {
		$link = $this->input->post('link');
		
		$this->load->model('Banner_Model');
		$this->Banner_Model->update(array('bid' => $bid, 'link' => $link));
		
		$uploadPath = 'images/';
		$config['upload_path'] = $uploadPath;
		$config['allowed_types'] = 'jpg';
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		
		if($this->upload->do_upload('jpgfile')){
			$jpgfile_src = $uploadPath.'ban'.$bid.'.jpg';
			$upload_data = $this->upload->data();
			$file_src = $uploadPath.$upload_data['file_name'];
			list($width, $height) = getimagesize($file_src);
			if(($width != 222)||($height != 62)) {
				$source = imagecreatefromjpeg($file_src);
				$thumb = imagecreatetruecolor(222, 62);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 222, 62, $width, $height);
				imagejpeg($thumb, $jpgfile_src, 75);
				@unlink($file_src);
			} else {
				@unlink($jpgfile_src);
				rename($file_src, $jpgfile_src);
			}
		}
		
		header('location:/admin/mngBanner');
		return;
	}
	
	$this->load->model('Banner_Model');
	$resbanner = $this->Banner_Model->get(array('bid' => $bid));
	$banner['bid'] = $resbanner->bid;
	$banner['link'] = $resbanner->link;
	
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/home_left_view', $lmenu);
	$this->load->view('admin/editbanner_view', $banner);
	$this->load->view('admin/footer_view');
	return;
}

function mngBanner() {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	$menu['menu'] = 'home';
	$lmenu['left'] = 'banner';
	
	$this->load->model('Banner_Model');
	$banner['banners'] = $this->Banner_Model->get(array('sortBy' => 'sort'));
		
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/home_left_view', $lmenu);
	$this->load->view('admin/banner_view', $banner);
	$this->load->view('admin/footer_view');
	return;
}

// -- Video Statics

function reportPub() {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	$menu['menu'] = 'repo';
	$lmenu['left'] = 'pub';
	
	$sugg['right_title'] = '影音上架統計';
	$sugg['report_title'] = '請選擇影片上架時間';
	$sugg['message'] = '';

	$page = $this->input->post('page');
	$page = $page? (int) $page : 1;
	$sugg['page'] = $page;
	
	$limit = $this->input->post('limit');
	$limit = $limit ? (int) $limit : 50;
	$sugg['limit'] = $limit;
	
	$from = $this->input->post('from');
	$to = $this->input->post('to');
	$sugg['to'] = $to = $to?$to:date('Y-m-d');
	if($from && $to) {
		$sugg['from'] = $from;
		$sugg['to'] = $to;
		$sugg['itempp'] = $this->input->post('itempp');
		$sugg['csv'] = $this->input->post('csv');
		
		$this->load->model('Videos_model');
		$sugg['totals'] = $totals = $this->Videos_model->getNum();
		
		$this->load->model('VideoStatics_model');
		$queries = array('date_from' => $from, 'date_to' => $to,
			'sortBy' => 'clicks', 'sortDirection' => 'desc');
		if(!$sugg['csv']) {
			$queries['limit'] = $limit;
			$queries['offset'] = ($page - 1) * $limit;
		};
		$sugg['videos'] = $this->VideoStatics_model->stats($queries);
		$sugg['totalPages'] = ceil($totals/$limit);

		$this->load->helper( array('menutool') );
		$this->load->model('VideoCatalogs_model');
		$sugg['vcatalog'] = _makeVCHash($this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time')));
		
		if(!$sugg['csv']) {
			$sugg['right_title'] = '影音上架統計表';
			$sugg['report_title'] = '上架日期 '.$from.' ~ '.$to;
			
			$this->load->view('admin/header_view');
			$this->load->view('admin/menu_view', $menu);
			$this->load->view('admin/report_left_view', $lmenu);
			$this->load->view('admin/reportRes1_view', $sugg);
			$this->load->view('admin/footer_view');
			return;
		} else {
			$sugg['right_title'] = '影音上架統計表';
			$sugg['report_title'] = '上架日期 '.$from.' ~ '.$to;
			
			header('Content-Encoding: UTF-8');
			header('Content-type: text/csv; charset=UTF-8');
			header ('Content-disposition: attachment; filename="'. $sugg['right_title']. '-'. $sugg['report_title']. '.csv"');
			echo "\xEF\xBB\xBF";
			echo '"影片名稱","片長","影片分類","影片簡介","授權單位","點閱率"', "\n";
			foreach($sugg['videos'] as $video){
				echo '"', $video->title, '"', ',';
				$tmps = $video->vlength;
				$s = $tmps % 60;
				$tmps /= 60; $m = (int)$tmps % 60;
				$tmps /= 60; $h = (int)$tmps;
				echo '"', $h, ':', ($m<10?'0'.$m:$m), ':', ($s<10?'0'.$s:$s), '"', ',';
				echo '"', $sugg['vcatalog'][$video->videocatalog_id], '"', ',';
				echo '"', $video->description, '"', ',';
				echo '"', $video->author, '"', ',';
				echo '"', $video->clicks, '"', "\n";
			}
			return;
		}
	}

	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/report_left_view', $lmenu);
	$this->load->view('admin/report_view', $sugg);
	$this->load->view('admin/footer_view');
}

function reportHot() {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	$menu['menu'] = 'repo';
	$lmenu['left'] = 'hot';
	
	$sugg['right_title'] = '熱門影音排行統計';
	$sugg['report_title'] = '請設定查詢時間';
	$sugg['message'] = '';
	
	$page = $this->input->post('page');
	$page = $page? (int) $page : 1;
	$sugg['page'] = $page;
	
	$limit = $this->input->post('limit');
	$limit = $limit ? (int) $limit : 50;
	$sugg['limit'] = $limit;
	
	$from = $this->input->post('from');
	$to = $this->input->post('to');
	$sugg['to'] = $to = $to?$to:date('Y-m-d');
	if($from && $to) {
		$sugg['from'] = $from;
		$sugg['to'] = $to;
		$sugg['itempp'] = $this->input->post('itempp');
		$sugg['csv'] = $this->input->post('csv');
		
		$this->load->model('Videos_model');
		$sugg['totals'] = $totals = $this->Videos_model->getNum();
		
		$this->load->model('VideoStatics_model');
		$queries = array('date_from' => $from, 'date_to' => $to,
			'sortBy' => 'day_clicks', 'sortDirection' => 'desc');
		if(!$sugg['csv']) {
			$queries['limit'] = $limit;
			$queries['offset'] = ($page - 1) * $limit;
		};
		$sugg['videos'] = $this->VideoStatics_model->stats($queries);
		$sugg['totalPages'] = ceil($totals/$limit);

		$this->load->helper( array('menutool') );
		$this->load->model('VideoCatalogs_model');
		$sugg['vcatalog'] = _makeVCHash($this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time')));
			
		if(!$sugg['csv']) {
			$sugg['right_title'] = '熱門影音排行統計';
			$sugg['report_title'] = '查詢時間 '.$from.' ~ '.$to;
		
			$this->load->view('admin/header_view');
			$this->load->view('admin/menu_view', $menu);
			$this->load->view('admin/report_left_view', $lmenu);
			$this->load->view('admin/reportRes2_view', $sugg);
			$this->load->view('admin/footer_view');
			return;
		} else {
			$sugg['right_title'] = '熱門影音排行統計';
			$sugg['report_title'] = '查詢時間 '.$from.' ~ '.$to;
			
			header('Content-Encoding: UTF-8');
			header('Content-type: text/csv; charset=UTF-8');
			header ('Content-disposition: attachment; filename="'. $sugg['right_title']. '-'. $sugg['report_title']. '.csv"');
			echo "\xEF\xBB\xBF";
			echo '"影片名稱","片長","影片分類","影片簡介","授權單位","區間點閱數","點閱率"', "\n";
			foreach($sugg['videos'] as $video){
				echo '"', $video->title, '"', ',';
				$tmps = $video->vlength;
				$s = $tmps % 60;
				$tmps /= 60; $m = (int)$tmps % 60;
				$tmps /= 60; $h = (int)$tmps;
				echo '"', $h, ':', ($m<10?'0'.$m:$m), ':', ($s<10?'0'.$s:$s), '"', ',';
				echo '"', $sugg['vcatalog'][$video->videocatalog_id], '"', ',';
				echo '"', $video->description, '"', ',';
				echo '"', $video->author, '"', ',';
				echo '"', $video->day_clicks?$video->day_clicks:0, '"', ',';
				echo '"', $video->clicks, '"', "\n";
			}
			return;
		}
	}

	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/report_left_view', $lmenu);
	$this->load->view('admin/report_view', $sugg);
	$this->load->view('admin/footer_view');
}

function reportView() {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	$menu['menu'] = 'repo';
	$lmenu['left'] = 'view';
	
	$sugg['right_title'] = '影音觀看統計';
	$sugg['report_title'] = '請設定統計區間';
	$sugg['message'] = '';
	
	$page = $this->input->post('page');
	$page = $page? (int) $page : 1;
	$sugg['page'] = $page;
	
	$limit = $this->input->post('limit');
	$limit = $limit ? (int) $limit : 50;
	$sugg['limit'] = $limit;
	
	$from = $this->input->post('from');
	$to = $this->input->post('to');
	$sugg['to'] = $to = $to?$to:date('Y-m-d');
	if($from && $to) {
		$sugg['from'] = $from;
		$sugg['to'] = $to;
		$sugg['itempp'] = $this->input->post('itempp');
		$sugg['csv'] = $this->input->post('csv');
		
		$this->load->model('Videos_model');
		$sugg['totals'] = $totals = $this->Videos_model->getNum();

		$this->load->model('VideoStatics_model');
		$queries = array('date_from' => $from, 'date_to' => $to,
			'sortBy' => 'clicks', 'sortDirection' => 'desc');
		if(!$sugg['csv']) {
			$queries['limit'] = $limit;
			$queries['offset'] = ($page - 1) * $limit;
		};
		$sugg['videos'] = $this->VideoStatics_model->stats($queries);
		$sugg['totalPages'] = ceil($totals/$limit);
		
		$this->load->helper( array('menutool') );
		$this->load->model('VideoCatalogs_model');
		$sugg['vcatalog'] = _makeVCHash($this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time')));

		if(!$sugg['csv']) {
			$sugg['right_title'] = '影音觀看統計';
			$sugg['report_title'] = '統計區間 '.$from.' ~ '.$to;
			
			$this->load->view('admin/header_view');
			$this->load->view('admin/menu_view', $menu);
			$this->load->view('admin/report_left_view', $lmenu);
			$this->load->view('admin/reportRes2_view', $sugg);
			$this->load->view('admin/footer_view');
			return;
		} else {
			$sugg['right_title'] = '影音觀看統計';
			$sugg['report_title'] = '統計區間 '.$from.' ~ '.$to;
			
			header('Content-Encoding: UTF-8');
			header('Content-type: text/csv; charset=UTF-8');
			header ('Content-disposition: attachment; filename="'. $sugg['right_title']. '-'. $sugg['report_title']. '.csv"');
			echo "\xEF\xBB\xBF";
			echo '"影片名稱","片長","影片分類","影片簡介","授權單位","區間點閱數","點閱率"', "\n";
			foreach($sugg['videos'] as $video){
				echo '"', $video->title, '"', ',';
				$tmps = $video->vlength;
				$s = $tmps % 60;
				$tmps /= 60; $m = (int)$tmps % 60;
				$tmps /= 60; $h = (int)$tmps;
				echo '"', $h, ':', ($m<10?'0'.$m:$m), ':', ($s<10?'0'.$s:$s), '"', ',';
				echo '"', $sugg['vcatalog'][$video->videocatalog_id], '"', ',';
				echo '"', $video->description, '"', ',';
				echo '"', $video->author, '"', ',';
				echo '"', $video->day_clicks?$video->day_clicks:0, '"', ',';
				echo '"', $video->clicks, '"', "\n";
			}
			return;
		}
	}

	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/report_left_view', $lmenu);
	$this->load->view('admin/report_view', $sugg);
	$this->load->view('admin/footer_view');
}

// -- Video Suggesstion

function suggVideo($year = null, $mon = null) {
	$menu['menu'] = 'sugg';
	$lmenu['left'] = 'sugg';
	
	$this->load->model('SuggessVideoMonthly_model');
	$svmdate = ($year && $mon) ? $year.'-'.$mon.'-01' : $this->SuggessVideoMonthly_model->getLatest()->svmdate;
	$sugg_video_mon = $this->SuggessVideoMonthly_model->get(array(
		'sortBy' => 'svmdate', 'sortDirection' => 'desc',
	));

	$suggVideo['svmtitle'] = array_shift($this->SuggessVideoMonthly_model->get(array('svmdate' => $svmdate)))->svm_title;
	
	$this->load->model('SuggessVideos_model');
	$suggVideo['videos'] = $this->SuggessVideos_model->getVideos(null, $svmdate);
	list ($pyear, $pmon) = explode('-', $svmdate);
	$suggVideo['pyear'] = $pyear;
	$suggVideo['pmon'] = $pmon;
	
	$this->load->helper( array('menutool') );
	$this->load->model('VideoCatalogs_model');
	$suggVideo['vcatalog'] = _makeVCHash($this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time')));
	
	$svmdates = array();
	foreach ($sugg_video_mon as $svmdate) {
		list ($year, $mon) = explode('-', $svmdate->svmdate);
		if(array_key_exists($year, $svmdates)) {
			array_push($svmdates[$year], $mon);
		} else {
			$svmdates[$year] = array($mon);
		}
	}
	
	$suggVideo['svmdates'] = $svmdates;
	
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/suggvideo_left_view', $lmenu);
	$this->load->view('admin/suggvideo_view', $suggVideo);
	$this->load->view('admin/footer_view');
}

// -- Video catalog

function vcatalog() {
	if($this->session->userdata('login')) {
		$addVC = $this->input->post('addVC');
		
		if($addVC) {
			$this->load->model('VideoCatalogs_model');
			$noteId = $this->VideoCatalogs_model->addCatalog(array(
				'vcname' => $addVC, 'parent_id' => 0
			));
			header('location:/admin/vcatalog?'.rand());
			return;
		}
	
		$menu['menu'] = 'videos';
		$lmenu['left'] = 'vc';
		$this->load->view('admin/header_view');
		$this->load->view('admin/menu_view', $menu);
		$this->load->view('admin/video_left_view', $lmenu);
		$this->load->view('admin/videocatalog_view');
		$this->load->view('admin/footer_view');
		return;
	}
	header('location:/admin');
}

function vcatalogmng() {
	if($this->session->userdata('login')) {
		$this->load->view('admin/header_view');
		$this->load->view('admin/menu_view');
		$this->load->view('admin/videocatalogmng_view');
		$this->load->view('admin/footer_view');
		return;
	}
	header('location:/admin');
}

// -- Videos

function addVideo() {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}

	$video['message'] = '';
	$video['vid'] = $video['youtube_id'] = $video['title'] = $video['description']  
		= $video['org_source_path'] = $video['view_source_path'] = $video['other_source_path']
		= $video['vlength'] = $video['author'] = $video['vtags']  
		= $video['clicks'] = $video['create_time'] = '';
	$video['videocatalog_id'] = 1;
	$video['clicks'] = 0;
	
	if(isset($_POST['videocatalog_id'])) {
		$vcid = $_POST['videocatalog_id'];
		$this->load->model('Videos_model');
		$video['view_source_path'] = isset($_POST['view_source_path'])?$_POST['view_source_path']:null;
		$video['other_source_path'] = isset($_POST['other_source_path'])?$_POST['other_source_path']:null;
		$video['vtags'] = isset($_POST['vtags'])?$_POST['vtags']:null;
		
		$newid = $this->Videos_model->addVideo(array(
			'title' => $_POST['title'], 'youtube_id' => $_POST['youtube_id'], 'description' => $_POST['description'], 
			'org_source_path' => $_POST['org_source_path'], 'view_source_path' => $video['view_source_path'], 
			'other_source_path' => $video['other_source_path'],
			'vlength' => $_POST['vlength'], 'author' => $_POST['author'],
			'video' => $video['vtags'], 'videocatalog_id' => $_POST['videocatalog_id'],
			//'clicks' => $_POST['clicks'],'create_time' => $_POST['create_time'],
		));
		
		$this->load->model('VideoCatalogs_model');
		$this->VideoCatalogs_model->updateCatalog(array (
			'vcid' => $vcid,
			'video_counts' => $this->Videos_model->getNumInVC($vcid)
		));
		
		if($newid) {
			$uploadPath = 'images/videos/';
			$config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'jpg';
			//$confug['overwrite'] = TRUE;
			//$config['file_name'] = $newid;
			$config['encrypt_name'] = true;
			$this->load->library('upload', $config);
			$resultmsg = null;
			
			if($this->input->post('addsugg')){
				$this->load->model('SuggessVideos_model');
				$svid = $this->SuggessVideos_model->addVideo($newid);
			}
			
			if($this->upload->do_upload('thumbnail')){
				$jpgfile_src = $uploadPath.$newid.'.jpg';
				$upload_data = $this->upload->data();
				$file_src = $uploadPath.$upload_data['file_name'];
				list($width, $height) = getimagesize($file_src);
				if(($width != 197)||($height != 132)) {
					$source = imagecreatefromjpeg($file_src);
					$thumb = imagecreatetruecolor(197, 132);
					imagecopyresized($thumb, $source, 0, 0, 0, 0, 197, 132, $width, $height);
					imagejpeg($thumb, $jpgfile_src, 75);
					@unlink($file_src);
				} else {
					@unlink($jpgfile_src);
					rename($file_src, $jpgfile_src);
				}
				$resultmsg = '/1';
			} else {
				$resultmsg = '/0';
			}
			
			if($this->upload->do_upload('bigimg')){
				$jpgfile_src = $uploadPath.$newid.'_big.jpg';
				$upload_data = $this->upload->data();
				$file_src = $uploadPath.$upload_data['file_name'];
				list($width, $height) = getimagesize($file_src);
				if(($width != 650)||($height != 262)) {
					$source = imagecreatefromjpeg($file_src);
					$thumb = imagecreatetruecolor(650, 262);
					imagecopyresized($thumb, $source, 0, 0, 0, 0, 650, 262, $width, $height);
					imagejpeg($thumb, $jpgfile_src, 75);
					@unlink($file_src);
				} else {
					@unlink($jpgfile_src);
					rename($file_src, $jpgfile_src);
				}
				$bigimgmsg = '/1';
			} else {
				$bigimgmsg = '/0';
			}
			header('location:/admin/addVideoSecc/'.$newid.$resultmsg.$bigimgmsg);
			return;
		}
		$video['message'] = '新增影片失敗! ';
		
		$video['youtube_id'] = $_POST['youtube_id'];
		$video['title'] = $_POST['title'];
		$video['description'] = $_POST['description'];
		$video['org_source_path'] = $_POST['org_source_path'];
		$video['view_source_path'] = $_POST['view_source_path'];
		$video['other_source_path']	= $_POST['other_source_path'];
		$video['vlength'] = $_POST['vlength'];
		$video['author'] = $_POST['author'];
		$video['vtags'] = $_POST['vtags'];
		$video['videocatalog_id'] = $_POST['videocatalog_id'];
	}
	
	$menu['menu'] = 'videos';
	$lmenu['left'] = 'add';
	
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/video_left_view', $lmenu);
	$this->load->view('admin/addVideo_view', $video);
	$this->load->view('admin/footer_view');
}

function addVideoSecc($vid = null, $thumbres = null, $bigimg = null) {
	if((!$this->session->userdata('login'))||!$vid) {
		header('location:/admin');
		return;
	}
	
	$msg['message'] = '新增影片成功! '.($thumbres == '1' ? '縮圖上傳成功! ':'<b>縮圖上傳失敗!</b> ')
		.($bigimg == '1' ? '首頁大圖上傳成功! ':'<b>首頁大圖上傳失敗!</b> ');
	$msg['vid'] = $vid;
	
	$menu['menu'] = 'videos';
	$lmenu['left'] = 'add';
	
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/video_left_view', $lmenu);
	$this->load->view('admin/addvideosucc_view', $msg);
	$this->load->view('admin/footer_view');
}

function editVideo($id = 0) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	$id = (int) $id;
	
	if($id < 1){
		header('location:/admin');
		return;
	}
	
	$video['message'] = '';
	if(isset($_POST['videocatalog_id'])) {
		$vcid = $_POST['videocatalog_id'];
		$video['view_source_path'] = isset($_POST['view_source_path'])?$_POST['view_source_path']:null;
		$video['other_source_path'] = isset($_POST['other_source_path'])?$_POST['other_source_path']:null;
		$video['vtags'] = isset($_POST['vtags'])?$_POST['vtags']:null;
		
		$this->load->model('Videos_model');
		$numrow = $this->Videos_model->updateVideo(array(
			'vid' => $id,//$_POST['vid'],
			'title' => $_POST['title'], 'youtube_id' => $_POST['youtube_id'], 'description' => $_POST['description'], 
			'org_source_path' => $_POST['org_source_path'], 'view_source_path' => $video['view_source_path'], 
			'other_source_path' => $video['other_source_path'],
			'vlength' => $_POST['vlength'], 'author' => $_POST['author'],
			'vtags' => $video['vtags'], 'videocatalog_id' => $_POST['videocatalog_id'],
			'create_time' => $_POST['create_time'],
			//'clicks' => $_POST['clicks'], 
		));
		
		$this->load->model('VideoCatalogs_model');
		$this->VideoCatalogs_model->updateCatalog(array (
			'vcid' => $vcid,
			'video_counts' => $this->Videos_model->getNumInVC($vcid)
		));
		if(isset($_POST['ovcid'])){
			$ovcid = $_POST['ovcid'];
			if($ovcid != $vcid) {
				$this->VideoCatalogs_model->updateCatalog(array (
					'vcid' => $ovcid,
					'video_counts' => $this->Videos_model->getNumInVC($ovcid)
				));
			}
		}
		
		if($this->input->post('addsugg')){
			$this->load->model('SuggessVideos_model');
			$svid = $this->SuggessVideos_model->addVideo($id);
		}
		
		
		if($numrow) {
			$video['message'] = '更新影片資料成功! ';
		} else {
			$video['message'] = '<b>影片資料未更新!</b> ';
		}
		
		$uploadPath = 'images/videos/';
		$config['upload_path'] = $uploadPath;
		$config['allowed_types'] = 'jpg';
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		$resultmsg = null;
		if($this->upload->do_upload('thumbnail')){
			$jpgfile_src = $uploadPath.$id.'.jpg';
			$upload_data = $this->upload->data();
			$file_src = $uploadPath.$upload_data['file_name'];
			list($width, $height) = getimagesize($file_src);
			if(($width != 197)||($height != 132)) {
				$source = imagecreatefromjpeg($file_src);
				$thumb = imagecreatetruecolor(197, 132);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 197, 132, $width, $height);
				imagejpeg($thumb, $jpgfile_src, 75);
				@unlink($file_src);
			} else {
				@unlink($jpgfile_src);
				rename($file_src, $jpgfile_src);
			}
			$video['message'] .= '縮圖更新成功! ';
		} else {
			$video['message'] .= '<b>縮圖未更新!</b> ';
		}
		
		if($this->upload->do_upload('bigimg')){
			$jpgfile_src = $uploadPath.$id.'_big.jpg';
			$upload_data = $this->upload->data();
			$file_src = $uploadPath.$upload_data['file_name'];
			list($width, $height) = getimagesize($file_src);
			if(($width != 650)||($height != 262)) {
				$source = imagecreatefromjpeg($file_src);
				$thumb = imagecreatetruecolor(650, 262);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 650, 262, $width, $height);
				imagejpeg($thumb, $jpgfile_src, 75);
				@unlink($file_src);
			} else {
				@unlink($jpgfile_src);
				rename($file_src, $jpgfile_src);
			}
			$video['message'] .= '首頁大圖更新成功! ';
		} else {
			$video['message'] .= '<b>首頁大圖未更新!</b> ';
		}
	}

	$ovcid = $this->input->post('ovcid');
	if($ovcid) $video['ovcid'] = $ovcid;
	$this->load->model('Videos_model');
	$item = $this->Videos_model->getVideo(array('vid' => $id));
	if($item) {
		$video['vid'] = $item->vid;
		$video['youtube_id'] = $item->youtube_id;
		$video['title'] = $item->title;
		$video['description'] = $item->description;
		$video['org_source_path'] = $item->org_source_path;
		$video['view_source_path'] = $item->view_source_path;
		$video['other_source_path']	= $item->other_source_path;
		$video['vlength'] = $item->vlength;
		$video['author'] = $item->author;
		$video['vtags'] = $item->vtags;
		$video['clicks'] = $item->clicks;
		$video['create_time'] = $item->create_time;
		$video['videocatalog_id'] = $item->videocatalog_id;
		$this->load->model('SuggessVideos_model');
		$video['addsugg'] = $this->SuggessVideos_model->checkVideo($id);
	}
	
	$menu['menu'] = 'videos';
	$lmenu['left'] = 'mng';
	
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/video_left_view', $lmenu);
	$this->load->view('admin/editvideo_view', $video);;
	$this->load->view('admin/footer_view');
}

function deleVideo($vid = null, $vcid = null) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	if($vid) {
		$this->load->model('Videos_model');
		$this->Videos_model->deleteVideo(array('vid' => $vid));
		$from = $this->input->get('from');
		if($from ==='search') {
			header('location:/admin/searchVideo?q='.$this->input->get('q').'&p='.$this->input->get('p').'&l='.$this->input->get('l'));
		} else {
			header('location:/admin/mngVideo/'.$vcid);
		}
	} else {
		header('location:/admin');
	}
}

function mngVideo($vc = null) {
	if($this->session->userdata('login')) {
		$data['videocatalog_id'] = $vc;
		$data['message'] = '';
		$data['videos'] = null;
		
		$page = $this->input->post('page');
		$page = $page? (int) $page : 1;
		$data['page'] = $page;
		
		$limit = $this->input->post('limit');
		$limit = $limit ? (int) $limit : 50;
		$data['limit'] = $limit;
		if($vc) {
			$this->load->model('Videos_model');
			$data['totals'] = $totals = $this->Videos_model->getVideo(array('videocatalog_id' => $vc, 'counts' => 1));
			$data['videos'] = $this->Videos_model->getVideo(array('videocatalog_id' => $vc,
				'limit' => $limit, 'offset' => ($page - 1) * $limit));
			$data['totalPages'] = ceil($totals/$limit);
		}
		
		$this->load->model('SuggessVideoMonthly_model');
		$sugg_video_mon = $this->SuggessVideoMonthly_model->getLatest();
		#$data['svtitle'] = $sugg_video_mon->svm_title;

		$this->load->model('SuggessVideos_model');
		$sugg_video_ids = $this->SuggessVideos_model->get(array(
			'svm_id' => $sugg_video_mon->svmid,
			'select' => 'video_id'));
		$video_ids = array();
		foreach ($sugg_video_ids as $sugg_video_id) {
			array_push($video_ids, $sugg_video_id->video_id);
		}
		$data['sugg_video_ids'] = $video_ids;
				
		$menu['menu'] = 'videos';
		$lmenu['left'] = 'mng';
		
		$this->load->view('admin/header_view');
		$this->load->view('admin/menu_view', $menu);
		$this->load->view('admin/video_left_view', $lmenu);
		$this->load->view('admin/mngvideo_view', $data);
		$this->load->view('admin/footer_view');
		return;
	}
	header('location:/admin');
}


function searchVideo() {
	if($this->session->userdata('login')) {
		$data['message'] = '';
		$data['videos'] = null;
		
		$keyword = $this->input->post('keyword');
		$keyword = $keyword ? $keyword : $this->input->get('q');
		$page = $this->input->post('page');
		$page = $page? (int) $page : $this->input->get('p');
		$page = $page? (int) $page : 1;
		$data['page'] = $page;
		
		$limit = $this->input->post('limit');
		$limit = $limit ? (int) $limit : $this->input->get('l');
		$limit = $limit ? (int) $limit : 50;
		$data['limit'] = $limit;
		if($keyword) {
			$data['keyword'] = $keyword;
			$this->load->model('Videos_model');
			$data['totals'] = $totals = $this->Videos_model->getVideo(array('keyword' => $keyword, 'counts' => 1));
			$data['videos'] = $this->Videos_model->getVideo(array('keyword' => $keyword, 
				'limit' => $limit, 'offset' => ($page - 1) * $limit));
			$data['totalPages'] = ceil($totals/$limit);
		}
		
		$this->load->model('SuggessVideoMonthly_model');
		$sugg_video_mon = $this->SuggessVideoMonthly_model->getLatest();
		#$data['svtitle'] = $sugg_video_mon->svm_title;

		$this->load->model('SuggessVideos_model');
		$sugg_video_ids = $this->SuggessVideos_model->get(array(
			'svm_id' => $sugg_video_mon->svmid,
			'select' => 'video_id'));
		$video_ids = array();
		foreach ($sugg_video_ids as $sugg_video_id) {
			array_push($video_ids, $sugg_video_id->video_id);
		}
		$data['sugg_video_ids'] = $video_ids;
				
		$menu['menu'] = 'videos';
		$lmenu['left'] = 'search';
		
		$this->load->view('admin/header_view');
		$this->load->view('admin/menu_view', $menu);
		$this->load->view('admin/video_left_view', $lmenu);
		$this->load->view('admin/searchvideo_view', $data);
		$this->load->view('admin/footer_view');
		return;
	}
	header('location:/admin');
}

function videomng() {
	if($this->session->userdata('login')) {
		$this->load->view('admin/header_view');
		$this->load->view('admin/menu_view');
		$this->load->view('admin/videomng_view');
		$this->load->view('admin/footer_view');
		return;
	}
	header('location:/admin');
}

// --- Ebooks

function addEbook() {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}

	$ebook['message'] = '';
	$ebook['eid'] = $ebook['title'] = $ebook['description']  
		= $ebook['author'] = $ebook['author_unit'] = $ebook['etags']  
		= $ebook['clicks'] = $ebook['create_time'] = '';
	$ebook['clicks'] = 0;
	
	if(isset($_POST['title'])) {
		$ebook['etags'] = isset($_POST['etags'])?$_POST['etags']:null;
		$this->load->model('Ebooks_model');
		$newid = $this->Ebooks_model->addEbook(array(
			'title' => $_POST['title'], 'description' => $_POST['description'], 
			'author' => $_POST['author'], 'author_unit' => $_POST['author_unit'],
			'etags' => $ebook['etags']
			//'clicks' => $_POST['clicks'],'create_time' => $_POST['create_time'],
		));
		if($newid) {
			$uploadPath = 'images/ebooks/';
			$config['upload_path'] = $uploadPath;
			$config['allowed_types'] = 'jpg|pdf';
			//$confug['overwrite'] = TRUE;
			//$config['file_name'] = $newid;
			$config['encrypt_name'] = true;
			$this->load->library('upload', $config);
			$resultmsg = null;
			if($this->upload->do_upload('thumbnail')){
				$jpgfile_src = $uploadPath.$newid.'_list.jpg';
				$upload_data = $this->upload->data();
				$file_src = $uploadPath.$upload_data['file_name'];
				list($width, $height) = getimagesize($file_src);
				if(($width != 148)||($height != 208)) {
					$source = imagecreatefromjpeg($file_src);
					$thumb = imagecreatetruecolor(148, 208);
					imagecopyresized($thumb, $source, 0, 0, 0, 0, 148, 208, $width, $height);
					imagejpeg($thumb, $jpgfile_src, 75);
					@unlink($file_src);
				} else {
					@unlink($jpgfile_src);
					rename($file_src, $jpgfile_src);
				}
				$resultmsg = '/1';
			} else {
				$resultmsg = '/0';
			}
			
			if($this->upload->do_upload('bigimg')){
				$jpgfile_src = $uploadPath.$newid.'.jpg';
				$upload_data = $this->upload->data();
				$file_src = $uploadPath.$upload_data['file_name'];
				list($width, $height) = getimagesize($file_src);
				if(($width != 720)||($height != 468)) {
					$source = imagecreatefromjpeg($file_src);
					$thumb = imagecreatetruecolor(720, 468);
					imagecopyresized($thumb, $source, 0, 0, 0, 0, 720, 468, $width, $height);
					imagejpeg($thumb, $jpgfile_src, 75);
					@unlink($file_src);
				} else {
					@unlink($jpgfile_src);
					rename($file_src, $jpgfile_src);
				}
				$bigimgmsg = '/1';
			} else {
				$bigimgmsg = '/0';
			}
			
			//$config['allowed_types'] = 'pdf';
			//$this->load->library('upload', $config);
			if($this->upload->do_upload('pdffile')){
				$pdffile_src = $uploadPath.$newid.'.pdf';
				$upload_data = $this->upload->data();
				$file_src = $uploadPath.$upload_data['file_name'];
				@unlink($pdffile_src);
				rename($file_src, $pdffile_src);
				$pdfmsg = '/1';
			} else {
				$pdfmsg = '/0';
			}
			header('location:/admin/addEbookSecc/'.$newid.$resultmsg.$bigimgmsg.$pdfmsg);
			return;
		}
		$ebook['message'] = '新增電子書失敗! ';
		
		$ebook['title'] = $_POST['title'];
		$ebook['description'] = $_POST['description'];
		$ebook['author'] = $_POST['author'];
		$ebook['author_unit'] = $_POST['author_unit'];
		$ebook['etags'] = $_POST['etags'];
	}
	
	$menu['menu'] = 'ebooks';
	$lmenu['left'] = 'add';
	
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/ebook_left_view', $lmenu);
	$this->load->view('admin/addEbook_view', $ebook);
	$this->load->view('admin/footer_view');
}

function addEbookSecc($eid = null, $thumbres = null, $bigimg = null, $pdf = null) {
	if((!$this->session->userdata('login'))||!$eid) {
		header('location:/admin');
		return;
	}
	
	$msg['message'] = '新增電子書成功! '.($thumbres == '1' ? '縮圖上傳成功! ':'<b>縮圖上傳失敗!</b> ')
		.($bigimg == '1' ? '電子書封面上傳成功! ':'<b>電子書封面上傳失敗!</b> ')
		.($pdf == '1' ? 'PDF上傳成功! ':'<b>PDF上傳失敗!</b> ');
	$msg['eid'] = $eid;
	
	$menu['menu'] = 'ebooks';
	$lmenu['left'] = 'add';
	
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/ebook_left_view', $lmenu);
	$this->load->view('admin/addebooksucc_view', $msg);
	$this->load->view('admin/footer_view');
}

function editEbook($id = 0) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	$id = (int) $id;
	
	if($id < 1){
		header('location:/admin');
		return;
	}
	
	$ebook['message'] = '';
	$ebook['etags'] = isset($_POST['etags'])?$_POST['etags']:null;
	if(isset($_POST['title'])) {
		$this->load->model('Ebooks_model');
		$numrow = $this->Ebooks_model->updateEbook(array(
			'eid' => $id,
			'title' => $_POST['title'], 'description' => $_POST['description'], 
			'author' => $_POST['author'], 'author_unit' => $_POST['author_unit'],
			'etags' => $ebook['etags'],
			'create_time' => $_POST['create_time'],
			//'clicks' => $_POST['clicks'],
		));
		if($numrow) {
			$ebook['message'] = '更新影片資料成功! ';
		} else {
			$ebook['message'] = '<b>影片資料未更新!</b> ';
		}
		
		$uploadPath = 'images/ebooks/';
		$config['upload_path'] = $uploadPath;
		$config['allowed_types'] = 'jpg|pdf';
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		$resultmsg = null;
		if($this->upload->do_upload('thumbnail')){
			$jpgfile_src = $uploadPath.$id.'_list.jpg';
			$upload_data = $this->upload->data();
			$file_src = $uploadPath.$upload_data['file_name'];
			list($width, $height) = getimagesize($file_src);
			if(($width != 148)||($height != 208)) {
				$source = imagecreatefromjpeg($file_src);
				$thumb = imagecreatetruecolor(148, 208);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 148, 208, $width, $height);
				imagejpeg($thumb, $jpgfile_src, 75);
				@unlink($file_src);
			} else {
				@unlink($jpgfile_src);
				rename($file_src, $jpgfile_src);
			}
			$ebook['message'] .= '縮圖更新成功! ';
		} else {
			$ebook['message'] .= '<b>縮圖未更新!</b> ';
		}
		
		if($this->upload->do_upload('bigimg')){
			$jpgfile_src = $uploadPath.$id.'.jpg';
			$upload_data = $this->upload->data();
			$file_src = $uploadPath.$upload_data['file_name'];
			list($width, $height) = getimagesize($file_src);
			if(($width != 720)||($height != 468)) {
				$source = imagecreatefromjpeg($file_src);
				$thumb = imagecreatetruecolor(720, 468);
				imagecopyresized($thumb, $source, 0, 0, 0, 0, 720, 468, $width, $height);
				imagejpeg($thumb, $jpgfile_src, 75);
				@unlink($file_src);
			} else {
				@unlink($jpgfile_src);
				rename($file_src, $jpgfile_src);
			}
			$ebook['message'] .= '首頁大圖更新成功! ';
		} else {
			$ebook['message'] .= '<b>首頁大圖未更新!</b> ';
		}
		
		//$config['allowed_types'] = 'pdf';
		//$this->load->library('upload', $config);
		if($this->upload->do_upload('pdffile')){
			$pdffile_src = $uploadPath.$id.'.pdf';
			$upload_data = $this->upload->data();
			$file_src = $uploadPath.$upload_data['file_name'];
			@unlink($pdffile_src);
			rename($file_src, $pdffile_src);
			$ebook['message'] .= 'PDF更新成功! ';
		} else {
			$ebook['message'] .= '<b>PDF未更新!</b> ';
		}
	}
	
	$this->load->model('Ebooks_model');
	$item = $this->Ebooks_model->getEbook(array('eid' => $id));
	if($item) {
		$ebook['eid'] = $item->eid;
		$ebook['title'] = $item->title;
		$ebook['description'] = $item->description;
		$ebook['author'] = $item->author;
		$ebook['author_unit'] = $item->author_unit;
		$ebook['etags'] = $item->etags;
		$ebook['clicks'] = $item->clicks;
		$ebook['create_time'] = $item->create_time;
	}
	
	$menu['menu'] = 'ebooks';
	$lmenu['left'] = 'mng';
	
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view', $menu);
	$this->load->view('admin/ebook_left_view', $lmenu);
	$this->load->view('admin/editebook_view', $ebook);
	$this->load->view('admin/footer_view');
}

function deleEbook($eid = null) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	if($eid) {
		$this->load->model('Ebooks_model');
		$this->Ebooks_model->deleteEbook(array('eid' => $eid));		
		header('location:/admin/mngEbook');
	} else {
		header('location:/admin');
	}
}

function mngEbook() {
	if($this->session->userdata('login')) {
		$data['message'] = '';
		$data['ebooks'] = null;
		
		$this->load->model('Ebooks_model');
		$data['ebooks'] = $this->Ebooks_model->getEbook(array());
		
		$this->load->model('SuggessEbooks_model');
		$sugg_ebook_ids = $this->SuggessEbooks_model->get();
		$ebook_ids = array();
		if($sugg_ebook_ids) {
			foreach ($sugg_ebook_ids as $sugg_ebook_id) {
				array_push($ebook_ids, $sugg_ebook_id->ebook_id);
			}
		}
		$data['sugg_ebook_ids'] = $ebook_ids;
		
		$menu['menu'] = 'ebooks';
		$lmenu['left'] = 'mng';
		
		$this->load->view('admin/header_view');
		$this->load->view('admin/menu_view', $menu);
		$this->load->view('admin/ebook_left_view', $lmenu);
		$this->load->view('admin/mngebook_view', $data);
		$this->load->view('admin/footer_view');
		return;
	}
	header('location:/admin');
}

function ebookmng() {
	if($this->session->userdata('login')) {
		$this->load->view('admin/header_view');
		$this->load->view('admin/menu_view');
		$this->load->view('admin/ebookmng_view');
		$this->load->view('admin/footer_view');
		return;
	}
	header('location:/admin');
}

function suggEbook() {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}

	$this->load->model('SuggessEbooks_model');
	$sugg_ebook_ids = $this->SuggessEbooks_model->get();
	$ebook_ids = array();
	foreach ($sugg_ebook_ids as $sugg_ebook_id) {
		array_push($ebook_ids, $sugg_ebook_id->ebook_id);
	}
	$this->load->model('Ebooks_model');
	$data['suggestebooks'] = $this->Ebooks_model->getEbook(array(
		'eid' => $ebook_ids,
		'select' => 'eid, title, clicks',
		'sortDirection' => 'desc', 
		'multi' => true
	));	
	
	$this->load->view('admin/header_view');
	$this->load->view('admin/menu_view');
	$this->load->view('admin/suggebook_view', $data);
	$this->load->view('admin/footer_view');
}

function addSuggEbook($eid = null) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	if($eid) {
		$this->load->model('SuggessEbooks_model');
		$this->SuggessEbooks_model->add(array('ebook_id' => $eid));
		header('location:/admin/mngEbook');
	} else {
		header('location:/admin');
	}
}

function deleSuggEbook($eid = null) {
	if(!$this->session->userdata('login')) {
		header('location:/admin');
		return;
	}
	
	if($eid) {
		$this->load->model('SuggessEbooks_model');
		$this->SuggessEbooks_model->deleteEid(array('eid' => $eid));
		header('location:/admin/mngEbook');
	} else {
		header('location:/admin');
	}
}

// -- User

function addUser() {
	//$this->session->
	//$encrypted_string = $this->encrypt->encode($msg, $key);
	
#	echo $this->Users_model->addUser(array('user_name' => 'at', 'user_pass' => '1234'));
	
	echo header('Content-type: text/plain');
	
	$data = $this->Users_model->getUser(array('uid' => '1'));
	print_r($data);
	echo '====',"\n";
	
	//echo $this->encrypt->decode(rtrim($data->user_pass));
	echo $this->encrypt->decode($data->user_pass);
}

/* -------------------------------
 *  AJAX Functions
 * ------------------------------- */

/* -- Video Suggestion functions -- */ 

/**
* jsAddVideoSugg method add Suggestion video.
*
* Option: Values
* --------------
* $_POST['vid']				(required)
*
* @param null
* @return string json_encode($result)
*/
public function jsAddVideoSugg() {
	if(!$this->session->userdata('login')) {
		return false;
	}
	
	$vid = $this->input->post('vid');
	$vid = $vid ? $vid : $this->input->get('vid');
	$this->load->model('SuggessVideos_model');
	$result = $this->SuggessVideos_model->addVideo($vid);
	echo json_encode($result); 
}
 
/* -- Video functions -- */

/**
* jsgetVideos method get videos.
*
* Option: Values
* --------------
* $_POST['row']				(required)
* $_POST['page']			(required)
*
* @param null
* @return string json_encode($result)
*/
public function jsgetVideos() {
	if(!$this->session->userdata('login')) {
		return false;
	}
	
	/*if(!(isset($_POST['row'])&&isset($_POST['page']))) {
		echo 'false';
		return false;
	}*/

	$vcid = isset($_POST['vcid']) ? intval($_POST['vcid']) : -1;
	$rown = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$offset = $rown * ($page - 1);
	
	$result = array(); 
	$row = array();
	
	$this->load->model('Videos_model');
	if($vcid < 0) {
		$videos = $this->Videos_model->getVideo(array('offset' => $offset, 'limit' => $rown));
	} else {
		$videos = $this->Videos_model->getVideo(array('videocatalog_id' => $vcid, 'offset' => $offset, 'limit' => $row));
	}
	$result['total'] = $this->Videos_model->getNumInVC($vcid);
	
	header('Content-type: text/plain');

	if($videos) {
		foreach ($videos as $video) {
			$vitem = array();
			$vitem['vid'] = $video->vid;
			$vitem['youtube_id'] = $video->youtube_id;
			$vitem['title'] = $video->title;
			$vitem['description'] = $video->description;
			$vitem['videocatalog_id'] = $video->videocatalog_id;
			$vitem['clicks'] = $video->clicks;
			$vitem['org_source_path'] = $video->org_source_path;
			$vitem['create_time'] = $video->create_time;
			array_push($row, $vitem);
		}
		$result['rows'] = $row;
	}
	
	echo json_encode($result);  
}

/* -- Video Catalogs functions -- */ 

/**
* jsgetVCatalogTable method get all video_catalogs in Table.
*
* Option: Values
* --------------
* null
*
* @param null
* @return string json_encode($result)
*/
 
public function jsgetVCatalogTable() {
	if(!$this->session->userdata('login')) {
		return false;
	}
	
	$rown = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$offset = $rown * ($page - 1);
	
	$result = array(); 
	$row = array();
	
	$this->load->model('VideoCatalogs_model');
	$data = $this->VideoCatalogs_model->getCatalogs(array('offset' => $offset, 'limit' => $rown));
	header('Content-type: text/plain');
	
	$result['total'] = $this->VideoCatalogs_model->getNumAll();
	$result['rows'] = $data;
	echo json_encode($result);  
}

/**
* jsgetVCatalogList method get all video_catalogs in struture.
*
* Option: Values
* --------------
* null
*
* @param null
* @return string json_encode($result)
*/
 
public function jsgetVCatalogList() {
	if(!$this->session->userdata('login')) {
		return false;
	}
	
	$result = array(); 
	
	$this->load->model('VideoCatalogs_model');
	$data = $this->VideoCatalogs_model->getCatalogs(array());
	header('Content-type: text/plain');
	
	$this->load->helper( array('menutool') );
	$result = _makeVC($data);
	echo json_encode($result);  
}

/**
* jsgetVCatalog method get video_catalogs.
*
* Option: Values
* --------------
* $_POST['id']			(required)
*
* @param null
* @return string json_encode($result)
*/
 
public function jsgetVCatalog() {
	if(!$this->session->userdata('login')) {
		return false;
	}
	
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$result = array(); 
	
	$this->load->model('VideoCatalogs_model');
	$data = $this->VideoCatalogs_model->getCatalogs(array('parent_id' => $id, 'sortBy' => 'create_time'));
	header('Content-type: text/plain');
	
	if($data) {
		foreach ($data as $row) {
			$node = array();
			$node['id'] = $row->vcid;
			$node['text'] = $row->vcid. ') '. $row->vcname. ' ('. $row->video_counts . ')';
			$node['attributes'] = array ('vcid' => $row->vcid, 
				'vcname' => $row->vcname, 'vcounts' => $row->video_counts);
			$node['state'] = $this->VideoCatalogs_model->getNumChilden($row->vcid) ? 'closed' : 'open';  
			array_push($result, $node);
		}
	}
	
	echo json_encode($result);  
}
 
/**
* jsaddVCatalog method insert a new video_catalog to database.
*
* Option: Values
* --------------
* $_POST['name']		(required)
* $_POST['pid']			(required)
*
* @param null
* @return int $noteId
*/

public function jsaddVCatalog() {
	if(!$this->session->userdata('login')) {
		return false;
	}
	
	$this->load->model('VideoCatalogs_model');
	if(!(isset($_POST['name'])&&isset($_POST['pid']))) {
		echo '-1';
		return -1;
	}
	
	$vcname = $_POST['name'];
	$pid = $_POST['pid'];

	$noteId = $this->VideoCatalogs_model->addCatalog(array(
			'vcname' => $vcname, 'parent_id' => $pid
	));
	
	echo $noteId;
	return true;
}
 
/**
* jsmoveVCatalog method update parent_id of video_catalog.
*
* Option: Values
* --------------
* $_POST['id']				(required)
* $_POST['targetId']		(required)
* $_POST['point']			(required)
*
* @param null
* @return boolean
*/

public function jsmoveVCatalog() {
	if(!(isset($_POST['id'])&&isset($_POST['targetId'])&&isset($_POST['point']))) {
		echo 'false';
		return false;
	}
	
	$id = $_POST['id'];
	$targetId = $_POST['targetId'];
	$point = $_POST['point'];

	$this->load->model('VideoCatalogs_model');
	
	if($point === 'append') {
		$this->VideoCatalogs_model->updateCatalog(array('vcid' => $id, 'parent_id' => $targetId));
	} else if ($point) { // 'top', 'bottom'
		$spId = $this->VideoCatalogs_model->getParentId($id);
		$tpId = $this->VideoCatalogs_model->getParentId($targetId);
		
		if($spId != $tpId) {
			$this->VideoCatalogs_model->updateCatalog(array('vcid' => $id, 'parent_id' => $tpId));
		}
	}
	
	echo 'true';
	return true;
}

/**
* jsupdateVCatalog method update vcname of video_catalog.
*
* Option: Values
* --------------
* $_POST['id']			(required)
* $_POST['name']		(required)
*
* @param null
* @return boolean
*/

public function jsupdateVCatalog() {
	if(!(isset($_POST['id'])&&isset($_POST['name']))) {
		echo 'false';
		return false;
	}
	$id = $_POST['id'];
	$vcname = $_POST['name'];
	
	$this->load->model('VideoCatalogs_model');
	$this->VideoCatalogs_model->updateCatalog(array('vcid' => $id, 'vcname' => $vcname));
	
	echo 'true';
	return true;
}

/**
* jsdeleteVCatalog method update vcname of video_catalog.
*
* Option: Values
* --------------
* $_POST['id']			(required)
* $_POST['pid']
*
* @param null
* @return boolean
*/

public function jsdeleteVCatalog() {
	if(!(isset($_POST['id']))) {
		echo 'false';
		return false;
	}
	$id = $_POST['id'];
	if($id<=10) return false;
	
	$this->load->model('VideoCatalogs_model');
	$pid = $this->VideoCatalogs_model->getParentId($id);
	
	$this->load->model('Videos_model');
	
	$old_video_counts = $this->Videos_model->getNumInVC($pid);
	$new_video_counts = $this->Videos_model->changVc(array('ovcid' => $id, 'nvcid' => $pid));
	$real_video_counts = $old_video_counts + $new_video_counts;	

	$this->VideoCatalogs_model->deleteCatalog(array('vcid' => $id));
	$this->VideoCatalogs_model->updateCatalog(array('vcid' => $pid, 'video_counts' => $real_video_counts));
	
	echo 'true';
	return true;
}
	

} // End of class