<?php

class Banner_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->helper( array('modeltool') );
}


/**
* add method creates a record in banner table.
*
* Option: Values
* --------------
* sort			(required)
* link
*
* @param array $options
* @return int insert_id()
*/
function add($options = array()) {
    if(!_required(array('sort'), $options)) return false;
	$qualificationArray = array('sort', 'link', 'group_id');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }
    $this->db->insert('banner');
    return $this->db->insert_id();
}


/**
* update method alters a record in banner table.
*
* Option: Values
* --------------
* bid				the ID of the banner record that will be updated
* sort
* link
*
* @param array $options
* @return int affected_rows()
*/
function update($options = array()) {
	if(!_required(array('bid'), $options)) return false;
	$qualificationArray = array('sort', 'link');
	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }
	$this->db->where('bid', $options['bid']);
	$this->db->update('banner');
	return $this->db->affected_rows();
}

/**
* get method returns an array of banner record objects
*
* Option: Values
* --------------
* bid
* limit                limits the number of returned records
* offset                how many records to bypass before returning a record (limit required)
* sortBy                determines which column the sort takes place
* sortDirection        (asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* bid
* sort
* link
* update_time
*
* @param array $options
* @return array result()
*/
function get($options = array()) {
    $options = _default(array('sortDirection' => 'desc'), $options);
    $qualificationArray = array('bid');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
    }

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);

    $query = $this->db->get('banner');
	
    if($query->num_rows() == 0) return false;
	if($query->num_rows() == 1) return $query->row(0);
	return $query->result();
}

/**
* sort method swap two record from the banner table
*
* @param array $options
*/
function sort($options = array()) {
	if(!_required(array('bid', 'sort'), $options)) return false;
	
	$this->db->select('sort');
	$this->db->where('bid', $options['bid']);
	$query = $this->db->get('banner');
	$orginsort = $query->row(0)->sort;

	$this->db->select('bid');
	$this->db->where('sort', $options['sort']);
	$query = $this->db->get('banner');
	$targetbid = $query->row(0)->bid;

	if($targetbid) {
		$this->db->where('bid', $targetbid);
		$this->db->set('sort', $orginsort);
		$this->db->update('banner');
		
		$this->db->where('bid', $options['bid']);
		$this->db->set('sort', $options['sort']);
		$this->db->update('banner');
		return true;
	}
	return false;
}

/**
* maxSort method get the Max sort from the banner table
*
* @param array $options
*/
function maxSort() {
	$this->db->select_max('sort');
	$query = $this->db->get('banner');
	return $query->row(0)->sort;
}

/**
* delete method removes a record from the banner table
*
* @param array $options
*/
function delete($options = array()) {
    if(!_required(array('bid'), $options)) return false;
    $this->db->where('bid', $options['bid']);
    $this->db->delete('banner');
}



}
/* End of file banner_model.php */
/* Location: ./application/models/banner_model.php */