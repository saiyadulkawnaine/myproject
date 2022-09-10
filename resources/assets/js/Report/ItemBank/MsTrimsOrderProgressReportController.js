let MsTrimsOrderProgressReportModel = require('./MsTrimsOrderProgressReportModel');
require('./../../datagrid-filter.js');

class MsTrimsOrderProgressReportController {
	constructor(MsTrimsOrderProgressReportModel)
	{
		this.MsTrimsOrderProgressReportModel = MsTrimsOrderProgressReportModel;
		this.formId='trimsorderprogressreportFrm';
		this.dataTable='#trimsorderprogressreportTbl';
		this.route=msApp.baseUrl()+"/trimsorderprogressreport/getdata"
	}

    getParams(){
        let params={};
		params.company_id = $('#trimsorderprogressreportFrm  [name=company_id]').val();
		params.produced_company_id = $('#trimsorderprogressreportFrm  [name=produced_company_id]').val();
		params.buyer_id = $('#trimsorderprogressreportFrm  [name=buyer_id]').val();
		params.style_ref = $('#trimsorderprogressreportFrm  [name=style_ref]').val();
		params.style_id = $('#trimsorderprogressreportFrm  [name=style_id]').val();
		params.factory_merchant_id = $('#trimsorderprogressreportFrm  [name=factory_merchant_id]').val();
		params.date_from = $('#trimsorderprogressreportFrm  [name=date_from]').val();
		params.date_to = $('#trimsorderprogressreportFrm  [name=date_to]').val();
		params.order_status = $('#trimsorderprogressreportFrm  [name=order_status]').val();
		params.receive_date_from = $('#trimsorderprogressreportFrm  [name=receive_date_from]').val();
		params.receive_date_to = $('#trimsorderprogressreportFrm  [name=receive_date_to]').val();
		params.sort_by = $('#trimsorderprogressreportFrm  [name=sort_by]').val();
        return params;
    }
	
	get(){
		let params=this.getParams();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#trimsorderprogressreportTbl').datagrid('loadData', response.data);
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
			//fitColumns:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tBomQty=0;
				var tBomRate=0;
				var tBomAmount=0;
				var tQty=0;
				var tRate=0;
				var tAmount=0;
                var tBalQty=0;
                var tBalAmount=0;
                var tRcvQty=0;
                var tRcvAmount=0;
                var tBalRcvQty=0;
                var tBalRcvAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tBomQty+=data.rows[i]['bom_qty'].replace(/,/g,'')*1;
					tBomAmount+=data.rows[i]['bom_amount'].replace(/,/g,'')*1;
					tQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
					tBalQty+=data.rows[i]['bal_po_qty'].replace(/,/g,'')*1;
					tBalAmount+=data.rows[i]['bal_po_amount'].replace(/,/g,'')*1;
					tRcvQty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					tRcvAmount+=data.rows[i]['rcv_amount'].replace(/,/g,'')*1;
					tBalRcvQty+=data.rows[i]['bal_rcv_qty'].replace(/,/g,'')*1;
					tBalRcvAmount+=data.rows[i]['bal_rcv_amount'].replace(/,/g,'')*1;
				}
				tBomRate=tBomAmount/tBomQty;
				tRate=tAmount/tQty;
				$('#trimsorderprogressreportTbl').datagrid('reloadFooter', [
					{ 
						bom_qty: tBomQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						bom_rate: tBomRate.toFixed(4).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						bom_amount: tBomAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_qty: tQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_rate: tRate.toFixed(4).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						po_amount: tAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
                        bal_po_qty: tBalQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
                        bal_po_amount: tBalAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
                        rcv_qty: tRcvQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
                        rcv_amount: tRcvAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
                        bal_rcv_qty: tBalRcvQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
                        bal_rcv_amount: tBalRcvAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			},
			rowStyler:function(index,row){
				if (row.rcv_qty>=row.bom_qty && row.bom_qty!=0){
				return 'background-color: #90EE90;font-weight:bold;';//color:blue;
				}
			},
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	openTrimsReportStyleWindow(){
		$('#trimsreportstyleWindow').window('open');
	}

	getTrimsReportStyleParams(){
		let params={};
		params.buyer_id = $('#trimsreportstylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#trimsreportstylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#trimsreportstylesearchFrm  [name=style_description]').val();
		return params;
	}

	searchTrimsReportStyle(){
		let params=this.getTrimsReportStyleParams();
		let d= axios.get(msApp.baseUrl()+"/trimsorderprogressreport/gettrimsstyle",{params})
		.then(function(response){
			$('#trimsreportstylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showTrimsReportStyleGrid(data){
		let self=this;
		$('#trimsreportstylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#trimsorderprogressreportFrm [name=style_ref]').val(row.style_ref);
				$('#trimsorderprogressreportFrm [name=style_id]').val(row.id);
				$('#trimsreportstyleWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	openTrimsReportTeammemberDlmWindow(){
		$('#trimsreportteammemberDlmWindow').window('open');
	}
	getTdlmParams(){
		let params={};
		params.team_id = $('#trimsreportteammemberdlmFrm  [name=team_id]').val();
		return params;
	}
	searchTrimsTeammemberDlmGrid(){
		let params=this.getTdlmParams();
		let dlm= axios.get(msApp.baseUrl()+"/trimsorderprogressreport/gettrimsteammemberdlm",{params})
		.then(function(response){
			$('#trimsreportteammemberdlmTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showTrimsTeammemberDlmGrid(data){
		let self=this;
		$('#trimsreportteammemberdlmTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#trimsorderprogressreportFrm [name=factory_merchant_id]').val(row.factory_merchant_id);
				$('#trimsorderprogressreportFrm [name=team_member_name]').val(row.dlm_name);
				$('#trimsreportteammemberDlmWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	potrimqtyWindow(sales_order_id,itemclass_id)
	{
		let params=this.getParams();
		params.sales_order_id=sales_order_id;
		params.itemclass_id=itemclass_id;
		let data= axios.get(msApp.baseUrl()+"/trimsorderprogressreport/getpotrimqty",{params});
		data.then(function (response) {
			$('#potrimqtydtlTbl').datagrid('loadData', response.data);
			$('#openpotrimqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridPoTrimQty(data)
	{
		var dgp = $('#potrimqtydtlTbl');
		dgp.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tPoQty=0;
				var tPoRate=0;
				var tPoAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tPoQty+=data.rows[i]['po_qty'].replace(/,/g,'')*1;
					tPoAmount+=data.rows[i]['po_amount'].replace(/,/g,'')*1;
				}
				tPoRate=tPoAmount/tPoQty;
				$(this).datagrid('reloadFooter', [
				{ 
					po_qty: tPoQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_rate: tPoRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					po_amount: tPoAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dgp.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatpotrimqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsTrimsOrderProgressReport.potrimqtyWindow('+row.sales_order_id+','+'\''+row.itemclass_id+'\''+')">'+row.po_qty+'</a>';	
	}
	
	rcvtrimqtyWindow(sales_order_id,itemclass_id)
	{
		let params=this.getParams();
		params.sales_order_id=sales_order_id;
		params.itemclass_id=itemclass_id;
		let data= axios.get(msApp.baseUrl()+"/trimsorderprogressreport/getrcvtrimqty",{params});
		data.then(function (response) {
			$('#rcvtrimqtydtlTbl').datagrid('loadData', response.data);
			$('#openrcvtrimqtywindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridRcvTrimQty(data)
	{
		var dgr = $('#rcvtrimqtydtlTbl');
		dgr.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			
			onLoadSuccess: function(data){
				var tRcvQty=0;
				var tRcvRate=0;
				var tRcvAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tRcvQty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					tRcvAmount+=data.rows[i]['rcv_amount'].replace(/,/g,'')*1;
				}
				tRcvRate=tRcvAmount/tRcvQty;

				$(this).datagrid('reloadFooter', [
				{ 
					rcv_qty: tRcvQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_rate: tRcvRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_amount: tRcvAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dgr.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatrcvtrimqty(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsTrimsOrderProgressReport.rcvtrimqtyWindow('+row.sales_order_id+','+'\''+row.itemclass_id+'\''+')">'+row.rcv_qty+'</a>';
	}

}
window.MsTrimsOrderProgressReport = new MsTrimsOrderProgressReportController(new MsTrimsOrderProgressReportModel());
MsTrimsOrderProgressReport.showGrid([]);
MsTrimsOrderProgressReport.showTrimsReportStyleGrid([]);
MsTrimsOrderProgressReport.showTrimsTeammemberDlmGrid([]);
MsTrimsOrderProgressReport.showGridPoTrimQty([]);
MsTrimsOrderProgressReport.showGridRcvTrimQty([]);