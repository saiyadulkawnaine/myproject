let MsInvGeneralItemIsuReqApprovalModel = require('./MsInvGeneralItemIsuReqApprovalModel');
require('./../datagrid-filter.js');
class MsInvGeneralItemIsuReqApprovalController {
	constructor(MsInvGeneralItemIsuReqApprovalModel)
	{
		this.MsInvGeneralItemIsuReqApprovalModel = MsInvGeneralItemIsuReqApprovalModel;
		this.formId='invgeneralitemisureqapprovalFrm';
		this.dataTable='#invgeneralitemisureqapprovalTbl';
		this.route=msApp.baseUrl()+"/invgeneralitemisureqapproval/getdata";
	}
	getParams()
	{
		let params={};
		params.company_id = $('#invgeneralitemisureqapprovalFrm  [name=company_id]').val();
		params.req_date_from = $('#invgeneralitemisureqapprovalFrm  [name=req_date_from]').val();
		params.req_date_to = $('#invgeneralitemisureqapprovalFrm  [name=req_date_to]').val();
		return params;
	}

	show()
	{
		var pp = $('#invGeneralItemIsuReqApprovalAccordion').accordion('getSelected');
		var index = $('#invGeneralItemIsuReqApprovalAccordion').accordion('getPanelIndex', pp);
	    if(index==0)
	    {
			MsInvGeneralItemIsuReqApproval.showFirst();
		}
		if(index==1)
		{
			MsInvGeneralItemIsuReqApproval.showSecond();
		}
		if(index==2)
		{
			MsInvGeneralItemIsuReqApproval.showThird();
		}
		if(index==3)
		{
			MsInvGeneralItemIsuReqApproval.showFinal();
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
			$('#invgeneralitemisureqapprovalfirstTbl').datagrid('loadData', response.data);
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
			$('#invgeneralitemisureqapprovalsecoundTbl').datagrid('loadData', response.data);
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
			$('#invgeneralitemisureqapprovalthirdTbl').datagrid('loadData', response.data);
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
			$('#invgeneralitemisureqapprovalfinalTbl').datagrid('loadData', response.data);
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
		this.MsInvGeneralItemIsuReqApprovalModel.save(msApp.baseUrl()+"/invgeneralitemisureqapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);		
	}
    response(d)
	{
		if(d.type=='firstapproved')
		{
			 MsInvGeneralItemIsuReqApproval.showFirst();
		}
		if(d.type=='secondapproved')
		{
			 MsInvGeneralItemIsuReqApproval.showSecond();
		}
		if(d.type=='thirdapproved')
		{
			 MsInvGeneralItemIsuReqApproval.showThird();
		}
		if(d.type=='finalapproved')
		{
			 MsInvGeneralItemIsuReqApproval.showFinal();
		}
		
	}

	getdata(aproval_type){
		let formObj={};
		let i=1;
		let selected='';
		if(aproval_type=='firstapproved')
		{
			 selected=$('#invgeneralitemisureqapprovalfirstTbl').datagrid('getSelections');
		}
		if(aproval_type=='secondapproved')
		{
			 selected=$('#invgeneralitemisureqapprovalsecoundTbl').datagrid('getSelections');
		}
		if(aproval_type=='thirdapproved')
		{
			 selected=$('#invgeneralitemisureqapprovalthirdTbl').datagrid('getSelections');
		}
		if(aproval_type=='finalapproved')
		{
			 selected=$('#invgeneralitemisureqapprovalfinalTbl').datagrid('getSelections');
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
			alert("Select a General Item Issue chase Requisition No");
			return;
		}
		window.open(msApp.baseUrl()+"/invgeneralisurq/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralItemIsuReqApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}


}
window.MsInvGeneralItemIsuReqApproval=new MsInvGeneralItemIsuReqApprovalController(new MsInvGeneralItemIsuReqApprovalModel());
MsInvGeneralItemIsuReqApproval.showGrid([],'#invgeneralitemisureqapprovalfirstTbl');
MsInvGeneralItemIsuReqApproval.showGrid([],'#invgeneralitemisureqapprovalsecoundTbl');
MsInvGeneralItemIsuReqApproval.showGrid([],'#invgeneralitemisureqapprovalthirdTbl');
MsInvGeneralItemIsuReqApproval.showGrid([],'#invgeneralitemisureqapprovalfinalTbl');

$('#invGeneralItemIsuReqApprovalAccordion').accordion({
	onSelect:function(title,index){
		if(title==='First Approval'){
			//MsInvGeneralItemIsuReqApproval.showFirst();
		}
		if(title=='Second Approval'){
			MsInvGeneralItemIsuReqApproval.showSecond();
		}
		if(title=='Third Approval'){
			MsInvGeneralItemIsuReqApproval.showThird();
		}
		if(title=='Final Approval'){
			MsInvGeneralItemIsuReqApproval.showFinal();
		}
	}
})