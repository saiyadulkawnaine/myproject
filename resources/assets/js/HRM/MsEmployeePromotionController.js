

let MsEmployeePromotionModel = require('./MsEmployeePromotionModel');
require('./../datagrid-filter.js');
class MsEmployeePromotionController {
	constructor(MsEmployeePromotionModel)
	{
		this.MsEmployeePromotionModel = MsEmployeePromotionModel;
		this.formId='employeepromotionFrm';
		this.dataTable='#employeepromotionTbl';
		this.route=msApp.baseUrl()+"/employeepromotion"
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
            this.MsEmployeePromotionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeePromotionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeePromotionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeePromotionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeepromotionTbl').datagrid('reload');
		msApp.resetForm('employeepromotionFrm');
		//MsEmployeePromotionDtl.resetForm();
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
		this.MsEmployeePromotionModel.get(index,row);
	}

	openEmpHrWindow(){
		$('#openemphrpromotionwindow').window('open');
	}

	getparams(){
		let params={};
		params.company_id=$('#employhrsearchpromotionFrm [name=company_id]').val();
		params.designation_id=$('#employhrsearchpromotionFrm [name=designation_id]').val();
		params.department_id=$('#employhrsearchpromotionFrm [name=department_id]').val();
		params.location_id=$('#employhrsearchpromotionFrm [name=location_id]').val();
		return params;
	}

	searchEmployeeHr(){
		let params=this.getparams();
		let grid= axios.get(this.route+"/getemployeehr",{params})
		.then(function(response){
			$('#emphrsearchpromotionTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return grid;
	}

	showEmpHrGrid(data){
		let self=this;
		var sg=$('#emphrsearchpromotionTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeepromotionFrm  [name=employee_h_r_id]').val(row.id);
				$('#employeepromotionFrm  [name=employee_name]').val(row.employee_name);
				$('#employeepromotionFrm  [name=code]').val(row.code);
				$('#employeepromotionFrm  [name=designation_name]').val(row.designation_name);
				$('#employeepromotionFrm  [name=old_grade]').val(row.grade);

				$('#employeepromotionFrm  [name=company_name]').val(row.company_name);
				$('#employeepromotionFrm  [name=location_name]').val(row.location_name);
				$('#employeepromotionFrm  [name=division_name]').val(row.division_name);
				$('#employeepromotionFrm  [name=department_name]').val(row.department_name);
				$('#employeepromotionFrm  [name=section_name]').val(row.section_name);
				$('#employeepromotionFrm  [name=subsection_name]').val(row.subsection_name);
                $('#employeepromotionFrm  [name=old_report_to_name]').val(row.report_to_name);

                //$('#employeepromotionFrm  select[name=designation_id]').val(row.designation_id);
                $('#employeepromotionFrm  [name=grade]').val(row.grade);
                $('#employeepromotionFrm  [name=report_to_name]').val(row.report_to_name);
				$('#employeepromotionFrm  [name=report_to_id]').val(row.report_to_id);			
				
				$('#emphrsearchpromotionTbl').datagrid('loadData',[]);
				$('#openemphrpromotionwindow').window('close')
			}
		});
		sg.datagrid('enableFilter').datagrid('loadData',data);
	}


	openReportEmpHrWindow(){
		$('#employeepromotiontoreportwindow').window('open');
	}

	getEmployeeParams(){
		let params={};
		params.company_id=$('#employeepromotiontoreportFrm [name=company_id]').val();
		params.designation_id=$('#employeepromotiontoreportFrm [name=designation_id]').val();
		params.department_id=$('#employeepromotiontoreportFrm [name=department_id]').val();
		return params;
	}

	searchReportEmployee(){
		let params=this.getEmployeeParams();
		let rpt = axios.get(this.route+"/toreportemployee",{params})
		.then(function(response){
			$('#employeepromotiontoreportTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showReportEmployeeGrid(data){
		let self=this;
		var empr=$('#employeepromotiontoreportTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeepromotionFrm  [name=report_to_id]').val(row.id);
				$('#employeepromotionFrm  [name=report_to_name]').val(row.employee_name);
				$('#employeepromotiontoreportwindow').window('close')
				$('#employeepromotiontoreportTbl').datagrid('loadData',[]);
			}
		});
		empr.datagrid('enableFilter').datagrid('loadData',data);
	}

	
	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeePromotion.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsEmployeePromotion = new MsEmployeePromotionController(new MsEmployeePromotionModel());
MsEmployeePromotion.showGrid();
MsEmployeePromotion.showEmpHrGrid([]);
MsEmployeePromotion.showReportEmployeeGrid([]);

$('#emppromotiontabs').tabs({
	onSelect:function(title,index){
	   	let employee_promotion_id = $('#employeepromotionFrm  [name=id]').val();
	   	let employee_h_r_id = $('#employeepromotionFrm  [name=employee_h_r_id]');
		

		
		if(index==1){
			if(employee_promotion_id===''){
				$('#emppromotiontabs').tabs('select',0);
				msApp.showError('Select An Employee First',0);
				return;
			}
			$('#employeepromotionjobFrm  [name=employee_h_r_id]').val(employee_h_r_id);
			MsEmployeePromotionJob.get();
		}
	}
});