require('./../../datagrid-filter.js');
let MsDailyEfficiencyReportModel = require('./MsDailyEfficiencyReportModel');

class MsDailyEfficiencyReportController {
	constructor(MsDailyEfficiencyReportModel)
	{
		this.MsDailyEfficiencyReportModel = MsDailyEfficiencyReportModel;
		this.formId='dailyefficiencyreportFrm';
		this.dataTable='#dailyefficiencyreportTbl';
		this.route=msApp.baseUrl()+"/dailyefficiencyreport";
	}

	get(){
		let params={};
		params.date_to = $('#dailyefficiencyreportFrm  [name=date_to]').val();
		if(!params.date_to){
			alert('Select Date first');
			return;
		}

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#dailyefficiencyreportTblContainer').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	imageWindow(file_src)
	{
		let imageArr=file_src.split(',');
		let image=''
		for(var i=0; i<imageArr.length; i++)
		{
			image +='<img  src="'+msApp.baseUrl()+'/images/'+imageArr[i]+'"/>';
		}
		$('#dailyefficiencyreportimagegrid').html('');
		$('#dailyefficiencyreportimagegrid').html(image);
		$('#dailyefficiencyreportImageWindow').window('open');
	}


	

	detailWindow(sew_hour,wstudy_line_setup_id,sew_date){
		let params={};
        params.sew_hour=sew_hour;
        params.wstudy_line_setup_id=wstudy_line_setup_id;
        params.sew_date=sew_date;
		if(!params.wstudy_line_setup_id){
			alert('No line found');
			return;
		}

		let d= axios.get(this.route+'/getdatadetails',{params})
		.then(function (response) {
			$('#dailyefficiencyreportdetailsTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

          $('#dailyefficiencyreportDetailWindow').window('open');
	}

	showGridDetail(data)
	{
		var dg = $('#dailyefficiencyreportdetailsTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['sew_qty']*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					sew_qty: tQty,
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
    
	targetprice(value,row,index){
		if(value !== null){
			if(row.target_per_hour*1 > value*1){
				return 'background-color:#ff00004d';
			}
		}
	}

	getMonthly(){
		let params={};
		params.date_from = $('#monthlyefficiencyreportFrm  [name=monthly_date_from]').val();
		params.date_to = $('#monthlyefficiencyreportFrm  [name=monthly_date_to]').val();
		if(!params.date_from){
			alert('Select Date first');
			return;
		}
		if(!params.date_to){
			alert('Select Date first');
			return;
		}

		let d= axios.get(this.route+'/getdatamonthly',{params})
		.then(function (response) {
			$('#monthlyefficiencyreportTblContainer').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	
}
window.MsDailyEfficiencyReport=new MsDailyEfficiencyReportController(new MsDailyEfficiencyReportModel());
//MsDailyEfficiencyReport.showGrid([]);
MsDailyEfficiencyReport.showGridDetail([]);