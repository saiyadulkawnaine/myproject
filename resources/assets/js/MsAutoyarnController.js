//require('./jquery.easyui.min.js');
let MsAutoyarnModel = require('./MsAutoyarnModel');
require('./datagrid-filter.js');
class MsAutoyarnController {
	constructor(MsAutoyarnModel)
	{
		this.MsAutoyarnModel = MsAutoyarnModel;
		this.formId='autoyarnFrm';
		this.dataTable='#autoyarnTbl';
		this.route=msApp.baseUrl()+"/autoyarn"
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
			this.MsAutoyarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAutoyarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAutoyarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAutoyarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#autoyarnTbl').datagrid('reload');
		$('#autoyarnFrm  [name=id]').val(d.id);
		msApp.resetForm('autoyarnratioFrm');
		$('#autoyarnratioFrm  [name=autoyarn_id]').val(d.id);
		//msApp.resetForm('autoyarnFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAutoyarnModel.get(index,row);
		msApp.resetForm('autoyarnratioFrm');
		$('#autoyarnratioFrm  [name=autoyarn_id]').val(row.id);
		MsAutoyarnratio.showGrid(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsAutoyarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsAutoyarn=new MsAutoyarnController(new MsAutoyarnModel());
MsAutoyarn.showGrid();
