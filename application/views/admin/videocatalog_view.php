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
										"text": newId +')' + nodeName + ' (0)', 
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
						node.text = node.id +') ' + node.text + ' (' 
							+ node.attributes.vcounts + ')';
						$('#tt').tree('update', node);
						if(!data) {
							alert('更新失敗!!');
							$('#tt').tree('reload');
						}
					}
				});
			} else {
				node.text = node.id +') ' + node.text + ' (' 
					+ node.attributes.vcounts + ')';
				$('#tt').tree('update', node);
			}
		},
		onCancelEdit: function(node) {
			node.text = node.id +') ' + node.text + ' (' 
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
							alert('刪除失敗!!');
							$('#tt').tree('reload');
							$('#dd').dialog('close');
						} else {
							$('#dd').dialog('close');
							pnode = $('#tt').tree('getParent', node.target);
							if(pnode) {
								pnode.attributes.vcounts = parseInt(pnode.attributes.vcounts) + parseInt(node.attributes.vcounts);
								pnode.text = pnode.id +') ' + pnode.attributes.vcname + ' (' + pnode.attributes.vcounts + ')';
							}
							$('#tt').tree('remove', node.target);
							$('#tt').tree('reload');
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
			<div id="right_title">影音分類管理</div>
			<div id="right_main">
				<form id="ff" method="post">
					<div>
					  <input id="addVC" name="addVC" type="text" style="width:165px"/>
					  <a href="javascript:$('#ff').submit();"><img src="/images/newbutton.jpg" align="absmiddle"/></a>
					</div>
				</form>
				<!--div class="video_sort">
				  <ul>
					<li><a href="#">+ 系列長片</a>
					  <ul>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
					  </ul>
					</li>
				  </ul>
				</div>
				<div class="video_sort">
				  <ul>
					<li><a href="#">+ 系列長片</a>
					  <ul>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
						<li><a href="#">- 樂活農村樂趣GO</a></li>
					  </ul>
					</li>
				  </ul>
				</div-->
				<div id="mm" class="easyui-menu" style="width:80px;">
					<div id='add' iconCls="icon-add">新增</div>
					<div id='edit' iconCls="icon-edit">編輯</div>
					<div id='delete' iconCls="icon-remove">刪除</div>
				</div>
				<ul id="tt" class="easyui-tree" url="/coaadmin/jsgetVCatalog" editor="text"></ul>
				<div id="dd" class="easyui-dialog" title="刪除確認" style="width:280px;height:120px;visiable:false;" buttons="#dlg-buttons">類別刪除後將無法復原，影片將移至上層分類，<br/>若無上層分類，影片將無分類。請問您確定嗎？</div>
			</div>