
require('./../datagrid-filter.js');
let MsPurchaseTermsConditionModel = require('./MsPurchaseTermsConditionModel');

class MsPurchaseTermsConditionController {
	constructor(MsPurchaseTermsConditionModel)
	{
		this.MsPurchaseTermsConditionModel = MsPurchaseTermsConditionModel;
		this.formId='purchasetermsconditionFrm';
		this.dataTable='#purchasetermsconditionTbl';
		this.route=msApp.baseUrl()+"/purchasetermscondition"
	}

	submit()
	{
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsPurchaseTermsConditionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurchaseTermsConditionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurchaseTermsConditionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurchaseTermsConditionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#purchasetermsconditionTbl').datagrid('reload');
		$('#purchasetermsconditionFrm  [name=id]').val(d.id);
		//$('#teammemberFrm  [name=team_id]').val(d.id);
		//msApp.resetForm('teamFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurchaseTermsConditionModel.get(index,row);
		$('#purchasetermsconditionFrm  [name=purchasetermscondition_id]').val(row.id);
		MsPurchaseTermsCondition.showGrid(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsPurchaseTermsCondition.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPurchaseTermsCondition=new MsPurchaseTermsConditionController(new MsPurchaseTermsConditionModel());
MsPurchaseTermsCondition.showGrid();
