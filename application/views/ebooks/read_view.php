  <div class="ebook_detail_main">
    <div class="ebook_detail_left" >
      <div class="title" > <img src="/images/ebook.jpg" /><?php echo $ebook->title; ?></div>
      <div class="left20">
        <hr  size="4px" class="hr_blu">
      </div>
      <div class="left20"> <img src="/images/ebooks/<?php echo $ebook->eid ?>.jpg" width="720" height="468" /></div>
      <div  class="height40">
        <div class="ebookicon_list">
          <ul>
            <li> <img src="/images/people.jpg" width="18" height="16" />作者 / <?php echo $ebook->author ?> </li>
            <li> <img src="/images/unit.jpg"  /> 出版單位 / <?php echo $ebook->author_unit ?> </li>
          </ul>
        </div>
        <div class="ebookicon_list_right">
          <ul>
            <li> <a href="/images/ebooks/<?php echo $ebook->eid ?>.pdf"><img src="/images/read.jpg"  /> 線上閱讀</a></li>
            <li> <a href="/ebooks/download/<?php echo $ebook->eid ?>.pdf"><img src="/images/download.jpg"  />PDF下載</a> </li>
          </ul>
        </div>
      </div>
      <div class="left20">
        <hr  size="1px" class="hr_g">
      </div>
     
       <div class="ebook_content"> 
    <P class="text_c"> <?php echo $ebook->description ?></p> 
   </div> 
   
   
      
  <div class="backpage"> 
    <a href="javascript:history.back()"/><label class="button">回上頁</label></a>
  </div> 
  
      </div>
    <div  class="ebook_detail_right">
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
