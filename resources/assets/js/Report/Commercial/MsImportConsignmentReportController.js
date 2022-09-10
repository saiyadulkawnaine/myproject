let MsImportConsignmentReportModel = require('./MsImportConsignmentReportModel');
require('./../../datagrid-filter.js');

class MsImportConsignmentReportController {
	constructor(MsImportConsignmentReportModel)
	{
		this.MsImportConsignmentReportModel = MsImportConsignmentReportModel;
		this.formId='importconsignmentreportFrm';
		this.dataTable='#importconsignmentreportTbl';
		this.route=msApp.baseUrl()+"/importconsignmentreport";
	}
	getParams()
	{
	    let params={};
	    params.date_from = $('#importconsignmentreportFrm  [name=date_from]').val();
		params.date_to = $('#importconsignmentreportFrm  [name=date_to]').val();
		
		params.beneficiary_id = $('#importconsignmentreportFrm  [name=beneficiary_id]').val();
		params.supplier_id = $('#importconsignmentreportFrm  [name=supplier_id]').val();
		params.menu_id = $('#importconsignmentreportFrm  [name=menu_id]').val();
		params.lc_type_id = $('#importconsignmentreportFrm  [name=lc_type_id]').val();
		params.issuing_bank_branch_id = $('#importconsignmentreportFrm  [name=issuing_bank_branch_id]').val();

		params.lc_no = $('#importconsignmentreportFrm  [name=lc_no]').val();
		return 	params;
	}
	
	get(){
		let params=this.getParams();
		let d= axios.get(this.route+'/htmlgrid',{params})
		.then(function (response) {
			$('#importconsignmentreportTbl').datagrid('loadData', response.data);
				
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
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var doc_value=0;
				var lc_amount=0;
				var qty=0;
				var paid_amount=0;
				var overdue=0;
				

				for(var i=0; i<data.rows.length; i++){
					doc_value+=data.rows[i]['doc_value'].replace(/,/g,'')*1;
					lc_amount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					paid_amount+=data.rows[i]['paid_amount'].replace(/,/g,'')*1;
					overdue+=data.rows[i]['overdue'].replace(/,/g,'')*1;
					

				}
				//rate=stock_value/stock_qty;
					$(this).datagrid('reloadFooter', [
				{
					doc_value: doc_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_amount: lc_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					paid_amount: paid_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					overdue: overdue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
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

   latter(id)
   {
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(msApp.baseUrl()+"/expdocsubmission/latter?id="+id);
   }

   formatDetail(value,row)
   {
		return '<a href="javascript:void(0)"  onClick="MsImportConsignmentReport.latter('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Nego</span></a>';
   }

   fileWindow(id){

		let data= axios.get(msApp.baseUrl()+"/importconsignmentreport/getfile?id="+id);
		data.then(function (response) {
			$('#importfileTbl').datagrid('loadData', response.data);
			$('#importfilewindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridFileSrc(data)
	{
		var dg = $('#importfileTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:false,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}
	
	formatImportfile(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsImportConsignmentReport.fileWindow('+row.id+')">'+row.menu_id+'</a>';	
	}

	formatShowFile(value,row){
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}

	getbankpending(){
		
		let params=this.getParams();
		let d= axios.get(this.route+'/bankpending',{params})
		.then(function (response) {
			$('#containerDocWindow').window('open');
			$('#bankpendingWindow').html(response.data);	
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsImportConsignmentReport=new MsImportConsignmentReportController(new MsImportConsignmentReportModel());
MsImportConsignmentReport.showGrid([]);
MsImportConsignmentReport.showGridFileSrc([]);