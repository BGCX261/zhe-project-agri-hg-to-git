  <div class="movie_main">
    <div class="reco_main">
      <div class="title" > <img src="images/good.jpg" />每月主題推薦</div>
      <div class="left20">
        <hr  size="4px" class="hr_blu">
      </div>
      <div class="reco_month"><?php 
	echo date("Y年m月", strtotime($svmdate));
?>主題推薦 - <?php echo $svmtitle; ?></div>
      <div class="reco_search">
		<form method="POST">
        <input id="y_search_text" name='year' type="text" />
        年
        <input id="m_search_text" name='month' type="text" />
        月
        <input class="button" type='submit' value='搜尋'></input>
		</form>
      </div>
<?php
foreach ($svideos as $video) {
?>
      <div class="left20">
        <hr  size="1px" class="hr_g">
      </div>
      <div class="reco_list">
        <div class="reco_left"> <a href="/videos/play/<?php 
	echo $video->vid;
?>"> <img src="/videos/thumbnail/<?php 
	echo $video->vid;
?>" width="197" height="132" /></a> </div>
        <div class="reco_right" > <a href="/videos/play/<?php 
	echo $video->vid;
?>"><?php 
		$title = $video->title; 
		echo mb_strlen($title)>35? mb_substr($title, 0, 35). '...': $title;
?> </a>
          <p class="text_c"> <img src="images/long.jpg" width="17" height="16" /> <?php echo round($video->vlength/6)/10  ?>min<?php
			echo ($video->vlength>60)?'s':'' ?> <img src="images/people.jpg" width="18" height="16" /><?php echo $video->author; ?></p>
          <P class="text_c"> <?php $des = $video->description; echo mb_strlen($des)>135? mb_substr($des, 0, 135). '...': $des ?> </p>
        </div>
      </div>
<?php
}
?>
    </div>
  </div>