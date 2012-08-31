          <div id="right_title">編輯 Banner</div>
          <div id="right_main">
            <div id="divadd" class="banner_list">
				<form id="ff" method="post" enctype="multipart/form-data">
				  <input type="hidden" id="da" name="da" value="da" />
				  <table width="50%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="30">連結</td>
						<td><input type="text" name="link" id="link" value="<?=$link;?>"/><br /></td>
						<td><!--a href="#"><img src="/images/filebutton.jpg"/></a--></td>
						<td><!--a href="#"><img src="/images/send.jpg"/></a--></td>
					  </tr>
					  <tr>
						<td width="30">圖片</td>
						<td><img src="/images/ban<?=$bid;?>.jpg" width="222" height="62"/><br/>
							<input type="file" name="jpgfile" id="jpgfile" /><br /></td>
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
           </div>