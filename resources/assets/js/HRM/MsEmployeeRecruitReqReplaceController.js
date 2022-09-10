let MsEmployeeRecruitReqReplaceModel = require('./MsEmployeeRecruitReqReplaceModel');
class MsEmployeeRecruitReqReplaceController {
	constructor(MsEmployeeRecruitReqReplaceModel)
	{
		this.MsEmployeeRecruitReqReplaceModel = MsEmployeeRecruitReqReplaceModel;
		this.formId='employeerecruitreqreplaceFrm';
		this.dataTable='#employeerecruitreqreplaceTbl';
		this.route=msApp.baseUrl()+"/employeerecruitreqreplace"
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
            this.MsEmployeeRecruitReqReplaceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeRecruitReqReplaceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#employeerecruitreqreplaceFrm [name=employee_recruit_req_id]').val($('#employeerecruitreqFrm [name=id]').val());		
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeRecruitReqReplaceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeRecruitReqReplaceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeerecruitreqreplaceTbl').datagrid('reload');
        msApp.resetForm('employeerecruitreqreplaceFrm');
		$('#employeerecruitreqreplaceFrm [name=employee_recruit_req_id]').val($('#employeerecruitreqFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeRecruitReqReplaceModel.get(index,row);
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
		return '<a href="javascript:void(0)" onClick="MsEmployeeRecruitReqReplace.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openRepEmployeeWindow(){
		$('#replaceemployeesearchwindow').window('open');
	}

	getParams(){
		let params={};
		params.company_id=$('#replaceemployeesearchFrm [name=company_id]').val();
		params.designation_id=$('#replaceemployeesearchFrm [name=designation_id]').val();
		params.department_id=$('#replaceemployeesearchFrm [name=department_id]').val();
		return params;
	}

	searchReplaceEmployee(){
		let params=this.getParams();
		let rpt = axios.get(this.route+"/getreplaceemployee",{params})
		.then(function(response){
			$('#replaceemployeesearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmployeeGrid(data){
		let self=this;
		var pr=$('#replaceemployeesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeerecruitreqreplaceFrm  [name=employee_h_r_id]').val(row.id);
				$('#employeerecruitreqreplaceFrm  [name=employee_name]').val(row.name);
				$('#replaceemployeesearchwindow').window('close');
				$('#replaceemployeesearchTbl').datagrid('loadData',[]);
				
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsEmployeeRecruitReqReplace = new MsEmployeeRecruitReqReplaceController(new MsEmployeeRecruitReqReplaceModel());
MsEmployeeRecruitReqReplace.showEmployeeGrid([])