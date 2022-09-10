//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPoFabricItemQtyModel = require('./MsPoFabricItemQtyModel');
class MsPoFabricItemQtyController {
	constructor(MsPoFabricItemQtyModel)
	{
		this.MsPoFabricItemQtyModel = MsPoFabricItemQtyModel;
		this.formId='pofabricitemqtyFrm';
		this.dataTable='#pofabricitemqtyTbl';
		this.route=msApp.baseUrl()+"/pofabricitemqty"
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
		});	*/
		let po_fabric_id = $('#pofabricFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.po_fabric_id=po_fabric_id;
		if(formObj.id){
			this.MsPoFabricItemQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoFabricItemQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoFabricItemQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoFabricItemQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#bulkfabricpurchaseTbl').datagrid('reload');
		//MsPurFabric.fabricSearchGrid({rows :{}})
		let po_fabric_id=$('#pofabricFrm  [name=id]').val()
		MsPoFabricItem.get(po_fabric_id);
		MsPoFabricItemQty.refreshQtyWindow(d.po_fabric_item_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoFabricItemQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#pofabricitemqtyTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			
		});
		dg.datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabricItemQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(po_fabric_item_id){
		let data= axios.get(msApp.baseUrl()+"/pofabricitemqty/create?po_fabric_item_id="+po_fabric_item_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#pofabricitemqtyWindow').window('open');
		})
	}
	
	openQtyWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/pofabricitemqty/create?po_fabric_item_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#pofabricitemqtyWindow').window('open');
		})
			
	}
	calculateAmount(iteration,count,field){
		let rate=$('#pofabricitemqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#pofabricitemqtyFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#pofabricitemqtyFrm input[name="amount['+iteration+']"]').val(amount)
		
	}
	
}
window.MsPoFabricItemQty=new MsPoFabricItemQtyController(new MsPoFabricItemQtyModel());


