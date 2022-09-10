let MsRenewalItemDocModel = require('./MsRenewalItemDocModel');
class MsRenewalItemDocController {
	constructor(MsRenewalItemDocModel)
	{
		this.MsRenewalItemDocModel = MsRenewalItemDocModel;
		this.formId='renewalitemdocFrm';
		this.dataTable='#renewalitemdocTbl';
		this.route=msApp.baseUrl()+"/renewalitemdoc";
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
			this.MsRenewalItemDocModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsRenewalItemDocModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#renewalitemdocFrm [name=renewal_item_id]').val($('#renewalitemFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsRenewalItemDocModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsRenewalItemDocModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#renewalitemdocTbl').datagrid('reload');
		msApp.resetForm('renewalitemdocFrm');
		$('#renewalitemdocFrm [name=renewal_item_id]').val($('#renewalitemFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsRenewalItemDocModel.get(index,row);
	}

	showGrid(renewal_item_id)
	{
		let self=this;
		let data={};
		data.renewal_item_id=renewal_item_id;
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
		return '<a href="javascript:void(0)"  onClick="MsRenewalItemDoc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsRenewalItemDoc=new MsRenewalItemDocController(new MsRenewalItemDocModel());
