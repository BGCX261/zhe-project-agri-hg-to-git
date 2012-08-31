<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VCatalog extends CI_Controller {
function __construct() {
	parent::__construct();
	$this->load->model('VideoCatalogs_model');
	if(!$this->session->userdata('login')) {
		header('location:/admin');
	}
}

public function index() {
	//$this->load->view('vcatalog_view');
	header('location:/admin');
}

public function tree_getVCatalog() {
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$result = array(); 
	
	$data = $this->VideoCatalogs_model->getCatalogs(array('parent_id' => $id));
	header('Content-type: text/plain');
	
	if($data) {
		foreach ($data as $row) {
			$node = array();
			$node['id'] = $row->vcid;
			$node['text'] = $row->vcname. ' ('. $row->video_counts . ')';
			$node['attributes'] = array ('vcid' => $row->vcid, 
				'vcname' => $row->vcname, 'vcounts' => $row->video_counts);
			$node['state'] = $this->VideoCatalogs_model->getNumChilden($row->vcid) ? 'closed' : 'open';  
			array_push($result, $node);
		}
	}
	
	echo json_encode($result);  
}
}
/* End of file vCatalog.php */
/* Location: ./application/controllers/vCatalog.php */