<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>影片類別</title>
	<link rel="stylesheet" type="text/css" href="/js/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="/js/themes/icon.css">
	<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery.easyui.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('#tt').tree({
				dnd: true,
				lines: true,
				onContextMenu: function(e, node){
					e.preventDefault();
					$('#tt').tree('select', node.target);
					$('#mm').menu({  
						onClick:function(item){  
							if(item.id == 'add') {
								var parentNode = $('#tt').tree('getSelected');
								var nodeName = '新增分類';
								var pid = parentNode ? parentNode.id : 0;
								$.ajax({
									url: '/coaadmin/jsaddVCatalog',
									type: 'post',  
									dataType: 'json',
									data: {  
										name: nodeName,
										pid: pid
									},
									success: function(newId) {
										if(newId > 0) {
											var nodes = [{
												"id": newId, 
												"text": nodeName + ' (0)', 
												"attributes": {
														"vcid": newId,
														"vcname": nodeName,
														"vcounts": 0
													}
											}];
										}
										$('#tt').tree('append', {
											parent: parentNode.target,
											data: nodes
										});
									}
								});
							} else if (item.id == 'delete') {
								var node = $('#tt').tree('getSelected');
								if(!($('#tt').tree('isLeaf', node.target))){return;}
								$('#dd').dialog('open');
							} else if (item.id == 'edit') {
								var node = $('#tt').tree('getSelected');
								node.text = node.attributes.vcname;
								$('#tt').tree('update', node);
								$('#tt').tree('beginEdit', node.target);
							} else {
								// No this method.
							}
						}  
					});  
					$('#mm').menu('show', {
						left: e.pageX - 5,
						top: e.pageY - 5
					});

					if($('#tt').tree('isLeaf', node.target)){
						$('#mm').menu('enableItem', '#delete');
					} else {
						$('#mm').menu('disableItem', '#delete');
					}
				},
				onClick: function(node) {
					//$('#tt').tree('expand', node.target)
					//console.log($('#tt').tree('isLeaf', node.target) );
				},
				onDblClick: function(node){
					node.text = node.attributes.vcname;
					$('#tt').tree('update', node);
					$('#tt').tree('beginEdit', node.target);
				},
				onAfterEdit: function(node) {
					if(node.text != node.attributes.vcname) {
						$.ajax({  
							url: '/coaadmin/jsupdateVCatalog',  
							type: 'post',  
							dataType: 'json',  
							data: {  
								id: node.id,  
								name: node.text,
							},
							success: function(data) {
								node.attributes.vcname = node.text;
								node.text = node.text + ' (' 
									+ node.attributes.vcounts + ')';
								$('#tt').tree('update', node);
								if(!data) {
									alert('更新失敗!!');
									$('#tt').tree('reload');
								}
							}
						});
					} else {
						node.text = node.text + ' (' 
							+ node.attributes.vcounts + ')';
						$('#tt').tree('update', node);
					}
				},
				onCancelEdit: function(node) {
					node.text = node.text + ' (' 
								+ node.attributes.vcounts + ')';
					$('#tt').tree('update', node);
				},
				onDrop: function(targetNode, source, point){  
					var targetId = $(targetNode).tree('getNode', targetNode).id;  
					$.ajax({  
						url: '/coaadmin/jsmoveVCatalog',  
						type: 'post',  
						dataType: 'json',  
						data: {  
							id: source.id,  
							targetId: targetId,  
							point: point  
						},
						success: function(data) {
							if(!data) {
								alert('更新失敗!!');
								$('#tt').tree('reload');
							}
						}
					});
				}
			});
			$('#dd').dialog({
				buttons:[{
					text:'刪除',
					//iconCls:'icon-ok',
					iconCls:'icon-remove',
					handler:function(){
						var node = $('#tt').tree('getSelected');
						$.ajax({  
							url: '/coaadmin/jsdeleteVCatalog',  
							type: 'post',  
							dataType: 'json', 
							data: {  
								id: node.id, 
							},
							success: function(result) {
								console.log(result);
								if(!result) {
									alert('更新失敗!!');
									$('#tt').tree('reload');
								} else {
									$('#dd').dialog('close');
									$('#tt').tree('remove', node.target);
								}
							}
						});
					}
				},{
					text:'取消',
					iconCls:'icon-no',
					handler:function(){
						$('#dd').dialog('close');
					}
				}]
			});
			$('#dd').dialog('close');
		});
	</script>
	
<script type="text/javascript">
/*
$(document).ready(function() {
	$("#button").click(function(){$(this).myFunction();});
	$.fn.myFunction = function()
	{
		alert('OK');
		$.ajax({
			url: '/vcatalog/tree_updateVCatalog',
			type: 'post',  
			dataType: 'json',
			data: {
				id: 7,  
				targetId: 5,  
				point: 'append' 
			},
			success: function(data) {
				if(data) {
					alert('true');
				} else {
					alert('false');
				}
				//$('#result').html(data);
				alert('Load was performed.');}
		});
	}
});
*/
</script>

	
	
</head>
<body>
	<ul id="tt" class="easyui-tree" url="/vcatalog/tree_getVCatalog" editor="text"></ul>
	<div id="mm" class="easyui-menu" style="width:80px;">
		<div id='add' iconCls="icon-add">新增</div>
		<div id='edit' iconCls="icon-edit">編輯</div>
		<div id='delete' iconCls="icon-remove">刪除</div>
	</div>
	<div id="dd" class="easyui-dialog" title="刪除確認" style="width:280px;height:120px;visiable:false;" buttons="#dlg-buttons">類別刪除後將無法復原，影片將移至上層分類，<br/>若無上層分類，影片將無分類。請問您確定嗎？</div>
	<!--input id="vatalog" type=text size=8/><input id="addBtn" type="button" value="Add"/-->
</body>
</html>