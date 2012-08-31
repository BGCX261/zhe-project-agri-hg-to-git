<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TestModels extends CI_Controller {

/**
 * Test Case of Models
 * 
 * Test: 
 * VideoCatalogs_model
 * EbookCatalogs_model
 * UserGroups_model
 *
 */
public function index()
{
	echo '<a href="/testModels/testVideoCatalogsModel">/testModels/testVideoCatalogsModel</a></br>';
	echo '<a href="/testModels/testEbookCatalogsModel">/testModels/testEbookCatalogsModel</a></br>';
	echo '<a href="/testModels/testVideosModel">/testModels/testVideosModel</a> (Aboout 15 s)</br>';
	echo '<a href="/testModels/testEbooksModel">/testModels/testEbooksModel</a> (Aboout 15 s)</br>';
	echo '<a href="/testModels/testUserGroupsModel">/testModels/testUserGroupsModel</a></br>';
	echo '<a href="/testModels/testSuggessVideosModel">/testModels/testSuggessVideosModel</a></br>';
	echo '<a href="/testModels/testSuggessVideoMonthlyModel"
		>/testModels/testSuggessVideoMonthlyModel</a></br>';
	echo '<a href="/testModels/testSuggessEbooksModel">/testModels/testSuggessEbooksModel</a></br>';
	
	echo '<hr/>';
	echo '<a href="/testOneModel">/testOneModel</a></br>';
}

public function testVideoCatalogsModel()
{
	header('Content-type: text/plain');

/* Test VideoCatalogs_model */
	
	echo str_repeat ("-", 5), 'Test Model: VideoCatalogs_model ', str_repeat ("-", 5), "\n";
	$this->load->model('VideoCatalogs_model');
		
	$vcname='Test vcname';
	$vcdesc='Test description';
	
	echo 'Added vcname=', $vcname, "\n";
	$recid = $this->VideoCatalogs_model->addCatalog(array('vcname' => $vcname));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->VideoCatalogs_model->getCatalogs(array('vcid' => $recid)));

	echo 'Delete Record # ', $recid, "\n";
	$this->VideoCatalogs_model->deleteCatalog(array('vcid' => $recid));
	
	echo 'Added vcname=', $vcname, ', description=', $vcdesc, "\n";
	$recid = $this->VideoCatalogs_model->addCatalog(array('vcname' => $vcname, 'description' => $vcdesc));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->VideoCatalogs_model->getCatalogs(array('vcid' => $recid)));
	
	$vcname='Update vcname';
	$vcdesc='Update description';
	echo 'Update (vcid=', $recid, ') to vcname=', $vcname, ', description=', $vcdesc, "\n";
	echo 'Update Count: ', $this->VideoCatalogs_model->updateCatalog(array('vcid' => $recid, 'vcname' => $vcname, 'description' => $vcdesc)), "\n";
	echo 'Update Record # ', $recid, "\n";
	print_r($this->VideoCatalogs_model->getCatalogs(array('vcid' => $recid)));
	
	echo 'Delete Record # ', $recid, "\n";
	$this->VideoCatalogs_model->deleteCatalog(array('vcid' => $recid));
	
	echo str_repeat ("-=", 30), "\n";
}

public function testEbookCatalogsModel()
{
	header('Content-type: text/plain');
		
/* Test EbookCatalogs_model */
	
	echo str_repeat ("-", 5), 'Test Model: EbookCatalogs_model ', str_repeat ("-", 5), "\n";
	$this->load->model('EbookCatalogs_model');
		
	$ecname='Test ecname';
	$ecdesc='Test description';
	
	echo 'Added ecname=', $ecname, "\n";
	$recid = $this->EbookCatalogs_model->addCatalog(array('ecname' => $ecname));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->EbookCatalogs_model->getCatalogs(array('ecid' => $recid)));

	echo 'Delete Record # ', $recid, "\n";
	$this->EbookCatalogs_model->deleteCatalog(array('ecid' => $recid));
	
	echo 'Added ecname=', $ecname, ', description=', $ecdesc, "\n";
	$recid = $this->EbookCatalogs_model->addCatalog(array('ecname' => $ecname, 'description' => $ecdesc));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->EbookCatalogs_model->getCatalogs(array('ecid' => $recid)));
	
	$ecname='Update ecname';
	$ecdesc='Update description';
	echo 'Update (ecid=', $recid, ') to ecname=', $ecname, ', description=', $ecdesc, "\n";
	echo 'Update Count: ', $this->EbookCatalogs_model->updateCatalog(array('ecid' => $recid, 'ecname' => $ecname, 'description' => $ecdesc)), "\n";
	echo 'Update Record # ', $recid, "\n";
	print_r($this->EbookCatalogs_model->getCatalogs(array('ecid' => $recid)));
	
	echo 'Delete Record # ', $recid, "\n";
	$this->EbookCatalogs_model->deleteCatalog(array('ecid' => $recid));
	
	echo str_repeat ("-=", 30), "\n";
}

public function testVideosModel()
{
	header('Content-type: text/plain');

/* Test Videos_model */	
	
	echo str_repeat ("-", 5), 'Test Model: Videos_model ', str_repeat ("-", 5), "\n";
	$this->load->model('Videos_model');
	$a_vids = array();
	
	$title='Test title';
	$vcdesc='Test description';
	
	$youtube_id='Test youtube_id';
	$org_source_path='Test org_source_path';
	$view_source_path='Test view_source_path';
	$other_source_path='Test other_source_path';
	$vtags='Test vtags';
	$videocatalog_id=9;
	$clicks=99;
	$video_thumbnail='Test video_thumbnail';
	$video_thumbnail_tiny='Test video_thumbnail_tiny';
	
	// Test Required field
	echo 'Added title=', $title, "\n";
	$recid = $this->Videos_model->addVideo(array('title' => $title));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->Videos_model->getVideo(array('vid' => $recid)));

	/*
	echo 'Delete Record # ', $recid, "\n";
	//$this->Videos_model->deleteVideo(array('vid' => $recid));
	array_push($a_vids, $recid);
	*/
	
	// Test All field
	echo 'Added title=', $title, ', description=', $vcdesc, ', youtube_id=', $youtube_id, ', org_source_path=', $org_source_path, 
		', view_source_path=', $view_source_path, ', other_source_path=', $other_source_path, ', vtags=', $vtags, 
		'videocatalog_id', $videocatalog_id, ', clicks=', $clicks, ', 
		video_thumbnail=', $video_thumbnail, ', video_thumbnail_tiny=', $video_thumbnail_tiny, "\n";
	$recid = $this->Videos_model->addVideo(array('title' => $title, 'description' => $vcdesc, 
			'youtube_id' => $youtube_id, 'org_source_path' => $org_source_path, 'view_source_path' => $view_source_path, 
			'other_source_path' => $other_source_path, 'vtags' => $vtags, 'videocatalog_id' => $videocatalog_id, 
			'clicks' => $clicks, 'video_thumbnail' => $video_thumbnail, 'video_thumbnail_tiny' => $video_thumbnail_tiny
		));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->Videos_model->getVideo(array('vid' => $recid)));
	
	// Update All field
	$title='Update title';
	$vcdesc='Update description';
	
	$youtube_id='Update youtube_id';
	$org_source_path='Update org_source_path';
	$view_source_path='Update view_source_path';
	$other_source_path='Update other_source_path';
	$vtags='Update vtags';
	$videocatalog_id=19;
	$clicks=199;
	$video_thumbnail='Update video_thumbnail';
	$video_thumbnail_tiny='Update video_thumbnail_tiny';
	echo 'Update (vcid=', $recid, ') to title=', $title, ', description=', $vcdesc, 
		', youtube_id=', $youtube_id, ', org_source_path=', $org_source_path, 
		', view_source_path=', $view_source_path, ', other_source_path=', $other_source_path, ', vtags=', $vtags, 
		'videocatalog_id', $videocatalog_id, ', clicks=', $clicks, ', 
		video_thumbnail=', $video_thumbnail, ', video_thumbnail_tiny=', $video_thumbnail_tiny, "\n";
	echo 'Update Count: ', $this->Videos_model->updateVideo(array('vid' => $recid, 'title' => $title, 'description' => $vcdesc, 
		'youtube_id' => $youtube_id, 'org_source_path' => $org_source_path, 'view_source_path' => $view_source_path, 
		'other_source_path' => $other_source_path, 'vtags' => $vtags, 'videocatalog_id' => $videocatalog_id, 
		'clicks' => $clicks, 'video_thumbnail' => $video_thumbnail, 'video_thumbnail_tiny' => $video_thumbnail_tiny
	)), "\n";
	echo 'Update Record # ', $recid, "\n";
	print_r($this->Videos_model->getVideo(array('vid' => $recid)));
	echo 'Delete Record # ', $recid, "\n";
	//$this->Videos_model->deleteVideo(array('vid' => $recid));
	array_push($a_vids, $recid);
	
	// Add 10 Record and test get records between a period.
	$ic=0;
	while($ic++<4) {
		$recid = $this->Videos_model->addVideo(array('title' => $title.$ic,
			'description' => 'adf134asd13jlkfacxzvasdf'));
		array_push($a_vids, $recid);
		echo 'Added record #', $recid, ', time=', date('Y-m-d H:i:s', time()),"\n";
		sleep(1);
	}
	$date_from = date('Y-m-d H:i:s', time());
	echo 'Set date_from=', $date_from, "\n";
	while($ic++<8) {
		$recid = $this->Videos_model->addVideo(array('title' => $title.$ic,
			'description' => 'adflasfmva5676favasdf'));
		array_push($a_vids, $recid);
		echo 'Added record #', $recid, ', time=', date('Y-m-d H:i:s', time()),"\n";
		sleep(1);
	}
	$date_to = date('Y-m-d H:i:s', time());
	echo 'Set date_to=', $date_to, "\n";
	while($ic++<10) {
		$recid = $this->Videos_model->addVideo(array('title' => $title.$ic,
			'description' => 'adf134a876573facxzvasdf'));
		array_push($a_vids, $recid);
		echo 'Added record #', $recid, ', time=', date('Y-m-d H:i:s', time()),"\n";
		sleep(1);
	}
	
	echo 'Test searchRecord From ', $date_from, ' to ', $date_to, "\n";
	print_r($this->Videos_model->getVideo(array('date_from' => $date_from, 'date_to' => $date_to)));
	
	echo 'Test description fulltext search: 676fa', "\n";
	print_r($this->Videos_model->getVideo(array('keyword' => '676fa')));
	
	echo 'Test description fulltext search: 8', "\n";
	print_r($this->Videos_model->getVideo(array('keyword' => '8')));
	
	foreach ($a_vids as $del_vid) {
		$this->Videos_model->deleteVideo(array('vid' => $del_vid));
	}
	echo str_repeat ("-=", 30), "\n";
}

public function testEbooksModel()
{
	header('Content-type: text/plain');

/* Test Ebooks_model */	
	
	echo str_repeat ("-", 5), 'Test Model: Ebooks_model ', str_repeat ("-", 5), "\n";
	$this->load->model('Ebooks_model');
	
	$title='Test title';
	$data='Test data';
	
	$ecdesc='Test description';
	$etags='Test etags';
	$ebookcatalog_id=8;
	$clicks=88;
	$ebook_thumbnail='Test ebook_thumbnail';
	$ebook_thumbnail_tiny='Test ebook_thumbnail_tiny';
	
	// Test Required field
	echo 'Added title=', $title, "\n";
	$recid = $this->Ebooks_model->addEbook(array('title' => $title, 'data' => $data));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->Ebooks_model->getEbook(array('eid' => $recid)));

	echo 'Delete Record # ', $recid, "\n";
	$this->Ebooks_model->deleteEbook(array('eid' => $recid));

	
	// Test All field
	echo 'Added title=', $title, ', description=', $ecdesc, ', data=', $data, ', 
		etags=', $etags, 'ebookcatalog_id', $ebookcatalog_id, ', clicks=', $clicks, ', 
		ebook_thumbnail=', $ebook_thumbnail, ', ebook_thumbnail_tiny=', $ebook_thumbnail_tiny, "\n";
	$recid = $this->Ebooks_model->addEbook(array('title' => $title, 'description' => $ecdesc, 
			'data' => $data, 'etags' => $etags, 'ebookcatalog_id' => $ebookcatalog_id, 
			'clicks' => $clicks, 'ebook_thumbnail' => $ebook_thumbnail, 'ebook_thumbnail_tiny' => $ebook_thumbnail_tiny
		));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->Ebooks_model->getEbook(array('eid' => $recid)));
	
	// Update All field
	$title='Update title';
	$data='Update data';
	
	$ecdesc='Update description';
	$etags='Update etags';
	$ebookcatalog_id=19;
	$clicks=199;
	$ebook_thumbnail='Update ebook_thumbnail';
	$ebook_thumbnail_tiny='Update ebook_thumbnail_tiny';
	echo 'Update (vcid=', $recid, ') to title=', $title, ', description=', $ecdesc, 
		', data=', $data, ', etags=', $etags, 
		'ebookcatalog_id', $ebookcatalog_id, ', clicks=', $clicks, ', 
		ebook_thumbnail=', $ebook_thumbnail, ', ebook_thumbnail_tiny=', $ebook_thumbnail_tiny, "\n";
	echo 'Update Count: ', $this->Ebooks_model->updateEbook(array('eid' => $recid, 'title' => $title, 'description' => $ecdesc, 
		'data' => $data, 'etags' => $etags, 'ebookcatalog_id' => $ebookcatalog_id, 
		'clicks' => $clicks, 'ebook_thumbnail' => $ebook_thumbnail, 'ebook_thumbnail_tiny' => $ebook_thumbnail_tiny
	)), "\n";
	echo 'Update Record # ', $recid, "\n";
	print_r($this->Ebooks_model->getEbook(array('eid' => $recid)));
	echo 'Delete Record # ', $recid, "\n";
	$this->Ebooks_model->deleteEbook(array('eid' => $recid));

	// Add 10 Record and test get records between a period.
	$a_eids = array();
	$ic=0;
	while($ic++<4) {
		$recid = $this->Ebooks_model->addEbook(array('title' => $title.$ic,
			'data' => $data.$ic,
			'description' => 'adf134asd13jlkfacxzvasdf'));
		array_push($a_eids, $recid);
		echo 'Added record #', $recid, ', time=', date('Y-m-d H:i:s', time()),"\n";
		sleep(1);
	}
	$date_from = date('Y-m-d H:i:s', time());
	echo 'Set date_from=', $date_from, "\n";
	while($ic++<8) {
		$recid = $this->Ebooks_model->addEbook(array('title' => $title.$ic,
			'data' => $data.$ic,
			'description' => 'adflasfmva5676favasdf'));
		array_push($a_eids, $recid);
		echo 'Added record #', $recid, ', time=', date('Y-m-d H:i:s', time()),"\n";
		sleep(1);
	}
	$date_to = date('Y-m-d H:i:s', time());
	echo 'Set date_to=', $date_to, "\n";
	while($ic++<10) {
		$recid = $this->Ebooks_model->addEbook(array('title' => $title.$ic,
			'data' => $data.$ic,
			'description' => 'adf134a876573facxzvasdf'));
		array_push($a_eids, $recid);
		echo 'Added record #', $recid, ', time=', date('Y-m-d H:i:s', time()),"\n";
		sleep(1);
	}
	
	echo 'Test searchRecord From ', $date_from, ' to ', $date_to, "\n";
	print_r($this->Ebooks_model->getEbook(array('date_from' => $date_from, 'date_to' => $date_to)));
	
	echo 'Test description fulltext search: 676fa', "\n";
	print_r($this->Ebooks_model->getEbook(array('keyword' => '676fa')));
	
	echo 'Test description fulltext search: 8', "\n";
	print_r($this->Ebooks_model->getEbook(array('keyword' => '8')));
	
	foreach ($a_eids as $del_eid) {
		$this->Ebooks_model->deleteEbook(array('eid' => $del_eid));
	}

	echo str_repeat ("-=", 30), "\n";
}

public function testUserGroupsModel()
{
	header('Content-type: text/plain');
	
/* Test UserGroups_model */
	
	echo str_repeat ("-", 5), 'Test Model: userGroups_model ', str_repeat ("-", 5), "\n";
	$this->load->model('UserGroups_model');
		
	$gpname='Test group_name';
	$permession='admin';
	
	echo 'Added gpname=', $gpname, "\n";
	$recid = $this->UserGroups_model->addUserGroup(array('group_name' => $gpname));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->UserGroups_model->getUserGroup(array('gid' => $recid)));

	echo 'Delete Record # ', $recid, "\n";
	$this->UserGroups_model->deleteUserGroup(array('gid' => $recid));

	echo 'Added gpname=', $gpname, ', permession=', $permession, "\n";
	$recid = $this->UserGroups_model->addUserGroup(array('group_name' => $gpname, 'permession' => $permession));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->UserGroups_model->getUserGroup(array('gid' => $recid)));
	
	$ecname='Update gpname';
	$ecdesc='Update permession';
	echo 'Update (gid=', $recid, ') to group_name=', $gpname, ', permession=', $permession, "\n";
	echo 'Update Count: ', $this->UserGroups_model->updateUserGroup(array('gid' => $recid, 'group_name' => $gpname, 'permession' => $permession)), "\n";
	echo 'Update Record # ', $recid, "\n";
	print_r($this->UserGroups_model->getUserGroup(array('gid' => $recid)));
	
	echo 'Delete Record # ', $recid, "\n";
	$this->UserGroups_model->deleteUserGroup(array('gid' => $recid));
	
	echo str_repeat ("-=", 30), "\n";
	
}

public function testSuggessVideosModel()
{
	header('Content-type: text/plain');

/* Test SuggessVideos_model */
	
	echo str_repeat ("-", 5), 'Test Model: SuggessVideos_model ', str_repeat ("-", 5), "\n";
	$this->load->model('SuggessVideos_model');
		
	$video_id=77;
	$video_pic='Test video_pic';
	$svm_id=7;
	
	echo 'Added video_id=', $video_id, "\n";
	$recid = $this->SuggessVideos_model->add(array('video_id' => $video_id));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->SuggessVideos_model->get(array('svid' => $recid)));

	echo 'Delete Record # ', $recid, "\n";
	$this->SuggessVideos_model->delete(array('svid' => $recid));
	
	echo 'Added video_id=', $video_id, ', video_pic=', $video_pic, "\n";
	$recid = $this->SuggessVideos_model->add(array('video_id' => $video_id, 
		'video_pic' => $video_pic, 'svm_id' => $svm_id
	));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->SuggessVideos_model->get(array('svid' => $recid)));
	
	$video_id=88;
	$video_pic='Update video_pic';
	$svm_id=8;
	
	echo 'Update (svid=', $recid, ') to video_id=', $video_id, ', video_pic=', $video_pic, "\n";
	echo 'Update Count: ', $this->SuggessVideos_model->update(array('svid' => $recid, 
		'video_id' => $video_id, 'video_pic' => $video_pic, 'svm_id' => $svm_id
	)), "\n";
	echo 'Update Record # ', $recid, "\n";
	print_r($this->SuggessVideos_model->get(array('svid' => $recid)));
	
	echo 'Delete Record # ', $recid, "\n";
	$this->SuggessVideos_model->delete(array('svid' => $recid));
	
	echo str_repeat ("-=", 30), "\n";
}


public function testSuggessVideoMonthlyModel()
{
	header('Content-type: text/plain');

/* Test SuggessVideoMonthly_model */
	
	echo str_repeat ("-", 5), 'Test Model: SuggessVideoMonthly_model ', str_repeat ("-", 5), "\n";
	$this->load->model('SuggessVideoMonthly_model');
		
	$svmdate='2012/01/01';
	$svm_title='Test svm_title';
	
	echo 'Added svmdate=', $svmdate, "\n";
	$recid = $this->SuggessVideoMonthly_model->add(array('svmdate' => $svmdate));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->SuggessVideoMonthly_model->get(array('svmid' => $recid)));

	echo 'Delete Record # ', $recid, "\n";
	$this->SuggessVideoMonthly_model->delete(array('svmid' => $recid));
	
	echo 'Added svmdate=', $svmdate, ', svm_title=', $svm_title, "\n";
	$recid = $this->SuggessVideoMonthly_model->add(array('svmdate' => $svmdate, 
		'svm_title' => $svm_title
	));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->SuggessVideoMonthly_model->get(array('svmid' => $recid)));
	
	$svmdate='2012/02/02';
	$svm_title='Update svm_title';
	
	echo 'Update (svmid=', $recid, ') to svmdate=', $svmdate, ', svm_title=', $svm_title, "\n";
	echo 'Update Count: ', $this->SuggessVideoMonthly_model->update(array('svmid' => $recid, 
		'svmdate' => $svmdate, 'svm_title' => $svm_title
	)), "\n";
	echo 'Update Record # ', $recid, "\n";
	print_r($this->SuggessVideoMonthly_model->get(array('svmid' => $recid)));
	
	echo 'Delete Record # ', $recid, "\n";
	$this->SuggessVideoMonthly_model->delete(array('svmid' => $recid));
	
	echo str_repeat ("-=", 30), "\n";
}

public function testSuggessEbooksModel()
{
	header('Content-type: text/plain');

/* Test SuggessEbooks_model */
	
	echo str_repeat ("-", 5), 'Test Model: SuggessEbooks_model ', str_repeat ("-", 5), "\n";
	$this->load->model('SuggessEbooks_model');
		
	$ebook_id=66;
	$ebook_pic='Test ebook_pic';
	
	echo 'Added ebook_id=', $ebook_id, "\n";
	$recid = $this->SuggessEbooks_model->add(array('ebook_id' => $ebook_id));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->SuggessEbooks_model->get(array('seid' => $recid)));

	echo 'Delete Record # ', $recid, "\n";
	$this->SuggessEbooks_model->delete(array('seid' => $recid));
	
	echo 'Added ebook_id=', $ebook_id, ', ebook_pic=', $ebook_pic, "\n";
	$recid = $this->SuggessEbooks_model->add(array('ebook_id' => $ebook_id, 
		'ebook_pic' => $ebook_pic
	));
	echo 'Added to Record # ', $recid, "\n";
	print_r($this->SuggessEbooks_model->get(array('seid' => $recid)));
	
	$ebook_id=55;
	$ebook_pic='Update ebook_pic';
	
	echo 'Update (seid=', $recid, ') to ebook_id=', $ebook_id, ', ebook_pic=', $ebook_pic, "\n";
	echo 'Update Count: ', $this->SuggessEbooks_model->update(array('seid' => $recid, 
		'ebook_id' => $ebook_id, 'ebook_pic' => $ebook_pic
	)), "\n";
	echo 'Update Record # ', $recid, "\n";
	print_r($this->SuggessEbooks_model->get(array('seid' => $recid)));
	
	echo 'Delete Record # ', $recid, "\n";
	$this->SuggessEbooks_model->delete(array('seid' => $recid));
	
	echo str_repeat ("-=", 30), "\n";
}
	
	
}

/* End of file testModels.php */
/* Location: ./application/controllers/testModels.php */