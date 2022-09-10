//require('./jquery.easyui.min.js');
let MsAutoyarnratioModel = require('./MsAutoyarnratioModel');
require('./datagrid-filter.js');

class MsAutoyarnratioController {
	constructor(MsAutoyarnratioModel)
	{
		this.MsAutoyarnratioModel = MsAutoyarnratioModel;
		this.formId='autoyarnratioFrm';
		this.dataTable='#autoyarnratioTbl';
		this.route=msApp.baseUrl()+"/autoyarnratio"
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
			this.MsAutoyarnratioModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAutoyarnratioModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAutoyarnratioModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAutoyarnratioModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#autoyarnratioTbl').datagrid('reload');
		//$('#AutoyarnratioFrm  [name=id]').val(d.id);
		msApp.resetForm('autoyarnratioFrm');
		$('#autoyarnratioFrm  [name=autoyarn_id]').val($('#autoyarnFrm  [name=id]').val());
		MsAutoyarnratio.showGrid($('#autoyarnFrm  [name=id]').val())
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAutoyarnratioModel.get(index,row);
	}

	showGrid(autoYarnId)
	{
		let self=this;
		var data={};
		data.autoyarn_id=autoYarnId;
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
		return '<a href="javascript:void(0)"  onClick="MsAutoyarnratio.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAutoyarnratio=new MsAutoyarnratioController(new MsAutoyarnratioModel());
