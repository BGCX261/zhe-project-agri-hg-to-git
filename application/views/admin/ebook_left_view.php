      <div id="center"><?php if(!isset($left)) $left = 'mng'; ?>
        <div id="left">
          <div><img src="/images/submenu_title03.jpg"/></div>
          <div id="submenu">
            <ul>
              <li<?php echo ($left === 'add') ? ' class="cu"':''; ?>><a href="/coaadmin/addEbook">新增電子書</a></li>
              <li<?php echo ($left === 'mng') ? ' class="cu"':''; ?>><a href="/coaadmin/mngEbook">電子書管理</a></li>
            </ul>
          </div>
        </div>
        <div id="right">
