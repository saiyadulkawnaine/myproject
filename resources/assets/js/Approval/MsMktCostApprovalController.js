let MsMktCostApprovalModel = require('./MsMktCostApprovalModel');
require('./../datagrid-filter.js');
class MsMktCostApprovalController {
	constructor(MsMktCostApprovalModel)
	{
		this.MsMktCostApprovalModel = MsMktCostApprovalModel;
		this.formId='mktcostapprovalFrm';
		this.dataTable='#mktcostapprovalTbl';
		this.route=msApp.baseUrl()+"/mktcostapproval/getdata";
	}
	getParams()
	{
		let params={};
		params.buyer_id = $('#mktcostapprovalFrm  [name=buyer_id]').val();
		params.team_id = $('#mktcostapprovalFrm  [name=team_id]').val();
		params.teammember_id = $('#mktcostapprovalFrm  [name=teammember_id]').val();
		params.style_ref = $('#mktcostapprovalFrm  [name=style_ref]').val();
		params.date_from = $('#mktcostapprovalFrm  [name=date_from]').val();
		params.date_to = $('#mktcostapprovalFrm  [name=date_to]').val();
		params.confirm_from = $('#mktcostapprovalFrm  [name=confirm_from]').val();
		params.confirm_to = $('#mktcostapprovalFrm  [name=confirm_to]').val();
		params.costing_from = $('#mktcostapprovalFrm  [name=costing_from]').val();
		params.costing_to = $('#mktcostapprovalFrm  [name=costing_to]').val();
		return params;
	}

	show()
	{
		var pp = $('#mktCostApprovalAccordion').accordion('getSelected');
		var index = $('#mktCostApprovalAccordion').accordion('getPanelIndex', pp);
	    if(index==0)
	    {
			MsMktCostApproval.showFirst();
		}
		if(index==1)
		{
			MsMktCostApproval.showSecond();
		}
		if(index==2)
		{
			MsMktCostApproval.showThird();
		}
		if(index==3)
		{
			MsMktCostApproval.showFinal();
		}
	}

	showFirst()
	{
		let params=this.getParams();
		params.approval_type_id=1;
		if(!params.approval_type_id){
			alert('Select Approval Type');
			return;
		}
		let d=this.get(params);
		d.then(function (response) {
			$('#mktcostapprovalfirstTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showSecond()
	{
		let params=this.getParams();
		params.approval_type_id=2;
		if(!params.approval_type_id){
			alert('Select Approval Type');
			return;
		}
		let d=this.get(params);
		d.then(function (response) {
			$('#mktcostapprovalsecondTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showThird()
	{
		let params=this.getParams();
		params.approval_type_id=3;
		if(!params.approval_type_id){
			alert('Select Approval Type');
			return;
		}
		let d=this.get(params);
		d.then(function (response) {
			$('#mktcostapprovalthirdTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showFinal()
	{
		let params=this.getParams();
		params.approval_type_id=10;
		if(!params.approval_type_id){
			alert('Select Approval Type');
			return;
		}
		let d=this.get(params);
		d.then(function (response) {
			$('#mktcostapprovalfinalTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get(params)
	{
		let d= axios.get(this.route,{params});
		return d;
	}
	showGrid(data,table)
	{
		var dg = $(table);
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			rowStyler:function(index,row){
				if(row.return_to_third_approved_at){
					return 'background-color:red;';
				}
			}
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

	showHtml(id,approval_type){
		let params={};
		params.id=id;
		params.approval_type=approval_type;
		let d= axios.get(msApp.baseUrl()+"/mktcost/html",{params});
		d.then(function (response) {
			$('#mktcostApprovalDetailContainer').html(response.data);
			$('#mktcostApprovalDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsMktCostApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}
	formatHtmlFirst(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsMktCostApproval.showHtml('+row.id+',\'firstapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlSecond(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsMktCostApproval.showHtml('+row.id+',\'secondapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlThird(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsMktCostApproval.showHtml('+row.id+',\'thirdapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlFinal(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsMktCostApproval.showHtml('+row.id+',\'finalapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}

	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsMktCostApproval.imageWindow('+'\''+row.flie_src+'\''+')"/>';
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

	approved(aproval_type)
	{
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
		formObj=this.getdata(aproval_type);
		
		if(!Object.keys(formObj).length){
			alert("Please select at least one item");
			$.unblockUI();
			return;
		}
		this.MsMktCostApprovalModel.save(msApp.baseUrl()+"/mktcostapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);		
	}
    response(d)
	{
		if(d.type=='firstapproved')
		{
			 MsMktCostApproval.showFirst();
		}
		if(d.type=='secondapproved')
		{
			 MsMktCostApproval.showSecond();
		}
		if(d.type=='thirdapproved')
		{
			 MsMktCostApproval.showThird();
		}
		if(d.type=='finalapproved')
		{
			 MsMktCostApproval.showFinal();
		}

		$('#mktcostApprovalDetailContainer').html('');
		$('#mktcostApprovalDetailWindow').window('close');
		
	}

	getdata(aproval_type){
		let formObj={};
		let i=1;
		let selected='';
		if(aproval_type=='firstapproved')
		{
			 selected=$('#mktcostapprovalfirstTbl').datagrid('getSelections');
		}
		if(aproval_type=='secondapproved')
		{
			 selected=$('#mktcostapprovalsecondTbl').datagrid('getSelections');
		}
		if(aproval_type=='thirdapproved')
		{
			 selected=$('#mktcostapprovalthirdTbl').datagrid('getSelections');
		}
		if(aproval_type=='finalapproved')
		{
			 selected=$('#mktcostapprovalfinalTbl').datagrid('getSelections');
		}
		$.each(selected, function (idx, val) {
			formObj['id['+i+']']=val.id;
			i++;
		});
		return formObj;
	}

	selectAll(table)
	{
		$(table).datagrid('selectAll');
	}
	unselectAll(table)
	{
		$(table).datagrid('unselectAll');
	}

	approveSingle(aproval_type,mkt_cost_id){
		if(mkt_cost_id==''){
			alert("ID not found");
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
		formObj['id[1]']=mkt_cost_id;
		this.MsMktCostApprovalModel.save(msApp.baseUrl()+"/mktcostapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);
	}

	appReturn(aproval_type,mkt_cost_id){
		if(mkt_cost_id==''){
			alert("ID not found");
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
		let returned_coments=$('#mktcostaprovalreturncommentFrm  [name=mkt_cost_aproval_return_comments]').val();
		let formObj={};
		formObj.id=mkt_cost_id;
		formObj.returned_coments=returned_coments;
		formObj.aproval_type=aproval_type;
		this.MsMktCostApprovalModel.save(msApp.baseUrl()+"/mktcostapprovalreturn",'POST',msApp.qs.stringify(formObj),this.response);
	}

	closeWindow(){
		$('#mktcostApprovalDetailContainer').html('');
		$('#mktcostApprovalDetailWindow').window('close');
	}


}
window.MsMktCostApproval=new MsMktCostApprovalController(new MsMktCostApprovalModel());
MsMktCostApproval.showGrid([],'#mktcostapprovalfirstTbl');
MsMktCostApproval.showGrid([],'#mktcostapprovalsecondTbl');
MsMktCostApproval.showGrid([],'#mktcostapprovalthirdTbl');
MsMktCostApproval.showGrid([],'#mktcostapprovalfinalTbl');

$('#mktCostApprovalAccordion').accordion({
	onSelect:function(title,index){
		if(title==='First Approval'){
			//MsMktCostApproval.showFirst();
		}
		if(title=='Second Approval'){
			MsMktCostApproval.showSecond();
		}
		if(title=='Third Approval'){
			MsMktCostApproval.showThird();
		}
		if(title=='Final Approval'){
			MsMktCostApproval.showFinal();
		}
	}
})
