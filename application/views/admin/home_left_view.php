      <div id="center"><?php if(!isset($left)) $left = 'sugg'; ?>
        <div id="left">
          <div><img src="/images/submenu_title01.jpg"/></div>
          <div id="submenu">
            <ul>
              <li<?php echo ($left === 'sugg') ? ' class="cu"':''; ?>><a href="/coaadmin">本月推薦</a></li>
              <li<?php echo ($left === 'banner') ? ' class="cu"':''; ?>><a href="/coaadmin/mngBanner">banner</a></li>
            </ul>
          </div>
        </div>
        <div id="right">
