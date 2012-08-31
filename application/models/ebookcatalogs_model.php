<?php

class EbookCatalogs_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->helper( array('modeltool') );
}


/**
* addCatalog method creates a record in the ebook_catalogs table.
*
* Option: Values
* --------------
* ecname			(required)
* description
*
* @param array $options
* @return int insert_id()
*/
function addCatalog($options = array())
{
    // required values
    if(!_required(array('ecname'), $options)) return false;

    // default values
    //$options = $this->_default(array('userStatus' => 'active'), $options);

    // qualification (make sure that we're not allowing the site to insert data that it shouldn't)
    $qualificationArray = array('ecname', 'description');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->set('create_time', date('Y-m-d H:i:s', time()));

    // Execute the query
    $this->db->insert('ebook_catalogs');

    // Return the ID of the inserted row, or false if the row could not be inserted
    return $this->db->insert_id();
}


/**
* updateCatalog method alters a record in the ebook_catalogs table.
*
* Option: Values
* --------------
* ecid				the ID of the ebook_catalogs record that will be updated
* ecname
* description
*
* @param array $options
* @return int affected_rows()
*/
function updateCatalog($options = array())
{
    // required values
	if(!_required(array('ecid'), $options)) return false;

    // qualification (make sure that we're not allowing the site to update data that it shouldn't)
	$qualificationArray = array('ecname', 'description');
	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->where('ecid', $options['ecid']);

	// Execute the query
	$this->db->update('ebook_catalogs');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* getCatalogs method returns an array of catalog record objects
*
* Option: Values
* --------------
* ecid
* ecname
* limit                limits the number of returned records
* offset                how many records to bypass before returning a record (limit required)
* sortBy                determines which column the sort takes place
* sortDirection        (asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* ecid
* ecname
* description
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
    $qualificationArray = array('ecid', 'ecname');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
    }

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);

    $query = $this->db->get('ebook_catalogs');
    if($query->num_rows() == 0) return false;

    if(isset($options['ecid']) || isset($options['ecname'])) {
        // If we know that we're returning a singular record, then let's just return the object
        return $query->row(0);
    }
    else {
        // If we could be returning any number of records then we'll need to do so as an array of objects
        return $query->result();
    }
}

/**
* deleteCatalog method removes a record from the ebook_catalogs table
*
* @param array $options
*/
function deleteCatalog($options = array())
{
    // required values
    if(!_required(array('ecid'), $options)) return false;

    $this->db->where('ecid', $options['ecid']);
    $this->db->delete('ebook_catalogs');
}


}
/* End of file ebookCatalogs_model.php */
/* Location: ./application/models/ebookCatalogs_model.php */