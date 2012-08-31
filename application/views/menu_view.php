  <div class="mA_header">
	<div   class="height35">
      <div class="mAtop_menu">
        <ul class="menu">
          <li class="<?php echo ($func === 'videos') ? 'topgreen' : 'topwhite' ?>"> <a href="<?php
			echo isset($nosearch) ? '/videos/more' : '#'; ?>" >本館影音</a>
<?php
if(!isset($nosearch)) {
	function print_menu($menu, $isChilden = true) {
		echo '<ul style="z-index:1;">',"\n";
		$item_count = 0;
		foreach ($menu as $item) {
			$item_count++;
			if(($item_count ==1)&&$isChilden) {
				echo '<li class="li3_top">';
			} else {
				echo '<li>';
			}
			echo '<a href=', (!$item->children)? '/videos/lists/'.$item->vcid : '#', 
				'>', $item->vcname, 
				//' ('.$item->video_counts.')</a>'; // Show all numbers 
				//($isChilden)? ' ('.$item->video_counts.')': '', '</a>'; // Only SubFolder show number
				(!$item->children)? ' ('.$item->video_counts.')': '', '</a>'; // Only Leaves show number
			if($item->children) {
				print_menu($item->children);
			}
			echo '</li>',"\n";
			
		}
		echo '</ul>',"\n";
	};
	print_menu($menu, false);
};

?>
          </li>
          <li class="<?php echo ($func === 'ebooks') ? 'topgreen' : 'topwhite' ?>"> <a href="/ebooks/lists">電子書</a> </li>
          <li class="<?php echo ($func === 'recommended') ? 'topgreen' : 'topwhite' ?>"> <a href="/recommended">每月主題推薦</a> </li>
        </ul>
      </div>
<?php
if(!isset($nosearch)) {
?>
<script type="text/javascript">
$.fn.datebox.defaults.formatter = function(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	m = (m < 10) ? '0'+m : m;
	var d = date.getDate();
	d = (d < 10) ? '0'+d : d;
	return y+'-'+m+'-'+d;
}
</script>
      <div class="search" > 
      <form method="post" action="/videos/search">
      全文檢索      
        <input  id="search_text" name="keyword" type="text" value="<?php echo isset($keyword)? $keyword : ''; ?>" />
        <input  class="button" type='submit' value='搜尋'/>
         <input class="button_4" type='button' value='進階搜尋' onclick="searcha('search_area','block')"/>
       </form>
       
        <div   class="container">
          <div  id="search_area" class="candrag" style="z-index:1;">
			<form method="post" action="/videos/advsearch">
            <div class="dialog">
              <div class="dialogTitle"> <img src="/images/search.jpg" />進階搜尋
                <label for="showDialog" class="closeButton" onclick="searcha('search_area','none')">X</label>
              </div>
              <div class="dialogContent">
                <p > 關鍵字 :
                  <input id="keyword_text" name="keyword" type="text" value="<?php echo isset($keyword)? $keyword : ''; ?>" />
                </p>
                <p > 日期 :
                  <input class="easyui-datebox" id="opdate_text" name="datefrom" type="text" value="<?php echo isset($datefrom)? $datefrom : ''; ?>" />
                  <!--input class="button_4" type='button' value='選擇日期'/-->
                  ~
                  <input class="easyui-datebox" id="cldate_text" name="dateto" type="text" value="<?php echo isset($dateto)? $dateto : ''; ?>" />
                  <!--input class="button_4" type='button' value='選擇日期'/-->
                </p>
                <font style="color:#f00;font-size:12px;">請輸入日期，例如2012-07-05或是直接選擇日期。</font><br />
                <hr  size="1px" class="hr_g">
                <br />
                <div class="becenter" >
                 <input class="button" type='submit' value='搜尋' onclick="searcha('search_area','none')"/>
                
                </div>
              </div>
            </div>
			</form>
          </div>
        </div>
      </div>
<?php
}
?>
	</div>
  </div>
