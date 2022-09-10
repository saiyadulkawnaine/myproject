let MsAttachmentOperationModel = require('./MsAttachmentOperationModel');
class MsAttachmentOperationController {
	constructor(MsAttachmentOperationModel)
	{
		this.MsAttachmentOperationModel = MsAttachmentOperationModel;
		this.formId='attachmentoperationFrm';
		this.dataTable='#attachmentoperationTbl';
		this.route=msApp.baseUrl()+"/attachmentoperation"
	}

	submit()
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Saving...</i>',
			overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 999999,
				cursor: 'wait'
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});

		let formObj=msApp.get('attachmentoperationFrm');
		let i=1;
		$.each($('#attachmentoperationTbl').datagrid('getChecked'), function (idx, val) {
				formObj['attachment_id['+i+']']=val.id	
			i++;
		});
		this.MsAttachmentOperationModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var operation_id=$('#operationFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/attachmentoperation/create?operation_id="+operation_id);
				data.then(function (response) {
				$('#attachmentoperationTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'New Attachment',width:100},
				]],
				}).datagrid('enableFilter');
				
				$('#attachmentoperationsavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Saved',width:100},
				{field:'action',title:'',width:60,formatter:MsAttachmentOperation.formatDetail},
				]],
				}).datagrid('enableFilter');
				})
				.catch(function (error) {
				console.log(error);
				});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAttachmentOperationModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		//alert(id)
		this.MsAttachmentOperationModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsAttachmentOperation.create();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAttachmentOperationModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAttachmentOperation.delete(event,'+row.attachment_operation_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAttachmentOperation=new MsAttachmentOperationController(new MsAttachmentOperationModel());
//MsAttachmentOperation.showGrid([]);