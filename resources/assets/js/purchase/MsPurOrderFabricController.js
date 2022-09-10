//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurOrderFabricModel = require('./MsPurOrderFabricModel');
class MsPurOrderFabricController {
	constructor(MsPurOrderFabricModel)
	{
		this.MsPurOrderFabricModel = MsPurOrderFabricModel;
		this.formId='purorderfabricFrm';
		this.dataTable='#purorderfabricTbl';
		this.route=msApp.baseUrl()+"/purorderfabric"
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
			this.MsPurOrderFabricModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurOrderFabricModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurOrderFabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurOrderFabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#purorderfabricTbl').datagrid('reload');
		msApp.resetForm('purorderfabricFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurOrderFabricModel.get(index,row);
		MsPurFabric.get(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsPurOrderFabric.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPurOrderFabric=new MsPurOrderFabricController(new MsPurOrderFabricModel());
MsPurOrderFabric.showGrid();
$('#purorderfabricAccordion').accordion({
	onSelect:function(title,index){
		let purchase_order_id = $('#purorderfabricFrm  [name=id]').val();
		if(index==1){
			if(purchase_order_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#purorderfabricAccordion').accordion('unselect',1);
				$('#purorderfabricAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(purchase_order_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#purorderfabricAccordion').accordion('unselect',1);
				$('#purorderfabricAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(purchase_order_id)
			MsPurchaseTermsCondition.get();
		}
	}
})
