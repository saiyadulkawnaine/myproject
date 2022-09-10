let MsSampleRequirementModel = require('./MsSampleRequirementModel');
require('./../../datagrid-filter.js');

class MsSampleRequirementController {
	constructor(MsSampleRequirementModel)
	{
		this.MsSampleRequirementModel = MsSampleRequirementModel;
		this.formId='samplerequirementFrm';
		this.dataTable='#samplerequirementTbl';
		this.route=msApp.baseUrl()+"/samplerequirement/getdata";
	}

	get(){
		let params={};
		//params.company_id = $('#samplerequirementFrm  [name=company_id]').val();
		params.buyer_id = $('#samplerequirementFrm  [name=buyer_id]').val();
		params.team_id = $('#samplerequirementFrm  [name=team_id]').val();
		params.teammember_id = $('#samplerequirementFrm  [name=teammember_id]').val();
		params.style_ref = $('#samplerequirementFrm  [name=style_ref]').val();
		//params.job_no = $('#samplerequirementFrm  [name=job_no]').val();
		params.date_from = $('#samplerequirementFrm  [name=date_from]').val();
		params.date_to = $('#samplerequirementFrm  [name=date_to]').val();
        params.orderstage_id = $('#samplerequirementFrm  [name=orderstage_id]').val();

		let d= axios.get(this.route,{params})
		.then(function (response) {
			//MsProjectionProgress.showGrid(response.data.datad)
			$('#samplerequirementTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	/*samplereportWindow(flie_src){
			$('#samplereportWindow').window('open');
	}*/

	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			//queryParams:data,
			showFooter:true,
			fit:true,
			//url:this.route,
			rownumbers:true,
			nowrap:false,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
	
	imageWindow(flie_src){
		var output = document.getElementById('dashbordReportImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#dashbordReportImageWindow').window('open');
	}	

	stylebuyersub(value,row,index)
	{
		return 'background-color:#FC0DA8;';
	}
	formatbuyingAgent(value,row){
		//return '<a href="javascript:void(0)" onClick="MsSampleRequirement.buyingAgentWindow('+row.buying_agent_id+')">'+row.buying_agent_name+'</a>';
		return '<a href="javascript:void(0)" onClick="MsSampleRequirement.buyingAgentWindow('+row.buying_agent_id+')">'+row.buying_agent_name+'</a>';
	}	
	teamleaderWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/samplerequirement/getdlmerchant?user_id="+user_id);
		data.then(function (response) {
			$('#dealmctinfoTbl').datagrid('loadData', response.data);
			$('#dlmerchantWindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}	
	dlmerchantWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/samplerequirement/getdlmerchant?user_id="+user_id);
		data.then(function (response) {
			$('#dealmctinfoTbl').datagrid('loadData', response.data);
			$('#dlmerchantWindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}	
	showGridDlmct(data)
	{
		var dg = $('#dealmctinfoTbl');
		dg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dg.datagrid('loadData', data);
	}

	fileWindow(style_id){

		let data= axios.get(msApp.baseUrl()+"/samplerequirement/getfile?style_id="+style_id);
		data.then(function (response) {
			$('#filesrcTbl').datagrid('loadData', response.data);
			$('#filesrcwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridFileSrc(data)
	{
		var dg = $('#filesrcTbl');
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
	
	buyingAgentWindow(buying_agent_id){
		
		let agent= axios.get(msApp.baseUrl()+"/samplerequirement/getbuyhouse?buyer_id="+buying_agent_id);
		agent.then(function (response) {
			$('#buyagentTbl').datagrid('loadData', response.data);
			$('#buyagentwindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGridBAgent(data)
	{
		var dg = $('#buyagentTbl');
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

	formatimage(value,row)
	{
        return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsTodayShipment.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}
	
	formatteamleader(value,row){
		return '<a href="javascript:void(0)" onClick="MsSampleRequirement.teamleaderWindow('+row.teamleader_id+')">'+row.team_name+'</a>';
	}

	formatdlmerchant(value,row){
		return '<a href="javascript:void(0)" onClick="MsSampleRequirement.dlmerchantWindow('+row.user_id+')">'+row.team_member+'</a>';
	}
	
	formatfile(value,row)
	{
		/* if(row.file_name){
			return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_name + '">'+row.style_ref+'</a>';
		}else{ return row.style_ref; } */
		return '<a href="javascript:void(0)" onClick="MsSampleRequirement.fileWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}
	formatShowFile(value,row){
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}

}
window.MsSampleRequirement=new MsSampleRequirementController(new MsSampleRequirementModel());
MsSampleRequirement.showGrid([]);
MsSampleRequirement.showGridDlmct({rows :{}});
MsSampleRequirement.showGridBAgent({rows :{}});
MsSampleRequirement.showGridFileSrc({rows :{}});