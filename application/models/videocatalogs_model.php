<?php

class VideoCatalogs_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->helper( array('modeltool') );
}


/**
* addCatalog method creates a record in the video_catalogs table.
*
* Option: Values
* --------------
* vcname			(required)
* description
* parent_id
* video_counts
*
* @param array $options
* @return int insert_id()
*/
function addCatalog($options = array())
{
    // required values
    if(!_required(array('vcname'), $options)) return false;

    // default values
    //$options = $this->_default(array('userStatus' => 'active'), $options);

    // qualification (make sure that we're not allowing the site to insert data that it shouldn't)
    $qualificationArray = array('vcname', 'description', 'parent_id', 'video_counts');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->set('create_time', date('Y-m-d H:i:s', time()));

    // Execute the query
    $this->db->insert('video_catalogs');

    // Return the ID of the inserted row, or false if the row could not be inserted
    return $this->db->insert_id();
}


/**
* updateCatalog method alters a record in the video_catalogs table.
*
* Option: Values
* --------------
* vcid				the ID of the video_catalogs record that will be updated
* vcname
* description
* video_counts
* parent_id
*
* @param array $options
* @return int affected_rows()
*/
function updateCatalog($options = array())
{
    // required values
	if(!_required(array('vcid'), $options)) return false;

    // qualification (make sure that we're not allowing the site to update data that it shouldn't)
	$qualificationArray = array('vcname', 'description', 'parent_id', 'video_counts');
	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->where('vcid', $options['vcid']);

	// Execute the query
	$this->db->update('video_catalogs');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* getCatalogs method returns an array of catalog record objects
*
* Option: Values
* --------------
* vcid
* vcname
* parent_id
* limit                limits the number of returned records
* offset                how many records to bypass before returning a record (limit required)
* sortBy                determines which column the sort takes place
* sortDirection        (asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* vcid
* vcname
* description
* parent_id
* video_counts
* create_time
* update_time
*
* @param array $options
* @return array result()
*/
function getCatalogs($options = array())
{
    // default values
    $options = _default(array('sortDirection' => 'asc'), $options);

    // Add where clauses to query
    $qualificationArray = array('vcid', 'vcname', 'parent_id');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
    }

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);

    $query = $this->db->get('video_catalogs');
    if($query->num_rows() == 0) return false;

    if(isset($options['vcid']) || isset($options['vcname'])) {
        // If we know that we're returning a singular record, then let's just return the object
        return $query->row(0);
    }
    else {
        // If we could be returning any number of records then we'll need to do so as an array of objects
        return $query->result();
    }
}

/**
* deleteCatalog method removes a record from the video_catalogs table
*
* @param array $options
*/
function deleteCatalog($options = array())
{
    // required values
    if(!_required(array('vcid'), $options)) return false;
	if(((int)$options['vcid']) < 10) return false;

    $this->db->where('vcid', $options['vcid']);
    $this->db->delete('video_catalogs');
}

/**
* getNumAll method returns numbers of video_catalog
*
* @param null
* @return int 
*/

function getNumAll()
{
    $query = $this->db->get('video_catalogs');
    return $query->num_rows();
}

/**
* getNumChild method returns children numbers of video_catalog
*
* @param int $id
* @return int 
*/

function getNumChilden($id)
{
	$this->db->where('parent_id', $id);
    $query = $this->db->get('video_catalogs');
    return $query->num_rows();
}

/**
* getParentId method returns parent_id of video_catalog
*
* @param int $id
* @return int
*/

function getParentId($id)
{
    $this->db->where('vcid', $id);
	$this->db->select('parent_id');
    $query = $this->db->get('video_catalogs');
	$parent = $query->row(0);
    return $parent->parent_id;
}



}
/* End of file videoCatalogs_model.php */
/* Location: ./application/models/videoCatalogs_model.php */