//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPoFabricModel = require('./MsPoFabricModel');
class MsPoFabricController {
	constructor(MsPoFabricModel)
	{
		this.MsPoFabricModel = MsPoFabricModel;
		this.formId='pofabricFrm';
		this.dataTable='#pofabricTbl';
		this.route=msApp.baseUrl()+"/pofabric"
	}

	submit()
	{
		/*$.blockUI({
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
		});*/	
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsPoFabricModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoFabricModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#pofabricFrm [id="supplier_id"]').combobox('setValue','');
		$('#pofabricFrm [id="indentor_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoFabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoFabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#pofabricTbl').datagrid('reload');
		msApp.resetForm('pofabricFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		pYarn=this.MsPoFabricModel.get(index,row);
		pYarn.then(function (response) {
			MsPoFabricItem.get(row.id);	
			$('#pofabricFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#pofabricFrm [id="indentor_id"]').combobox('setValue', response.data.fromData.indentor_id);
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
			//fitColumns:true,
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['item_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}			
				
				$(this).datagrid('reloadFooter', [
					{ 
						item_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabric.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pospdf(){
		var id= $('#pofabricFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/getpospdf?id="+id);
	}

	podpdf(){
		var id= $('#pofabricFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/getpodpdf?id="+id);
	}
}
window.MsPoFabric=new MsPoFabricController(new MsPoFabricModel());
MsPoFabric.showGrid();

$('#pofabricAccordion').accordion({
	onSelect:function(title,index){
		let po_fabric_id = $('#pofabricFrm  [name=id]').val();
		if(index==1){
			if(po_fabric_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#pofabricAccordion').accordion('unselect',1);
				$('#pofabricAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(po_fabric_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#pofabricAccordion').accordion('unselect',1);
				$('#pofabricAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_fabric_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(1)
			MsPurchaseTermsCondition.get();
		}
	}
})

