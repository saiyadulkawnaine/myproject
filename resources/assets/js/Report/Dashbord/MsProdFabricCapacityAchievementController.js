let MsProdFabricCapacityAchievementModel = require('./MsProdFabricCapacityAchievementModel');
require('./../../datagrid-filter.js');
class MsProdFabricCapacityAchievementController {
	constructor(MsProdFabricCapacityAchievementModel)
	{
		this.MsProdFabricCapacityAchievementModel = MsProdFabricCapacityAchievementModel;
		this.formId='prodfabriccapacityachievementFrm';
		this.dataTable='#prodfabriccapacityachievementTbl';
		this.route=msApp.baseUrl()+"/prodfabriccapacityachievement"
	}

	
	
	get()
	{
		let params={};
		params.date_from = $('#prodfabriccapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodfabriccapacityachievementFrm  [name=capacity_date_to]').val();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#pcafabriccolorsizematrix').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	imageWindow(flie_src){
		var output = document.getElementById('dashbordReportImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#dashbordReportImageWindow').window('open');
	}
	prodFabricMonthTargetWindow()
    {
    	let params={};
		params.date_from = $('#prodfabriccapacityachievementFrm  [name=capacity_date_from]').val();
		params.date_to = $('#prodfabriccapacityachievementFrm  [name=capacity_date_to]').val();
    	$('#prodFabricMonthTargetWindow').window('open');
    	let d= axios.get(this.route+"/fabricmonthtarget",{params})
		.then(function (response) {
			$('#prodFabricMonthTargetTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
    }

    prodFabricMonthTargetGrid(data)
	{
		var dg = $('#prodFabricMonthTargetTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			showFooter:true,
			emptyMsg:'No Record Found',
			rowStyler:function(index,row){
			if (row.company_name==='Sub Total'){
			return 'background-color:pink;color:#000000;font-weight:bold;';
			}
			if (row.group_name===1){
			return 'background-color:#EAE9E9;color:#000000;font-weight:bold;';
			}
			
		    },
			onLoadSuccess: function(data){
				var tgrey_fab=0;
				var tfin_fab=0;
				for(var i=0; i<data.rows.length; i++){
					if(data.rows[i]['company_name'] !=='Sub Total')
					{
					tgrey_fab+=data.rows[i]['grey_fab'].replace(/,/g,'')*1;
					tfin_fab+=data.rows[i]['fin_fab'].replace(/,/g,'')*1;
					}
				}
				$(this).datagrid('reloadFooter', [
				{
				 grey_fab: Math.round(tgrey_fab).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
				 fin_fab: Math.round(tfin_fab).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","),
				}
				]);
			}
		});
		dg.datagrid('loadData', data);
	}

	
    prodFabricKnitTodayTergetWindow()
    {
		$('#prodFabricKnitTodayTergetWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/plknitreport")
		.then(function (response) {
			$('#prodFabricKnitTodayTergetPage').html(response.data);
			$.parser.parse('#prodFabricKnitTodayTergetPage');
			var capacity_date_to=$('#prodfabriccapacityachievementFrm  [name=capacity_date_to]').val();
			$('#plknitreportFrm  [name=company_id]').val(4);
			$('#plknitreportFrm  [name=location_id]').val(1);
			$('#plknitreportFrm  [name=date_from]').val(capacity_date_to);
			$('#plknitreportFrm  [name=date_to]').val(capacity_date_to);
			MsPlKnitReport.get();
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
    //Row 8 Today Achievement
	prodFabricTodayAchieveRcvYarnWindow(){
		$('#prodFabricTodayAchieveRcvYarnWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement/todayachievercvyarn")
		.then(function (response) {
			$('#prodFabricTodayAchieveRcvYarnPage').html(response.data);
			$.parser.parse('#prodFabricTodayAchieveRcvYarnPage');
		})
		.catch(function (error) {
			console.log(error);
		});	
	}
	prodFabricTodayAchieveKnitYarnIssueWindow(){
		$('#prodFabricTodayAchieveKnitYarnIssueWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement/todayachieveknityarnissue")
		.then(function (response) {
			$('#prodFabricTodayAchieveKnitYarnIssuePage').html(response.data);
			$.parser.parse('#prodFabricTodayAchieveKnitYarnIssuePage');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	prodFabricTodayAchieveDyeingWindow(){
		$('#prodFabricTodayAchieveDyeingWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement/todayachievedye")
		.then(function (response) {
			$('#prodFabricTodayAchieveDyeingPage').html(response.data);
			$.parser.parse('#prodFabricTodayAchieveDyeingPage');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	prodFabricTodayAchieveAopWindow(){
		$('#prodFabricTodayAchieveAopWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement/todayachieveaop")
		.then(function (response) {
			$('#prodFabricTodayAchieveAopPage').html(response.data);
			$.parser.parse('#prodFabricTodayAchieveAopPage');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
    prodFabricKnitTodayAchievementWindow()
    {
    	let params={};
    	
    	var capacity_date_to=$('#prodfabriccapacityachievementFrm  [name=capacity_date_to]').val();
    	params.capacity_date_to=capacity_date_to;
		$('#prodFabricKnitTodayAchievementWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement/knittodayachieve",{params})
		.then(function (response) {
			$('#prodFabricKnitTodayAchievePage').html(response.data);
			$.parser.parse('#prodFabricKnitTodayAchievePage');
			//$('#plknitreportFrm  [name=company_id]').val(4);
			//$('#plknitreportFrm  [name=location_id]').val(1);
			//$('#plknitreportFrm  [name=date_from]').val(capacity_date_to);
			//$('#plknitreportFrm  [name=date_to]').val(capacity_date_to);
			MsPlKnitReport.get();
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	// Row 17 Month Achievement 
	prodFabricMonthAchieveRcvYarnWindow(){
		$('#prodFabricMonthAchieveRcvYarnWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement/monthachievercvyarn")
		.then(function (response) {
			$('#prodFabricMonthAchieveRcvYarnPage').html(response.data);
			$.parser.parse('#prodFabricMonthAchieveRcvYarnPage');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	prodFabricMonthAchieveKnitYarnIssueWindow(){
		$('#prodFabricMonthAchieveKnitYarnIssueWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement/monthachieveyarnissueknit")
		.then(function (response) {
			$('#prodFabricMonthAchieveKnitYarnIssuePage').html(response.data);
			$.parser.parse('#prodFabricMonthAchieveKnitYarnIssuePage');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	prodFabricMonthAchieveKnittingWindow(){
		let params={};
		var capacity_date_to=$('#prodfabriccapacityachievementFrm  [name=capacity_date_to]').val();
    	params.capacity_date_to=capacity_date_to;
		$('#prodFabricMonthAchieveKnittingWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodfabriccapacityachievement/monthachieveknit",{params})
		.then(function (response) {
			$('#prodFabricMonthAchieveKnittingPage').html(response.data);
			$.parser.parse('#prodFabricMonthAchieveKnittingPage');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}	
window.MsProdFabricCapacityAchievement=new MsProdFabricCapacityAchievementController(new MsProdFabricCapacityAchievementModel());
MsProdFabricCapacityAchievement.prodFabricMonthTargetGrid([]);
