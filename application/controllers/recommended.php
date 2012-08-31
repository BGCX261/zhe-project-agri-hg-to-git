<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recommended extends CI_Controller {

function __construct() {
	parent::__construct();
	$this->load->helper( array('menutool') );
}

public function index()
{
	$this->load->model('VideoCatalogs_model');
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array('sortBy' => 'create_time'));
	$menu['func'] = 'recommended';
	$menu['menu'] = _makeMenu($menuitems);

	if($this->input->post('year') && $this->input->post('month')) {
		$year = $this->input->post('year');
		$year = ($year< 1000) ? $year + 1911 : $year;
		$month = $this->input->post('month');
		$this->load->model('SuggessVideoMonthly_model');
		$sugg_video_mons = $this->SuggessVideoMonthly_model->get(array(
			'svmdate' => date("Y-m-d", strtotime($year.'/'.$month.'/01'))
		));
		
		if($sugg_video_mons) {
			$sugg_video_mon = $sugg_video_mons[0];
		} else {
			$this->load->view('header_view');
			$this->load->view('menu_view', $menu);
			$this->load->view('recommended_no_view');
			$this->load->view('bottom_view');
			$this->load->view('footer_view');
			return;
		}
	} else {
		$this->load->model('SuggessVideoMonthly_model');
		$sugg_video_mon = $this->SuggessVideoMonthly_model->getLatest();
	}
	$data['svmdate'] = $sugg_video_mon->svmdate;
	$data['svmtitle'] = $sugg_video_mon->svm_title;
	
	$this->load->model('SuggessVideos_model');
	$sugg_video_ids = $this->SuggessVideos_model->get(array(
		'svm_id' => $sugg_video_mon->svmid,
		'select' => 'video_id'));
	$video_ids = array();
	if($sugg_video_ids) {
		foreach ($sugg_video_ids as $sugg_video_id) {
			array_push($video_ids, $sugg_video_id->video_id);
		}
		$this->load->model('Videos_model');
		$data['svideos'] = $this->Videos_model->getVideo(array(
			'vid' => $video_ids,
			'select' => 'vid, title, description, author, vlength',
			'multi' => true
		));
	} else {
		$data['svideos'] = array();
	}
	
	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('recommended_view', $data);
	$this->load->view('footer_view');
}


}

/* End of file recommended.php */
/* Location: ./application/controllers/recommended.php */