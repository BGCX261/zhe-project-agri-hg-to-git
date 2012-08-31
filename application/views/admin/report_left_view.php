      <div id="center"><?php if(!isset($left)) $left = 'pub'; ?>
        <div id="left">
          <div><img src="/images/submenu_title05.jpg"/></div>
          <div id="submenu">
            <ul>
              <li<?php echo ($left === 'pub') ? ' class="cu"':''; ?>><a href="/coaadmin/reportPub">影音上架統計表</a></li>
              <li<?php echo ($left === 'hot') ? ' class="cu"':''; ?>><a href="/coaadmin/reportHot">熱門影音排行統計表</a></li>
              <li<?php echo ($left === 'view') ? ' class="cu"':''; ?>><a href="/coaadmin/reportView">影音觀看數統計表</a></li>
            </ul>
          </div>
        </div>
        <div id="right">
