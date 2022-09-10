require('./../../datagrid-filter.js');
let MsProdKnitDailyReportModel = require('./MsProdKnitDailyReportModel');

class MsProdKnitDailyReportController {
	constructor(MsProdKnitDailyReportModel)
	{
		this.MsProdKnitDailyReportModel = MsProdKnitDailyReportModel;
		this.formId='prodknitdailyreportFrm';
		this.dataTable='#prodknitdailyreportTbl';
		this.route=msApp.baseUrl()+"/prodknitdailyreport";
	}

	get(){
		let params={};
		params.supplier_id = $('#prodknitdailyreportFrm  [name=supplier_id]').val();
		params.basis_id = $('#prodknitdailyreportFrm  [name=basis_id]').val();
		params.location_id = $('#prodknitdailyreportFrm  [name=location_id]').val();
		params.date_from = $('#prodknitdailyreportFrm  [name=date_from]').val();
		params.date_to = $('#prodknitdailyreportFrm  [name=date_to]').val();
		if(!params.date_from && !params.date_to){
			alert('Select Date Range First');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#prodknitdailyreportTbl').datagrid('loadData', response.data);
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
				var yarn_used_qty=0;
				var prod_knit_qc_qty=0;
				var prod_knit_qc_wip=0;
				var prod_knit_dlv_qty=0;
				var prod_knit_dlv_wip=0;
				var knit_charge=0;
				for(var i=0; i<data.rows.length; i++){
					prod_knit_qty+=data.rows[i]['prod_knit_qty'].replace(/,/g,'')*1;
					yarn_used_qty+=data.rows[i]['yarn_used_qty'].replace(/,/g,'')*1;
					prod_knit_qc_qty+=data.rows[i]['prod_knit_qc_qty'].replace(/,/g,'')*1;
					prod_knit_qc_wip+=data.rows[i]['prod_knit_qc_wip'].replace(/,/g,'')*1;
					prod_knit_dlv_qty+=data.rows[i]['prod_knit_dlv_qty'].replace(/,/g,'')*1;
					prod_knit_dlv_wip+=data.rows[i]['prod_knit_dlv_wip'].replace(/,/g,'')*1;
					knit_charge+=data.rows[i]['knit_charge'].replace(/,/g,'')*1;
				}
				$('#prodknitdailyreportTbl').datagrid('reloadFooter', [
					{ 
						prod_knit_qty: prod_knit_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						yarn_used_qty: yarn_used_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						prod_knit_qc_qty: prod_knit_qc_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						prod_knit_qc_wip: prod_knit_qc_wip.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						prod_knit_dlv_qty: prod_knit_dlv_qty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						prod_knit_dlv_wip: prod_knit_dlv_wip.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						knit_charge: knit_charge.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
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

}
window.MsProdKnitDailyReport=new MsProdKnitDailyReportController(new MsProdKnitDailyReportModel());
MsProdKnitDailyReport.showGrid([]);