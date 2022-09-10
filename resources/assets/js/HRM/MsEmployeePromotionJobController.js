

let MsEmployeePromotionJobModel = require('./MsEmployeePromotionJobModel');
class MsEmployeePromotionJobController {
	constructor(MsEmployeePromotionJobModel)
	{
		this.MsEmployeePromotionJobModel = MsEmployeePromotionJobModel;
		this.formId='employeepromotionjobFrm';
		this.dataTable='#employeepromotionjobTbl';
		this.route=msApp.baseUrl()+"/employeepromotionjob"
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
		var employee_h_r_id=$('#employeepromotionFrm  [name=employee_h_r_id]').val();	
		let formObj=msApp.get(this.formId);
		formObj.employee_h_r_id=employee_h_r_id;

        if(formObj.id){
            this.MsEmployeePromotionJobModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeePromotionJobModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let employee_h_r_id = $('#employeepromotionFrm  [name=employee_h_r_id]').val();
		$('#employeepromotionjobFrm [name=employee_h_r_id]').val(employee_h_r_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeePromotionJobModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeePromotionJobModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsEmployeePromotionJob.get();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeePromotionJobModel.get(index,row);
	}

	
	get(){
		var employee_h_r_id=$('#employeepromotionFrm  [name=employee_h_r_id]').val();
		let params={};
		params.employee_h_r_id=employee_h_r_id;
		let d= axios.get(this.route,{params})
		.then(function (response) {
			MsEmployeePromotionJob.showGrid(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}
	
	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeePromotionJob.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsEmployeePromotionJob = new MsEmployeePromotionJobController(new MsEmployeePromotionJobModel());
MsEmployeePromotionJob.showGrid([]);