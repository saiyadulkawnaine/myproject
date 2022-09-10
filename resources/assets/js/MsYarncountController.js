//require('./jquery.easyui.min.js');
let MsYarncountModel = require('./MsYarncountModel');
require('./datagrid-filter.js');

class MsYarncountController {
	constructor(MsYarncountModel)
	{
		this.MsYarncountModel = MsYarncountModel;
		this.formId='yarncountFrm';
		this.dataTable='#yarncountTbl';
		this.route=msApp.baseUrl()+"/yarncount"
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
			this.MsYarncountModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsYarncountModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsYarncountModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsYarncountModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#yarncountTbl').datagrid('reload');
		//$('#YarncountFrm  [name=id]').val(d.id);
		msApp.resetForm('yarncountFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsYarncountModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsYarncount.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsYarncount=new MsYarncountController(new MsYarncountModel());
MsYarncount.showGrid();
