<script type="text/javascript">
function deleSuggEbook(eid){
	$.messager.confirm('刪除確認','刪除之後無法復原，確定要刪除？',function(r){
		if (r){
			location.assign('/coaadmin/deleSuggEbook/'+eid);
		}
	});
}
</script>
<?php
if($suggestebooks) {
?>
<table>
<tr>
<th>ID</th>
<th>書名</th>
<th>點擊數</th>
<th>刪除</th>
</tr>
<?php
	foreach ($suggestebooks as $ebook) {
?>
<tr>
<td><?php echo $ebook->eid; ?></td>
<td><?=$ebook->title; ?></td>
<td><?=$ebook->clicks; ?></td>
<td><a href="javascript:deleSuggEbook(<?php echo $ebook->eid; ?>);"/>刪除</a></td></td>
</tr>
<?php
	}
?>
</table>
<?php
}
?>

