<?php

class Videos_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->helper( array('modeltool') );
}


/**
* addVideo method creates a record in the videos table.
*
* Option: Values
* --------------
* title			(required)
* youtube_id
* org_source_path
* view_source_path
* other_sorce_path
* vtags
* description
* vlength
* author
* videocatalog_id
* clicks
* video_thumbnail
* video_thumbnail_tiny
*
* @param array $options
* @return int insert_id()
*/
function addVideo($options = array())
{
    // required values
    if(!_required(array('title'), $options)) return false;

    // default values
    //$options = $this->_default(array('userStatus' => 'active'), $options);

    // qualification (make sure that we're not allowing the site to insert data that it shouldn't)
    $qualificationArray = array('title', 'youtube_id', 
		'org_source_path', 'view_source_path', 'other_sorce_path', 
		'vtags', 'description', 'videocatalog_id', 'clicks', 
		'video_thumbnail', 'video_thumbnail_tiny',
		'vlength', 'author'
	);
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->set('create_time', date('Y-m-d H:i:s', time()));

    // Execute the query
    $this->db->insert('videos');

    // Return the ID of the inserted row, or false if the row could not be inserted
    return $this->db->insert_id();
}


/**
* updateVideo method alters a record in the videos table.
*
* Option: Values
* --------------
* vid				the ID of the video record that will be updated
* title
* youtube_id
* org_source_path
* view_source_path
* other_source_path
* vtags
* author
* vlength
* description
* videocatalog_id
* clicks
* video_thumbnail
* video_thumbnail_tiny
* create_time
*
* @param array $options
* @return int affected_rows()
*/
function updateVideo($options = array())
{
    // required values
	if(!_required(array('vid'), $options)) return false;

    // qualification (make sure that we're not allowing the site to update data that it shouldn't)
   $qualificationArray = array('youtube_id', 'title',
		'org_source_path', 'view_source_path', 'other_source_path', 
		'vtags', 'description', 'videocatalog_id', 'clicks', 
		'video_thumbnail', 'video_thumbnail_tiny', 'create_time',
		'vlength', 'author'
	);
	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->where('vid', $options['vid']);

	// Execute the query
	$this->db->update('videos');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* changVc method change video catalog of videos by video catalog.
*
* Option: Values
* --------------
* ovcid				the ID of the video record that will be updated
* nvcid
*
* @param array $options
* @return int affected_rows()
*/
function changVc($options = array())
{
    // required values
	if(!_required(array('ovcid'), $options)) return false;

    // qualification (make sure that we're not allowing the site to update data that it shouldn't)
   $qualificationArray = array('ovcid', 'nvcid');
   
	if(isset($options['nvcid'])) $this->db->set('videocatalog_id', $options['nvcid']);

	$this->db->where('videocatalog_id', $options['ovcid']);

	// Execute the query
	$this->db->update('videos');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* getVideo method returns an array of videos record objects
*
* Option: Values
* --------------
* vid
* title
* youtube_id
* vtags
* videocatalog_id
* keyword				fulltext search of title or description
* date_from				begin of creation date period search
* date_to				end of creation date period search
* limit					limits the number of returned records
* offset				how many records to bypass before returning a record (limit required)
* sortBy				determines which column the sort takes place
* sortDirection			(asc, desc) sort ascending or descending (sortBy required)
*
* Returns (array of objects)
* --------------------------
* vid				the ID of the video record that will be updated
* title
* youtube_id
* org_source_path
* view_source_path
* other_source_path
* vtags
* description
* author
* vlength
* videocatalog_id
* clicks
* video_thumbnail
* video_thumbnail_tiny
* create_time
* update_time
*
* @param array $options
* @return array result()
*/
function getVideo($options = array())
{
    // default values
    $options = _default(array('sortDirection' => 'desc'), $options);

    // Add where clauses to query
    $qualificationArray = array('vid', 'title', 'youtube_id', 
		'vtags', 'videocatalog_id', 
		//'keyword', 'date_from', 'date_to'
	);
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) {
			if(is_array($options[$qualifier])) {
				$this->db->where($qualifier, array_shift($options[$qualifier]));
				foreach ($options[$qualifier] as $where) {
					$this->db->or_where($qualifier, $where);
				}
			} else {
				$this->db->where($qualifier, $options[$qualifier]);
			}
		}
    }

    // If limit / offset are declared (usually for pagination) then we need to take them into account
    if(isset($options['limit']) && isset($options['offset'])) $this->db->limit($options['limit'], $options['offset']);
    else if(isset($options['limit'])) $this->db->limit($options['limit']);

    // sort
    if(isset($options['sortBy'])) $this->db->order_by($options['sortBy'], $options['sortDirection']);
	
	if(isset($options['keyword'])) {
		$keyword = addslashes($options['keyword']);
		//$this->db->like('title', $options['keyword']);
		//$this->db->or_like('description', $options['keyword']);
		$this->db->where("(`title` LIKE '%$keyword%' OR `description` LIKE '%$keyword%')");
	};
	
	if(isset($options['date_from'])) {
		$date_from = strtotime($options['date_from']);
		$this->db->where('create_time >=', date('Y-m-d H:i:s', $date_from));
	}
	if(isset($options['date_to'])) {
		$date_to = strtotime($options['date_to']);
		$this->db->where('create_time <=', date('Y-m-d H:i:s', $date_to + 86399));
	}
	
	// select
	if(isset($options['select'])) {
		$this->db->select($options['select']);
	} else {
		$this->db->select('vid, title, youtube_id, org_source_path, view_source_path, other_source_path, '
			.'vtags, description, author, vlength, videocatalog_id, clicks, create_time, '
			.'update_time');
	}
	
	//echo $this->db->get_compiled_select('videos');z
    $query = $this->db->get('videos');
    if($query->num_rows() == 0) return false;
		
    if((isset($options['vid']) && !isset($options['multi'])) || isset($options['youtube_id'])) {
        // If we know that we're returning a singular record, then let's just return the object
        return $query->row(0);
    }
    else {
        // If we could be returning any number of records then we'll need to do so as an array of objects
		if(isset($options['counts'])) {
			return $query->num_rows();
		} else {
			return $query->result();
		}
    }
}

/**
* getVideoThumbnail method returns an thumbnail jpeg of video object
*
* Option: Values
* --------------
* vid
*
* Returns (array of objects)
* --------------------------
* video_thumbnail
* video_thumbnail_tiny
*
* @param array $options
* @return array result()
*/
function getVideoThumbnail($options = array(), $isTiny = false)
{
	if(isset($options['vid'])) {
		$this->db->where('vid', $options['vid']);
	} else {
		return false;
	}
	
	if($isTiny) {
		$this->db->select('video_thumbnail_tiny');
	} else {
		$this->db->select('video_thumbnail');
	}
	
    $query = $this->db->get('videos');
    if($query->num_rows() == 0) return false;

	return $query->row(0);
}

/*
/**
* getNumChild method returns children numbers of video_catalog
*
* @param int $id
* @return int 

function getNumChilden($id)
{
	$this->db->where('parent_id', $id);
    $query = $this->db->get('video_catalogs');
    return $query->num_rows();
}
*/

/**
* getNum method returns video numbers of all
*
* @param null
* @return 
*/
function getNum()
{
    return $this->db->count_all_results('videos');
}


/**
* getNumInVC method returns video numbers of video_catalog object
*
* @param int $videocatalog_id    ( -1 : All Videos)
* @return int $query->num_rows();
*/
function getNumInVC($videocatalog_id)
{
	if(isset($videocatalog_id)) {
		if($videocatalog_id < 0) {
			#$this->db->select('vid');
		} else {
			$this->db->where('videocatalog_id', $videocatalog_id);
		}
	} else {
		return 0;
	}
	
    $query = $this->db->get('videos');
	return $query->num_rows();
}

/**
* deleteVideo method removes a record from the videos table
*
* @param array $options
*/
function deleteVideo($options = array())
{
    // required values
    if(!_required(array('vid'), $options)) return false;

    $this->db->where('vid', $options['vid']);
    $this->db->delete('videos');
}




}
/* End of file videos_model.php */
/* Location: ./application/models/videos_model.php */