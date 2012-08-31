  <div class="movie_main2">
  
    <div class="title" > <img src="/images/movie.jpg" /><?php echo $video->title; ?></div>
    
    <div class="left20">
    <hr  size="4px" class="hr_blu">
    </div>
   <div class="blackbg">
    <iframe width="720" height="480" src="http://www.youtube.com/embed/<?php
	echo $video->youtube_id;
?>?rel=0&fmt=22" frameborder="0" allowfullscreen></iframe></div>
  
      <div class="icon_list"> <ul>
      <li> <img src="/images/long.jpg" width="17" height="16" /> 片長 / <?php echo round($video->vlength/6)/10  ?>min<?php
			echo ($video->vlength>60)?'s':'' ?> </li>
     <li>    
       <img src="/images/people.jpg" width="18" height="16" />授權單位 / <?php echo $video->author; ?></li> 
       <li>
  <img src="/images/calendar.jpg"  /> 上架日期 / <?php
	echo date('Y-m-d', strtotime($video->create_time));
?></li>
  <li>   
      <img src="/images/classified.jpg"  /> 影片分類 / <?php echo $vcname; ?></li> 
      <li>
       <img src="/images/hit.jpg"  /> 點閱數 / <?php echo $video->clicks; ?>
       </li>
       <!--li>  
          <img src="/images/download.jpg"  /><a href="#">下載</a>  
          </li-->
           </ul>   
      </div>
  <div class="left20">
   <hr  size="1px" class="hr_g">
  </div>
   
   <div class="movie_content"> 
    <P class="text_c"> <?php echo $video->description; ?></p> 
   </div> 
  <div class="backpage"> 
    <a href="javascript:history.back()"/><label class="button">回上頁</label></a>
  </div>    
  </div>