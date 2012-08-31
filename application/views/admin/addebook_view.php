          <div id="right_title">新增電子書</div>
		  <form id="ff" method="post" enctype="multipart/form-data">
          <div id="right_main">
			<p><font color="red" size=2><?php echo $message;?></font></p>
            <table width="80%" border="0" cellspacing="0" cellpadding="0" class="tabledate02">
              <tr>
                <th><label for="title">書名</label></th>
                <td width="210"><input name="title" id="title" type="text" style="width:200px;"
					maxlength="100" value="<?php echo $title;?>"/></td>
                <td class="FontRed" width="35%"><!--您未輸入任何資訊，請重新輸入--></td>
              </tr>
              <tr>
                <th><label for="author">作者</label></th>
                <td><input name="author" id="author" type="text" style="width:200px;"
					maxlength="100" value="<?php echo $author;?>"/></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th><label for="author_unit">出版單位</label></th>
                <td><input name="author_unit" id="author_unit" type="text" style="width:200px;"
					maxlength="100" value="<?php echo $author_unit;?>"/></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th valign="top"><label for="description">簡介</label></th>
                <td><textarea name="description" id="description" style="width:200px;"><?php echo $description;?></textarea></td>
                <td>&nbsp;</td>
              </tr>
<?php /*
              <tr>
                <th><label for="etags">標籤</label></th>
                <td><input name="etags" id="etags" type="text" style="width:200px;"
					value="<?php echo $author_unit;?>"/></td>
                <td>&nbsp;</td>
              </tr>
*/ ?>
              <tr>
                <th valign="top"><label for="pdffile">PDF</label></th>
                <td>
                  <input type="file" name="pdffile" id="pdffile" /><br />
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th valign="top"><label for="bigimg">封面圖</label></th>
                <td>
                  <input type="file" name="bigimg" id="bigimg" />
				  <br />
                  <span class="FontGray">限定尺寸720*468x</span>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th valign="top"><label for="thumbnail">縮圖</label></th>
                <td>
                  <input type="file" name="thumbnail" id="thumbnail" /><br />
                  <span class="FontGray">限定尺寸148*208x</span>
                </td>
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