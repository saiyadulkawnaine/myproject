//require('./jquery.easyui.min.js');
let MsDepartmentModel = require('./MsDepartmentModel');
require('./datagrid-filter.js');

class MsDepartmentController {
	constructor(MsDepartmentModel)
	{
		this.MsDepartmentModel = MsDepartmentModel;
		this.formId='departmentFrm';
		this.dataTable='#departmentTbl';
		this.route=msApp.baseUrl()+"/department"
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

		// let floorId= new Array();
		// $('#floorBox2 option').map(function(i, el) {
		// 	floorId.push($(el).val());
		// });
		// $('#floor_id').val( floorId.join());
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsDepartmentModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsDepartmentModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsDepartmentModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsDepartmentModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#departmentTbl').datagrid('reload');
		msApp.resetForm('departmentFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsDepartmentModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsDepartment.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsDepartment=new MsDepartmentController(new MsDepartmentModel());
MsDepartment.showGrid();
utildepartmenttabs
$('#utildepartmenttabs').tabs({
	onSelect:function(title,index){
	   let department_id = $('#departmentFrm  [name=id]').val();

		var data={};
		data.department_id=department_id;

		if(index==1){
			if(department_id===''){
				$('#utildepartmenttabs').tabs('select',0);
				msApp.showError('Select Buyer First',0);
				return;
			}
			$('#departmentfloorFrm  [name=department_id]').val(department_id)
			MsDepartmentFloor.create(department_id)
		}
	}
});