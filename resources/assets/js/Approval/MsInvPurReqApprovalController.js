let MsInvPurReqApprovalModel = require('./MsInvPurReqApprovalModel');
require('./../datagrid-filter.js');
class MsInvPurReqApprovalController {
	constructor(MsInvPurReqApprovalModel)
	{
		this.MsInvPurReqApprovalModel = MsInvPurReqApprovalModel;
		this.formId='invpurreqapprovalFrm';
		this.dataTable='#invpurreqapprovalTbl';
		this.route=msApp.baseUrl()+"/invpurreqapproval/getdata";
	}
	getParams()
	{
		let params={};
		params.company_id = $('#invpurreqapprovalFrm  [name=company_id]').val();
		params.req_date_from = $('#invpurreqapprovalFrm  [name=req_date_from]').val();
		params.req_date_to = $('#invpurreqapprovalFrm  [name=req_date_to]').val();
		return params;
	}

	show()
	{
		/*var pp = $('#invPurReqApprovalAccordion').accordion('getSelected');
		var index = $('#invPurReqApprovalAccordion').accordion('getPanelIndex', pp);

	    if(index==0)
	    {
			MsInvPurReqApproval.showFirst();
		}
		if(index==1)
		{
			MsInvPurReqApproval.showSecond();
		}
		if(index==2)
		{
			MsInvPurReqApproval.showThird();
		}
		if(index==3)
		{
			MsInvPurReqApproval.showFinal();
		}*/
		var pp = $('#invPurReqApprovalAccordion').accordion('getSelected');
		var title = pp.panel('options').title;
		if(title === "First Approval")
	    {
			MsInvPurReqApproval.showFirst();
		}
		if(title === "Second Approval")
		{
			MsInvPurReqApproval.showSecond();
		}
		if(title === "Third Approval")
		{
			MsInvPurReqApproval.showThird();
		}
		if(title === "Final Approval")
		{
			MsInvPurReqApproval.showFinal();
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
			$('#invpurreqapprovalfirstTbl').datagrid('loadData', response.data);
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
			$('#invpurreqapprovalsecoundTbl').datagrid('loadData', response.data);
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
			$('#invpurreqapprovalthirdTbl').datagrid('loadData', response.data);
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
			$('#invpurreqapprovalfinalTbl').datagrid('loadData', response.data);
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
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
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
		this.MsInvPurReqApprovalModel.save(msApp.baseUrl()+"/invpurreqapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);		
	}
    response(d)
	{
		if(d.type=='firstapproved')
		{
			 MsInvPurReqApproval.showFirst();
		}
		if(d.type=='secondapproved')
		{
			 MsInvPurReqApproval.showSecond();
		}
		if(d.type=='thirdapproved')
		{
			 MsInvPurReqApproval.showThird();
		}
		if(d.type=='finalapproved')
		{
			 MsInvPurReqApproval.showFinal();
		}

		$('#invpurreqApprovalDetailContainer').html('');
		$('#invpurreqApprovalDetailWindow').window('close');
		
	}

	getdata(aproval_type){
		let formObj={};
		let i=1;
		let selected='';
		if(aproval_type=='firstapproved')
		{
			 selected=$('#invpurreqapprovalfirstTbl').datagrid('getSelections');
		}
		if(aproval_type=='secondapproved')
		{
			 selected=$('#invpurreqapprovalsecoundTbl').datagrid('getSelections');
		}
		if(aproval_type=='thirdapproved')
		{
			 selected=$('#invpurreqapprovalthirdTbl').datagrid('getSelections');
		}
		if(aproval_type=='finalapproved')
		{
			 selected=$('#invpurreqapprovalfinalTbl').datagrid('getSelections');
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

	pdf(id){
		if(id==""){
			alert("Select a Purchase Requisition No");
			return;
		}
		window.open(msApp.baseUrl()+"/invpurreq/getprpdf?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsInvPurReqApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}

	showHtml(id,approval_type){
		let params={};
		params.id=id;
		params.approval_type=approval_type;
		let d= axios.get(msApp.baseUrl()+"/invpurreq/html",{params});
		d.then(function (response) {
			$('#invpurreqApprovalDetailContainer').html(response.data);
			$('#invpurreqApprovalDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	formatHtmlFirst(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsInvPurReqApproval.showHtml('+row.id+',\'firstapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlSecond(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsInvPurReqApproval.showHtml('+row.id+',\'secondapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlThird(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsInvPurReqApproval.showHtml('+row.id+',\'thirdapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlFinal(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsInvPurReqApproval.showHtml('+row.id+',\'finalapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}

	approveSingle(aproval_type,inv_pur_req_id){
		if(inv_pur_req_id==''){
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
		formObj['id[1]']=inv_pur_req_id;
		this.MsInvPurReqApprovalModel.save(msApp.baseUrl()+"/invpurreqapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);
	}

	appReturn(aproval_type,inv_pur_req_id){
		if(inv_pur_req_id==''){
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
		let returned_coments=$('#invpurreqaprovalreturncommentFrm  [name=inv_pur_req_aproval_return_comments]').val();
		let formObj={};
		formObj.id=inv_pur_req_id;
		formObj.returned_coments=returned_coments;
		formObj.aproval_type=aproval_type;
		this.MsInvPurReqApprovalModel.save(msApp.baseUrl()+"/invpurreqapprovalreturn",'POST',msApp.qs.stringify(formObj),this.response);
	}

	closeWindow(){
		$('#invpurreqApprovalDetailContainer').html('');
		$('#invpurreqApprovalDetailWindow').window('close');
	}


}
window.MsInvPurReqApproval=new MsInvPurReqApprovalController(new MsInvPurReqApprovalModel());
MsInvPurReqApproval.showGrid([],'#invpurreqapprovalfirstTbl');
MsInvPurReqApproval.showGrid([],'#invpurreqapprovalsecoundTbl');
MsInvPurReqApproval.showGrid([],'#invpurreqapprovalthirdTbl');
MsInvPurReqApproval.showGrid([],'#invpurreqapprovalfinalTbl');

$('#invPurReqApprovalAccordion').accordion({
	onSelect:function(title,index){
		if(title==='First Approval'){
			//MsInvPurReqApproval.showFirst();
		}
		if(title=='Second Approval'){
			MsInvPurReqApproval.showSecond();
		}
		if(title=='Third Approval'){
			MsInvPurReqApproval.showThird();
		}
		if(title=='Final Approval'){
			MsInvPurReqApproval.showFinal();
		}
	}
})