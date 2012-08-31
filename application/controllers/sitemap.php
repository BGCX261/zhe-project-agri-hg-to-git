<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sitemap extends CI_Controller {

function __construct() {
	parent::__construct();
	$this->load->helper( array('menutool') );
}

public function index() {
	$this->load->model('VideoCatalogs_model');
	$menuitems = $this->VideoCatalogs_model->getCatalogs(array(
		'sortBy' => 'create_time',
	));
	$menu['func'] = 'videos';
	$menu['menu'] = _makeMenu($menuitems);
	//$data['vcatalog'] = _makeVCHash($menuitems);
	
	$this->load->view('header_view');
	$this->load->view('menu_view', $menu);
	$this->load->view('sitemap_view', $menu);
	$this->load->view('footer_view');
}


}
/* End of file sitemap.php */
/* Location: ./application/controllers/sitemap.php */