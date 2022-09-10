//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPurYarnModel = require('./MsPurYarnModel');
class MsPurYarnController {
	constructor(MsPurYarnModel)
	{
		this.MsPurYarnModel = MsPurYarnModel;
		this.formId='puryarnFrm';
		this.dataTable='#puryarnTbl';
		this.route=msApp.baseUrl()+"/puryarn"
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
			this.MsPurYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let purchase_order_id=$('#purorderyarnFrm  [name=id]').val();
		let formObj=msApp.get('puryarnitemFrm');
		formObj.purchase_order_id=purchase_order_id;
		this.MsPurYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}

	/*submitAndClose()
	{
		let formObj=this.getSelections();
		if(formObj.id){
			this.MsPurYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPurYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
			$('#importyarnWindow').window('close');
			$('#budgetyarnsearchTbl').datagrid('loadData', []);
		}
	}*/

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPurYarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPurYarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsPurYarn.yarnSearchGrid({rows :{}})
		let purchase_order_id=$('#purorderyarnFrm  [name=id]').val()
		MsPurYarn.get(purchase_order_id);
		
	}

	edit(index,row)
	{
		//if(row.basis_id==20){
		row.route=this.route;
		row.formId=this.formId;
		this.MsPurYarnModel.get(index,row);	
		//}
		if(row.basis_id==1){
			MsPurYarnQty.openQtyWindow(row.pur_yarn_budget_id);
			$('#puryarnFrm  [name=rate]').prop("readonly",true);
		    $('#puryarnFrm  [name=qty]').prop("readonly",true);
		}
		else{

			//$("#descrip").prop("readonly",true);
			$('#puryarnFrm  [name=rate]').prop("readonly",false);
		    $('#puryarnFrm  [name=qty]').prop("readonly",false);
		}
		
	}
	get(purchase_order_id){
		let data= axios.get(this.route+"?purchase_order_id="+purchase_order_id)
		.then(function (response) {
			$('#puryarnTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	showGrid(data)
	{
		let self=this;
		var dg = $('#puryarnTbl');
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
		return '<a href="javascript:void(0)"  onClick="MsPurYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	formatQty(value,row)
	{
		//if(row.id && row.basis_id==1){
		return '<a href="javascript:void(0)"  onClick="MsPurYarnQty.openQtyWindow('+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		//}
	}
	openConsWindow(id)
	{
		$('#budgetyarnsearchTbl').datagrid('loadData', []);
		let company_id=$('#purorderyarnFrm  [name=company_id]').val();
		$('#budgetyarnsearchFrm  [name=company_id]').val(company_id);
		$('#importyarnWindow').window('open');
		//$('#budgetyarnsearchTbl').datagrid();
		//MsPurYarn.yarnSearchGrid({rows :{}})
	}
	searchYarn(){
		let company_id=$('#budgetyarnsearchFrm  [name=company_id]').val();
		let budget_id=$('#budgetyarnsearchFrm  [name=budget_id]').val();
		let job_no=$('#budgetyarnsearchFrm  [name=job_no]').val();
		let purchase_order_id=$('#purorderyarnFrm  [name=id]').val();
		let data= axios.get(this.route+"/importyarn"+"?company_id="+company_id+"&budget_id="+budget_id+"&job_no="+job_no+"&purchase_order_id="+purchase_order_id)
		.then(function (response) {
			$('#budgetyarnsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	yarnSearchGrid(data)
	{
		var dg = $('#budgetyarnsearchTbl');
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
		formObj.purchase_order_id=$('#purorderyarnFrm  [name=id]').val();
		let i=1;
		$.each($('#budgetyarnsearchTbl').datagrid('getSelections'), function (idx, val) {
			formObj['budget_yarn_id['+i+']']=val.id;
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
		let purchase_order_id=$('#purorderyarnFrm  [name=id]').val();
		let budget_yarn_id=[];
		let name=[];
		let checked=$('#budgetyarnsearchTbl').datagrid('getSelections');

		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				budget_yarn_id.push(val.id)
		});
		budget_yarn_id=budget_yarn_id.join(',');
		$('#importyarnWindow').window('close');

		let data= axios.get(this.route+"/create"+"?budget_yarn_id="+budget_yarn_id+'&purchase_order_id='+purchase_order_id)
		.then(function (response) {
			$('#puryarnitemscs').html(response.data);
			$('#puryarnitemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculateAmount(iteration,count,field){
		let rate=$('#puryarnitemFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#puryarnitemFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#puryarnitemFrm input[name="amount['+iteration+']"]').val(amount)
		
	}

	calculate(){

		let rate=$('#puryarnFrm  [name=rate]').val();
		let qty=$('#puryarnFrm  [name=qty]').val();
		let amount=msApp.multiply(qty,rate);
		$('#puryarnFrm  [name=amount]').val(amount);
		
	}
}
window.MsPurYarn=new MsPurYarnController(new MsPurYarnModel());
$('#budgetyarnsearchTbl').datagrid();
MsPurYarn.yarnSearchGrid({rows:{}});
$('#puryarnTbl').datagrid();
MsPurYarn.showGrid({rows :{}});
