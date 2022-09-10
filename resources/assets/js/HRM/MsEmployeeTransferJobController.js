

let MsEmployeeTransferJobModel = require('./MsEmployeeTransferJobModel');
class MsEmployeeTransferJobController {
	constructor(MsEmployeeTransferJobModel)
	{
		this.MsEmployeeTransferJobModel = MsEmployeeTransferJobModel;
		this.formId='employeetransferjobFrm';
		this.dataTable='#employeetransferjobTbl';
		this.route=msApp.baseUrl()+"/employeetransferjob"
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
		var employee_h_r_id=$('#employeetransferFrm  [name=employee_h_r_id]').val();	
		let formObj=msApp.get(this.formId);
		formObj.employee_h_r_id=employee_h_r_id;

        if(formObj.id){
            this.MsEmployeeTransferJobModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeTransferJobModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let employee_h_r_id = $('#employeetransferFrm  [name=employee_h_r_id]').val();
		$('#employeetransferjobFrm [name=employee_h_r_id]').val(employee_h_r_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeTransferJobModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeTransferJobModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsEmployeeTransferJob.get();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeTransferJobModel.get(index,row);
	}

	

	get(){
		var employee_h_r_id=$('#employeetransferFrm  [name=employee_h_r_id]').val();
		let params={};
		params.employee_h_r_id=employee_h_r_id;
		let d= axios.get(this.route,{params})
		.then(function (response) {
			MsEmployeeTransferJob.showGrid(response.data);
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
		return '<a href="javascript:void(0)" onClick="MsEmployeeTransferJob.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsEmployeeTransferJob = new MsEmployeeTransferJobController(new MsEmployeeTransferJobModel());
MsEmployeeTransferJob.showGrid([]);