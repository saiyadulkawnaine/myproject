let MsBudgetTrimDtmModel = require('./MsBudgetTrimDtmModel');
class MsBudgetTrimDtmController {
	constructor(MsBudgetTrimDtmModel)
	{
		this.MsBudgetTrimDtmModel = MsBudgetTrimDtmModel;
		this.formId='budgettrimdtmFrm';
		this.dataTable='#budgettrimdtmTbl';
		this.route=msApp.baseUrl()+"/budgettrimdtm"
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
			this.MsBudgetTrimDtmModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetTrimDtmModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetTrimDtmModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetTrimDtmModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#budgettrimdtmTbl').datagrid('reload');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetTrimDtmModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrimDtm.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsBudgetTrimDtm=new MsBudgetTrimDtmController(new MsBudgetTrimDtmModel());
//MsBudgetTrimDtm.showGrid();
