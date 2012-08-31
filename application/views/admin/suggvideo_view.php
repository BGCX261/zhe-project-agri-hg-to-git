<script>
function showYear(year) {
	$('.theme_mouth').hide();
	$('#y'+year).show();
};
</script>
          <div id="right_title">每月主題推薦編輯</div>
          <div id="right_main">
            <div id="" class="theme_years">
              <ul>
<?php 
krsort($svmdates);
foreach (array_keys($svmdates) as $year) {
	echo '                ';
	if(strcmp($year,$pyear) == 0) {
		echo '<li class="op01">';
	} else {
		echo '<li>';
	}
	echo '<a href="javascript:showYear(',$year,');">',$year,'年</a></li>',"\n";
}
?>
              </ul>
            </div>
<?php
foreach (array_keys($svmdates) as $year) {
	echo '            ';
	echo '<div id="y', $year, '" class="theme_mouth"';
	if (strcmp($year,$pyear)) {
		echo ' style="display:none;"';
	}
	echo '>', "\n";
	echo '              <ul>', "\n";
	foreach ($svmdates[$year] as $mon) {
		echo '                ';
		echo '<li';
		if ((strcmp($year,$pyear) == 0)&&(strcmp($mon,$pmon) == 0)) {
			echo ' class="op02"';
		};
		echo '><a href="/coaadmin/suggVideo/', $year, '/', $mon,'">', (int) $mon, '月份</a></li>', "\n";
	}
	echo '              </ul>', "\n";
	echo '            </div>', "\n";
}
?>
            <div class="theme_form">主題名稱&nbsp;&nbsp;<input name="svmtitle" type="text" value="<?=$svmtitle;?>" disabled/></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabledate01">
              <tr>
                <th width="50%">影片名稱</th>
                <th width="12%">片長</th>
                <th width="12%">影片分類</th>
                <th>授權單位</th>
              </tr>
<?php
$lineoe = 0;
foreach ($videos as $video) {
?>
              <tr class="<?=(($lineoe%2)<=0)?'':'GrayBG';?>">
                <td class="txtL"><a href="/videos/play/<?=$video->vid?>"><?=$video->title?></a></td>
                <td><?php 
	$tmps = $video->vlength;
	$s = $tmps % 60;
	$tmps /= 60; $m = (int)$tmps % 60;
	$tmps /= 60; $h = (int)$tmps;
	echo $h,':',($m<10?'0'.$m:$m),':',($s<10?'0'.$s:$s);
?></td>
                <td><?=$vcatalog[$video->videocatalog_id];?></td>
                <td><?=$video->author;?></td>
              </tr>
<?php
	$lineoe++;
}
?>
            </table>
          </div>
        </div>