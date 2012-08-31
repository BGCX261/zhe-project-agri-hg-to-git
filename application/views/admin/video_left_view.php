      <div id="center"><?php if(!isset($left)) $left = 'vc'; ?>
        <div id="left">
          <div><img src="/images/submenu_title02.jpg"/></div>
          <div id="submenu">
            <ul>
              <li<?php echo ($left === 'vc') ? ' class="cu"':''; ?>><a href="/coaadmin/vcatalog">影音分類管理</a></li>
              <li<?php echo ($left === 'add') ? ' class="cu"':''; ?>><a href="/coaadmin/addVideo">新增影音</a></li>
              <li<?php echo ($left === 'mng') ? ' class="cu"':''; ?>><a href="/coaadmin/mngVideo">影音管理</a></li>
			  <li<?php echo ($left === 'search') ? ' class="cu"':''; ?>><a href="/coaadmin/searchVideo">搜尋影音</a></li>
            </ul>
          </div>
        </div>
        <div id="right">
