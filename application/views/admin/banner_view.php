<script>
function deleBanner(bid){
	$.messager.confirm('刪除確認','刪除之後無法復原，確定要刪除？',function(r){
		if (r){
			location.assign('/coaadmin/deleBanner/'+bid);
		}
	});
};
function toggleAdd() {
	if($('#divadd').is(":visible")) {
		$('#divadd').hide();
	} else {
		$('#divadd').show();
	}
};
</script>
          <div id="right_title">Banner</div>
          <div id="right_main">
            <div class="banner_list"><input type="button" value="新增" onClick="toggleAdd()"/></div>
            <div id="divadd" class="banner_list" style="display: none;">
				<form id="ff" method="post" enctype="multipart/form-data"
					action="/coaadmin/addBanner">
				  <table width="50%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="30">連結</td>
						<td><input type="text" name="link" id="link" value="http://"/><br /></td>
						<td><!--a href="#"><img src="/images/filebutton.jpg"/></a--></td>
						<td><!--a href="#"><img src="/images/send.jpg"/></a--></td>
					  </tr>
					  <tr>
						<td width="30">圖片</td>
						<td><input type="file" name="jpgfile" id="jpgfile" /><br /></td>
						<td><!--a href="#"><img src="/images/filebutton.jpg"/></a--></td>
						<td><input type="image" src="/images/send.jpg" onClick="$('#divadd').submit();"/></td>
					  </tr>
					  <tr>
						<td valign="top">&nbsp;</td>
						<td class="FontGray">圖片限制尺寸為222*62px</td>
						<td valign="top">&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					</table>
				</form>
            </div>
<?php
$lineoe = 0;
foreach ($banners as $banner) {
?>
            <div class="banner_list">
              <div class="banner_img">
                <div><a href="<?=$banner->link;?>"><img src="/images/ban<?=$banner->bid;?>.jpg"/></a></div>
                <div class="FontGray">圖片預覽</div>
              </div>
              <div class="banner_icon">
                <div><a href="/coaadmin/editBanner/<?=$banner->bid;?>"><img src="/images/edit.png"/></a></div>
	<?php if($lineoe > 0) {?>
                <div><a href="/coaadmin/moveBanner/<?=$banner->bid;?>/<?=$banner->sort+1;?>"><img src="/images/upload.png"/></a></div>
	<?php } ?>
                <div><a href="javascript:deleBanner(<?=$banner->bid;?>);"><img src="/images/delete.png"/	></a></div>
              </div>
            </div>
<?php
	$lineoe++;
}
?>
           </div>