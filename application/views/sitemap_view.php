<?php

	#print_r($menu);

?>

  <div class="movie_main">
   <div class="reco_main">
    <div class="title" > <img src="images/map.jpg" />網站導覽</div>
    
    <div class="left20">
    <hr  size="4px" class="hr_blu">
    </div>

  <div class="map">
   <ul> <li> <img src="images/rectangular.jpg" width="12" height="17" /> <a href="#">本館影音</a>
<?php
function print_menumap($menu, $isChilden = true) {
	echo '<ul>',"\n";
	foreach ($menu as $item) {
		if($isChilden) {
			echo '<li>';
			echo '<img src="images/point.jpg" width="7" height="11" />';
		} else {
			echo '<li>';
			echo '<img src="images/triangle.jpg" width="9" height="13" />';
		}
		echo '<a href=/videos/lists/', 
			$item->vcid, '>', $item->vcname, '</a>';
		if($item->children) {
			print_menumap($item->children);
		}
		echo '</li>',"\n";
		
	}
	echo '</ul>',"\n";
}
print_menumap($menu, false);
?>
     </li>   
    </ul></div>
  
      <div class="map">
   <ul> <li> <img src="images/rectangular.jpg" width="12" height="17" /> <a href="/ebooks/lists">電子書</a>
       </li>   
    </ul></div>
    
    <div class="map">
   <ul> <li> <img src="images/rectangular.jpg" width="12" height="17" /> <a href="/sugestions">每月主題推薦</a>
       </li>   
    </ul></div>
    
        
  </div>
  
          
  </div>