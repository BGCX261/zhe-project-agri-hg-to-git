<script type="text/javascript">
function deleEbook(eid){
	$.messager.confirm('刪除確認','刪除之後無法復原，確定要刪除？',function(r){
		if (r){
			location.assign('/coaadmin/deleEbook/'+eid);
		}
	});
};
function chgSugg(box, eid){
	if(box.checked) {
		location.assign('/coaadmin/addSuggEbook/'+eid);
	} else {
		location.assign('/coaadmin/deleSuggEbook/'+eid);
	}
};
</script>
<?php
if($ebooks) {
?>
          <div id="right_title">電子書管理</div>
          <div id="right_main">
            <div id="book_title">推薦好書建議勾選五本</div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabledate01">
              <tr>
                <th width="30%">書名</th>
                <th width="10%">作者</th>
                <th width="14%">出版單位</th>
                <!--th width="30%">簡介</th-->
                <th width="8%">推薦好書</th>
                <th width="4%">&nbsp;</th>
                <th width="4%">&nbsp;</th>
              </tr>
<?php
	$lineoe = 0;
	foreach ($ebooks as $ebook) {
?>
              <tr class="<?php echo (($lineoe % 2) <= 0)? '':'GrayBG' ;?>">
                <td class="txtL"><?=$ebook->title; ?></td>
                <td><?=$ebook->author; ?></td>
                <td><?=$ebook->author_unit; ?></td>
                <!--td><?=$ebook->description; ?></td-->
				<td><input name="chgSugg" type="checkbox"
<?php
	$match = false;
	foreach ($sugg_ebook_ids as $seid) {
		if($ebook->eid == $seid) $match = true;
	}
	if($match) {
		echo 'checked ';
	} else {
		//
	}
	echo 'onClick="chgSugg(this, ', $ebook->eid,');"';
?> /></td>
                <td><a href="/coaadmin/editEbook/<?php echo $ebook->eid; ?>"><img src="/images/edit.png"/></a></td>
                <td><a href="javascript:deleEbook(<?php echo $ebook->eid; ?>);"><img src="/images/delete.png"/></a></td>
              </tr>
<?php
		$lineoe++;
	}
?>
            </table>
          </div>
<?php
}
?>

