<script>
function deleSuggVideo(svid){
	$.messager.confirm('刪除確認','刪除之後無法復原，確定要刪除？',function(r){
		if (r){
			location.assign('/coaadmin/deleVideoSugg/'+svid);
		}
	});
}
</script>
          <div id="right_title">本月推薦</div>
          <div id="right_main">
			<form id="sugnamefrm" method="post">
			<input type="hidden" name="svmid" value="<?php echo $svmid; ?>" />
			<p><font color="red" size=2><?php echo $message;?></font></p>
            <div>主題名稱&nbsp;&nbsp;<input name="svm_title" type="text" value="<?php echo $svm_title; ?>"/>&nbsp;&nbsp;<a href="javascript:$('#sugnamefrm').submit();"><img src="/images/send.jpg" align="absmiddle"/></a></div>
			</form>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabledate01">
              <tr>
                <th width="45%">影片名稱</th>
                <th width="12%">片長</th>
                <th width="12%">影片分類</th>
                <th width="15%">授權單位</th>
                <th width="8%">&nbsp;</th>
                <th width="8%">本月推薦</th>
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
                <td><?php echo $video->author; ?></td>
                <td><a href="/coaadmin/editVideo/<?php echo $video->vid;?>"><img src="/images/edit.png"/></a></td>
                <td><a href="javascript:deleSuggVideo(<?php echo $video->svid;?>);"><img src="/images/delete.png"/></a></td>
              </tr>
<?php
		$lineoe++;
	}
?>
            </table>
           </div>