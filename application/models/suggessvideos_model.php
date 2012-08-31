<?php

class SuggessVideos_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->helper( array('modeltool') );
}


/**
* add method creates a record in the suggess_video table.
*
* Option: Values
* --------------
* video_id			(required)
* video_pic
* svm_id
*
* @param array $options
* @return int insert_id()
*/
function add($options = array())
{
    // required values
    if(!_required(array('video_id'), $options)) return false;

    // default values
    //$options = $this->_default(array('userStatus' => 'active'), $options);

    // qualification (make sure that we're not allowing the site to insert data that it shouldn't)
    $qualificationArray = array('video_id', 'video_pic', 'svm_id');
	
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }
	
    // Execute the query
    $this->db->insert('suggess_video');

    // Return the ID of the inserted row, or false if the row could not be inserted
    return $this->db->insert_id();
}


/**
* update method alters a record in the suggess_video table.
*
* Option: Values
* --------------
* svid				the ID of the video record that will be updated
* video_id
* video_pic
* svm_id
*
* @param array $options
* @return int affected_rows()
*/
function update($options = array())
{
	// required values
	if(!_required(array('svid'), $options)) return false;

	// qualification (make sure that we're not allowing the site to update data that it shouldn't)
	$qualificationArray = array('video_id', 'video_pic', 'svm_id');

	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->where('svid', $options['svid']);

	// Execute the query
	$this->db->update('suggess_video');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* get method returns an array of suggess_video record objects
*
* Option: Values
* --------------
* svid
* svm_id
* limit					limits the number of returned records
* offset				how many records to bypass before returning a record (limit required)
* sortBy				determines which column the sort takes place
* sortDirection			(asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* svid				the ID of the video record that will be updated
* video_id
* video_pic
* svm_id
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
    $qualificationArray = array('svid', 'svm_id');
	
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
    }

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
	
	// select
	if(isset($options['select'])) $this->db->select($options['select']);
	
    $query = $this->db->get('suggess_video');
    if($query->num_rows() == 0) return false;

    if(isset($options['svid'])) {
        // If we know that we're returning a singular record, then let's just return the object
        return $query->row(0);
    }
    else {
        // If we could be returning any number of records then we'll need to do so as an array of objects
        return $query->result();
    }
}

/**
* addVideo method creates a record in the suggess_video table.
*
* Option: Values
* --------------
* @param int $vid
* @return array array(insert_id(), $needReload)
*/
function addVideo($vid = null) {
    if(!$vid) return false;
	$this->db->order_by('svmdate', 'desc');
	$this->db->limit(1);
	$query = $this->db->get('suggess_video_monthly');
	$thismon = date('Y-m-').'01';
	$svmdate = $query->row()->svmdate;
    $svmid = $query->row()->svmid;
	$needReload = false;
	
	if(strcmp($thismon, $svmdate)){ // Different
		$this->db->set('svmdate', $thismon);
		$this->db->insert('suggess_video_monthly');
		
		$this->db->order_by('svmdate', 'desc');
		$this->db->limit(1);
		$query = $this->db->get('suggess_video_monthly');
		$svmdate = $query->row()->svmdate;
		$svmid = $query->row()->svmid;
		$needReload = true;
	};
	
	$this->db->set('svm_id', $svmid);
	$this->db->set('video_id', $vid);
    $this->db->insert('suggess_video');
    // Return the ID of the inserted row, or false if the row could not be inserted
    return array($this->db->insert_id(), $needReload);
}

/**
* checkVideo method check if video is in suggess_video table.
*
* Option: Values
* --------------
* @param int $vid
* @return boolean $hasvideo
*/
function checkVideo($vid = null)
{
    if(!$vid) return false;
	$this->db->order_by('svm_title', 'desc');
	$this->db->limit(1);
	$query = $this->db->get('suggess_video_monthly');
    $svmid = $query->row(0)->svmid;
	
	$this->db->where('svm_id', $svmid);
	$this->db->where('video_id', $vid);
	$hasvideo = ($this->db->count_all_results('suggess_video') > 0);
    return $hasvideo;
}

/**
* getVideos method returns an array of join suggess_video with videos record objects
*
* Option: Values
* --------------
*
* Returns (array of objects)
* --------------------------
* svid				the ID of the video record that will be updated
* vid 
* title
* vlength
* videocatalog_id
* author
* create_time
*
* @param $svm_id, $svm_date
* @return array result()
*/
function getVideos($svm_id = null, $svm_date = null)
{
	if(!$svm_id){
		if($svm_date) {
			$this->db->where('svmdate', $svm_date);
			$query = $this->db->get('suggess_video_monthly');
			if($query->num_rows() >0) {
				$svm_id = $query->row()->svmid;
			} else {
				return array();
			}
		} else {
			return false;
		}
	}
	$this->db->select('svid, vid, title, vlength, videocatalog_id, author, create_time');
	$this->db->where('svm_id', $svm_id);
	$this->db->from('suggess_video');
	$this->db->join('videos', 'videos.vid = suggess_video.video_id');
    $this->db->order_by('create_time', 'desc');
    $query = $this->db->get();
    return $query->result();
}

/**
* delete method removes a record from the suggess_video table
*
* @param array $options
*/
function delete($options = array())
{
    // required values
    if(!_required(array('svid'), $options)) return false;

    $this->db->where('svid', $options['svid']);
    $this->db->delete('suggess_video');
}

}
/* End of file suggessVideos_model.php */
/* Location: ./application/models/suggessVideos_model.php */