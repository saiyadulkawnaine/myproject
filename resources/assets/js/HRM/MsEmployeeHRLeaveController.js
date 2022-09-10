let MsEmployeeHRLeaveModel = require('./MsEmployeeHRLeaveModel');
class MsEmployeeHRLeaveController {
	constructor(MsEmployeeHRLeaveModel)
	{
		this.MsEmployeeHRLeaveModel = MsEmployeeHRLeaveModel;
		this.formId='employeehrleaveFrm';
		this.dataTable='#employeehrleaveTbl';
		this.route=msApp.baseUrl()+"/employeehrleave"
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
            this.MsEmployeeHRLeaveModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeHRLeaveModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#employeehrleaveFrm [name=employee_h_r_id]').val($('#employeehrFrm [name=id]').val());
		
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeHRLeaveModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeHRLeaveModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeehrleaveTbl').datagrid('reload');
		$('#employeehrleaveFrm [name=employee_h_r_id]').val($('#employeehrFrm [name=id]').val());
		
		msApp.resetForm('employeehrleaveFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeHRLeaveModel.get(index,row);
	}

	showGrid(employee_h_r_id)
	{
		let self=this;
		let data={}
		data.employee_h_r_id=employee_h_r_id
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeHRLeave.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsEmployeeHRLeave = new MsEmployeeHRLeaveController(new MsEmployeeHRLeaveModel());