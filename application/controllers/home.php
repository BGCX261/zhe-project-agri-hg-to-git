<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

function __construct() {
	parent::__construct();
	$this->load->helper( array('menutool') );
}

public function index()
{
	$this->load->model('Videos_model');
	$data['videos'] = $this->Videos_model->getVideo(array(
		'sortBy' => 'create_time', 'sortDirection' => 'desc', 'limit' => 3
	));
	
	$this->load->model('Ebooks_model');
	$data['ebooks'] = $this->Ebooks_model->getEbook(array(
		'sortBy' => 'create_time', 'sortDirection' => 'desc', 'limit' => 3
	));

	/*$this->load->model('SuggessEbooks_model');
	$sugg_ebook_ids = $this->SuggessEbooks_model->get(array('limit' => 3));
	$ebook_ids = array();
	foreach ($sugg_ebook_ids as $sugg_ebook_id) {
		array_push($ebook_ids, $sugg_ebook_id->ebook_id);
	}
	
	$data['suggestebooks'] = $this->Ebooks_model->getEbook(array(
		'eid' => $ebook_ids,
		'select' => 'eid, title, description, clicks',
		'sortDirection' => 'desc', 
		'multi' => true
	));	*/
	
	$this->load->model('VideoCatalogs_model');
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time'));
	$menu['func'] = 'home';
	$menu['menu'] = _makeMenu($menuitems);

	$this->load->model('SuggessVideoMonthly_model');
	$sugg_video_mon = $this->SuggessVideoMonthly_model->getLatest();
	$data['svtitle'] = $sugg_video_mon->svm_title;
	
	$this->load->model('SuggessVideos_model');
	$sugg_video_ids = $this->SuggessVideos_model->get(array(
		'svm_id' => $sugg_video_mon->svmid,
		'select' => 'video_id'));
	$video_ids = array();
	if($sugg_video_ids) {
		foreach ($sugg_video_ids as $sugg_video_id) {
			array_push($video_ids, $sugg_video_id->video_id);
		}
		sort($video_ids);
		$data['svideo_ids'] = $video_ids;
		$data['svideos'] = $this->Videos_model->getVideo(array(
			'vid' => $video_ids,
			'select' => 'vid, title, description',
			'multi' => true
		));
	} else {
		$data['svideo_ids'] = $data['svideos'] = array();
	}

	$this->load->model('Banner_Model');
	$banner['banners'] = $this->Banner_Model->get(array('sortBy' => 'sort'));
	
	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('home_view', $data);
	$this->load->view('bottom_view', $banner);
	$this->load->view('footer_view');
}

public function test()
{
	//echo realpath('.');

	echo '<a href="/home/videos">/home/videos</a><br/>';
	echo '<br/>';
	echo '<a href="/welcome">/welcome</a><br/>';
}

public function videos()
{
	header('Content-type: text/plain');
	//$this->load->model('VideoCatalogs_model');
	$menu = $this->getVCChildren();
}

public function e404()
{
	$data['error_title'] = '錯誤';
	$data['error_message'] = '查無此頁面!';
	
	$this->load->view('header_view');
	$this->load->view('error_view', $data);
	$this->load->view('footer_view');
}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */