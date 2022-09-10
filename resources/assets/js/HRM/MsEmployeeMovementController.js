let MsEmployeeMovementModel = require('./MsEmployeeMovementModel');
require('./../datagrid-filter.js');
class MsEmployeeMovementController {
	constructor(MsEmployeeMovementModel)
	{
		this.MsEmployeeMovementModel = MsEmployeeMovementModel;
		this.formId='employeemovementFrm';
		this.dataTable='#employeemovementTbl';
		this.route=msApp.baseUrl()+"/employeemovement"
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
            this.MsEmployeeMovementModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeMovementModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeMovementModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeMovementModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeemovementTbl').datagrid('reload');
		msApp.resetForm('employeemovementFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeMovementModel.get(index,row);
	}


	showGrid()
	{
		let self=this;
		var ac=$('#employeemovementTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			//fitColumns:true,
			emptyMsg:'No Record Found',
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
		ac.datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeMovement.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openEmpWindow(){
		$('#openemployeewindow').window('open');
	}

	getEmployeeParams(){
		let params={};
		params.company_id=$('#employeehrsearchFrm [name=company_id]').val();
		params.designation_id=$('#employeehrsearchFrm [name=designation_id]').val();
		params.department_id=$('#employeehrsearchFrm [name=department_id]').val();
		return params;
	}

	searchEmployeeHr(){
		let params=this.getEmployeeParams();
		let rpt = axios.get(this.route+"/getemployeehrm",{params})
		.then(function(response){
			$('#employhrsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmployeeGrid(data){
		let self=this;
		var pr=$('#employhrsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeemovementFrm  [name=employee_h_r_id]').val(row.id);
				$('#employeemovementFrm  [name=employee_name]').val(row.employee_name);
				$('#employeemovementFrm  [name=company_id]').val(row.company_id);
				$('#employeemovementFrm  [name=location_id]').val(row.location_id);
				$('#employeemovementFrm  [name=designation_id]').val(row.designation_id);
				$('#employeemovementFrm  [name=department_id]').val(row.department_id);
				$('#employeemovementFrm  [name=code]').val(row.code);
				$('#employeemovementFrm  [name=contact]').val(row.contact);
				$('#openemployeewindow').window('close')
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}

	

	pdf(){
		var id= $('#employeemovementFrm  [name=id]').val();
		if(id==""){
			alert("Select an Entry");
			return;
		}
		window.open(this.route+"/getempticket?id="+id);
	}

}
window.MsEmployeeMovement = new MsEmployeeMovementController(new MsEmployeeMovementModel());
MsEmployeeMovement.showGrid();
MsEmployeeMovement.showEmployeeGrid([]);
$('#EmployeeMovetabs').tabs({
	onSelect:function(title,index){
	   let employee_movement_id = $('#employeemovementFrm  [name=id]').val();

		var data={};
		data.employee_movement_id=employee_movement_id;

		if(index==1){
			if(employee_movement_id===''){
				$('#EmployeeMovetabs').tabs('select',0);
				msApp.showError('Select An Entry First',0);
				return;
			}
			//msApp.resetForm('employeemovementdtlFrm');
			$('#employeemovementdtlFrm  [name=employee_movement_id]').val(employee_movement_id)
			MsEmployeeMovementDtl.showGrid(employee_movement_id);
		}
		
	}
});