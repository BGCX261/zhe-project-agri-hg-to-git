  <div class="main_bottom">
    <div class="mid">
      <div class="sub_trns_list">
        <ul>
<?php
foreach ($banners as $banner) {
?>
          <li><a href="<?=$banner->link;?>"><img src="/images/ban<?=$banner->bid;?>.jpg" /></a></li>
<?php
}
?>
        </ul>
      </div>
    </div>
  </div>