let MsApprovalVisitorModel = require('./MsApprovalVisitorModel');
require('./../datagrid-filter.js');

class MsApprovalVisitorController {
	constructor(MsApprovalVisitorModel)
	{
		this.MsApprovalVisitorModel = MsApprovalVisitorModel;
		this.formId='approvalvisitorFrm';
		this.dataTable='#approvalvisitorTbl';
		this.route=msApp.baseUrl()+"/approvalvisitor"
	}
	
	
    approve(){
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
	let i=1;
	$.each($('#approvalvisitorTbl').datagrid('getSelections'), function (idx, val) {
		formObj['id['+i+']']=val.id;
		i++;
	});

	this.MsApprovalVisitorModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	
	}
    
	getParams(){
		let params={};
		params.organization_dtl = $('#approvalvisitorFrm  [name=organization_dtl]').val();
		params.date_from = $('#approvalvisitorFrm  [name=date_from]').val();
		params.date_to = $('#approvalvisitorFrm  [name=date_to]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#approvalvisitorTbl').datagrid('loadData', response.data);
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
			singleSelect:false,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

    getVisits(){
        let params=this.getParams();
        let d= axios.get(this.route+'/approvedvisit',{params})
		.then(function (response) {
			$('#approvedvisitorTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	showGridVisitor(data)
	{
		var dg = $('#approvedvisitorTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}



    response(d){
		//$('#approvalvisitorTbl').datagrid('reloadData');
        MsApprovalVisitor.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}


}
window.MsApprovalVisitor = new MsApprovalVisitorController(new MsApprovalVisitorModel());
MsApprovalVisitor.showGrid([]);
MsApprovalVisitor.get();
//sApprovalVisitor.showGridVisitor([]);
MsApprovalVisitor.getVisits();

$('#ApprovalVisitorAccordion').accordion({
	onSelect:function(title,index){
		if(title==='Approved'){
			MsApprovalVisitor.showGridVisitor([]);
			MsApprovalVisitor.getVisits();
		}
	}
})