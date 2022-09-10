//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurOrderChemModel = require('./MsPurOrderChemModel');
class MsPurOrderChemController {
	constructor(MsPurOrderChemModel)
	{
		this.MsPurOrderChemModel = MsPurOrderChemModel;
		this.formId='purorderchemFrm';
		this.dataTable='#purorderchemTbl';
		this.route=msApp.baseUrl()+"/purorderchem"
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
			this.MsPurOrderChemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurOrderChemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurOrderChemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurOrderChemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#purorderchemTbl').datagrid('reload');
		//$('#BulkFabricPurchaseFrm  [name=id]').val(d.id);
		msApp.resetForm('purorderchemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurOrderChemModel.get(index,row);
		MsPurChem.get(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsPurOrderChem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPurOrderChem=new MsPurOrderChemController(new MsPurOrderChemModel());
MsPurOrderChem.showGrid();
$('#purorderchemAccordion').accordion({
	onSelect:function(title,index){
		let purchase_order_id = $('#purorderchemFrm  [name=id]').val();
		if(index==1){
			if(purchase_order_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#purorderchemAccordion').accordion('unselect',1);
				$('#purorderchemAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(purchase_order_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#purorderchemAccordion').accordion('unselect',1);
				$('#purorderchemAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(purchase_order_id)
			MsPurchaseTermsCondition.get();
		}
	}
})
