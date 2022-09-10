//require('./jquery.easyui.min.js');
let MsCostingNegotiModel = require('./MsCostingNegotiModel');
require('./datagrid-filter.js');

class MsCostingNegotiController {
	constructor(MsCostingNegotiModel)
	{
		this.MsCostingNegotiModel = MsCostingNegotiModel;
		this.formId='costingnegotiFrm';
		this.dataTable='#costingnegotiTbl';
		this.route=msApp.baseUrl()+"/costingnegoti/getdata";
	}

	get(){
		let params={};
		//params.company_id = $('#costingnegotiFrm  [name=company_id]').val();
		params.buyer_id = $('#costingnegotiFrm  [name=buyer_id]').val();
		params.team_id = $('#costingnegotiFrm  [name=team_id]').val();
		params.teammember_id = $('#costingnegotiFrm  [name=teammember_id]').val();
		params.style_ref = $('#costingnegotiFrm  [name=style_ref]').val();
		//params.job_no = $('#costingnegotiFrm  [name=job_no]').val();
		params.date_from = $('#costingnegotiFrm  [name=date_from]').val();
		params.date_to = $('#costingnegotiFrm  [name=date_to]').val();
		params.confirm_from = $('#costingnegotiFrm  [name=confirm_from]').val();
		params.confirm_to = $('#costingnegotiFrm  [name=confirm_to]').val();
		params.costing_from = $('#costingnegotiFrm  [name=costing_from]').val();
		params.costing_to = $('#costingnegotiFrm  [name=costing_to]').val();

		params.submission_from = $('#costingnegotiFrm  [name=submission_from]').val();
		params.submission_to = $('#costingnegotiFrm  [name=submission_to]').val();
		params.refused_from = $('#costingnegotiFrm  [name=refused_from]').val();
		params.refused_to = $('#costingnegotiFrm  [name=refused_to]').val();
		params.cancel_from = $('#costingnegotiFrm  [name=cancel_from]').val();
		params.cancel_to = $('#costingnegotiFrm  [name=cancel_to]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			//MsProjectionProgress.showGrid(response.data.datad)
			$('#costingnegotiTbl').datagrid('loadData', response.data);
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
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['offer_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 offer_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
			var output = document.getElementById('costingnegotiImageWindowoutput');
			var fp=msApp.baseUrl()+"/images/"+flie_src;
			output.src =  fp;
			$('#costingnegotiImageWindow').window('open');
	}
	
	pdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/mktcost/report?id="+id);
	}
	
	formatpdf(value,row)
	{	
		if(row.style_ref){
			return '<a href="javascript:void(0)"  onClick="MsCostingNegoti.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
		}
		else{
			return '';
		}	
	}
	formatimage(value,row)
	{
		if(row.flie_src){
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsCostingNegoti.imageWindow('+'\''+row.flie_src+'\''+')"/>';
		}
		else
		{
		return '';
		}
	}
	
	
	quotedprice(value,row,index)
	{
		
		
		if (row.cost_per_pcs*1 > value*1){
				return 'color:red;';
			}
	}

	styleformat(value,row,index)
	{
		if (row.status == 'Confirmed'){
				return 'background-color:#8DF2AD;';
		}
		if (row.status == 'Refused'){
				return 'background-color:#E66775;';
		}
		if (row.status == 'Cancel'){
				return 'background-color:#E66775;';
		}
	}

	frofitformat(value,row,index)
	{
		if ( value <0 ){
				return 'color:red;';
		}
	}

	
	showGridMktCostFileSrc(data)
	{
		$('#costingnegotifilesrcTbl').datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
	
		}).datagrid('enableFilter').datagrid('loadData', data);
	}
	formatMktCostFileSrc(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsCostingNegoti.fileMktCostWindow('+row.style_id+')">'+row.style_ref+'</a>';	
	}
	formatShowMktCostFile(value,row){
		return '<a download href="' + msApp.baseUrl()+"/images/"+row.file_src + '">'+row.original_name+'</a>';
	}

	fileMktCostWindow(style_id){		
		let data= axios.get(msApp.baseUrl()+"/costingnegoti/getmktcostfilesrc?style_id="+style_id);
		data.then(function (response) {
			$('#costingnegotifilesrcTbl').datagrid('loadData', response.data);
			$('#costingnegotifilesrcwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});		
	}

	formatdlmerchant(value,row){
		if (row.team_member) {
			return '<a href="javascript:void(0)" onClick="MsCostingNegoti.dlmerchantWindow('+row.user_id+')">'+row.team_member+'</a>';
		}
		return ;
	}

	dlmerchantWindow(user_id){
		let data= axios.get(msApp.baseUrl()+"/costingnegoti/getdlmerchant?user_id="+user_id);
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
		var dgm = $('#dealmctinfoTbl');
		dgm.datagrid({
		border:false,
		singleSelect:true,
		showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found'

		});
		dgm.datagrid('loadData', data);
	}


}
window.MsCostingNegoti=new MsCostingNegotiController(new MsCostingNegotiModel());
MsCostingNegoti.showGrid([]);
MsCostingNegoti.showGridDlmct([]);
MsCostingNegoti.showGridMktCostFileSrc({rows :{}});
