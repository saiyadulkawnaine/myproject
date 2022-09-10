let MsEmployeeRecruitReqJobModel = require('./MsEmployeeRecruitReqJobModel');
class MsEmployeeRecruitReqJobController {
	constructor(MsEmployeeRecruitReqJobModel)
	{
		this.MsEmployeeRecruitReqJobModel = MsEmployeeRecruitReqJobModel;
		this.formId='employeerecruitreqjobFrm';
		this.dataTable='#employeerecruitreqjobTbl';
		this.route=msApp.baseUrl()+"/employeerecruitreqjob"
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
            this.MsEmployeeRecruitReqJobModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeRecruitReqJobModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#employeerecruitreqjobFrm [name=employee_recruit_req_id]').val($('#employeerecruitreqFrm [name=id]').val());		
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeRecruitReqJobModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeRecruitReqJobModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeerecruitreqjobTbl').datagrid('reload');
        msApp.resetForm('employeerecruitreqjobFrm');
		$('#employeerecruitreqjobFrm [name=employee_recruit_req_id]').val($('#employeerecruitreqFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeRecruitReqJobModel.get(index,row);
	}

	showGrid(employee_recruit_req_id)
	{
		let self=this;
		let data={}
		data.employee_recruit_req_id=employee_recruit_req_id
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeRecruitReqJob.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


}
window.MsEmployeeRecruitReqJob = new MsEmployeeRecruitReqJobController(new MsEmployeeRecruitReqJobModel());