//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurFabricQtyModel = require('./MsPurFabricQtyModel');
class MsPurFabricQtyController {
	constructor(MsPurFabricQtyModel)
	{
		this.MsPurFabricQtyModel = MsPurFabricQtyModel;
		this.formId='purfabricqtyFrm';
		this.dataTable='#purfabricsqtyTbl';
		this.route=msApp.baseUrl()+"/purfabricqty"
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
			this.MsPurFabricQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurFabricQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurFabricQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurFabricQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#bulkfabricpurchaseTbl').datagrid('reload');
		//MsPurFabric.fabricSearchGrid({rows :{}})
		let purchase_order_id=$('#purorderfabricFrm  [name=id]').val()
		MsPurFabric.get(purchase_order_id);
		MsPurFabricQty.refreshQtyWindow(d.pur_fabric_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurFabricQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#purfabricsqtyTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPurFabricQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(pur_fabric_id){
		let data= axios.get(msApp.baseUrl()+"/purfabricqty/create?pur_fabric_id="+pur_fabric_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#purfabricqtyWindow').window('open');
		})
	}
	
	openQtyWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/purfabricqty/create?pur_fabric_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#purfabricqtyWindow').window('open');
		})
			
	}
	calculateAmount(iteration,count,field){
		let rate=$('#purfabricqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#purfabricqtyFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#purfabricqtyFrm input[name="amount['+iteration+']"]').val(amount)
		
	}
	
}
window.MsPurFabricQty=new MsPurFabricQtyController(new MsPurFabricQtyModel());


