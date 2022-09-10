//require('./../jquery.easyui.min.js');
require('./../datagrid-filter.js');
let MsPoYarnItemModel = require('./MsPoYarnItemModel');
class MsPoYarnItemController {
	constructor(MsPoYarnItemModel)
	{
		this.MsPoYarnItemModel = MsPoYarnItemModel;
		this.formId='poyarnitemFrm';
		this.dataTable='#poyarnitemTbl';
		this.route=msApp.baseUrl()+"/poyarnitem"
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
			this.MsPoYarnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoYarnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let po_yarn_id=$('#poyarnFrm  [name=id]').val();
		let formObj=msApp.get('poyarnitemgridFrm');
		formObj.po_yarn_id=po_yarn_id;
		this.MsPoYarnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoYarnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoYarnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//MsPoYarnItem.yarnSearchGrid({rows :{}})
		let po_yarn_id=$('#poyarnFrm  [name=id]').val()
		MsPoYarnItem.get(po_yarn_id);
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoYarnItemModel.get(index,row);	
		/*if(row.basis_id==1){
			MsPoYarnItemBomQty.openQtyWindow(row.pur_yarn_budget_id);
			$('#poyarnitemFrm  [name=rate]').prop("readonly",true);
		    $('#poyarnitemFrm  [name=qty]').prop("readonly",true);
		}
		else{
			$('#poyarnitemFrm  [name=rate]').prop("readonly",false);
		    $('#poyarnitemFrm  [name=qty]').prop("readonly",false);
		}*/
		
	}
	get(po_yarn_id){
		let data= axios.get(this.route+"?po_yarn_id="+po_yarn_id)
		.then(function (response) {
			$('#poyarnitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		var dg = $('#poyarnitemTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			showFooter: 'true',
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				var tRate=0;
				
				if(tQty){
				   tRate=(tAmout/tQty);	
				}
				
				
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		});
		dg.datagrid('loadData', data);
	}

	deleteButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	formatQty(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnItemBomQty.openpoyarnitembomqtyWindow('+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
	}

	openConsWindow(id)
	{
		//$('#poyarnitemsearchTbl').datagrid('loadData', []);
		let company_id=$('#poyarnFrm  [name=company_id]').val();
		$('#poyarnitemsearchFrm  [name=company_id]').val(company_id);
		$('#poyarnitemimportWindow').window('open');
	}

	searchYarn(){
		let yarn_count=$('#poyarnitemsearchFrm  [name=yarn_count]').val();
		let yarn_type=$('#poyarnitemsearchFrm  [name=yarn_type]').val();
		let po_yarn_id=$('#poyarnFrm  [name=id]').val();
		let data= axios.get(this.route+"/importyarn"+"?yarn_count="+yarn_count+"&yarn_type="+yarn_type+"&po_yarn_id="+po_yarn_id)
		.then(function (response) {
			$('#poyarnitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	yarnSearchGrid(data)
	{
		var dg = $('#poyarnitemsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	poyarnorderSearchGrid(data)
	{
		let self=this;
		var dg = $('#poyarnordersearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.add(row);
			},
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	 add(row) {
	 	$('#poyarnorderselectedTbl').datagrid('appendRow',row);
	 }
	 remove(index)
	 {
	 	$('#poyarnorderselectedTbl').datagrid('deleteRow', index);
	 }

	poyarnorderselectedGrid(data)
	{
		let self=this;
		var dg = $('#poyarnorderselectedTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.remove(index);
			},
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	

	closeOrderWindow()
	{
		let po_yarn_item_id=$('#poyarnitemFrm  [name=id]').val();
		let sales_order_id=[];
		let budget_yarn_id=[];
		//let checked=$('#poyarnordersearchTbl').datagrid('getSelections');
		let checked=$('#poyarnorderselectedTbl').datagrid('getRows');

		if(checked.lenght > 100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			sales_order_id.push(val.sale_order_id);
			budget_yarn_id.push(val.budget_yarn_id);
		});
		sales_order_id=sales_order_id.join(',');
		budget_yarn_id=budget_yarn_id.join(',');
		$('#poyarnordersearchTbl').datagrid('clearSelections');
		MsPoYarnItem.poyarnorderselectedGrid([]);
		$('#poyarnordersearchWindow').window('close');
		MsPoYarnItemBomQty.openQtyWindow(po_yarn_item_id,sales_order_id,budget_yarn_id);
	}
	
	closeConsWindow()
	{
		let po_yarn_id=$('#poyarnFrm  [name=id]').val();
		let item_account_id=[];
		let name=[];
		let checked=$('#poyarnitemsearchTbl').datagrid('getSelections');

		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			item_account_id.push(val.id)
		});
		item_account_id=item_account_id.join(',');
		$('#poyarnitemsearchTbl').datagrid('clearSelections');
		$('#poyarnitemimportWindow').window('close');

		let data= axios.get(this.route+"/create"+"?item_account_id="+item_account_id+'&po_yarn_id='+po_yarn_id)
		.then(function (response) {
			$('#poyarnitemscs').html(response.data);
			$('#poyarnitemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculateAmount(iteration,count,field)
	{
		let rate=$('#poyarnitemgridFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#poyarnitemgridFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#poyarnitemgridFrm input[name="amount['+iteration+']"]').val(amount)
	}

	calculate()
	{
		let rate=$('#poyarnitemFrm  [name=rate]').val();
		let qty=$('#poyarnitemFrm  [name=qty]').val();
		let amount=msApp.multiply(qty,rate);
		$('#poyarnitemFrm  [name=amount]').val(amount);
	}
}
window.MsPoYarnItem=new MsPoYarnItemController(new MsPoYarnItemModel());
MsPoYarnItem.yarnSearchGrid([]);
$('#poyarnitemTbl').datagrid();
MsPoYarnItem.showGrid([]);
MsPoYarnItem.poyarnorderSearchGrid([]);
MsPoYarnItem.poyarnorderselectedGrid([]);