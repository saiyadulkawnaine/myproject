require('./../datagrid-filter.js');
let MsPurChemModel = require('./MsPurChemModel');
class MsPurChemController {
	constructor(MsPurChemModel)
	{
		this.MsPurChemModel = MsPurChemModel;
		this.formId='purchemFrm';
		this.dataTable='#purchemTbl';
		this.route=msApp.baseUrl()+"/purchem"
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
			this.MsPurChemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurChemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	submitBatch()
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
		/*let exp_pi_id=$('#exppiFrm  [name=id]').val();
		let formObj=msApp.get('exptagorderFrm');
		formObj.exp_pi_id=exp_pi_id;
		this.MsExpPiOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);*/
		let purchase_order_id=$('#purorderchemFrm  [name=id]').val();
		let formObj=msApp.get('purchemitemFrm');
		formObj.purchase_order_id=purchase_order_id;
		this.MsPurChemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}

	/*submitAndClose()
	{
		let formObj=this.getSelections();
		if(formObj.id){
			this.MsPurChemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurChemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
			$('#importchemWindow').window('close');
			$('#budgetchemsearchTbl').datagrid('loadData', []);
		}
	}*/

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurChemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurChemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsPurChem.chemSearchGrid({rows :{}})
		let purchase_order_id=$('#purorderchemFrm  [name=id]').val()
		MsPurChem.get(purchase_order_id);
		
	}

	edit(index,row)
	{
		//if(row.basis_id==20){
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurChemModel.get(index,row);	
		//}
		if(row.basis_id==1){
			MsPurChemQty.openQtyWindow(row.pur_chem_budget_id);
			$('#purchemFrm  [name=rate]').prop("readonly",true);
		    $('#purchemFrm  [name=qty]').prop("readonly",true);
		}
		else{

			//$("#descrip").prop("readonly",true);
			$('#purchemFrm  [name=rate]').prop("readonly",false);
		    $('#purchemFrm  [name=qty]').prop("readonly",false);
		}
		
	}
	get(purchase_order_id){
		let data= axios.get(this.route+"?purchase_order_id="+purchase_order_id)
		.then(function (response) {
			$('#purchemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	showGrid(data)
	{
		let self=this;
		var dg = $('#purchemTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
		dg.datagrid('loadData', data);
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPurChem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		if(row.id && row.basis_id==1){
		return '<a href="javascript:void(0)"  onClick="MsPurChemQty.openQtyWindow('+row.pur_chem_budget_id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}
	openConsWindow(id)
	{
		//$('#budgetchemsearchTbl').datagrid('loadData', []);
		let company_id=$('#purorderchemFrm  [name=company_id]').val();
		$('#budgetchemsearchFrm  [name=company_id]').val(company_id);
		$('#importchemWindow').window('open');
		//$('#budgetchemsearchTbl').datagrid();
		//MsPurChem.chemSearchGrid({rows :{}})
	}
	searchChem(){
		let company_id=$('#budgetchemsearchFrm  [name=company_id]').val();
		let budget_id=$('#budgetchemsearchFrm  [name=budget_id]').val();
		let job_no=$('#budgetchemsearchFrm  [name=job_no]').val();
		let purchase_order_id=$('#purorderchemFrm  [name=id]').val();
		let data= axios.get(this.route+"/importchem"+"?company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&purchase_order_id="+purchase_order_id)
		.then(function (response) {
			$('#budgetchemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	chemSearchGrid(data)
	{
		var dg = $('#budgetchemsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
		//dg.datagrid('loadData', data);

	}
	/*getSelections()
	{
		let formObj={};
		formObj.purchase_order_id=$('#purorderchemFrm  [name=id]').val();
		let i=1;
		$.each($('#budgetchemsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['budget_chem_id['+i+']']=val.id;
			formObj['item_account_id['+i+']']=val.item_account_id;
			formObj['qty['+i+']']=val.qty;
			formObj['rate['+i+']']=val.rate;
			formObj['amount['+i+']']=val.amount;
			i++;
		});
		return formObj;
	}*/

	closeConsWindow()
	{
		let purchase_order_id=$('#purorderchemFrm  [name=id]').val();
		let budget_chem_id=[];
		let name=[];
		let checked=$('#budgetchemsearchTbl').datagrid('getSelections');

		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				budget_chem_id.push(val.id)
		});
		budget_chem_id=budget_chem_id.join(',');
		$('#importchemWindow').window('close');

		let data= axios.get(this.route+"/create"+"?budget_chem_id="+budget_chem_id+'&purchase_order_id='+purchase_order_id)
		.then(function (response) {
			$('#purchemitemscs').html(response.data);
			$('#purchemitemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculateAmount(iteration,count,field){
		let rate=$('#purchemitemFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#purchemitemFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#purchemitemFrm input[name="amount['+iteration+']"]').val(amount)
		
	}

	calculate(){

		let rate=$('#purchemFrm  [name=rate]').val();
		let qty=$('#purchemFrm  [name=qty]').val();
		let amount=msApp.multiply(qty,rate);
		$('#purchemFrm  [name=amount]').val(amount);
		
	}
}
window.MsPurChem=new MsPurChemController(new MsPurChemModel());
$('#budgetchemsearchTbl').datagrid();
MsPurChem.chemSearchGrid({rows:{}});
$('#purchemTbl').datagrid();
MsPurChem.showGrid({rows :{}});
