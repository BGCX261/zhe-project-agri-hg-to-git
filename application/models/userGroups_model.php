<?php

class UserGroups_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->library('encrypt');
	$this->load->helper( array('modeltool') );
}


/**
* addUserGroup method creates a record in user_groups table.
*
* Option: Values
* --------------
* group_name			(required)
* permession
* create_time
*
* @param array $options
* @return int insert_id()
*/
function addUserGroup($options = array())
{
    // required values
    if(!_required(array('group_name'), $options)) return false;

    // default values
    // $options = _default(array('permession' => ''), $options);

    // qualification (make sure that we're not allowing the site to insert data that it shouldn't)
    $qualificationArray = array('group_name', 'permession');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->set('create_time', date('Y-m-d H:i:s', time()));

    // Execute the query
    $this->db->insert('user_groups');

    // Return the ID of the inserted row, or false if the row could not be inserted
    return $this->db->insert_id();
}


/**
* updateUserGroup method alters a record in user_groups table.
*
* Option: Values
* --------------
* gid				the ID of the user_groups record that will be updated
* group_name
* permession
* create_time
*
* @param array $options
* @return int affected_rows()
*/
function updateUserGroup($options = array())
{
    // required values
	if(!_required(array('gid'), $options)) return false;

    // qualification (make sure that we're not allowing the site to update data that it shouldn't)
	$qualificationArray = array('group_name', 'permession', 'create_time');
	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }
	
	$this->db->where('gid', $options['gid']);

	// Execute the query
	$this->db->update('user_groups');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* getUserGroup method returns an array of user_groups record objects
*
* Option: Values
* --------------
* gid
* group_name
* limit                limits the number of returned records
* offset                how many records to bypass before returning a record (limit required)
* sortBy                determines which column the sort takes place
* sortDirection        (asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* gid
* group_name
* permession
* create_time
* update_time
*
* @param array $options
* @return array result()
*/
function getUserGroup($options = array())
{
    // default values
    $options = _default(array('sortDirection' => 'asc'), $options);

    // Add where clauses to query
    $qualificationArray = array('gid', 'group_name');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
    }

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $this->encrypt->encode($options['user_pass']));

    $query = $this->db->get('user_groups');
    if($query->num_rows() == 0) return false;

    if(isset($options['gid'])) {
        // If we know that we're returning a singular record, then let's just return the object
        return $query->row(0);
    } else {
        // If we could be returning any number of records then we'll need to do so as an array of objects
        return $query->result();
    }
}

/**
* deleteUser method removes a record from the user_groups table
*
* @param array $options
*/
function deleteUserGroup($options = array())
{
    // required values
    if(!_required(array('gid'), $options)) return false;

    $this->db->where('gid', $options['gid']);
    $this->db->delete('user_groups');
}


}
/* End of file userGroups_model.php */
/* Location: ./application/models/userGroups_model.php */