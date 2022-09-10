let MsOrderPendingModel = require('./MsOrderPendingModel');
require('./datagrid-filter.js');

class MsOrderPendingController {
	constructor(MsOrderPendingModel)
	{
		this.MsOrderPendingModel = MsOrderPendingModel;
		this.formId='orderpendingFrm';
		this.dataTable='#orderpendingTbl';
		this.route=msApp.baseUrl()+"/orderpending"
	}

	getParams(){
		let params={};
		params.company_id = $('#orderpendingFrm  [name=company_id]').val();
		params.buyer_id = $('#orderpendingFrm  [name=buyer_id]').val();
		params.style_ref = $('#orderpendingFrm  [name=style_ref]').val();
		params.style_id = $('#orderpendingFrm  [name=style_id]').val();
		params.factory_merchant_id = $('#orderpendingFrm  [name=factory_merchant_id]').val();
		params.date_from = $('#orderpendingFrm  [name=date_from]').val();
		params.date_to = $('#orderpendingFrm  [name=date_to]').val();
		params.receive_date_from = $('#orderpendingFrm  [name=receive_date_from]').val();
		params.receive_date_to = $('#orderpendingFrm  [name=receive_date_to]').val();
		return params;
	}
	

	get()
	{
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#orderpendingTbl').datagrid('loadData', response.data.details);
			$('#monthlystyleTbl').datagrid('loadData', response.data.month);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var opdg = $('#orderpendingTbl');
		opdg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					
				}
				$('#orderpendingTbl').datagrid('reloadFooter', [
				{ 
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					
				}
				]);
							
			}
		});
		opdg.datagrid('enableFilter').datagrid('loadData', data);
	}

	showMonthGrid(data)
	{
		var mdg= $('#monthlystyleTbl');
		mdg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					
				}
				$('#monthlystyleTbl').datagrid('reloadFooter', [
					{
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		mdg.datagrid('enableFilter').datagrid('loadData', data);//
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	formatteamleader(value,row){
		return '<a href="javascript:void(0)" onClick="MsOrderPending.teamleaderWindow('+row.teamleader_id+')">'+row.teamleader_name+'</a>';
	}

	teamleaderWindow(teamleader_id){
		let data= axios.get(msApp.baseUrl()+"/orderpending/getdlmerchant?user_id="+teamleader_id);
		data.then(function (response) {
			$('#dealmctinfoTbl').datagrid('loadData', response.data);
			$('#dlmerchantWindow').window('open');			    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	formatdlmerchant(value,row){
		if (row.team_member_name) {
			return '<a href="javascript:void(0)" onClick="MsOrderPending.dlmerchantWindow('+row.user_id+')">'+row.team_member_name+'</a>';
		}
	}

	dlmerchantWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/orderpending/getdlmerchant?user_id="+user_id);
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

	formatbuyingAgent(value,row){
		if (row.buying_agent_name) {
			return '<a href="javascript:void(0)" onClick="MsOrderPending.buyingAgentWindow('+row.buying_agent_id+')">'+row.buying_agent_name+'</a>';
		}
		
	}

	buyingAgentWindow(buying_agent_id){
		
		let agent= axios.get(msApp.baseUrl()+"/orderpending/getbuyhouse?buyer_id="+buying_agent_id);
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
		var bdg = $('#buyagentTbl');
		bdg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:false,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		bdg.datagrid('loadData', data);
	}

	formatopfiles(value,row)
	{
		if (row.style_ref) {
			return '<a href="javascript:void(0)" onClick="MsOrderPending.opfileWindow('+row.style_id+')">'+row.style_ref+'</a>';
		}
			
	}

	opfileWindow(style_id)
	{
		let data= axios.get(msApp.baseUrl()+"/orderpending/getopfile?style_id="+style_id);
		data.then(function (response) {
			$('#opfilesrcTbl').datagrid('loadData', response.data);
			$('#opfilesrcwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridOpFileSrc(data)
	{
		var fdg = $('#opfilesrcTbl');
		fdg.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'
		});
		fdg.datagrid('loadData', data);
	}

	formatShowOpFile(value,row)
	{
		if (row.file_src) {
			return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
		}else{
			return '';
		}
	}

	formatimage(value,row)
	{
		if (row.flie_src) {
			return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsOrderPending.imageWindow('+'\''+row.flie_src+'\''+')"/>';
		}else{
			return '';
		}
	
	}

	imageWindow(flie_src)
	{
		var output = document.getElementById('orderPendingImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#orderPendingImageWindow').window('open');
	}

	openOrdStyleWindow(){
		$('#styleWindow').window('open');
	}

	getStyleParams(){
		let params={};
		params.buyer_id = $('#stylesearchFrm  [name=buyer_id]').val();
		params.style_ref = $('#stylesearchFrm  [name=style_ref]').val();
		params.style_description = $('#stylesearchFrm  [name=style_description]').val();
		return params;
	}
	searchStyleGrid(){
		let params=this.getStyleParams();
		let d= axios.get(this.route+'/getstyle',{params})
		.then(function(response){
			$('#stylesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showStyleGrid(data){
		let self=this;
		$('#ordstylesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#orderpendingFrm [name=style_ref]').val(row.style_ref);
				$('#orderpendingFrm [name=style_id]').val(row.id);
				$('#styleWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}


}
window.MsOrderPending = new MsOrderPendingController(new MsOrderPendingModel());
MsOrderPending.showGrid([]);
MsOrderPending.showMonthGrid([]);
MsOrderPending.showStyleGrid([]);
MsOrderPending.showGridDlmct([]);
MsOrderPending.showGridBAgent([]);
MsOrderPending.showGridOpFileSrc([]);