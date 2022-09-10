//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurChemQtyModel = require('./MsPurChemQtyModel');

class MsPurChemQtyController {
	constructor(MsPurChemQtyModel)
	{
		this.MsPurChemQtyModel = MsPurChemQtyModel;
		this.formId='purchemqtyFrm';
		this.dataTable='#purchemsqtyTbl';
		this.route=msApp.baseUrl()+"/purchemqty"
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
			this.MsPurChemQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurChemQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurChemQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurChemQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#bulkchempurchaseTbl').datagrid('reload');
		//MsPurchem.chemSearchGrid({rows :{}})
		let purchase_order_id=$('#purorderchemFrm  [name=id]').val()
		MsPurChem.get(purchase_order_id);
		MsPurChemQty.refreshQtyWindow(d.pur_yarn_budget_id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurChemQtyModel.get(index,row);
	}
	

	showGrid(data)
	{
		var dg = $('#purchemsqtyTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPurChemQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	refreshQtyWindow(pur_yarn_budget_id){
		let data= axios.get(msApp.baseUrl()+"/puryarnqty/create?pur_yarn_budget_id="+pur_yarn_budget_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#purchemqtyWindow').window('open');
		})
	}
	
	openQtyWindow(pur_chem_budget_id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/puryarnqty/create?pur_yarn_budget_id="+pur_yarn_budget_id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#puryarnqtyWindow').window('open');
		})
			
	}
	calculateAmount(iteration,count,field){
		let rate=$('#puryarnqtyFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#puryarnqtyFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#puryarnqtyFrm input[name="amount['+iteration+']"]').val(amount)
		
	}
	
}
window.MsPurChemQty=new MsPurChemQtyController(new MsPurChemQtyModel());


