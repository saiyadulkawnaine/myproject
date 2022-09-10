let MsMktCostConfirmationModel = require('./MsMktCostConfirmationModel');
require('./../datagrid-filter.js');
class MsMktCostConfirmationController {
	constructor(MsMktCostConfirmationModel)
	{
		this.MsMktCostConfirmationModel = MsMktCostConfirmationModel;
		this.formId='mktcostconfirmationFrm';
		this.dataTable='#mktcostconfirmationTbl';
		this.route=msApp.baseUrl()+"/mktcostconfirmation/getdata";
	}
	getParams()
	{
		let params={};
		params.buyer_id = $('#mktcostconfirmationFrm  [name=buyer_id]').val();
		params.team_id = $('#mktcostconfirmationFrm  [name=team_id]').val();
		params.teammember_id = $('#mktcostconfirmationFrm  [name=teammember_id]').val();
		params.style_ref = $('#mktcostconfirmationFrm  [name=style_ref]').val();
		params.date_from = $('#mktcostconfirmationFrm  [name=date_from]').val();
		params.date_to = $('#mktcostconfirmationFrm  [name=date_to]').val();
		params.confirm_from = $('#mktcostconfirmationFrm  [name=confirm_from]').val();
		params.confirm_to = $('#mktcostconfirmationFrm  [name=confirm_to]').val();
		params.costing_from = $('#mktcostconfirmationFrm  [name=costing_from]').val();
		params.costing_to = $('#mktcostconfirmationFrm  [name=costing_to]').val();
		return params;
	}

	confirmed(e,id)
	{
		let returned_coments=$('#mktcostaprovalreturncommentFrm  [name=mkt_cost_aproval_return_comments]').val();
		if(returned_coments == ""){
			alert("Please write comments");
			return;
		}
		$.blockUI({
				message: '<i class="icon-spinner4 spinner">Just a moment...</i>',
				overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 999999,
				cursor: 'wait'
			},
			css:{
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});
		

        let formObj={};
        formObj.id=id;
        formObj.returned_coments=returned_coments;
		this.MsMktCostConfirmationModel.save(msApp.baseUrl()+'/mktcostconfirmation/confirmed','POST',msApp.qs.stringify(formObj),this.response);		
	}

	get()
	{
		let params=this.getParams();
        let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#mktcostconfirmationTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

    response(d)
	{
		MsMktCostConfirmation.get();
		$('#mktcostConfirmationDetailContainer').html('');
		$('#mktcostConfirmationDetailWindow').window('close');
	}


	confirmButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostConfirmation.confirmed(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Confirm</span></a>';
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
			

		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	
	imageWindow(flie_src){
		var output = document.getElementById('quotationstatementImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#quotationstatementImageWindow').window('open');
	}
	
	pdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/mktcost/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsMktCostConfirmation.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}

	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsMktCostConfirmation.imageWindow('+'\''+row.flie_src+'\''+')"/>';
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

	
	returned(value,row,index)
	{
		if (row.returned_at !== null){
			return 'color:red;';
		}
	}

	showHtml(id){
		let params={};
		params.id=id;
		let d= axios.get(msApp.baseUrl()+"/mktcost/html",{params});
		d.then(function (response) {
			$('#mktcostConfirmationDetailContainer').html(response.data);
			$('#mktcostConfirmationDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	formatHtml(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsMktCostConfirmation.showHtml('+row.id+')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}

	closeWindow(){
		$('#mktcostConfirmationDetailContainer').html('');
		$('#mktcostConfirmationDetailWindow').window('close');
	}

	getParamsReturned()
	{
		let params={};
		params.buyer_id = $('#mktcostreturnedFrm  [name=buyer_id]').val();
		params.team_id = $('#mktcostreturnedFrm  [name=team_id]').val();
		params.teammember_id = $('#mktcostreturnedFrm  [name=teammember_id]').val();
		params.style_ref = $('#mktcostreturnedFrm  [name=style_ref]').val();
		params.date_from = $('#mktcostreturnedFrm  [name=date_from_return]').val();
		params.date_to = $('#mktcostreturnedFrm  [name=date_to_return]').val();
		params.confirm_from = $('#mktcostreturnedFrm  [name=confirm_from_return]').val();
		params.confirm_to = $('#mktcostreturnedFrm  [name=confirm_to_return]').val();
		params.costing_from = $('#mktcostreturnedFrm  [name=costing_from_return]').val();
		params.costing_to = $('#mktcostreturnedFrm  [name=costing_to_return]').val();
		return params;
	}

	getReturned()
	{
		let params=this.getParamsReturned();
        let d= axios.get(msApp.baseUrl()+"/mktcostconfirmation/getdatareturned",{params})
		.then(function (response) {
			$('#mktcostreturnedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

    responseReturned(d)
	{
		MsMktCostConfirmation.getReturned();
	}

	showGridReturned(data)
	{
		var dg = $('#mktcostreturnedTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getParamsApproved()
	{
		let params={};
		params.buyer_id = $('#mktcostapprovedFrm  [name=buyer_id]').val();
		params.team_id = $('#mktcostapprovedFrm  [name=team_id]').val();
		params.teammember_id = $('#mktcostapprovedFrm  [name=teammember_id]').val();
		params.style_ref = $('#mktcostapprovedFrm  [name=style_ref]').val();
		params.date_from = $('#mktcostapprovedFrm  [name=date_from_approved]').val();
		params.date_to = $('#mktcostapprovedFrm  [name=date_to_approved]').val();
		params.confirm_from = $('#mktcostapprovedFrm  [name=confirm_from_approved]').val();
		params.confirm_to = $('#mktcostapprovedFrm  [name=confirm_to_approved]').val();
		params.costing_from = $('#mktcostapprovedFrm  [name=costing_from_approved]').val();
		params.costing_to = $('#mktcostapprovedFrm  [name=costing_to_approved]').val();
		params.first_approved_from = $('#mktcostapprovedFrm  [name=first_approved_from]').val();
		params.first_approved_to = $('#mktcostapprovedFrm  [name=first_approved_to]').val();
		params.second_approved_from = $('#mktcostapprovedFrm  [name=second_approved_from]').val();
		params.second_approved_to = $('#mktcostapprovedFrm  [name=second_approved_to]').val();
		params.third_approved_from = $('#mktcostapprovedFrm  [name=third_approved_from]').val();
		params.third_approved_to = $('#mktcostapprovedFrm  [name=third_approved_to]').val();
		return params;
	}

	getApproved()
	{
		let params=this.getParamsApproved();
        let d= axios.get(msApp.baseUrl()+"/mktcostconfirmation/getdataapproved",{params})
		.then(function (response) {
			$('#mktcostapprovedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

    responseApproved(d)
	{
		MsMktCostConfirmation.getApproved();
	}

	showGridApproved(data)
	{
		var dg = $('#mktcostapprovedTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var offer_qty=0;
				var amount=0;
				var cm=0;
				
				
				for(var i=0; i<data.rows.length; i++){
					offer_qty+=data.rows[i]['offer_qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					cm+=data.rows[i]['cm'].replace(/,/g,'')*1;
				}
				//tCartonAmount=tCarton_qty*tRate;
				/*if(tCarton_qty){
					tCmRate=(tCmAmount/tCarton_qty)*12;
				}*/
				
				$('#mktcostapprovedTbl').datagrid('reloadFooter', [
					{ 
						offer_qty: offer_qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount: amount.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						cm: cm.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetFormApproved ()
	{
		msApp.resetForm('mktcostapprovedFrm');
	}

}
window.MsMktCostConfirmation=new MsMktCostConfirmationController(new MsMktCostConfirmationModel());
MsMktCostConfirmation.showGrid([]);
MsMktCostConfirmation.showGridReturned([]);
MsMktCostConfirmation.showGridApproved([]);