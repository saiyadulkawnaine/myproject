require('./../../datagrid-filter.js');
let MsProdDyeingDailyLoadReportModel = require('./MsProdDyeingDailyLoadReportModel');

class MsProdDyeingDailyLoadReportController {
	constructor(MsProdDyeingDailyLoadReportModel)
	{
		this.MsProdDyeingDailyLoadReportModel = MsProdDyeingDailyLoadReportModel;
		this.formId='proddyeingdailyloadreportFrm';
		this.dataTable='#proddyeingdailyloadreportTbl';
		this.route=msApp.baseUrl()+"/proddyeingdailyloadreport";
	}

	getParams(){
		let params={};
		params.date_to = $('#proddyeingdailyloadreportFrm  [name=date_to]').val();
		return params;
	}

	get(){
		let params=this.getParams();
		if(!params.date_to){
			alert('Select Date Range First');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#proddyeingdailyloadreportTbl').datagrid('loadData', response.data);
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
				var batch_qty=0;

				for(var i=0; i<data.rows.length; i++){
					batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;	
				}
				$('#proddyeingdailyloadreportTbl').datagrid('reloadFooter', [
					{ 
						batch_qty: batch_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						
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

	dyeingIsuRqWindow(prod_batch_id){
		let params=this.getParams();
		params.prod_batch_id=prod_batch_id;
		let data= axios.get(msApp.baseUrl()+"/proddyeingdailyloadreport/getdyeingisuerq",{params});
		let ic=data.then(function (response) {
			$('#dyeingisurqWindow').window('open');
			$('#dyeingisurqTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		//return ic;
	}

	showGridDyeingIsuRq(data){
		var dq = $("#dyeingisurqTbl");
		dq.datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		dq.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatdyeingIsuRq(value,row){
		if(row.prod_batch_id){
			return '<a href="javascript:void(0)" onClick="MsProdDyeingDailyLoadReport.dyeingIsuRqWindow('+row.prod_batch_id+')">Click</a>';
		}
		return;
	}

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

	pdf(id){
		window.open(msApp.baseUrl()+"/invdyechemisurq/report?id="+id);
	}

	formatPdf(value,row){
		return '<a href="javascript:void(0)" onClick="MsProdDyeingDailyLoadReport.pdf('+row.id+')">PDF</a>';
	}

	getdyeingloadsummery(){
		let params=this.getParams();

		let d= axios.get(this.route+"/getdyeingloadsummery",{params})
		.then(function (response) {
			$('#dyeingloadsummeryWindow').window('open');
			$('#dyeingloadsummeryTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showDyeingLoadSummeryGrid(data)
	{
		var sdg = $('#dyeingloadsummeryTbl');
		sdg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var batch_qty=0;
				var prod_capacity=0;
				var unused_prod_capacity=0;

				for(var i=0; i<data.rows.length; i++){
					batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;	
					prod_capacity+=data.rows[i]['prod_capacity'].replace(/,/g,'')*1;	
					unused_prod_capacity+=data.rows[i]['unused_prod_capacity'].replace(/,/g,'')*1;	
				}
				$('#dyeingloadsummeryTbl').datagrid('reloadFooter', [
					{ 
						batch_qty: batch_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						prod_capacity: prod_capacity.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						unused_prod_capacity: unused_prod_capacity.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		sdg.datagrid('enableFilter').datagrid('loadData', data);
	}

	exceedhour(value,row,index)
	{
		if (row.running_hour > row.tgt_hour){
		    return 'color:red;';
	    }
	}

}
window.MsProdDyeingDailyLoadReport=new MsProdDyeingDailyLoadReportController(new MsProdDyeingDailyLoadReportModel());
MsProdDyeingDailyLoadReport.showGrid([]);
MsProdDyeingDailyLoadReport.showGridDyeingIsuRq([]);
MsProdDyeingDailyLoadReport.showDyeingLoadSummeryGrid([]);