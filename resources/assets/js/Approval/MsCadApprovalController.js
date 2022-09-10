let MsCadApprovalModel = require('./MsCadApprovalModel');
require('./../datagrid-filter.js');
class MsCadApprovalController {
	constructor(MsCadApprovalModel)
	{
		this.MsCadApprovalModel = MsCadApprovalModel;
		this.formId='cadapprovalFrm';
		this.dataTable='#cadapprovalTbl';
		this.route=msApp.baseUrl()+"/cadapproval/getdata";
	}
	getParams()
	{
		let params={};
		params.cad_date_from = $('#cadapprovalFrm  [name=cad_date_from]').val();
		params.cad_date_to = $('#cadapprovalFrm  [name=cad_date_to]').val();
		return params;
	}

	show()
	{
		/*var pp = $('#cadApprovalAccordion').accordion('getSelected');
		var index = $('#cadApprovalAccordion').accordion('getPanelIndex', pp);

	    if(index==0)
	    {
			MsCadApproval.showFirst();
		}
		if(index==1)
		{
			MsCadApproval.showSecond();
		}
		if(index==2)
		{
			MsCadApproval.showThird();
		}
		if(index==3)
		{
			MsCadApproval.showFinal();
		}*/
		var pp = $('#cadApprovalAccordion').accordion('getSelected');
		var title = pp.panel('options').title;
		if(title === "First Approval")
	    {
			MsCadApproval.showFirst();
		}
		if(title === "Second Approval")
		{
			MsCadApproval.showSecond();
		}
		if(title === "Third Approval")
		{
			MsCadApproval.showThird();
		}
		if(title === "Final Approval")
		{
			MsCadApproval.showFinal();
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
			$('#cadapprovalfirstTbl').datagrid('loadData', response.data);
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
			$('#cadapprovalsecoundTbl').datagrid('loadData', response.data);
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
			$('#cadapprovalthirdTbl').datagrid('loadData', response.data);
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
			$('#cadapprovalfinalTbl').datagrid('loadData', response.data);
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
		this.MsCadApprovalModel.save(msApp.baseUrl()+"/cadapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);		
	}
    response(d)
	{
		if(d.type=='firstapproved')
		{
			 MsCadApproval.showFirst();
		}
		if(d.type=='secondapproved')
		{
			 MsCadApproval.showSecond();
		}
		if(d.type=='thirdapproved')
		{
			 MsCadApproval.showThird();
		}
		if(d.type=='finalapproved')
		{
			 MsCadApproval.showFinal();
		}

		$('#cadApprovalDetailContainer').html('');
		$('#cadApprovalDetailWindow').window('close');
		
	}

	getdata(aproval_type){
		let formObj={};
		let i=1;
		let selected='';
		if(aproval_type=='firstapproved')
		{
			 selected=$('#cadapprovalfirstTbl').datagrid('getSelections');
		}
		if(aproval_type=='secondapproved')
		{
			 selected=$('#cadapprovalsecoundTbl').datagrid('getSelections');
		}
		if(aproval_type=='thirdapproved')
		{
			 selected=$('#cadapprovalthirdTbl').datagrid('getSelections');
		}
		if(aproval_type=='finalapproved')
		{
			 selected=$('#cadapprovalfinalTbl').datagrid('getSelections');
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
		window.open(msApp.baseUrl()+"/cad/getprpdf?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsCadApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}

	showHtml(id,approval_type){
		let params={};
		params.id=id;
		params.approval_type=approval_type;
		let d= axios.get(msApp.baseUrl()+"/cad/html",{params});
		d.then(function (response) {
			$('#cadApprovalDetailContainer').html(response.data);
			$('#cadApprovalDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	formatHtmlFirst(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsCadApproval.showHtml('+row.id+',\'firstapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlSecond(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsCadApproval.showHtml('+row.id+',\'secondapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlThird(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsCadApproval.showHtml('+row.id+',\'thirdapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlFinal(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsCadApproval.showHtml('+row.id+',\'finalapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}

	approveSingle(aproval_type,cad_id){
		if(cad_id==''){
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
		formObj['id[1]']=cad_id;
		this.MsCadApprovalModel.save(msApp.baseUrl()+"/cadapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);
	}

	appReturn(aproval_type,cad_id){
		if(cad_id==''){
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
		let returned_coments=$('#cadaprovalreturncommentFrm  [name=cad_aproval_return_comments]').val();
		let formObj={};
		formObj.id=cad_id;
		formObj.returned_coments=returned_coments;
		formObj.aproval_type=aproval_type;
		this.MsCadApprovalModel.save(msApp.baseUrl()+"/cadapprovalreturn",'POST',msApp.qs.stringify(formObj),this.response);
	}

	closeWindow(){
		$('#cadApprovalDetailContainer').html('');
		$('#cadApprovalDetailWindow').window('close');
	}


}
window.MsCadApproval=new MsCadApprovalController(new MsCadApprovalModel());
MsCadApproval.showGrid([],'#cadapprovalfirstTbl');
MsCadApproval.showGrid([],'#cadapprovalsecoundTbl');
MsCadApproval.showGrid([],'#cadapprovalthirdTbl');
MsCadApproval.showGrid([],'#cadapprovalfinalTbl');

$('#cadApprovalAccordion').accordion({
	onSelect:function(title,index){
		if(title==='First Approval'){
			//MsCadApproval.showFirst();
		}
		if(title=='Second Approval'){
			MsCadApproval.showSecond();
		}
		if(title=='Third Approval'){
			MsCadApproval.showThird();
		}
		if(title=='Final Approval'){
			MsCadApproval.showFinal();
		}
	}
})