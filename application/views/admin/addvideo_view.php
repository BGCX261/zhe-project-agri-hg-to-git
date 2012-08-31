          <div id="right_title">新增影音</div>
		  <form id="ff" method="post" enctype="multipart/form-data">
          <div id="right_main">
            <table width="80%" border="0" cellspacing="0" cellpadding="0" class="tabledate02">
              <tr>
                <th><label for="title">影片名稱</label></th>
                <td width="210"><input id="title" name="title" type="text" style="width:200px;"
					maxlength="100" value="<?php echo $title;?>"/></td>
                <td class="FontRed" width="35%"><!--您未輸入任何資訊，請重新輸入--></td>
              </tr>
              <tr>
                <th><label for="vlength">片長</label></th>
                <td><input id="vlength" name="vlength" type="text" style="width:200px;"
					value="<?php echo $vlength;?>"/></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th><label for="author">授權單位</label></th>
                <td><input id="author" name="author" type="text" style="width:200px;"
					maxlength="100" value="<?php echo $author;?>"/></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th><label for="videocatalog_id">影片分類</label></th>
                <td><input id="videocatalog_id" name="videocatalog_id" class="easyui-combotree" url="/coaadmin/jsgetVCatalogList" 
		value="<?php echo $videocatalog_id;?>" required="true" style="width:200px;">
				</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th valign="top"><label for="description">簡介</label></th>
                <td><textarea id="description" name="description" style="width:200px;"><?php echo $description;?></textarea></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th><label for="youtube_id">Youtube ID</label></th>
                <td><input id="youtube_id" name="youtube_id" type="text" style="width:200px;"
					 maxlength="11" size="11" value="<?php echo $youtube_id;?>"/></td>
                <td>&nbsp;</td>
              </tr>
<?php /*
              <tr>
                <th><label for="vtags">標籤</label></th>
                <td><input id="vtags" name="vtags" type="text" style="width:200px;"
					 value="<?php echo $vtags;?>" /></td>
                <td>&nbsp;</td>
              </tr>
*/?>
              <tr>
                <th><label for="org_source_path">原始檔路徑</label></th>
                <td><input id="org_source_path" name="org_source_path" type="text" style="width:200px;"
					value="<?php echo $org_source_path;?>"/></td>
                <td>&nbsp;</td>
              </tr>
<?php /*
              <tr>
                <th><label for="view_source_path">檢視檔路徑</label></th>
                <td><input id="view_source_path" name="view_source_path" type="text" style="width:200px;"
					value="<?php echo $view_source_path;?>"/></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th><label for="other_source_path">其他檔路徑</label></th>
                <td><input id="other_source_path" name="other_source_path" type="text" style="width:200px;"
					value="<?php echo $other_source_path;?>"/></td>
                <td>&nbsp;</td>
              </tr>
*/?>
              <tr>
                <th><label for="bigimg">首頁圖</label></th>
                <td>
                  <input id="bigimg" name="bigimg" type="file" />
				  <span class="FontGray">限定尺寸650*262x</span>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th><label for="thumbnail">縮圖</label></th>
                <td>
                  <input id="thumbnail" name="thumbnail" type="file"/>
				  <span class="FontGray">限定尺寸197x132px</span>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th><label for="addsugg">本會推薦</label></th>
                <td><input id="addsugg" name="addsugg" type="checkbox" /></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th>&nbsp;</th>
                <td align="right">
                  <input type="reset" value="重新填寫"/>
				  <input type="submit" value="送出" onClick="$('#ff').submit();"/>
                </td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </div>
		  </form>