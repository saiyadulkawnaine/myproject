require('./../../datagrid-filter.js');
let MsProdKnittingDailyLoadReportModel = require('./MsProdKnittingDailyLoadReportModel');

class MsProdKnittingDailyLoadReportController {
	constructor(MsProdKnittingDailyLoadReportModel)
	{
		this.MsProdKnittingDailyLoadReportModel = MsProdKnittingDailyLoadReportModel;
		this.formId='prodknittingdailyloadreportFrm';
		this.dataTable='#prodknittingdailyloadreportTbl';
		this.route=msApp.baseUrl()+"/prodknittingdailyloadreport";
	}

	getParams(){
		let params={};
		//params.date_from = $('#prodknittingdailyloadreportFrm  [name=date_from]').val();
		params.date_to = $('#prodknittingdailyloadreportFrm  [name=date_to]').val();
		return params;
	}

	get(){
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select Date Range First');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#prodknittingdailyloadreportTbl').datagrid('loadData', response.data);
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
				var prod_knit_qty=0;
				var prod_capacity=0;
				var unused_prod_capacity=0;
				var knit_charge_usd=0;
				var knit_charge_bdt=0;

				for(var i=0; i<data.rows.length; i++){
					prod_knit_qty+=data.rows[i]['prod_knit_qty'].replace(/,/g,'')*1;
					prod_capacity+=data.rows[i]['prod_capacity'].replace(/,/g,'')*1;	
					unused_prod_capacity+=data.rows[i]['unused_prod_capacity'].replace(/,/g,'')*1;
					knit_charge_usd+=data.rows[i]['knit_charge_usd'].replace(/,/g,'')*1;
					knit_charge_bdt+=data.rows[i]['knit_charge_bdt'].replace(/,/g,'')*1;
				}
				$('#prodknittingdailyloadreportTbl').datagrid('reloadFooter', [
					{ 
						prod_knit_qty: prod_knit_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						prod_capacity: prod_capacity.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						unused_prod_capacity: unused_prod_capacity.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						knit_charge_usd: knit_charge_usd.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						knit_charge_bdt: knit_charge_bdt.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						
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

	// dyeingIsuRqWindow(prod_batch_id){
	// 	let params=this.getParams();
	// 	params.prod_batch_id=prod_batch_id;
	// 	let data= axios.get(msApp.baseUrl()+"/prodknittingdailyloadreport/getdyeingisuerq",{params});
	// 	let ic=data.then(function (response) {
	// 		$('#dyeingisurqWindow').window('open');
	// 		$('#dyeingisurqTbl').datagrid('loadData',response.data);
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// 	//return ic;
	// }

	// showGridDyeingIsuRq(data){
	// 	var dq = $("#dyeingisurqTbl");
	// 	dq.datagrid({
	// 		border:false,
	// 		singleSelect:true,
	// 		fit:true,
	// 		rownumbers:true,
	// 		emptyMsg:'No Record Found'
	// 	});
	// 	dq.datagrid('enableFilter').datagrid('loadData', data);
	// }

	// formatdyeingIsuRq(value,row){
	// 	if(row.prod_batch_id){
	// 		return '<a href="javascript:void(0)" onClick="MsProdKnittingDailyLoadReport.dyeingIsuRqWindow('+row.prod_batch_id+')">Click</a>';
	// 	}
	// 	return;
	// }

	// showExcel(table_id,file_name){
	// 	let params=this.getParams();
	// 	let d= axios.get(this.route+'/getdata',{params})
	// 	.then(function (response) {
	// 		$('#orderprogressTbl').datagrid('loadData', response.data);
	// 		msApp.toExcel(table_id,file_name);
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

	// pdf(id){
	// 	window.open(msApp.baseUrl()+"/invdyechemisurq/report?id="+id);
	// }

	// formatPdf(value,row){
	// 	return '<a href="javascript:void(0)" onClick="MsProdKnittingDailyLoadReport.pdf('+row.id+')">PDF</a>';
	// }

}
window.MsProdKnittingDailyLoadReport=new MsProdKnittingDailyLoadReportController(new MsProdKnittingDailyLoadReportModel());
MsProdKnittingDailyLoadReport.showGrid([]);
//MsProdKnittingDailyLoadReport.showGridDyeingIsuRq([]);