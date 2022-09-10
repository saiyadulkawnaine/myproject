let MsCashIncentiveDocPrepModel = require('./MsCashIncentiveDocPrepModel');
class MsCashIncentiveDocPrepController {
	constructor(MsCashIncentiveDocPrepModel)
	{
		this.MsCashIncentiveDocPrepModel = MsCashIncentiveDocPrepModel;
		this.formId='cashincentivedocprepFrm';
		this.dataTable='#cashincentivedocprepTbl';
		this.route=msApp.baseUrl()+"/cashincentivedocprep"
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
			this.MsCashIncentiveDocPrepModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCashIncentiveDocPrepModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#cashincentivedocprepFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCashIncentiveDocPrepModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCashIncentiveDocPrepModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cashincentivedocprepTbl').datagrid('reload');
		//msApp.resetForm('cashincentivedocprepFrm');
		$('#cashincentivedocprepFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCashIncentiveDocPrepModel.get(index,row);
	}

	showGrid(cash_incentive_ref_id)
	{
		let self=this;
		var data={};
		data.cash_incentive_ref_id=cash_incentive_ref_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveDocPrep.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCashIncentiveDocPrep=new MsCashIncentiveDocPrepController(new MsCashIncentiveDocPrepModel());