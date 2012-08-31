<?php

class SuggessEbooks_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->helper( array('modeltool') );
}


/**
* add method creates a record in the suggess_ebook table.
*
* Option: Values
* --------------
* ebook_id			(required)
* ebook_pic
*
* @param array $options
* @return int insert_id()
*/
function add($options = array())
{
    // required values
    if(!_required(array('ebook_id'), $options)) return false;

    // default values
    //$options = $this->_default(array('userStatus' => 'active'), $options);

    // qualification (make sure that we're not allowing the site to insert data that it shouldn't)
    $qualificationArray = array('ebook_id', 'ebook_pic');
	
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }
	
    // Execute the query
    $this->db->insert('suggess_ebook');

    // Return the ID of the inserted row, or false if the row could not be inserted
    return $this->db->insert_id();
}


/**
* update method alters a record in the suggess_ebook table.
*
* Option: Values
* --------------
* seid				the ID of the video record that will be updated
* ebook_id
* ebook_pic
*
* @param array $options
* @return int affected_rows()
*/
function update($options = array())
{
	// required values
	if(!_required(array('seid'), $options)) return false;

	// qualification (make sure that we're not allowing the site to update data that it shouldn't)
	$qualificationArray = array('ebook_id', 'ebook_pic');

	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->where('seid', $options['seid']);

	// Execute the query
	$this->db->update('suggess_ebook');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* get method returns an array of suggess_ebook record objects
*
* Option: Values
* --------------
* seid
* limit					limits the number of returned records
* offset				how many records to bypass before returning a record (limit required)
* sortBy				determines which column the sort takes place
* sortDirection			(asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* seid				the ID of the video record that will be updated
* ebook_id
* ebook_pic
* update_time
*
* @param array $options
* @return array result()
*/
function get($options = array())
{
    // default values
    $options = _default(array('sortDirection' => 'desc'), $options);

    // Add where clauses to query
    $qualificationArray = array('seid');
	
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
    }

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
	
    $query = $this->db->get('suggess_ebook');
    if($query->num_rows() == 0) return false;

    if(isset($options['seid'])) {
        // If we know that we're returning a singular record, then let's just return the object
        return $query->row(0);
    }
    else {
        // If we could be returning any number of records then we'll need to do so as an array of objects
        return $query->result();
    }
}

/**
* delete method removes a record from the suggess_ebook table
*
* @param array $options
*/
function delete($options = array())
{
    // required values
    if(!_required(array('seid'), $options)) return false;

    $this->db->where('seid', $options['seid']);
    $this->db->delete('suggess_ebook');
}

/**
* delete method removes a record from the suggess_ebook table
*
* @param array $options
*/
function deleteEid($options = array())
{
    // required values
    if(!_required(array('eid'), $options)) return false;

    $this->db->where('ebook_id', $options['eid']);
    $this->db->delete('suggess_ebook');
}



}
/* End of file suggessEbooks_model.php */
/* Location: ./application/models/suggessEbooks_model.php */