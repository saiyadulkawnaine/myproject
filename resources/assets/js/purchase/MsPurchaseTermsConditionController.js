
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
		MsPurchaseTermsCondition.get();
		$('#purchasetermsconditionFrm  [name=id]').val('');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurchaseTermsConditionModel.get(index,row);
	}

	get(){
		var purchase_order_id=$('#purchasetermsconditionFrm  [name=purchase_order_id]').val();
		var menu_id=$('#purchasetermsconditionFrm  [name=menu_id]').val();
		let params={};
		params.purchase_order_id=purchase_order_id;
		params.menu_id=menu_id;
		let data= axios.get(this.route,{params})
		.then(function (response) {
			MsPurchaseTermsCondition.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			data:data,
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
//MsPurchaseTermsCondition.get();
