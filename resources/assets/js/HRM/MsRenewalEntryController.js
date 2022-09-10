let MsRenewalEntryModel = require('./MsRenewalEntryModel');
require('./../datagrid-filter.js');
class MsRenewalEntryController {
	constructor(MsRenewalEntryModel)
	{
		this.MsRenewalEntryModel = MsRenewalEntryModel;
		this.formId='renewalentryFrm';
		this.dataTable='#renewalentryTbl';
		this.route=msApp.baseUrl()+"/renewalentry";
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
			this.MsRenewalEntryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsRenewalEntryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsRenewalEntryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsRenewalEntryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#renewalentryTbl').datagrid('reload');
		msApp.resetForm('renewalentryFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsRenewalEntryModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsRenewalEntry.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	/*pdf(id){
		var id=$('#renewalentryFrm [name=id]').val();
		if(id==''){
			alert("Select An Entry First");
			return;
		}
		window.open(this.route+"/getrenewalpdf?id="+id);
		//window.open(this.route+"/getrenewalpdf");
	}*/

}
window.MsRenewalEntry=new MsRenewalEntryController(new MsRenewalEntryModel());
MsRenewalEntry.showGrid();