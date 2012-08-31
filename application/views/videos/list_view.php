  <div class="movie_main">
  
    <div class="title" > <img src="/images/movie.jpg" /><?php 
	echo $title;
?></div>
    
   <div class="left20">
     <hr  size="4px" class="hr_blu">
   </div>
<?php
if(isset($keyword)) {
?>
   <div class="left20">
   查詢"<font class="text_b"><?php echo $keyword; ?></font>"共"<font class="text_r"><?php echo $numResults; ?></font>個符合結果
   </div>
<?php
}
?>
   <div class="movie_list2">
<?php 
$vc = 0;
if($videos) {
	foreach ($videos as $video) {
?>
    <div class="movie_list"> <a href="/videos/play/<?php 
	echo $video->vid;
?>"> <img src="/videos/thumbnail/<?php 
	echo $video->vid;
?>" width="197" height="132" /> <?php 
			  $title = $video->title; 
			  echo mb_strlen($title)>49? mb_substr($title, 0, 49). '...': $title;
?></a>
             <P class="text_c"> 出版日期：<?php
	echo date('Y-m-d', strtotime($video->create_time));
?></p>
      </div>
<?php 
		if(++$vc % 4 <= 0) {
			if($vc != $itemsInPage) {
				echo '</div><div class="movie_list2">';
			}
		}
	}
} else {
?>
	
<?php
}
?>
 </div>
  <div class="prepage"> 
  <hr  size="1px" class="hr_g">
  <br>
<?php
if($thisPage > 1) {
	echo '<a href="', $thisLink, '/', $thisPage - 1,'">';
	echo '<label class="button">上一頁</label></a> ';
}
$startno = ($thisPage > 6) ? $thisPage - 5 : 1;
$endno = ($thisPage < ($totalPages - 5)) ? $thisPage + 5 : $totalPages;
$pno = $startno - 1;
if($pno >= 1) echo '... ';
while($pno++ < $endno) {
	echo ' <a ';
	if($pno == $thisPage) {
		echo 'class="prepage_link">', $pno;
	} else {
		echo 'href="', $thisLink, '/', $pno, '">', $pno; 
	}
	echo '</a> ', "\n";
}
if($endno < $totalPages) echo '... ';
if($thisPage < $totalPages) {
	echo ' <a href="', $thisLink, '/', $thisPage + 1,'">';
	echo '<label class="button">下一頁</label></a>';
}

?>
    <!--a >1</a>,<a >2</a>,<a class="prepage_link">3</a>...<a >8</a>,<a >9</a>,<a >10</a--> 
  </div>
  </div>