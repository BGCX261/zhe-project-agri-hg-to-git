<script type="text/javascript">
$(function(){
	$('#tt').datagrid({
		onDblClickRow:function(index,row){
			editrow(index);
		},
		onBeforeEdit:function(index,row){
			row.editing = true;
			updateActions();
		},
		onAfterEdit:function(index,row){
			row.editing = false;
			updateActions();
		},
		onCancelEdit:function(index,row){
			row.editing = false;
			updateActions();
		}
	});
});

function formatAction(value,row,index){
	if (row.editing){
		var s = '<a href="#" onclick="saverow('+index+')">儲存</a> ';
		var c = '<a href="#" onclick="cancelrow('+index+')">取消</a>';
		return s+c;
	} else {
		var e = '<a href="#" onclick="editrow('+index+')">編輯</a> ';
		var d = '<a href="#" onclick="deleterow('+index+')">刪除</a>';
		return e+d;
	}
}


function updateActions(){
	var rowcount = $('#tt').datagrid('getRows').length;
	for(var i=0; i<rowcount; i++){
		$('#tt').datagrid('updateRow',{
			index:i,
			row:{action:''}
		});
	}
}
function editrow(index){
	$('#tt').datagrid('beginEdit', index);
}
function deleterow(index){
	$.messager.confirm('刪除確認','刪除之後無法復原，確定要刪除？<br/>(ID <= 10的類別禁止刪除)',function(r){
		if (r){
			var row = $('#tt').datagrid('getSelected');
			if(row.vcid > 10) {
				$('#tt').datagrid('deleteRow', index);
				updateActions();
			}
		}
	});
}
function saverow(index){
	$('#tt').datagrid('endEdit', index);
}
function cancelrow(index){
	$('#tt').datagrid('cancelEdit', index);
}
/*
function insert(){
	var row = $('#tt').datagrid('getSelected');
	if (row){
		var index = $('#tt').datagrid('getRowIndex', row);
	} else {
		index = 0;
	}
	$('#tt').datagrid('insertRow', {
		index: index,
		row:{
			status:'P'
		}
	});
	$('#tt').datagrid('selectRow',index);
	$('#tt').datagrid('beginEdit',index);
}
*/
</script>

<table id="tt" 
		url="/coaadmin/jsgetVideos" singleSelect="true"
		title="影片管理" autoRowHeight="true"
		idField="vid" pagination="true">
	<thead>
		<tr>
			<th field="vid" width="30">ID</th>
			<th field="youtube_id" width="100" editor="text">Youtube ID</th>
			<th field="title" width="120" editor="text">標題</th>
			<th field="description" width="300" editor="text">描述</th>
			<th field="videocatalog_id" width="30" editor="numberbox">分類</th>
			<th field="clicks" width="40" align="right" editor="numberbox">點擊數</th>
			<th field="org_source_path" width="100" editor="text">原始檔路徑</th>
			<th field="create_time" width="110" editor="text">建立時間</th>
			<th field="action" width="60" formatter="formatAction">動作</th>
		</tr>
	</thead>
</table>
