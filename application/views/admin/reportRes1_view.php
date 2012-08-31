<script type="text/javascript">
$.fn.datebox.defaults.formatter = function(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	m = (m < 10) ? '0'+m : m;
	var d = date.getDate();
	d = (d < 10) ? '0'+d : d;
	return y+'-'+m+'-'+d;
};
function dlFile(){
	if ($.browser.name == "chrome") {
		$('#ffcsv').submit();
	} else {
		$('#ff #csv').val('csv');
		$('#ff').submit();
		$('#ff #csv').val('');
	}
}
function chgPage(page){
	$('#csv').val('');
	$('#page').val(page);
	$('#ff').submit();
};
</script>
		  <form id="ffcsv" method="post" action="">
		  <input type="hidden" id="from" name="from" value="<?php echo isset($from)?$from:''; ?>" />
		  <input type="hidden" id="to" name="to" value="<?php echo isset($to)?$to:''; ?>" />
		  <input type="hidden" id="csv" name="csv" value="csv" />
		  </form>
		  <form id="ff" method="post">
		  <input type="hidden" id="page" name="page" value="<?php echo isset($page)?$page:1; ?>" />
		  <input type="hidden" id="limit" name="limit" value="<?php echo isset($limit)?$limit:50; ?>" />
		  <input type="hidden" id="from" name="from" value="<?php echo isset($from)?$from:''; ?>" />
		  <input type="hidden" id="to" name="to" value="<?php echo isset($to)?$to:''; ?>" />
		  <input type="hidden" id="csv" name="csv" />
          <div id="right_title"><?php echo $right_title; ?></div>
          <div id="right_main">
            <div id="report_title"><?php echo $report_title; ?></div>
            <div id="report_btn"><input type="image" src="/images/export.jpg" onClick="dlFile()"/></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabledate01">
              <tr>
                <th width="30%">影片名稱</th>
                <th width="9%">片長</th>
                <th width="9%">影片分類</th>
                <th width="30%">影片簡介</th>
                <th width="14%">授權單位</th>
                <th width="8%">點閱率</th>
              </tr>
<?php
	$lineoe = 0;
	foreach ($videos as $video) {
?>
              <tr class="<?php echo (($lineoe % 2) <= 0)? '':'GrayBG' ;?>">
                <td class="txtL"><?php echo $video->title; ?></td>
                <td><?php 
	$tmps = $video->vlength;
	$s = $tmps % 60;
	$tmps /= 60; $m = (int)$tmps % 60;
	$tmps /= 60; $h = (int)$tmps;
	echo $h,':',($m<10?'0'.$m:$m),':',($s<10?'0'.$s:$s);
?></td>
                <td><?php echo $vcatalog[$video->videocatalog_id]; ?></td>
                <td><?php 
	$description = $video->description;
	echo mb_strlen($description)>25? mb_substr($description, 0, 25). '...': $description;
?></td>
                <td><?php echo $video->author; ?></td>
                <td><?php echo $video->clicks; ?></td>
              </tr>
<?php
		$lineoe++;
	}
?>
              <tr class="GrayBG">
                <td class="txtL">影片名稱影片名稱影片名稱影片名稱影片名稱</td>
                <td>01:02:05</td>
                <td>影片分類</td>
                <td>&nbsp;</td>
                <td>農業虛擬博物館</td>
                <td>168</td>
              </tr>
            </table>
            <div id="page">
              <ul>
<?php
if($page > 1) {
	echo '<li><a href="javascript:chgPage(', $page - 1,');">上一頁</a></li> ';
}
$startno = ($page > 6) ? $page - 5 : 1;
$endno = ($page < ($totalPages - 5)) ? $page + 5 : $totalPages;
$pno = $startno - 1;
if($pno >= 1) echo '... ';
while($pno++ < $endno) {
	echo ' <li';
	if($pno == $page) {
		echo ' class="op"><a>', $pno, '</a>';
	} else {
		echo '><a href="javascript:chgPage(', $pno, ');">', $pno; 
	}
	echo '</a></li> ', "\n";
}
if($endno < $totalPages) echo '... ';
if($page < $totalPages) {
	echo ' <li><a href="javascript:chgPage(', $page + 1,');">下一頁</a></li>';
}

?>
              </ul>
            </div>
          </div>
		  </form>