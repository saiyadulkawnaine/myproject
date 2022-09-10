let MsEmployeeToDoListModel = require('./MsEmployeeToDoListModel');
require('../datagrid-filter.js');
class MsEmployeeToDoListController {
	constructor(MsEmployeeToDoListModel)
	{
		this.MsEmployeeToDoListModel = MsEmployeeToDoListModel;
		this.formId='employeetodolistFrm';
		this.dataTable='#employeetodolistTbl';
		this.route=msApp.baseUrl()+"/employeetodolist"
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
            this.MsEmployeeToDoListModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeToDoListModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeToDoListModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeToDoListModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeetodolistTbl').datagrid('reload');
		msApp.resetForm('employeetodolistFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeToDoListModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeToDoList.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsEmployeeToDoList = new MsEmployeeToDoListController(new MsEmployeeToDoListModel());
MsEmployeeToDoList.showGrid();
$('#EmployeeToDoListTabs').tabs({
	onSelect:function(title,index){
	    let employee_to_do_list_id = $('#employeetodolistFrm  [name=id]').val();
	    let employee_to_do_list_task_id = $('#employeetodolisttaskFrm  [name=id]').val();
		if(index==1){
			if(employee_to_do_list_id===''){
				$('#EmployeeToDoListTabs').tabs('select',0);
				msApp.showError('Select An List',0);
				return;
			}
			msApp.resetForm('employeetodolisttaskFrm');
			$('#employeetodolisttaskFrm  [name=employee_to_do_list_id]').val(employee_to_do_list_id)
			MsEmployeeToDoListTask.get(employee_to_do_list_id);
		}
		if(index==2){
			if(employee_to_do_list_task_id===''){
				$('#EmployeeToDoListTabs').tabs('select',1);
				msApp.showError('Select An List',1);
				return;
			}
			msApp.resetForm('employeetodolisttaskbarFrm');
			$('#employeetodolisttaskbarFrm  [name=employee_to_do_list_task_id]').val(employee_to_do_list_task_id)
			MsEmployeeToDoListTaskBar.get(employee_to_do_list_task_id);
		}


	}
});