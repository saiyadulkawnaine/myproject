//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurOrderTrimModel = require('./MsPurOrderTrimModel');
class MsPurOrderTrimController {
	constructor(MsPurOrderTrimModel)
	{
		this.MsPurOrderTrimModel = MsPurOrderTrimModel;
		this.formId='purordertrimFrm';
		this.dataTable='#purordertrimTbl';
		this.route=msApp.baseUrl()+"/purordertrim"
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
			this.MsPurOrderTrimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurOrderTrimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurOrderTrimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurOrderTrimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#purordertrimTbl').datagrid('reload');
		//$('#BulkTrimPurchaseFrm  [name=id]').val(d.id);
		msApp.resetForm('purordertrimFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurOrderTrimModel.get(index,row);
		MsPurTrim.get(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsPurOrderTrim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf(){
		var id= $('#purordertrimFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
}
window.MsPurOrderTrim=new MsPurOrderTrimController(new MsPurOrderTrimModel());
MsPurOrderTrim.showGrid();

$('#purordertrimAccordion').accordion({
	onSelect:function(title,index){
		let purchase_order_id = $('#purordertrimFrm  [name=id]').val();
		if(index==1){
			if(purchase_order_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#purordertrimAccordion').accordion('unselect',1);
				$('#purordertrimAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(purchase_order_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#purordertrimAccordion').accordion('unselect',1);
				$('#purordertrimAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(purchase_order_id)
			MsPurchaseTermsCondition.get();
		}
	}
})

