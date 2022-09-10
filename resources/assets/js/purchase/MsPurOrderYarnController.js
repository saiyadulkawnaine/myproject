//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurOrderYarnModel = require('./MsPurOrderYarnModel');
class MsPurOrderYarnController {
	constructor(MsPurOrderYarnModel)
	{
		this.MsPurOrderYarnModel = MsPurOrderYarnModel;
		this.formId='purorderyarnFrm';
		this.dataTable='#purorderyarnTbl';
		this.route=msApp.baseUrl()+"/purorderyarn"
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
			this.MsPurOrderYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurOrderYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#purorderyarnFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurOrderYarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurOrderYarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#purorderyarnTbl').datagrid('reload');
		//$('#BulkFabricPurchaseFrm  [name=id]').val(d.id);
		msApp.resetForm('purorderyarnFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		
		let yarn=this.MsPurOrderYarnModel.get(index,row);
		MsPurYarn.get(row.id);
		yarn.then(function (response) {	
	
			$('#purorderyarnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
		.catch(function (error) {
			console.log(error);
		});
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
		return '<a href="javascript:void(0)"  onClick="MsBulkFabricPurchase.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPurOrderYarn=new MsPurOrderYarnController(new MsPurOrderYarnModel());
MsPurOrderYarn.showGrid();
$('#purorderyarnAccordion').accordion({
	onSelect:function(title,index){
		let purchase_order_id = $('#purorderyarnFrm  [name=id]').val();
		if(index==1){
			if(purchase_order_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#purorderyarnAccordion').accordion('unselect',1);
				$('#purorderyarnAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(purchase_order_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#purorderyarnAccordion').accordion('unselect',1);
				$('#purorderyarnAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(purchase_order_id)
			MsPurchaseTermsCondition.get();
		}
	}
})
