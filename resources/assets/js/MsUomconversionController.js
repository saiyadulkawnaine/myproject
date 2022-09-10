//require('./jquery.easyui.min.js');
let MsUomconversionModel = require('./MsUomconversionModel');
require('./datagrid-filter.js');

class MsUomconversionController {
	constructor(MsUomconversionModel)
	{
		this.MsUomconversionModel = MsUomconversionModel;
		this.formId='uomconversionFrm';
		this.dataTable='#uomconversionTbl';
		this.route=msApp.baseUrl()+"/uomconversion"
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
			this.MsUomconversionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsUomconversionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsUomconversionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsUomconversionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#uomconversionTbl').datagrid('reload');
		msApp.resetForm('uomconversionFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsUomconversionModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsUomconversion.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsUomconversion=new MsUomconversionController(new MsUomconversionModel());
MsUomconversion.showGrid();