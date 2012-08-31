  <div class="ebook_main">
    <div class="ebook_left" >
      <div class="title" > <img src="/images/ebook.jpg" />電子書</div>
    <div class="left20">  
    <hr  size="4px" class="hr_blu">
    </div>
     <div class="ebook_list2">
<?php 
$ec = 0;
if($ebooks) {
	foreach ($ebooks as $ebook) {
?>
      <div class="ebook_list"> 
      <a href="/ebooks/read/<?php
	  echo $ebook->eid;
?>">
        <img src="/ebooks/thumbnail/<?php 
	echo $ebook->eid;
?>" width="148" height="208" /> <?php 
			  $title = $ebook->title; 
			  echo mb_strlen($title)>22? mb_substr($title, 0, 22). '...': $title;
?></a>
      </div>
<?php 
		if(++$ec % 4 <= 0) {
			if($ec != $itemsInPage) {
				echo '</div><div class="ebook_list2">';
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
	echo ' <a href="';
	if($pno == $thisPage) {
		echo '" class="prepage_link">', $pno;
	} else {
		echo $thisLink, '/', $pno, '">', $pno; 
	}
	echo '</a> ', "\n";
}
if($endno < $totalPages) echo '... ';
if($thisPage < $totalPages) {
	echo ' <a href="', $thisLink, '/', $thisPage + 1,'">';
	echo '<label class="button">下一頁</label></a>';
}

?>
  </div>     
     
    </div>
    <div  class="ebook_right">
      <div class="title_ebook" > 推薦好書</div>
      
      <hr  size="4px" class="hr_gre">
      <div class="re_ebook">
        <ul >
<?php 
foreach ($suggestebooks as $ebook) {
?>
          <li>
            <div class="img_i"> <a href="/ebooks/read/<?php echo $ebook->eid ?>"> <img 
			src="/ebooks/thumbnail/<?php echo $ebook->eid ?>/1" width="73" height="102" /></a></div>
            <div class="ebook_t">
              <p class="text_ebook2"><a href="/ebooks/read/<?php echo $ebook->eid ?>"><?php 
			  $title = $ebook->title; 
			  echo mb_strlen($title)>9? mb_substr($title, 0, 9). '...': $title;
?></a></p>
              <br />
              <p class="text_ebook2"> 觀看次數：</p>
              <p class="text_ebook2"><?php echo $ebook->clicks ?></p>
            </div>
            <hr  size="1px" class="hr_g">
          </li>
<?php
}
?>
        </ul>
      </div>
    </div>
  </div>