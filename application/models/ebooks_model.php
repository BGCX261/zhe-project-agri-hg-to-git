<?php

class Ebooks_model extends CI_Model {	

public function __construct() {
	parent::__construct();
	$this->load->helper( array('modeltool') );
}


/**
* addEbook method creates a record in the ebooks table.
*
* Option: Values
* --------------
* title			(required)
* data
* author
* author_unit
* etags
* description
* ebookcatalog_id
* clicks
* ebook_thumbnail
* ebook_thumbnail_tiny
*
* @param array $options
* @return int insert_id()
*/
function addEbook($options = array())
{
    // required values
    if(!_required(array('title'), $options)) return false;

    // default values
    //$options = $this->_default(array('userStatus' => 'active'), $options);

    // qualification (make sure that we're not allowing the site to insert data that it shouldn't)
    $qualificationArray = array('title', 'data', 
		'author', 'author_unit', 
		'etags', 'description', 'ebookcatalog_id', 'clicks', 
		'ebook_thumbnail', 'ebook_thumbnail_tiny'
	);
    foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->set('create_time', date('Y-m-d H:i:s', time()));

    // Execute the query
    $this->db->insert('ebooks');

    // Return the ID of the inserted row, or false if the row could not be inserted
    return $this->db->insert_id();
}


/**
* updateEbook method alters a record in the ebooks table.
*
* Option: Values
* --------------
* eid				the ID of the video record that will be updated
* title
* data
* author
* author_unit
* etags
* description
* ebookcatalog_id
* clicks
* ebook_thumbnail
* ebook_thumbnail_tiny
* create_time
*
* @param array $options
* @return int affected_rows()
*/
function updateEbook($options = array())
{
    // required values
	if(!_required(array('eid'), $options)) return false;

    // qualification (make sure that we're not allowing the site to update data that it shouldn't)
   $qualificationArray = array('data', 'title', 
		'author', 'author_unit', 
		'etags', 'description', 'ebookcatalog_id', 'clicks', 
		'ebook_thumbnail', 'ebook_thumbnail_tiny', 'create_time'
	);
	foreach($qualificationArray as $qualifier) {
        if(isset($options[$qualifier])) $this->db->set($qualifier, $options[$qualifier]);
    }

	$this->db->where('eid', $options['eid']);

	// Execute the query
	$this->db->update('ebooks');

	// Return the number of rows updated, or false if the row could not be inserted
	return $this->db->affected_rows();
}

/**
* getEbook method returns an array of ebooks record objects
*
* Option: Values
* --------------
* eid
* title
* etags
* ebookcatalog_id
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
* eid				the ID of the video record that will be updated
* title
* data
* etags
* description
* ebookcatalog_id
* clicks
* ebook_thumbnail
* ebook_thumbnail_tiny
* create_time
* update_time
*
* @param array $options
* @return array result()
*/
function getEbook($options = array())
{
    // default values
    $options = _default(array('sortDirection' => 'desc'), $options);

    // Add where clauses to query
    $qualificationArray = array('eid', 'title',	'etags', 'ebookcatalog_id', 
		'author', 'author_unit', 
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
		$this->db->like('title', $options['keyword']);
		$this->db->or_like('description', $options['keyword']);
	};
	
	if(isset($options['date_from'])) $this->db->where('create_time >=', $options['date_from']);
	if(isset($options['date_to'])) $this->db->where('create_time <=', $options['date_to']);
	
	// select
	if(isset($options['select'])) {
		$this->db->select($options['select']);
	} else {
		$this->db->select('eid, title, etags, author, author_unit,'
			.'description, ebookcatalog_id, clicks, create_time, '
			.'update_time'
			);
	}
	
    $query = $this->db->get('ebooks');
    if($query->num_rows() == 0) return false;

    if((isset($options['eid']) && !isset($options['multi'])) || isset($options['data'])) {
        // If we know that we're returning a singular record, then let's just return the object
        return $query->row(0);
    }
    else {
        // If we could be returning any number of records then we'll need to do so as an array of objects
        return $query->result();
    }
}

/**
* getEbookThumbnail method returns an thumbnail jpeg of ebook object
*
* Option: Values
* --------------
* eid
*
* Returns (array of objects)
* --------------------------
* ebook_thumbnail
* ebook_thumbnail_tiny
*
* @param array $options
* @return array result()
*/
function getEbookThumbnail($options = array(), $isTiny = false)
{
	if(isset($options['eid'])) {
		$this->db->where('eid', $options['eid']);
	} else {
		return false;
	}
	
	if($isTiny) {
		$this->db->select('ebook_thumbnail_tiny');
	} else {
		$this->db->select('ebook_thumbnail');
	}
	
    $query = $this->db->get('ebooks');
    if($query->num_rows() == 0) return false;

	return $query->row(0);
}

/**
* getEbookNum method returns all ebooks counts
*
* @param null
* @return int $query->num_rows();
*/
function getEbookNum()
{
    $query = $this->db->get('ebooks');
	return $query->num_rows();
}

/**
* deleteEbook method removes a record from the ebooks table
*
* @param array $options
*/
function deleteEbook($options = array())
{
    // required values
    if(!_required(array('eid'), $options)) return false;

    $this->db->where('eid', $options['eid']);
    $this->db->delete('ebooks');
}




}
/* End of file ebooks_model.php */
/* Location: ./application/models/ebooks_model.php */