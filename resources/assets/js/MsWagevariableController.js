//require('./jquery.easyui.min.js');
let MsWagevariableModel = require('./MsWagevariableModel');
require('./datagrid-filter.js');

class MsWagevariableController {
	constructor(MsWagevariableModel)
	{
		this.MsWagevariableModel = MsWagevariableModel;
		this.formId='wagevariableFrm';
		this.dataTable='#wagevariableTbl';
		this.route=msApp.baseUrl()+"/wagevariable"
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
			this.MsWagevariableModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsWagevariableModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsWagevariableModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsWagevariableModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#wagevariableTbl').datagrid('reload');
		//$('#WagevariableFrm  [name=id]').val(d.id);
		msApp.resetForm('wagevariableFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsWagevariableModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsWagevariable.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsWagevariable=new MsWagevariableController(new MsWagevariableModel());
MsWagevariable.showGrid();
