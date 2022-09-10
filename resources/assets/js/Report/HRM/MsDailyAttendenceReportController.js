let MsDailyAttendenceReportModel = require('./MsDailyAttendenceReportModel');
require('./../../datagrid-filter.js');

class MsDailyAttendenceReportController {
	constructor(MsDailyAttendenceReportModel)
	{
		this.MsDailyAttendenceReportModel = MsDailyAttendenceReportModel;
		this.formId='dailyattencereportFrm';
		this.dataTable='#dailyattencereportTbl';
		this.route=msApp.baseUrl()+"/dailyattendencereport"
	}
	
	get(){
		let params={};
		params.company_id = $('#dailyattencereportFrm  [name=company_id]').val();
		params.from_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		params.to_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		
		if(!params.from_work_date){
			alert('Please Select Date');
		}
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#dailyattencereportTbl').datagrid('loadData', response.data);
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
				var enlisted=0;
				var present=0;
				var leave=0;
				var absent=0;
				for(var i=0; i<data.rows.length; i++){
				enlisted+=data.rows[i]['enlisted'].replace(/,/g,'')*1;
				present+=data.rows[i]['present'].replace(/,/g,'')*1;
				leave+=data.rows[i]['leave'].replace(/,/g,'')*1;
				absent+=data.rows[i]['absent'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						enlisted: enlisted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						present: present.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						leave: leave.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						absent: absent.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	getpdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/dailyattencereport/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)" onClick="MsDailyAttenceReport.getpdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Print Pdf</span></a>';
	}

	formatdept(value,row)
	{	
	    if(row.btn==1){
		return '<a href="javascript:void(0)" onClick="MsDailyAttenceReport.getdept('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Department</span></a>';
	    }	
	}

	getdept(company_id){
		let params={};
		params.company_id = company_id;
		params.from_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		params.to_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		if(!params.from_work_date){
			alert('Please Select Date');
		}

		let d= axios.get(this.route+'/getdatadept',{params})
		.then(function (response) {
			$('#dailyattencereportdeptTbl').datagrid('loadData', response.data);
			$('#dailyattencereportdeptwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridDept(data)
	{
		var dg = $('#dailyattencereportdeptTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var enlisted=0;
				var present=0;
				var leave=0;
				var absent=0;
				var budgeted=0;
				for(var i=0; i<data.rows.length; i++){
				enlisted+=data.rows[i]['enlisted'].replace(/,/g,'')*1;
				present+=data.rows[i]['present'].replace(/,/g,'')*1;
				leave+=data.rows[i]['leave'].replace(/,/g,'')*1;
				absent+=data.rows[i]['absent'].replace(/,/g,'')*1;
				budgeted+=data.rows[i]['budgeted'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						enlisted: enlisted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						present: present.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						leave: leave.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						absent: absent.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						budgeted: budgeted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatsect(value,row)
	{	
	    if(row.btn==1){	
		return '<a href="javascript:void(0)" onClick="MsDailyAttenceReport.getsect('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Section</span></a>';
	    }
	}

	getsect(company_id){
		let params={};
		params.company_id = company_id;
		params.from_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		params.to_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		if(!params.from_work_date){
			alert('Please Select Date');
		}

		let d= axios.get(this.route+'/getdatasect',{params})
		.then(function (response) {
			$('#dailyattencereportsectTbl').datagrid('loadData', response.data);
			$('#dailyattencereportsectwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridSect(data)
	{
		var dg = $('#dailyattencereportsectTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var enlisted=0;
				var present=0;
				var leave=0;
				var absent=0;
				var budgeted=0;
				for(var i=0; i<data.rows.length; i++){
				enlisted+=data.rows[i]['enlisted'].replace(/,/g,'')*1;
				present+=data.rows[i]['present'].replace(/,/g,'')*1;
				leave+=data.rows[i]['leave'].replace(/,/g,'')*1;
				absent+=data.rows[i]['absent'].replace(/,/g,'')*1;
				budgeted+=data.rows[i]['budgeted'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						enlisted: enlisted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						present: present.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						leave: leave.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						absent: absent.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						budgeted: budgeted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatsubsect(value,row)
	{	
	    if(row.btn==1){	
		return '<a href="javascript:void(0)" onClick="MsDailyAttenceReport.getsubsect('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Sub-Section</span></a>';
	    }
	}
	getsubsect(company_id){
		let params={};
		params.company_id = company_id;
		params.from_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		params.to_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		if(!params.from_work_date){
			alert('Please Select Date');
		}
		let d= axios.get(this.route+'/getdatasubsect',{params})
		.then(function (response) {
			$('#dailyattencereportsubsectTbl').datagrid('loadData', response.data);
			$('#dailyattencereportsubsectwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridSubSect(data)
	{
		var dg = $('#dailyattencereportsubsectTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var enlisted=0;
				var present=0;
				var leave=0;
				var absent=0;
				var budgeted=0;
				for(var i=0; i<data.rows.length; i++){
				enlisted+=data.rows[i]['enlisted'].replace(/,/g,'')*1;
				present+=data.rows[i]['present'].replace(/,/g,'')*1;
				leave+=data.rows[i]['leave'].replace(/,/g,'')*1;
				absent+=data.rows[i]['absent'].replace(/,/g,'')*1;
				budgeted+=data.rows[i]['budgeted'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						enlisted: enlisted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						present: present.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						leave: leave.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						absent: absent.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						budgeted: budgeted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatdegn(value,row)
	{	
	    if(row.btn==1){	
		return '<a href="javascript:void(0)" onClick="MsDailyAttenceReport.getdegn('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Designation</span></a>';
	    }
	}
	getdegn(company_id){
		let params={};
		params.company_id = company_id;
		params.from_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		params.to_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		if(!params.from_work_date){
			alert('Please Select Date');
		}
		let d= axios.get(this.route+'/getdatadegn',{params})
		.then(function (response) {
			$('#dailyattencereportdegnTbl').datagrid('loadData', response.data);
			$('#dailyattencereportdegnwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridDegn(data)
	{
		var dg = $('#dailyattencereportdegnTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var enlisted=0;
				var present=0;
				var leave=0;
				var absent=0;
				var budgeted=0;
				for(var i=0; i<data.rows.length; i++){
				enlisted+=data.rows[i]['enlisted'].replace(/,/g,'')*1;
				present+=data.rows[i]['present'].replace(/,/g,'')*1;
				leave+=data.rows[i]['leave'].replace(/,/g,'')*1;
				absent+=data.rows[i]['absent'].replace(/,/g,'')*1;
				budgeted+=data.rows[i]['budgeted'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						enlisted: enlisted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						present: present.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						leave: leave.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						absent: absent.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						budgeted: budgeted.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatempl(value,row)
	{	
	    if(row.btn==1){	
		return '<a href="javascript:void(0)" onClick="MsDailyAttenceReport.getempl('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Employee</span></a>';
	    }
	}
	getempl(company_id){
		let params={};
		params.company_id = company_id;
		params.from_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		params.to_work_date = $('#dailyattencereportFrm  [name=work_date]').val();
		if(!params.from_work_date){
			alert('Please Select Date');
		}
		let d= axios.get(this.route+'/getdataempl',{params})
		.then(function (response) {
			$('#dailyattencereportemplTbl').datagrid('loadData', response.data);
			$('#dailyattencereportemplwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridEmpl(data)
	{
		var dg = $('#dailyattencereportemplTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsDailyAttenceReport = new MsDailyAttendenceReportController(new MsDailyAttendenceReportModel());
MsDailyAttenceReport.showGrid([]);
MsDailyAttenceReport.showGridDept([]);
MsDailyAttenceReport.showGridSect([]);
MsDailyAttenceReport.showGridSubSect([]);
MsDailyAttenceReport.showGridDegn([]);
MsDailyAttenceReport.showGridEmpl([]);
