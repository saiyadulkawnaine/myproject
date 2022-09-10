

let MsEmployeeTransferModel = require('./MsEmployeeTransferModel');
require('./../datagrid-filter.js');
class MsEmployeeTransferController {
	constructor(MsEmployeeTransferModel)
	{
		this.MsEmployeeTransferModel = MsEmployeeTransferModel;
		this.formId='employeetransferFrm';
		this.dataTable='#employeetransferTbl';
		this.route=msApp.baseUrl()+"/employeetransfer"
	}

	submit()
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Saving...</i>',
			overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 999999,
				cursor: 'wait'
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});	
		let formObj=msApp.get(this.formId);
        if(formObj.id){
            this.MsEmployeeTransferModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeTransferModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeTransferModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeTransferModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeetransferTbl').datagrid('reload');
		msApp.resetForm('employeetransferFrm');
		//MsEmployeeTransferDtl.resetForm();
	}

	showGrid(){
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeTransferModel.get(index,row);
	}

	openEmpHrWindow(){
		$('#openemphrwindow').window('open');
	}

	getparams(){
		let params={};
		params.company_id=$('#employhrsearchFrm [name=company_id]').val();
		params.designation_id=$('#employhrsearchFrm [name=designation_id]').val();
		params.department_id=$('#employhrsearchFrm [name=department_id]').val();
		params.location_id=$('#employhrsearchFrm [name=location_id]').val();
		return params;
	}

	searchEmployeeHr(){
		let params=this.getparams();
		let grid= axios.get(this.route+"/getemployeehr",{params})
		.then(function(response){
			$('#emphrsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return grid;
	}

	showEmpHrGrid(data){
		let self=this;
		var sg=$('#emphrsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeetransferFrm  [name=employee_h_r_id]').val(row.id);
				$('#employeetransferFrm  [name=employee_name]').val(row.employee_name);
				$('#employeetransferFrm  [name=designation_name]').val(row.designation_name);
				$('#employeetransferFrm  [name=department_name]').val(row.department_name);
				$('#employeetransferFrm  [name=location_name]').val(row.location_name);
				$('#employeetransferFrm  [name=division_name]').val(row.division_name);
				$('#employeetransferFrm  [name=section_name]').val(row.section_name);
				$('#employeetransferFrm  [name=subsection_name]').val(row.subsection_name);
				$('#employeetransferFrm  [name=code]').val(row.code);
				$('#employeetransferFrm  [name=company_name]').val(row.company_name);
				$('#employeetransferFrm  [name=report_to_name]').val(row.report_to_name);

				$('#employeetransferFrm  select[name=company_id]').val(row.company_id);
				$('#employeetransferFrm  [name=code]').val(row.code);
				$('#employeetransferFrm  select[name=location_id]').val(row.location_id);
				$('#employeetransferFrm  select[name=division_id]').val(row.division_id);
				$('#employeetransferFrm  select[name=department_id]').val(row.department_id);
				$('#employeetransferFrm  select[name=section_id]').val(row.section_id);
				$('#employeetransferFrm  select[name=subsection_id]').val(row.subsection_id);
				$('#employeetransferFrm  [name=new_report_to_name]').val(row.report_to_name);
				$('#employeetransferFrm  [name=report_to_id]').val(row.report_to_id);
				
				$('#emphrsearchTbl').datagrid('loadData',[]);
				$('#openemphrwindow').window('close')
			}
		});
		sg.datagrid('enableFilter').datagrid('loadData',data);
	}

	openReportEmpHrWindow(){
		$('#employeetoreportwindow').window('open');
	}

	getEmployeeParams(){
		let params={};
		params.company_id=$('#employeetoreportFrm [name=company_id]').val();
		params.designation_id=$('#employeetoreportFrm [name=designation_id]').val();
		params.department_id=$('#employeetoreportFrm [name=department_id]').val();
		return params;
	}

	searchReportEmployee(){
		let params=this.getEmployeeParams();
		let rpt = axios.get(this.route+"/toreportemployee",{params})
		.then(function(response){
			$('#employeetoreportTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showReportEmployeeGrid(data){
		let self=this;
		var empr=$('#employeetoreportTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeetransferFrm  [name=report_to_id]').val(row.id);
				$('#employeetransferFrm  [name=new_report_to_name]').val(row.employee_name);
				$('#employeetoreportwindow').window('close')
				$('#employeetoreportTbl').datagrid('loadData',[]);
			}
		});
		empr.datagrid('enableFilter').datagrid('loadData',data);
	}

	
	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeTransfer.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsEmployeeTransfer = new MsEmployeeTransferController(new MsEmployeeTransferModel());
MsEmployeeTransfer.showGrid();
MsEmployeeTransfer.showEmpHrGrid([]);
MsEmployeeTransfer.showReportEmployeeGrid([]);

$('#emptransfertabs').tabs({
	onSelect:function(title,index){
	   	let employee_transfer_id = $('#employeetransferFrm  [name=id]').val();
	   let employee_h_r_id = $('#employeetransferFrm  [name=employee_h_r_id]');
		

		
		if(index==1){
			if(employee_h_r_id===''){
				$('#emptransfertabs').tabs('select',0);
				msApp.showError('Select An Employee First',0);
				return;
			}
			$('#employeetransferjobFrm  [name=employee_h_r_id]').val(employee_h_r_id);
			MsEmployeeTransferJob.get();
			//MsEmployeeTransferJob.showGrid(employee_transfer_id);
		}
	}
});