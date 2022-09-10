let MsOrderWiseMaterialCostModel = require('./MsOrderWiseMaterialCostModel');
require('./../../datagrid-filter.js');

class MsOrderWiseMaterialCostController {
	constructor(MsOrderWiseMaterialCostModel)
	{
		this.MsOrderWiseMaterialCostModel = MsOrderWiseMaterialCostModel;
		this.formId='orderwisematerialcostFrm';
		this.dataTable='#orderwisematerialcostTbl';
		this.route=msApp.baseUrl()+"/orderwisematerialcost/html";
	}

	getParams(){
		let params={};
		params.company_id = $('#orderwisematerialcostFrm  [name=company_id]').val();
		params.buyer_id = $('#orderwisematerialcostFrm  [name=buyer_id]').val();
		params.lc_sc_no = $('#orderwisematerialcostFrm  [name=lc_sc_no]').val();
		params.lc_sc_date_from = $('#orderwisematerialcostFrm  [name=lc_sc_date_from]').val();
		params.lc_sc_date_to = $('#orderwisematerialcostFrm  [name=lc_sc_date_to]').val();
		params.invoice_no = $('#orderwisematerialcostFrm  [name=invoice_no]').val();
		params.invoice_date_from = $('#orderwisematerialcostFrm  [name=invoice_date_from]').val();
		params.invoice_date_to = $('#orderwisematerialcostFrm  [name=invoice_date_to]').val();
		params.invoice_status_id = $('#orderwisematerialcostFrm  [name=invoice_status_id]').val();
		params.exporter_bank_branch_id = $('#orderwisematerialcostFrm  [name=exporter_bank_branch_id]').val();
		params.ex_factory_date_from = $('#orderwisematerialcostFrm  [name=ex_factory_date_from]').val();
		params.ex_factory_date_to = $('#orderwisematerialcostFrm  [name=ex_factory_date_to]').val();
		return params;
		//if(params.date_to=='' || params.date_to==0){
		//	alert('Select As on Date');
		//	return;
		//}
		
		
	}

    // getYarnCost(){
    //     let params=this.getParams();
    //     let d= axios.get(this.route,{params})
	// 	.then(function (response) {
    //         $('#orderwisematerialcostTbl').datagrid('loadData', response.data);
	// 	})
	// 	.catch(function (error) {
	// 		alert('vvvv')
	// 		console.log(error);
	// 	});
    // }

	
    showYarnCostExcel(table_id,file_name){
		let params=this.getParams();
		let ef= axios.get(msApp.baseUrl()+"/orderwisematerialcost/getyarn",{params})
		.then(function (response) {
			$('#orderwisematerialcostTbl').datagrid('loadData', response.data);
			$('#orderwisematerialcostTbl').datagrid('toExcel','Yarn Cost.xls');
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridYarnCost(data)
	{
		var dgy = $(this.dataTable);
		dgy.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tCiQty=0;
				var tCiAmount=0;
				var tCost=0;

				for(var i=0; i<data.rows.length; i++){
					tCiQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					tCiAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					tCost+=data.rows[i]['yarn_cost'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					invoice_qty: tCiQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					invoice_amount: tCiAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					yarn_cost: tCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});

		var yarnfilter=[
			{
				field:'issued_per',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			}
		];
		dgy.datagrid('enableFilter',yarnfilter).datagrid('loadData', data);
	}

    showFabricCostExcel(table_id,file_name){
		let params=this.getParams();
		let e= axios.get(msApp.baseUrl()+"/orderwisematerialcost/getfabric",{params})
		.then(function (response) {
			$('#orderwisematerialcostfabricWindow').window('open');
			$('#orderwisematerialcostFabricTbl').datagrid('loadData', response.data);
			$('#orderwisematerialcostFabricTbl').datagrid('toExcel','Fabric Cost.xls');
			//msApp.toExcel('#orderwisematerialcostFabricTbl','Fabric Cost.xls');
			
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridFabricCost(data)
	{
		var dgf = $('#orderwisematerialcostFabricTbl');
		dgf.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tCiQty=0;
				var tCiAmount=0;
				var tCost=0;

				for(var i=0; i<data.rows.length; i++){
					tCiQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					tCiAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					tCost+=data.rows[i]['fabric_cost'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					invoice_qty: tCiQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					invoice_amount: tCiAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					fabric_cost: tCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		var fabricfilter=[
			{
				field:'rcv_per',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			}
		];
		dgf.datagrid('enableFilter',fabricfilter).datagrid('loadData', data);
	}

	showKnittingCostExcel(table_id,file_name){
		let params=this.getParams();
		let e= axios.get(msApp.baseUrl()+"/orderwisematerialcost/getknitting",{params})
		.then(function (response) {
			$('#orderwisematerialcostknitWindow').window('open');
			$('#orderwisematerialcostknitTbl').datagrid('loadData', response.data);
			$('#orderwisematerialcostknitTbl').datagrid('toExcel','Knitting Cost.xls');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridKnittingCost(data)
	{
		var dgk = $('#orderwisematerialcostknitTbl');
		dgk.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tCiQty=0;
				var tCiAmount=0;
				var tCost=0;

				for(var i=0; i<data.rows.length; i++){
					tCiQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					tCiAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					tCost+=data.rows[i]['knitting_cost'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					invoice_qty: tCiQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					invoice_amount: tCiAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					knitting_cost: tCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		var knittingfilter=[
			{
				field:'knitting_per',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			}
		];
		dgk.datagrid('enableFilter',knittingfilter).datagrid('loadData', data);
	}

	showDyeingCostExcel(table_id,file_name){
		let params=this.getParams();
		let e= axios.get(msApp.baseUrl()+"/orderwisematerialcost/getdyeing",{params})
		.then(function (response) {
			$('#orderwisematerialcostdyeingWindow').window('open');
			$('#orderwisematerialcostdyeingTbl').datagrid('loadData', response.data);
			$('#orderwisematerialcostdyeingTbl').datagrid('toExcel','Dyeing Cost.xls');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridDyeingCost(data)
	{
		var dgd = $('#orderwisematerialcostdyeingTbl');
		dgd.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tCiQty=0;
				var tCiAmount=0;
				var tCost=0;

				for(var i=0; i<data.rows.length; i++){
					tCiQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					tCiAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					tCost+=data.rows[i]['dyeing_cost'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					invoice_qty: tCiQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					invoice_amount: tCiAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dyeing_cost: tCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		var dyeingfilter=[
			{
				field:'dyeing_per',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			}
		];
		dgd.datagrid('enableFilter',dyeingfilter).datagrid('loadData', data);
	}

	showAopCostExcel(table_id,file_name){
		let params=this.getParams();
		let e= axios.get(msApp.baseUrl()+"/orderwisematerialcost/getaop",{params})
		.then(function (response) {
			$('#orderwisematerialcostaopWindow').window('open');
			$('#orderwisematerialcostaopTbl').datagrid('loadData', response.data);
			$('#orderwisematerialcostaopTbl').datagrid('toExcel','AOP Cost.xls');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridAopCost(data)
	{
		var dga = $('#orderwisematerialcostaopTbl');
		dga.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tCiQty=0;
				var tCiAmount=0;
				var tCost=0;

				for(var i=0; i<data.rows.length; i++){
					tCiQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					tCiAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					tCost+=data.rows[i]['aop_cost'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					invoice_qty: tCiQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					invoice_amount: tCiAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					aop_cost: tCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		var aopfilter=[
			{
				field:'aop_per',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			}
		];
		dga.datagrid('enableFilter',aopfilter).datagrid('loadData', data);
	}

	showTrimsCostExcel(table_id,file_name){
		let params=this.getParams();
		let e= axios.get(msApp.baseUrl()+"/orderwisematerialcost/gettrims",{params})
		.then(function (response) {
			$('#orderwisematerialcosttrimsWindow').window('open');
			$('#orderwisematerialcosttrimsTbl').datagrid('loadData', response.data);
			$('#orderwisematerialcosttrimsTbl').datagrid('toExcel','Accessories Cost.xls');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridTrimsCost(data)
	{
		var dgt = $('#orderwisematerialcosttrimsTbl');
		dgt.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tCiQty=0;
				var tCiAmount=0;
				var tCost=0;

				for(var i=0; i<data.rows.length; i++){
					tCiQty+=data.rows[i]['invoice_qty'].replace(/,/g,'')*1;
					tCiAmount+=data.rows[i]['invoice_amount'].replace(/,/g,'')*1;
					tCost+=data.rows[i]['trims_cost'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					invoice_qty: tCiQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					invoice_amount: tCiAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					trims_cost: tCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		var trimsfilter=[
			{
				field:'rcv_trims_per',
				type:'textbox',
				op:['equal','notequal','less','lessorequal','greater','greaterorequal','between']
			}
		];
		dgt.datagrid('enableFilter',trimsfilter).datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	ordercipdf(exp_invoice_id){
		window.open(msApp.baseUrl()+"/expinvoice/orderwiseinvoice?id="+exp_invoice_id);
   	}

   	formatOrderCIPdf(value,row){
		return '<a href="javascript:void(0)" onClick="MsOrderWiseMaterialCost.ordercipdf('+row.exp_invoice_id+')">'+row.invoice_no+'</a>';
	}

	formatQty(value,row,index)
	{
		if (row.invoice_qty*1 >= row.order_qty*1){
				return 'color:green;';
		}
	}

	poDtlWindow(sales_order_id,menu_id){

		let e= axios.get(msApp.baseUrl()+"/orderwisematerialcost/getpurchaseorderdtl?sales_order_id="+sales_order_id+"&menu_id="+menu_id)
		.then(function (response) {
			if (menu_id==1 || menu_id==4 || menu_id==5 || menu_id==6) {
				$('#purchaseorderdtlWindow1').window('open');
				$('#purchaseorderdtlTbl1').datagrid('loadData', response.data);
			}else{
				$('#purchaseorderdtlWindow2').window('open');
				$('#purchaseorderdtlTbl2').datagrid('loadData', response.data);
			}
			
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridWo(data)
	{
		var dgwo = $('#purchaseorderdtlTbl1');
		dgwo.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tPoQty=0;
				var tPoAmount=0;
				var tPoAmountBdt=0;

				for(var i=0; i<data.rows.length; i++){
					tPoQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tPoAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					tPoAmountBdt+=data.rows[i]['po_amount_bdt'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					po_qty: tPoQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: tPoAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount_bdt: tPoAmountBdt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});

		dgwo.datagrid('enableFilter').datagrid('loadData', data);
	}

	showGridPo(data)
	{
		var dgpo = $('#purchaseorderdtlTbl2');
		dgpo.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tPoQty=0;
				var tPoAmount=0;
				var tPoAmountBdt=0;

				for(var i=0; i<data.rows.length; i++){
					tPoQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tPoAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					tPoAmountBdt+=data.rows[i]['po_amount_bdt'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					po_qty: tPoQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: tPoAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount_bdt: tPoAmountBdt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});

		dgpo.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatPoDtl(value,row){
		if (row.net_consumption) {
			return '<a href="javascript:void(0)" onClick="MsOrderWiseMaterialCost.poDtlWindow('+row.sales_order_id+','+'\''+row.menu_id+'\''+')">'+row.net_consumption+'</a>';
		}
		if (row.rcv_trims_amount) {
			return '<a href="javascript:void(0)" onClick="MsOrderWiseMaterialCost.poDtlWindow('+row.sales_order_id+','+'\''+row.menu_id+'\''+')">'+row.rcv_trims_amount+'</a>';
		}
	}


}
window.MsOrderWiseMaterialCost=new MsOrderWiseMaterialCostController(new MsOrderWiseMaterialCostModel());
MsOrderWiseMaterialCost.showGridYarnCost([]);
MsOrderWiseMaterialCost.showGridFabricCost([]);
MsOrderWiseMaterialCost.showGridKnittingCost([]);
MsOrderWiseMaterialCost.showGridDyeingCost([]);
MsOrderWiseMaterialCost.showGridAopCost([]);
MsOrderWiseMaterialCost.showGridTrimsCost([]);
MsOrderWiseMaterialCost.showGridPo([]);
MsOrderWiseMaterialCost.showGridWo([]);