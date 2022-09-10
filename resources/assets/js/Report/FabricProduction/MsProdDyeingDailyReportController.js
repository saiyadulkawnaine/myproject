require('./../../datagrid-filter.js');
let MsProdDyeingDailyReportModel = require('./MsProdDyeingDailyReportModel');

class MsProdDyeingDailyReportController {
	constructor(MsProdDyeingDailyReportModel)
	{
		this.MsProdDyeingDailyReportModel = MsProdDyeingDailyReportModel;
		this.formId='proddyeingdailyreportFrm';
		this.dataTable='#proddyeingdailyreportTbl';
		this.route=msApp.baseUrl()+"/proddyeingdailyreport";
	}

	getParams(){
		let params={};
		params.date_from = $('#proddyeingdailyreportFrm  [name=date_from]').val();
		params.date_to = $('#proddyeingdailyreportFrm  [name=date_to]').val();
		params.time_from = $('#proddyeingdailyreportFrm  [name=time_from]').val();
		params.time_to = $('#proddyeingdailyreportFrm  [name=time_to]').val();
		params.unload_shift = $('#proddyeingdailyreportFrm  [name=unload_shift]').val();
		return params;
	}

	get(){
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select Date Range First');
			return;
		}

		if(params.time_from && params.time_to && params.date_from!=params.date_to){
			alert('Select Time Range First');
			return;
		}


		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#proddyeingdailyreportTbl').datagrid('loadData', response.data);
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
				$('#proddyeingdailyreportTbl').datagrid('reloadFooter', [
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
		let data= axios.get(msApp.baseUrl()+"/proddyeingdailyreport/getdyeingisuerq",{params});
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
			return '<a href="javascript:void(0)" onClick="MsProdDyeingDailyReport.dyeingIsuRqWindow('+row.prod_batch_id+')">Click</a>';
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
		return '<a href="javascript:void(0)" onClick="MsProdDyeingDailyReport.pdf('+row.id+')">PDF</a>';
	}

}
window.MsProdDyeingDailyReport=new MsProdDyeingDailyReportController(new MsProdDyeingDailyReportModel());
MsProdDyeingDailyReport.showGrid([]);
MsProdDyeingDailyReport.showGridDyeingIsuRq([]);