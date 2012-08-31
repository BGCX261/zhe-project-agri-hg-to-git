<?php

class Users_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->library('encrypt');
	$this->load->helper( array('modeltool') );
}


/**
* addUser method creates a record in users table.
*
* Option: Values
* --------------
* user_name			(required)
* user_pass			(required)
* user_note
* group_id
* create_time
*
* @param array $options
* @return int insert_id()
*/
function addUser($options = array())
{
    // required values
    if(!_required(array('user_name', 'user_pass'), $options)) return false;

    // default values
    $options = _default(array('group_id' => '1'), $options);

    // qualification (make sure that we're not allowing the site to insert data that it shouldn't)
    $qualificationArray = array('user_name', 'user_note', 'group_id');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }
	// User password need to be encrypted
	if(isset($options['user_pass'])) $this->db->set('user_pass', $this->encrypt->encode($options['user_pass']));

	$this->db->set('create_time', date('Y-m-d H:i:s', time()));

    // Execute the query
    $this->db->insert('users');

    // Return the ID of the inserted row, or false if the row could not be inserted
    return $this->db->insert_id();
}


/**
* updateUser method alters a record in users table.
*
* Option: Values
* --------------
* uid				the ID of the user record that will be updated
* user_name
* user_pass
* user_note
* group_id
* create_time
*
* @param array $options
* @return int affected_rows()
*/
function updateUser($options = array())
{
    // required values
	if(!_required(array('uid'), $options)) return false;

    // qualification (make sure that we're not allowing the site to update data that it shouldn't)
	$qualificationArray = array('user_name', 'user_note', 'group_id', 'create_time');
	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }
	
	// User password need to be encrypted
	if(isset($options['user_pass'])) $this->db->set('user_pass', $this->encrypt->encode($options['user_pass']));

	$this->db->where('uid', $options['uid']);

	// Execute the query
	$this->db->update('users');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* getUser method returns an array of users record objects
*
* Option: Values
* --------------
* uid
* user_name
* limit                limits the number of returned records
* offset                how many records to bypass before returning a record (limit required)
* sortBy                determines which column the sort takes place
* sortDirection        (asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* uid
* user_name
* user_pass
* user_note
* group_id
* create_time
* update_time
*
* @param array $options
* @return array result()
*/
function getUser($options = array())
{
    // default values
    $options = _default(array('sortDirection' => 'asc'), $options);

    // Add where clauses to query
    $qualificationArray = array('uid', 'user_name');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
    }

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);

    $query = $this->db->get('users');
    if($query->num_rows() == 0) return false;

    if(isset($options['uid']) || isset($options['user_name'])) {
        // If we know that we're returning a singular record, then let's just return the object
        return $query->row(0);
    } else { // Never run
        // If we could be returning any number of records then we'll need to do so as an array of objects
        return $query->result();
    }
}

/**
* deleteUser method removes a record from the users table
*
* @param array $options
*/
function deleteUser($options = array())
{
    // required values
    if(!_required(array('uid'), $options)) return false;

    $this->db->where('uid', $options['uid']);
    $this->db->delete('users');
}


}
/* End of file users_model.php */
/* Location: ./application/models/users_model.php */