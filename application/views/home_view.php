<script language="javascript">vids=<?php echo json_encode($svideo_ids); ?>;</script>
<script language="javascript" type="text/javascript" src="/js/home.min.js"></script>
  <div class="main">
    <h1> <img src="/images/good.jpg" width="32" height="28" />本月推薦-<?php echo $svtitle ?></h1>
    <hr  size="4px" class="hr_r">
<?php
foreach (array_reverse($svideos) as $svideo){
?>
    <div id="bimg<?php echo $svideo->vid?>" class="bigimg" style="background: url(/videos/bigimg/<?php echo $svideo->vid; ?>)">
      <div class="half-op"> </div>
      <div class="title_area"> <span class="title2"><?php 
		$title = $svideo->title; 
		echo mb_strlen($title)>40? mb_substr($title, 0, 40). '...': $title;
?></span>
        <p class="text_img"><?php $des = $svideo->description; 
		echo mb_strlen($des)>52? mb_substr($des, 0, 52). '...': $des ?></p>
      </div>
    </div>
<?php 
}
?>
    <div id="svideodiv" class="img">
      <ul id="svideoul">
<?php
foreach ($svideos as $svideo){
?>
        <li id="simg<?php echo $svideo->vid?>">
          <div class="img_i"><a href="/videos/play/<?php echo $svideo->vid ?>"><img 
			src="/videos/thumbnail/<?php echo $svideo->vid ?>" width="80" height="53" /> </a></div>
          <div class="img_t"> <a href="/videos/play/<?php echo $svideo->vid ?>"><?php 
		$title = $svideo->title; 
		echo mb_strlen($title)>19? mb_substr($title, 0, 19). '...': $title;
?></a></div>
          <hr  size="1px" class="hr_g">
        </li>
<?php
}
?>
      </ul>
    </div>
  </div>
  <div class="main_min">
    <div class="main_left" >
      <div class="title_move" > <img src="/images/new.jpg" />最新影音</div>
      <div class="more"> <a href='/videos/more'>More</a></div>
      <hr  size="4px" class="hr_b">
	  <div class="movie_list2">
<?php 
foreach ($videos as $video) {
?>
      <div class="move"><a href="/videos/play/<?php echo $video->vid ?>"><img 
			src="/videos/thumbnail/<?php echo $video->vid ?>" width="197" height="132" />
		<?php 
		$title = $video->title; 
		echo mb_strlen($title)>25? mb_substr($title, 0, 25). '...': $title;
?></a>
        <div class="icon_main"><div class="icon_long"> <?php echo round($video->vlength/6)/10  ?>min<?php
			echo ($video->vlength>60)?'s':'' ?></div>
		<div class="icon_people"> <?php echo $video->author ?></div></div>
        <P class="text_c"> <?php $des = $video->description; echo mb_strlen($des)>62? mb_substr($des, 0, 62). '...': $des ?> </p>
      </div>
<?php
}
?>
      </div>
	</div>
    <div  class="main_right">
      <div class="title_ebook" > <img src="/images/ebook.jpg" />典藏電子書</div>
      <div class="more_ebook" > <a href="/ebooks/lists"  >More</a> </div>
      <hr  size="4px" class="hr_gre">
      <div class="ebook">
        <ul >
<?php 
foreach ($ebooks as $ebook) {
?>
          <li>
            <div class="img_i"><a href="/ebooks/read/<?php echo $ebook->eid ?>"><img 
			src="/ebooks/thumbnail/<?php echo $ebook->eid ?>/1" width="73" height="102" /></a></div>
            <div class="img_t"><a href="/ebooks/read/<?php echo $ebook->eid ?>"><?php
				$title = $ebook->title; 
				echo mb_strlen($title)>19? mb_substr($title, 0, 19). '...': $title;
?></a>
              <p class="text_ebook"><?php $des = $ebook->description; echo mb_strlen($des)>34? mb_substr($des, 0, 34). '...': $des ?></p>
            </div>
          </li>
<?php
}
?>
        </ul>
      </div>
    </div>
  </div>