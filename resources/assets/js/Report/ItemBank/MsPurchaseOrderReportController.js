let MsPurchaseOrderReportModel = require('./MsPurchaseOrderReportModel');
require('./../../datagrid-filter.js');

class MsPurchaseOrderReportController {
	constructor(MsPurchaseOrderReportModel)
	{
		this.MsPurchaseOrderReportModel = MsPurchaseOrderReportModel;
		this.formId='purchaseorderreportFrm';
		this.dataTable='#purchaseorderreportTbl';
		this.route=msApp.baseUrl()+"/purchaseorderreport/getdata"
	}

	getParams(){
		let params={};
		params.date_from = $('#purchaseorderreportFrm  [name=date_from]').val();
		params.date_to = $('#purchaseorderreportFrm  [name=date_to]').val();
		params.menu_id = $('#purchaseorderreportFrm  [name=menu_id]').val();
		params.company_id = $('#purchaseorderreportFrm  [name=company_id]').val();
		params.supplier_id = $('#purchaseorderreportFrm  [name=supplier_id]').val();
		params.itemcategory_id = $('#purchaseorderreportFrm  [name=itemcategory_id]').val();
		params.itemclass_id = $('#purchaseorderreportFrm  [name=itemclass_id]').val();
		params.buyer_id = $('#purchaseorderreportFrm  [name=buyer_id]').val();
		return params;
	}
	
	get(){
		let params=this.getParams();
      if(!params.menu_id){
			alert('Select A Menu Name First ');
			return;
		}

		$('#purchaseorderreportTbl').datagrid('loadData', []);
		$('#purchaseorderreportcategorywiseTbl').datagrid('loadData', []);
		$('#purchaseorderreportsupplierwiseTbl').datagrid('loadData', []);
		$('#purchaseorderreportpowiseTbl').datagrid('loadData', []);
		
		let d= axios.get(this.route,{params})
		.then(function (response) {

			$('#purchaseorderreportTbl').datagrid('loadData', response.data.maindata);
			$('#purchaseorderreportcategorywiseTbl').datagrid('loadData', response.data.categorydata);
			$('#purchaseorderreportsupplierwiseTbl').datagrid('loadData', response.data.supplierdata);
			$('#purchaseorderreportpowiseTbl').datagrid('loadData', response.data.podata);
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tRcvQty=0;
				var tBalanceQty=0;
				var tAmount=0;
				var tRcvAmount=0;
				var tBalanceAmount=0;
				var tAmountTaka=0;
				var tRcvAmountTaka=0;
				var tBalanceAmountTaka=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty_d'].replace(/,/g,'')*1;
					tRcvQty+=data.rows[i]['rcv_qty_d'].replace(/,/g,'')*1;
					tBalanceQty+=data.rows[i]['balance_qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['amount_d'].replace(/,/g,'')*1;
					tRcvAmount+=data.rows[i]['rcv_amount_d'].replace(/,/g,'')*1;
					tBalanceAmount+=data.rows[i]['balance_amount_d'].replace(/,/g,'')*1;
					tAmountTaka+=data.rows[i]['amount_taka'].replace(/,/g,'')*1;
					tRcvAmountTaka+=data.rows[i]['rcv_amount_taka'].replace(/,/g,'')*1;
					tBalanceAmountTaka+=data.rows[i]['balance_amount_taka'].replace(/,/g,'')*1;
				}
				$('#purchaseorderreportTbl').datagrid('reloadFooter', [
					{ 
						qty_d: tQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						rcv_qty_d: tRcvQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						balance_qty: tBalanceQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_d: tAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						rcv_amount_d: tRcvAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						balance_amount_d: tBalanceAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_taka: tAmountTaka.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						rcv_amount_taka: tRcvAmountTaka.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						balance_amount_taka: tBalanceAmountTaka.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}


	showGridCategory(data)
	{
		var dg = $('#purchaseorderreportcategorywiseTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var no_of_supplier=0;
				var no_of_po=0;
				var qty=0;
				var amount_taka=0;
				var po_usd=0;
				var po_taka=0;
				var po_oth=0;
				for(var i=0; i<data.rows.length; i++){
					no_of_po+=data.rows[i]['no_of_po'].replace(/,/g,'')*1;
					no_of_supplier+=data.rows[i]['no_of_supplier'].replace(/,/g,'')*1;
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount_taka+=data.rows[i]['amount_taka'].replace(/,/g,'')*1;
					po_usd+=data.rows[i]['po_usd'].replace(/,/g,'')*1;
					po_taka+=data.rows[i]['po_taka'].replace(/,/g,'')*1;
					po_oth+=data.rows[i]['po_taka'].replace(/,/g,'')*1;
				}			
				$('#purchaseorderreportcategorywiseTbl').datagrid('reloadFooter', [
					{ 
						no_of_po: no_of_po.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						no_of_supplier: no_of_supplier.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_taka: amount_taka.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_usd: po_usd.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_taka: po_taka.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_oth: po_taka.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	showGridSupplier(data)
	{
		var dg = $('#purchaseorderreportsupplierwiseTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			rowStyler:function(index,row)
			{
				if (row.supplier_name==='Sub Total'){
					return 'background-color:pink;color:black;font-weight:bold;';
				}
		    },
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				//var no_of_supplier=0;
				var no_of_po=0;
				var qty=0;
				var amount_taka=0;
				var po_usd=0;
				var po_taka=0;
				var po_oth=0;
				for(var i=0; i<data.rows.length; i++){
					if(data.rows[i]['supplier_name'] !=='Sub Total')
					{
						no_of_po+=data.rows[i]['no_of_po'].replace(/,/g,'')*1;
						//no_of_supplier+=data.rows[i]['no_of_supplier'].replace(/,/g,'')*1;
						qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						amount_taka+=data.rows[i]['amount_taka'].replace(/,/g,'')*1;
						po_usd+=data.rows[i]['po_usd'].replace(/,/g,'')*1;
						po_taka+=data.rows[i]['po_taka'].replace(/,/g,'')*1;
						po_oth+=data.rows[i]['po_oth'].replace(/,/g,'')*1;
					}
				}			
				$('#purchaseorderreportsupplierwiseTbl').datagrid('reloadFooter', [
					{ 
						no_of_po: no_of_po.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						//no_of_supplier: no_of_supplier.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_taka: amount_taka.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_usd: po_usd.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_taka: po_taka.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_oth: po_oth.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	showGridPo(data)
	{
		var dg = $('#purchaseorderreportpowiseTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount=0;
				var amount_taka=0;
				for(var i=0; i<data.rows.length; i++){
					
						qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
						amount_taka+=data.rows[i]['amount_taka'].replace(/,/g,'')*1;
				}			
				$('#purchaseorderreportpowiseTbl').datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount: amount.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_taka: amount_taka.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	rcvWindow(po_item_id){
		let params=this.getParams();
		params.po_item_id=po_item_id;
		let data= axios.get(msApp.baseUrl()+"/purchaseorderreport/getrcvno",{params})
		.then(function (response) {
		    $('#rcvdetailTbl').datagrid('loadData', response.data);
		    $('#rcvdetailWindow').window('open');	
		})
		.catch(function (error) {
			console.log(error);
        });
        return data;
	}

	showRcvNo(data){
		var rc = $('#rcvdetailTbl');
		rc.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tRate=0;
				var tAmount=0;
				var tStoreQty=0;
				var tStoreAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					tStoreQty+=data.rows[i]['store_qty'].replace(/,/g,'')*1;
					tStoreAmount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
				}
				tRate=tAmount/tQty;
				$('#rcvdetailTbl').datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						rate: tRate.toFixed(4).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount: tAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						store_qty: tStoreQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						store_amount: tStoreAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		rc.datagrid('enableFilter').datagrid('loadData', data);
	}
 
	formatRcvNo(value,row){
		return '<a href="javascript:void(0)" onClick="MsPurchaseOrderReport.rcvWindow('+'\''+row.po_item_id+'\''+')">'+row.rcv_qty_d+'</a>';
	}

	pdf(id){

		let menu_id=$('#purchaseorderreportFrm  [name=menu_id]').val();;

		if(menu_id==1){
			window.open(msApp.baseUrl()+"/pofabric/getpospdf?id="+id);
		}
		if(menu_id==2){
			window.open(msApp.baseUrl()+"/potrim/report?id="+id);
		}
		if(menu_id==3){
			window.open(msApp.baseUrl()+"/poyarn/report?id="+id);
		}
		if(menu_id==4){
			window.open(msApp.baseUrl()+"/poknitservice/report?id="+id);
		}
		if(menu_id==5){
			window.open(msApp.baseUrl()+"/poaopservice/report?id="+id);
		}
		if(menu_id==6){
			window.open(msApp.baseUrl()+"/podyeingservice/report?id="+id);
		}
		if(menu_id==7){
			window.open(msApp.baseUrl()+"/podyechem/report?id="+id);
		}
		if(menu_id==8){
			window.open(msApp.baseUrl()+"/pogeneral/report?id="+id);
		}
		if(menu_id==9){
			window.open(msApp.baseUrl()+"/poyarndyeing/report?id="+id);
		}
		if(menu_id==10){
			window.open(msApp.baseUrl()+"/poembservice/report?id="+id);
		}
	}

	formatpdf(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPurchaseOrderReport.pdf('+row.id+')">'+row.po_no+'</a>';
	}

}
window.MsPurchaseOrderReport = new MsPurchaseOrderReportController(new MsPurchaseOrderReportModel());
MsPurchaseOrderReport.showGrid([]);
MsPurchaseOrderReport.showGridCategory([]);
MsPurchaseOrderReport.showGridSupplier([]);
MsPurchaseOrderReport.showGridPo([]);
MsPurchaseOrderReport.showRcvNo([]);