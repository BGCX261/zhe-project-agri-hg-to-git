		  <div id="right_title">編輯影音</div>
		  <form id="ff" method="post" enctype="multipart/form-data">
		  <input name="vid" id="vid" type="hidden" value="<?php echo $vid;?>"/>
		  <input name="ovcid" id="vid" type="hidden" value="<?=isset($ovcid)?$ovcid:$videocatalog_id;?>"/>
		  <div id="right_main">
			<p><font color="red" size=2><?php echo $message;?></font></p>
			<p style="float:right;">[<a href="/coaadmin/mngVideo/<?=isset($ovcid)?$ovcid:$videocatalog_id;?>"/>回影片列表</a>]</p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabledate02">
			  <tr>
				<th width="80"><label for="title">影片名稱</label></th>
				<td><input name="title" id="title" type="text" style="width:200px;"
					maxlength="100" value="<?php echo $title;?>"/></td>
			  </tr>
			  <tr>
				<th><label for="vlength">片長</label></th>
				<td><input name="vlength" id="vlength" type="text" style="width:200px;"
					value="<?php echo $vlength;?>"/></td>
			  </tr>
			  <tr>
				<th><label for="author">授權單位</label></th>
				<td><input name="author" id="author" type="text" style="width:200px;"
					maxlength="100" value="<?php echo $author;?>"/></td>
			  </tr>
			  <tr>
				<th><label for="create_time">上架日期</label></th>
				<td><input name="create_time" id="create_time" type="text" style="width:200px;"
					value="<?php echo $create_time;?>"/></td>
			  </tr>
			  <tr>
				<th><label for="videocatalog_id">影片分類</label></th>
				<td><input id="videocatalog_id" name="videocatalog_id" class="easyui-combotree" url="/coaadmin/jsgetVCatalogList" 
						value="<?php echo $videocatalog_id;?>" required="true" style="width:200px;">
					<input type="hidden" id="ovcid" name="ovcid" value="<?php echo $videocatalog_id;?>" />
				</td>
			  </tr>
			  <tr>
				<th><label for="clicks">點閱數</label></th>
				<td><?php echo $clicks;?></td>
			  </tr>
<?php /*
			  <tr>
				<th><label for="vtags">標籤</label></th>
				<td><input name="vtags" id="vtags" type="text" style="width:200px;"
					value="<?php echo $vtags;?>"/></td>
			  </tr>
*/ ?>
			  <tr>
				<th valign="top"><label for="description">簡介</label></th>
				<td><textarea name="description" id="description" style="width:200px;"><?php echo $description;?></textarea></td>
			  </tr>
			  <tr>
				<th><label for="youtube_id">Youtube ID</label></th>
				<td><input name="youtube_id" id="youtube_id" type="text" style="width:200px;"
					maxlength="11" size="11" value="<?php echo $youtube_id;?>"/></td>
			  </tr>
			  <tr>
				<th><label for="org_source_path">原始檔路徑</label></th>
				<td><input name="org_source_path" id="org_source_path" type="text" style="width:200px;"
					value="<?php echo $org_source_path;?>"/></td>
			  </tr>
<?php /*
			  <tr>
				<th><label for="view_source_path">檢視檔路徑</label></th>
				<td><input name="view_source_path" id="view_source_path" type="text" style="width:200px;"
					value="<?php echo $view_source_path;?>"/></td>
			  </tr>
			  <tr>
				<th><label for="other_source_path">其他檔路徑</label></th>
				<td><input name="other_source_path" id="other_source_path" type="text" style="width:200px;"
					value="<?php echo $other_source_path;?>"/></td>
			  </tr>
*/ ?>		  
			  <tr>
				<th><label for="bigimg">首頁圖</label></th>
				<td>
				  <input name="bigimg" id="bigimg" type="file" />
				  <br />
				  <span class="FontGray">限定尺寸650*262x</span>
				</td>
			  </tr>
			</table>
			<div><img src="/videos/bigimg/<?php echo $vid;?>" width="650" height="262" /></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabledate02">
			  <tr>
				<th width="80"><label for="thumbnail">縮圖</label></th>
				<td>
				  <input type="file" name="thumbnail" id="thumbnail" />
				  <br />
				  <span class="FontGray">限定尺寸197*132x</span>
				</td>
			  </tr>
			  <tr>
				<th width="80">&nbsp;</th>
				<td><img src="/videos/thumbnail/<?php echo $vid;?>" width="197" height="132" /></td>
			  </tr>
			  <tr>
				<th><label for="addsugg">本會推薦</th>
				<td><input type="checkbox" <?php echo $addsugg?'checked disabled':'id="addsugg" name="addsugg"';?> /></td>
			  </tr>
			  <tr>
				<th>&nbsp;</th>
				<td align="right">
				  <input type="reset" value="重新填寫"/>
				  <input type="submit" value="送出" onClick="$('#ff').submit();"/>
				</td>
			  </tr>
			</table>
		  </div>
		  </form>