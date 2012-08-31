      <div id="bannerBG">
        <div id="menu">
          <ul><?php if(!isset($menu)) $menu = 'home'; ?>
            <li<?php echo ($menu === 'home') ? ' class="current"':''; ?>><a href="/coaadmin">首頁</a></li>
            <li<?php echo ($menu === 'videos') ? ' class="current"':''; ?>><a href="/coaadmin/vcatalog">全館影音</a></li>
            <li<?php echo ($menu === 'ebooks') ? ' class="current"':''; ?>><a href="/coaadmin/mngEbook">電子書</a></li>
            <li<?php echo ($menu === 'sugg') ? ' class="current"':''; ?>><a href="/coaadmin/suggVideo">每月主題推薦</a></li>
            <li<?php echo ($menu === 'repo') ? ' class="current"':''; ?>><a href="/coaadmin/reportPub">統計報表</a></li>
			<li><a href="/coaadmin/logout">登出</a></li>
          </ul>
        </div>
      </div>