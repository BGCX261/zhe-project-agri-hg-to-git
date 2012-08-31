<?php

class VideoStatics_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->helper( array('modeltool') );
}


/**
* add method creates a record in the video_statics table.
*
* Option: Values
* --------------
* video_id			(required)
* day_clicks
* week_clicks
* month_clicks
* date				(required)
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
    $qualificationArray = array('video_id', 'day_clicks', 'week_clicks', 'month_clicks', 'date');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

    // Execute the query
    $this->db->insert('video_statics');

    // Return the ID of the inserted row, or false if the row could not be inserted
    return $this->db->insert_id();
}


/**
* update method alters a record in the video_statics table.
*
* Option: Values
* --------------
* vsid				the ID of the video_statics record that will be updated
* video_id
* day_clicks
* week_clicks
* month_clicks
* date
*
* @param array $options
* @return int affected_rows()
*/
function update($options = array())
{
    // required values
	if(!_required(array('vsid'), $options)) return false;

    // qualification (make sure that we're not allowing the site to update data that it shouldn't)
	$qualificationArray = array('video_id', 'day_clicks', 'week_clicks', 'month_clicks', 'date');
	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->where('vsid', $options['vsid']);

	// Execute the query
	$this->db->update('video_statics');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* get method returns an array of video_statics record objects
*
* Option: Values
* --------------
* vsid
* video_id
* date_from				begin of creation date period search
* date_to				end of creation date period search
* limit                limits the number of returned records
* offset                how many records to bypass before returning a record (limit required)
* sortBy                determines which column the sort takes place
* sortDirection        (asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* vsid
* video_id
* day_clicks
* week_clicks
* month_clicks
* date
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
    $qualificationArray = array('vsid', 'video_id');
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->where($qualifier, $options[$qualifier]);
    }

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);

	if(isset($options['date_from'])) $this->db->where('date >=', $options['date_from']);
	if(isset($options['date_to'])) $this->db->where('date <=', $options['date_to']);
	
    $query = $this->db->get('video_statics');
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
* delete method removes a record from the video_statics table
*
* @param array $options
*/
function delete($options = array())
{
    // required values
    if(!_required(array('vsid'), $options)) return false;

    $this->db->where('vsid', $options['vsid']);
    $this->db->delete('video_statics');
}

/**
* plusOne method alters a record in the video_statics table.
*
* Option: Values
* --------------
* video_id
* date
*
* @param array $options
* @return int affected_rows()
*/
function plusOne($vid = null)
{
	if($vid === null) return false;
	$date = date('Y-m-d');
	$this->db->start_cache();
	$this->db->where('video_id', $vid);
	$this->db->where('date', $date);
	$this->db->stop_cache();
	if(count($this->db->get('video_statistics')->result()) < 1) {
		$this->db->flush_cache();
		$this->db->set('video_id', $vid);
		$this->db->set('date', $date);
		$this->db->set('day_clicks', '1');
		$this->db->insert('video_statistics');
	} else {
		$this->db->set('day_clicks', 'day_clicks+1', false);
		$this->db->update('video_statistics');
	}

	$this->db->flush_cache();
	
	$date = date('Y-m-d', time() - date('w')*86400 );
	$this->db->start_cache();
	$this->db->where('video_id', $vid);
	$this->db->where('date', $date);
	$this->db->stop_cache();
	if(count($this->db->get('video_statistics')->result()) < 1) {
		$this->db->flush_cache();
		$this->db->set('video_id', $vid);
		$this->db->set('date', $date);
		$this->db->set('week_clicks', '1');
		$this->db->insert('video_statistics');
	} else {
		$this->db->set('week_clicks', 'week_clicks+1', false);
		$this->db->update('video_statistics');
	}
	$this->db->flush_cache();
	
	$date = date('Y-m-').'01';
	$this->db->start_cache();
	$this->db->where('video_id', $vid);
	$this->db->where('date', $date);
	$this->db->stop_cache();
	if(count($this->db->get('video_statistics')->result()) < 1) {
		$this->db->flush_cache();
		$this->db->set('video_id', $vid);
		$this->db->set('date', $date);
		$this->db->set('month_clicks', '1');
		$this->db->insert('video_statistics');
	} else {
		$this->db->set('month_clicks', 'month_clicks+1', false);
		$this->db->update('video_statistics');
	}
	$this->db->flush_cache();
}

/**
* stats method returns an array of video_statics record objects
*
* Option: Values
* --------------
* date_from				begin of creation date period search
* date_to				end of creation date period search
* limit                limits the number of returned records
* offset                how many records to bypass before returning a record (limit required)
* sortBy                determines which column the sort takes place
* sortDirection        (asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* vsid
* video_id
* day_clicks
* update_time
*
* @param array $options
* @return array result()
*/
function stats($options = array())
{
	if(!_required(array('date_from', 'date_to'), $options)) return false;
    $options = _default(array('sortDirection' => 'asc'), $options);

	$date_from = strtotime($options['date_from']);
	$this->db->where('date >=', date('Y-m-d H:i:s', $date_from));
	$date_to = strtotime($options['date_to']);
	$this->db->where('date <=', date('Y-m-d H:i:s', $date_to + 86399));
	$this->db->or_where('vid !=', 0);
	$this->db->group_by('vid');
	$this->db->select_sum('day_clicks');
	$this->db->select('vid, title, vlength, videocatalog_id, clicks, description, author, create_time');
	
	$this->db->from('videos');
	$this->db->join('video_statistics', 'videos.vid = video_statistics.video_id', 'left');

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
	
    $query = $this->db->get();
	return $query->result();
}


}
/* End of file ebookCatalogs_model.php */
/* Location: ./application/models/ebookCatalogs_model.php */