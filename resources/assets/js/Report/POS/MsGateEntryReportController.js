let MsGateEntryReportModel = require('./MsGateEntryReportModel');
require('./../../datagrid-filter.js');

class MsGateEntryReportController {
	constructor(MsGateEntryReportModel)
	{
		this.MsGateEntryReportModel = MsGateEntryReportModel;
		this.formId='gateentryreportFrm';
		this.dataTable='#gateentryreportTbl';
		this.route=msApp.baseUrl()+"/gateentryreport/getdata"
	}

	getParams(){
		let params={};
		params.menu_id = $('#gateentryreportFrm  [name=menu_id]').val();
		params.date_from = $('#gateentryreportFrm  [name=date_from]').val();
		params.date_to = $('#gateentryreportFrm  [name=date_to]').val();
		params.company_id = $('#gateentryreportFrm  [name=company_id]').val();
		params.supplier_id = $('#gateentryreportFrm  [name=supplier_id]').val();
		params.po_pr_no = $('#gateentryreportFrm  [name=po_pr_no]').val();
		return params;
	}
	
	get(){
		let params=this.getParams();
        if(!params.menu_id){
			alert('Select A Menu Name First ');
			return;
		}
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
            $('#gateentryreportTbl').datagrid('loadData', response.data);
            //$('#gateentryreportmatrix').html(response.data);
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
			fit:true,
			rownumbers:true,
			nowrap:false,
			showFooter:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmount=0;
				var tRcvQty=0;
				

				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					tRcvQty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rcv_qty: tRcvQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	openPoPrWindow(){
		let params=this.getParams();
        if(!params.menu_id){
			alert('Select A Menu Name First ');
			return;
		}
		$('#poWindow').window('open');
	}

    getPoParams(){
        let params = {};
		params.menu_id = $('#gateentryreportFrm [name="menu_id"]').val();
		params.po_pr_no = $('#poprsearchFrm [name="po_pr_no"]').val();
		params.po_pr_date = $('#poprsearchFrm [name="po_pr_date"]').val();
        return params;
    }

	searchPoGrid(){
		let params=this.getPoParams();
		// if(!params.menu_id){
		// 	alert('Select A Menu Name First ');
		// 	return;
		// }
		let lcsc= axios.get(msApp.baseUrl()+"/gateentryreport/getpo",{params})
		.then(function (response) {
			$('#prposearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
        return lcsc;
	}

    showPoGrid(data){
        let self = this;
		$('#prposearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
                $('#gateentryreportFrm [name=purchase_order_id]').val(row.purchase_order_id);
                $('#gateentryreportFrm [name=po_pr_no]').val(row.po_pr_no);
                $('#poWindow').window('close');
                $('#prposearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
    }

	showExcel(table_id,file_name){
		let params=this.getParams();
		let e= axios.get(this.route,{params})
		.then(function (response) {
			$('#gateentryreportTbl').datagrid('loadData', response.data);
			$('#gateentryreportTbl').datagrid('toExcel','Gate Entry Report.xls');
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}	

}
window.MsGateEntryReport = new MsGateEntryReportController(new MsGateEntryReportModel());
//MsGateEntryReport.showGrid({rows :{}});
MsGateEntryReport.showGrid([]);
MsGateEntryReport.showPoGrid([]);