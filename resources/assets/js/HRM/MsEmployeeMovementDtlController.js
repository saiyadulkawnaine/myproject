let MsEmployeeMovementDtlModel = require('./MsEmployeeMovementDtlModel');
class MsEmployeeMovementDtlController {
	constructor(MsEmployeeMovementDtlModel)
	{
		this.MsEmployeeMovementDtlModel = MsEmployeeMovementDtlModel;
		this.formId='employeemovementdtlFrm';
		this.dataTable='#employeemovementdtlTbl';
		this.route=msApp.baseUrl()+"/employeemovementdtl"
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
            this.MsEmployeeMovementDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeMovementDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		let employee_movement_id = $('#employeemovementFrm  [name=id]').val();
		msApp.resetForm(this.formId);
		$('#employeemovementdtlFrm [name=employee_movement_id]').val(employee_movement_id);
		$('#employeemovementdtlFrm [id="purpose_id"]').combobox('setValue','');
		$('#employeemovementdtlFrm [id="transport_mode_id"]').combobox('setValue','');
		
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeMovementDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeMovementDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeemovementdtlTbl').datagrid('reload');
		let employee_movement_id = $('#employeemovementFrm  [name=id]').val();
		msApp.resetForm('employeemovementdtlFrm');
		$('#employeemovementdtlFrm [name=employee_movement_id]').val(employee_movement_id);
		$('#employeemovementdtlFrm [id="purpose_id"]').combobox('setValue','');
		$('#employeemovementdtlFrm [id="transport_mode_id"]').combobox('setValue','');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let empdtl=this.MsEmployeeMovementDtlModel.get(index,row);
		empdtl.then(function(response){
			$('#employeemovementdtlFrm [id="purpose_id"]').combobox('setValue',response.data.fromData.purpose_id);
			$('#employeemovementdtlFrm [id="transport_mode_id"]').combobox('setValue',response.data.fromData.transport_mode_id);
		})
		.catch(function(error){
			console.log(error);
		})
	}

	showGrid(employee_movement_id)
	{
		let self=this;
		let data={}
		data.employee_movement_id=employee_movement_id
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
		return '<a href="javascript:void(0)" onClick="MsEmployeeMovementDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsEmployeeMovementDtl = new MsEmployeeMovementDtlController(new MsEmployeeMovementDtlModel());