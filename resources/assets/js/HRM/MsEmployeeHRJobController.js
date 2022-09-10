let MsEmployeeHRJobModel = require('./MsEmployeeHRJobModel');
class MsEmployeeHRJobController {
	constructor(MsEmployeeHRJobModel)
	{
		this.MsEmployeeHRJobModel = MsEmployeeHRJobModel;
		this.formId='employeehrjobFrm';
		this.dataTable='#employeehrjobTbl';
		this.route=msApp.baseUrl()+"/employeehrjob"
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
            this.MsEmployeeHRJobModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeHRJobModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#employeehrjobFrm [name=employee_h_r_id]').val($('#employeehrFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeHRJobModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeHRJobModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeehrjobTbl').datagrid('reload');
		$('#employeehrjobFrm [name=employee_h_r_id]').val($('#employeehrFrm [name=id]').val());
		msApp.resetForm('employeehrjobFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeHRJobModel.get(index,row);
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
		return '<a href="javascript:void(0)" onClick="MsEmployeeHRJob.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsEmployeeHRJob = new MsEmployeeHRJobController(new MsEmployeeHRJobModel());