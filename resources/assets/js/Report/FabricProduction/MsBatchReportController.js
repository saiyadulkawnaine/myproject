let MsBatchReportModel = require('./MsBatchReportModel');
require('./../../datagrid-filter.js');

class MsBatchReportController {
	constructor(MsBatchReportModel)
	{
		this.MsBatchReportModel = MsBatchReportModel;
		this.formId='batchreportFrm';
		this.dataTable='#batchreportTbl';
		this.route=msApp.baseUrl()+"/batchreport"
	}
	getParams(){
		let params={};
		params.date_from = $('#batchreportFrm  [name=date_from]').val();
		params.date_to = $('#batchreportFrm  [name=date_to]').val();
		params.target_date_from = $('#batchreportFrm  [name=target_date_from]').val();
		params.target_date_to = $('#batchreportFrm  [name=target_date_to]').val();
		return params;
	}
	
	get()
	{
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#batchreportTbl').datagrid('loadData', response.data);
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
				var batch_wgt=0;
				var prod_capacity=0;
				var prod_capacity_var=0;
				for(var i=0; i<data.rows.length; i++){
                    batch_wgt+=data.rows[i]['batch_wgt'].replace(/,/g,'')*1;
                    prod_capacity+=data.rows[i]['prod_capacity'].replace(/,/g,'')*1;
                    prod_capacity_var+=data.rows[i]['prod_capacity_var'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					batch_wgt: batch_wgt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					prod_capacity: prod_capacity.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					prod_capacity_var: prod_capacity_var.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatbatchno(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsBatchReport.rollWindow('+row.id+')">'+row.batch_no+'</a>';	
	}

	
    
    rollWindow(id){
		let params={};
		params.prod_batch_id=id;

		let d= axios.get(this.route+'/getroll',{params})
		.then(function(response){
			$('#batchreportrollTbl').datagrid('loadData', response.data);
			$('#batchreportrollWindow').window('open');

		}).catch(function (error) {
			console.log(error);
		});
	}
	rollGrid(data){
		let self=this;
		$('#batchreportrollTbl').datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onLoadSuccess: function(data){
				var batch_qty=0;
				for(var i=0; i<data.rows.length; i++){
                    batch_qty+=data.rows[i]['batch_qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					batch_qty: batch_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}


	batchCardButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBatchReport.showBatchCard(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Batch Card</span></a>';
	}

	
	showBatchCard(e,id)
	{
		if(id==""){
			alert("Select a Batch");
			return;
		}
		window.open(msApp.baseUrl()+"/prodbatch/report?id="+id);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
    }

	
}
window.MsBatchReport=new MsBatchReportController(new MsBatchReportModel());
MsBatchReport.showGrid([]);
MsBatchReport.rollGrid([]);