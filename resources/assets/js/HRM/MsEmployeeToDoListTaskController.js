let MsEmployeeToDoListTaskModel = require('./MsEmployeeToDoListTaskModel');
require('../datagrid-filter.js');
class MsEmployeeToDoListTaskController {
	constructor(MsEmployeeToDoListTaskModel)
	{
		this.MsEmployeeToDoListTaskModel = MsEmployeeToDoListTaskModel;
		this.formId='employeetodolisttaskFrm';
		this.dataTable='#employeetodolisttaskTbl';
		this.route=msApp.baseUrl()+"/employeetodolisttask"
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
            this.MsEmployeeToDoListTaskModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeToDoListTaskModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeToDoListTaskModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeToDoListTaskModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#employeetodolisttaskTbl').datagrid('reload');
		let employee_to_do_list_id = $('#employeetodolistFrm  [name=id]').val();
		MsEmployeeToDoListTask.get(employee_to_do_list_id);
		msApp.resetForm('employeetodolisttaskFrm');
		$('#employeetodolisttaskFrm  [name=employee_to_do_list_id]').val(employee_to_do_list_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeToDoListTaskModel.get(index,row);
	}

	get(employee_to_do_list_id)
	{
		let params={};
		params.employee_to_do_list_id=employee_to_do_list_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#employeetodolisttaskTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeToDoListTask.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsEmployeeToDoListTask = new MsEmployeeToDoListTaskController(new MsEmployeeToDoListTaskModel());
MsEmployeeToDoListTask.showGrid([]);