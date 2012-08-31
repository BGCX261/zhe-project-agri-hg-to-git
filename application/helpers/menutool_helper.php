<?php

/**
* _makeVC method returns structure video catalogs.
*
* @param array $menuitems
* @param int $id
* @return array $menu
*/

function _makeVC($menuitems, $id = 0) {
	$menu = array();
	foreach($menuitems as $item) {
		$vcitem = array();
		if($item->parent_id == $id) {
			$vcitem['id'] = $item->vcid;
			$vcitem['text'] = $item->vcname. ' ('. $item->video_counts. ')';
			$children = _makeVC($menuitems, $item->vcid);
			if(count($children) > 0) {
				$vcitem['state'] = 'open';
				$vcitem['children'] = $children;
			}
			array_push($menu, $vcitem);
		}
	}
	if(count($menu) <= 0) return null;
	return $menu;
}

/**
* _makeMenu method returns structure menu of video catalogs menu items.
*
* @param array $menuitems
* @param int $vcid
* @return array $menu
*/

function _makeMenu($menuitems, $vcid = 0) {
	$menu = array();
	foreach($menuitems as $item) {
		if($item->parent_id == $vcid) {
			$item->children = _makeMenu($menuitems, $item->vcid);
			array_push($menu, $item);
		}
	}
	if(count($menu) <= 0) return null;
	return $menu;
}

/**
* _makeVCHash method returns video catalog hash of video catalogs names.
*
* @param array $menuitems
* @return array menu
*/

function _makeVCHash($menuitems) {
	$vcatalog = array();
	foreach($menuitems as $item) {
		$vcatalog[$item->vcid] = $item->vcname;
	}
	$vcatalog[0] = '無分類';
	return $vcatalog;
}