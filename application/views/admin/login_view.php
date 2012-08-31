<!DOCTYPE html>
<html lang="zh_tw">
<head>
	<meta charset="utf-8">
	<title>Admin Login</title>
	<link href="/css/css.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <div id="login_BG">
    <div id="login_list">
		<form name="form1" method="post" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="40" width="190">
              <input id="username" name="username" type="text" style="width:175px;"/>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="40">
              <input id="password" name="password" type="password" style="width:175px;"/>
            </td>
            <td>
              <input type="submit" value="登入" />
            </td>
          </tr>
        </table>
		</form>
		<!--p class="footer">渲染網頁在 <strong>{elapsed_time}</strong> 秒內</p-->
    </div>
  </div>
</body>
</html>