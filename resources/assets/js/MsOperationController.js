let MsOperationModel = require('./MsOperationModel');
require('./datagrid-filter.js');

class MsOperationController {
	constructor(MsOperationModel)
	{
		this.MsOperationModel = MsOperationModel;
		this.formId='operationFrm';
		this.dataTable='#operationTbl';
		this.route=msApp.baseUrl()+"/operation"
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

		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsOperationModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsOperationModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#operationFrm [id="gmtspart_id"]').combobox('setValue', '');
		$('#operationFrm [id="resource_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsOperationModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsOperationModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#operationTbl').datagrid('reload');
		msApp.resetForm('operationFrm');
		$('#operationFrm [id="gmtspart_id"]').combobox('setValue', '');
		$('#operationFrm [id="resource_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let opt=this.MsOperationModel.get(index,row);
		opt.then(function (response) {	
			$('#operationFrm [id="gmtspart_id"]').combobox('setValue', response.data.fromData.gmtspart_id);
			$('#operationFrm [id="resource_id"]').combobox('setValue', response.data.fromData.resource_id);
		})
		.catch(function (error) {
			console.log(error);
		});
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
		return '<a href="javascript:void(0)"  onClick="MsOperation.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openFabricationWindow(){
		$('#styleFabricationWindow').window('open');
	}
	searchFabric(){
		let construction_name=$('#stylefabricsearchFrm  [name=construction_name]').val();
		let composition_name=$('#stylefabricsearchFrm  [name=composition_name]').val();
		let data= axios.get(this.route+"/getfabric?construction_name="+construction_name+"&composition_name="+composition_name);
		data.then(function (response) {
			$('#stylefabricsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	fabricSearchGrid(data)
	{
		var dg = $('#stylefabricsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				$('#operationFrm  [name=autoyarn_id]').val(row.id);
				$('#operationFrm  [name=fabrication]').val(row.name+","+row.composition_name);
				$('#styleFabricationWindow').window('close')
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsOperation=new MsOperationController(new MsOperationModel());
MsOperation.showGrid();
MsOperation.fabricSearchGrid([]);
$('#utilOperationtabs').tabs({
	onSelect:function(title,index){
	   let operation_id = $('#operationFrm  [name=id]').val();

		var data={};
		data.operation_id=operation_id;

		if(index==1){
			if(operation_id===''){
				$('#utilOperationtabs').tabs('select',0);
				msApp.showError('Select an Operation First',0);
				return;
			}
			$('#attachmentoperationFrm  [name=operation_id]').val(operation_id);
			MsAttachmentOperation.create(operation_id);
			//MsAttachmentOperation.get(operation_id);
		}
	}
});