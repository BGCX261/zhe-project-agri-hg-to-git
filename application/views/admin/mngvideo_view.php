<script>
$(function(){
	$('#videocatalog_id').combotree({ 
		onClick: function(node) {
			location.assign('/coaadmin/mngVideo/'+node.id);
		},
	});
});
$(function(){
	$('#limit').change(function(){
		$('#ff').submit();
	});
});
function deleVideo(vid, vcid){
	$.messager.confirm('刪除確認','刪除之後無法復原，確定要刪除？',function(r){
		if (r){
			location.assign('/coaadmin/deleVideo/'+vid+'/'+vcid+'?from=mng');
		}
	});
};
function addSugg(box, vid){
	$.ajax({
		url: '/coaadmin/jsAddVideoSugg',
		type: 'post',  
		dataType: 'json',
		data: {  
			vid: vid
		},
		success: function(aryResult) {
			needReload = aryResult[1];
			if(needReload) {
				location.reload();
			}
			newId = aryResult[0];
			if(newId > 0) {
				box.disabled = true;
				box.checked = true;
			}
		}
	});
	box.disabled = false;
	box.checked = false;
};
function chgPage(page){
	$('#page').val(page);
	$('#ff').submit();
};
</script>
		<form id="ff" method="post">
		  <input id="page" name="page" type="hidden" value="<?php echo isset($page)?$page:1; ?>" />
          <div id="right_title">影音管理</div>
				<br />
				<label for="videocatalog_id">影片分類</label>
				<input id="videocatalog_id" name="videocatalog_id" class="easyui-combotree" url="/coaadmin/jsgetVCatalogList" 
					style="width:200px;" onChange="javascript:alert('test');" 
					value="<?php echo $videocatalog_id; ?>" /> <!-- required="true -->
				<p><font color="red" size=2><?php echo $message;?></font></p>
          <div id="right_main">
            <div id="video_page">
              單頁顯示則數
              <select id="limit" name="limit">
                <option<?php echo ($limit == 10) ? ' selected':''; ?>>10</option>
                <option<?php echo ($limit == 20) ? ' selected':''; ?>>20</option>
				<option<?php echo ($limit == 30) ? ' selected':''; ?>>30</option>
				<option<?php echo ($limit == 50) ? ' selected':''; ?>>50</option>
				<option<?php echo ($limit == 100) ? ' selected':''; ?>>100</option>
              </select>
            </div>
          </div>
		</form>
<?php
if($videos) {
?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabledate01">
              <tr>
                <th width="45%">影片名稱</th>
                <th width="12%">片長</th>
                <th width="12%">影片分類</th>
                <th width="15%">授權單位</th>
                <th width="8%">本月推薦</th>
                <th width="4%">&nbsp;</th>
                <th width="4%">&nbsp;</th>
              </tr>
<?php
	$lineoe = 0;
	foreach ($videos as $video) {
?>
              <tr class="<?php echo (($lineoe % 2) <= 0)? '':'GrayBG' ;?>">
                <td class="txtL"><?=$video->title; ?></td>
                <td><?php 
	$tmps = $video->vlength;
	$s = $tmps % 60;
	$tmps /= 60; $m = (int)$tmps % 60;
	$tmps /= 60; $h = (int)$tmps;
	echo $h,':',($m<10?'0'.$m:$m),':',($s<10?'0'.$s:$s);
?></td>
                <td><?php echo $video->videocatalog_id; ?></td>
                <td><?=$video->author; ?></td>
                <td><input name="sugVideo" type="checkbox" 
<?php
	$match = false;
	foreach ($sugg_video_ids as $svid) {
		if($video->vid == $svid) $match = true;
	}
	if($match) {
		echo 'checked="check" disabled';
	} else {
		echo 'onClick="addSugg(this, ', $video->vid,');"';
	}
?>/></td>
                <td><a href="/coaadmin/editVideo/<?php echo $video->vid; ?>"><img src="/images/edit.png"/></a></td>
                <td><a href="javascript:deleVideo(<?php echo $video->vid; ?>, <?php echo $videocatalog_id; ?>);"><img src="/images/delete.png"/></a></td>
              </tr>
<?php
		$lineoe++;
	}
?>
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
<?php
}
?>
          </div>


