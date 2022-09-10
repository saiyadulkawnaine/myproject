//require('./jquery.easyui.min.js');
let MsKeycontrolParameterModel = require('./MsKeycontrolParameterModel');
class MsKeycontrolParameterController {
	constructor(MsKeycontrolParameterModel)
	{
		this.MsKeycontrolParameterModel = MsKeycontrolParameterModel;
		this.formId='keycontrolparameterFrm';
		this.dataTable='#keycontrolparameterTbl';
		this.route=msApp.baseUrl()+"/keycontrolparameter"
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
			this.MsKeycontrolParameterModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsKeycontrolParameterModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsKeycontrolParameterModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsKeycontrolParameterModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#keycontrolparameterTbl').datagrid('reload');
		msApp.resetForm('keycontrolparameterFrm');
		$('#keycontrolparameterFrm  [name=keycontrol_id]').val($('#keycontrolFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsKeycontrolParameterModel.get(index,row);
	}

	showGrid(keycontrol_id)
	{
		let self=this;
		var data={};
		data.keycontrol_id=keycontrol_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsKeycontrolParameter.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsKeycontrolParameter=new MsKeycontrolParameterController(new MsKeycontrolParameterModel());
//MsKeycontrolParameter.showGrid();
