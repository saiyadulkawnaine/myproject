//require('./jquery.easyui.min.js');
let MsFabricprocesslossModel = require('./MsFabricprocesslossModel');
require('./datagrid-filter.js');

class MsFabricprocesslossController {
	constructor(MsFabricprocesslossModel)
	{
		this.MsFabricprocesslossModel = MsFabricprocesslossModel;
		this.formId='fabricprocesslossFrm';
		this.dataTable='#fabricprocesslossTbl';
		this.route=msApp.baseUrl()+"/fabricprocessloss"
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
			this.MsFabricprocesslossModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsFabricprocesslossModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsFabricprocesslossModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsFabricprocesslossModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#fabricprocesslossTbl').datagrid('reload');
		$('#fabricprocesslossFrm  [name=id]').val(d.id);
		msApp.resetForm('fabricprocesslosspercentFrm');
	  $('#fabricprocesslosspercentFrm  [name=fabricprocessloss_id]').val(d.id);
		//msApp.resetForm('fabricprocesslossFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsFabricprocesslossModel.get(index,row);
		msApp.resetForm('fabricprocesslosspercentFrm');
	  $('#fabricprocesslosspercentFrm  [name=fabricprocessloss_id]').val(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsFabricprocessloss.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsFabricprocessloss=new MsFabricprocesslossController(new MsFabricprocesslossModel());
MsFabricprocessloss.showGrid();
