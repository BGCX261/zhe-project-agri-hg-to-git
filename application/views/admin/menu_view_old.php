<script>
$(function(){
	$('#sugEbook').click(function(){
		location.assign('/coaadmin/sugEbook');
	});
	$('#editVc').click(function(){
		location.assign('/coaadmin/vcatalog');
	});
	$('#vcMngr').click(function(){
		location.assign('/coaadmin/vcatalogmng');
	});
	$('#addVideo').click(function(){
		location.assign('/coaadmin/addVideo');
	});
	$('#mngVideo').click(function(){
		location.assign('/coaadmin/mngVideo');
	});
	$('#addEbook').click(function(){
		location.assign('/coaadmin/addEbook');
	});
	$('#mngEbook').click(function(){
		location.assign('/coaadmin/mngEbook');
	});
	$('#videoMngr').click(function(){
		location.assign('/coaadmin/videomng');
	});
	$('#statVideos').click(function(){
		location.assign('/coaadmin/statVideos');
	});
	$('#statAllVideos').click(function(){
		location.assign('/coaadmin/statAllVideos');
	});
	$('#statVideoView').click(function(){
		location.assign('/coaadmin/statVideoView');
	});
	$('#mb7').click(function(){
		location.assign('/coaadmin/logout');
	});
});
</script>

<div style="background:#C9EDCC;padding:5px;width:98%;">
	<a href="javascript:void(0)" id="mb1" class="easyui-menubutton" menu="#mm1">首頁</a>
	<a href="javascript:void(0)" id="mb2" class="easyui-menubutton" menu="#mm2">全館影音</a>
	<a href="javascript:void(0)" id="mb3" class="easyui-menubutton" menu="#mm3">電子書管理</a>
	<a href="javascript:void(0)" id="mb4" class="easyui-menubutton" menu="#mm4">每月主題推薦</a>
	<a href="javascript:void(0)" id="mb5" class="easyui-menubutton" menu="#mm5">統計報表</a>
	<a href="javascript:void(0)" id="mb6" class="easyui-menubutton" menu="#mm6">權限管理</a>
	<a href="javascript:void(0)" id="mb7" class="easyui-linkbutton" plain="true">登出</a>
	<div id="mm1" style="width:150px;">
		<div id="monSugg">本月推薦</div>
		<div id="sugEbook">典藏電子書</div>
		<div id="edtBanner">Banner</div>
	</div>
	<div id="mm2" style="width:150px;">
		<div id="editVc">影音分類管理</div>
		<div id="addVideo">新增影音</div>
		<div id="mngVideo">管理影音</div>
		<div class="menu-sep"></div>
		<div id="vcMngr">進階類別修改</div>
		<div id="videoMngr">進階影片修改</div>
	</div>
	<div id="mm3" style="width:150px;">
		<div id="addEbook">新增電子書</div>
		<div id="mngEbook">管理電子書</div>
		<div class="menu-sep"></div>
		<div id="ebookMngr">進階電子書修改</div>
	</div>
	<div id="mm4" style="width:150px;">
		<div id="sugVideo">主題推薦編輯</div>
	</div>
	<div id="mm5" style="width:150px;">
		<div id="statVideos">影音上架統計表</div>
		<div id="statAllVideos">全站熱門影音排行統計表</div>
		<div id="statVideoView">影音觀看統計表</div>
	</div>
	<div id="mm6" style="width:150px;">
		<div id="addUser">新增使用者</div>
		<div id="mngUser">管理使用者</div>
		<div id="deleUser">刪除使用者</div>
		<div class="menu-sep"></div>
		<div id="userMngr">進階使用者修改</div>
	</div>
</div>
