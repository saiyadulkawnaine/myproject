let MsEmployeeToDoListTaskBarModel = require('./MsEmployeeToDoListTaskBarModel');
require('../datagrid-filter.js');
class MsEmployeeToDoListTaskBarController {
	constructor(MsEmployeeToDoListTaskBarModel)
	{
		this.MsEmployeeToDoListTaskBarModel = MsEmployeeToDoListTaskBarModel;
		this.formId='employeetodolisttaskbarFrm';
		this.dataTable='#employeetodolisttaskbarTbl';
		this.route=msApp.baseUrl()+"/employeetodolisttaskbar"
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
            this.MsEmployeeToDoListTaskBarModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeToDoListTaskBarModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeToDoListTaskBarModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeToDoListTaskBarModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#employeetodolisttaskTbl').datagrid('reload');
		let employee_to_do_list_task_id = $('#employeetodolisttaskFrm  [name=id]').val();
		MsEmployeeToDoListTaskBar.get(employee_to_do_list_task_id);
		msApp.resetForm('employeetodolisttaskbarFrm');
		$('#employeetodolisttaskbarFrm  [name=employee_to_do_list_task_id]').val(employee_to_do_list_task_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeToDoListTaskBarModel.get(index,row);
	}

	get(employee_to_do_list_task_id)
	{
		let params={};
		params.employee_to_do_list_task_id=employee_to_do_list_task_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#employeetodolisttaskbarTbl').datagrid('loadData',response.data);
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
		return '<a href="javascript:void(0)" onClick="MsEmployeeToDoListTaskBar.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsEmployeeToDoListTaskBar = new MsEmployeeToDoListTaskBarController(new MsEmployeeToDoListTaskBarModel());
MsEmployeeToDoListTaskBar.showGrid([]);