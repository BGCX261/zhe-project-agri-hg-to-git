<script type="text/javascript">
$.fn.datebox.defaults.formatter = function(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	m = (m < 10) ? '0'+m : m;
	var d = date.getDate();
	d = (d < 10) ? '0'+d : d;
	return y+'-'+m+'-'+d;
}
</script>
          <div id="right_title"><?php echo $right_title; ?></div>
          <div id="right_main">
			<form method=post>
            <table width="52%" border="0" cellspacing="0" cellpadding="0" class="tabledate02">
              <tr>
                <td colspan="2" id="report_title"><?php echo $report_title; ?></td>
              </tr>
              <tr>
                <th><label for="from">時間起</label></th>
                <td>
                  <input class="easyui-datebox" type="text" name="from" id="from" value="<?php echo isset($from)?$from:''; ?>" />
                </td>
              </tr>
              <tr>
                <th><label for="to">時間至</label></th>
                <td>
                  <input class="easyui-datebox" type="text" name="to" id="to" value="<?php echo isset($to)?$to:''; ?>"/>
                </td>
              </tr>
              <tr>
                <th><label for="limit">每頁顯示</label></th>
                <td>
					<select name="limit">
					<?php if(!isset($limit)) $limit = 50; ?>
						<option value="10"<?php echo ($limit == 10) ? ' selected' : '' ?>>10</option>
						<option value="20"<?php echo ($limit == 20) ? ' selected' : '' ?>>20</option>
						<option value="30"<?php echo ($limit == 30) ? ' selected' : '' ?>>30</option>
						<option value="50"<?php echo ($limit == 50) ? ' selected' : '' ?>>50</option>
						<option value="100"<?php echo ($limit == 100) ? ' selected' : '' ?>>100</option>
					</select>
				</td>
              </tr>
              <tr>
                <td colspan="2">
					<input type="checkbox" name="csv" id="csv"<?php echo isset($csv)?' checked="checked"':''; ?>"/><label for="csv">匯出Excel檔(.csv)</label>
				</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td align="left">
					<input type="submit" name="submit" value="查詢"/>
					<input type="hidden" name="page" value="<?php echo isset($page)?$page:1; ?>" />
				</td>
              </tr>
            </table>
			</form>
          </div>