let MsTnaProgressDelayDtlModel = require('./MsTnaProgressDelayDtlModel');
class MsTnaProgressDelayDtlController {
	constructor(MsTnaProgressDelayDtlModel)
	{
		this.MsTnaProgressDelayDtlModel = MsTnaProgressDelayDtlModel;
		this.formId='tnaprogressdelaydtlFrm';
		this.dataTable='#tnaprogressdelaydtlTbl';
		this.route=msApp.baseUrl()+"/tnaprogressdelaydtl"
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
			this.MsTnaProgressDelayDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsTnaProgressDelayDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsTnaProgressDelayDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTnaProgressDelayDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#tnaprogressdelaydtlTbl').datagrid('reload');
		msApp.resetForm('tnaprogressdelaydtlFrm');
		$('#tnaprogressdelaydtlFrm [name=tna_progress_delay_id]').val($('#tnaprogressdelayFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsTnaProgressDelayDtlModel.get(index,row);

	}

	showGrid(tna_progress_delay_id)
	{
		let self=this;
		var data={};
		data.tna_progress_delay_id=tna_progress_delay_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsTnaProgressDelayDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openEmployeeHr(){
		$('#openemployeehrwindow').window('open');
	}

	getHrEmpParams(){
		let params = {}
		params.designation_id=$('#employeehrsearchFrm [name=designation_id]').val();
		params.department_id=$('#employeehrsearchFrm [name=department_id]').val();
		params.company_id=$('#employeehrsearchFrm [name=company_id]').val();
		return params;
	}
	searchEmployeeGrid(){
		let params=this.getHrEmpParams();
		let emp= axios.get(this.route+'/getemployeehr',{params})
		.then(function(response){
			$('#employeehrsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		
	}

	showEmployeeGrid(data){
		let self = this;
		$('#employeehrsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
				$('#tnaprogressdelaydtlFrm [name=employee_h_r_id]').val(row.id);
				$('#tnaprogressdelaydtlFrm [name=name]').val(row.name);
				$('#employeehrsearchTbl').datagrid('loadData',[]);
				$('#openemployeehrwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsTnaProgressDelayDtl=new MsTnaProgressDelayDtlController(new MsTnaProgressDelayDtlModel());
MsTnaProgressDelayDtl.showEmployeeGrid([])