require('./../../datagrid-filter.js');
let MsProdAopBatchProcessModel = require('./MsProdAopBatchProcessModel');
class MsProdAopBatchProcessController {
	constructor(MsProdAopBatchProcessModel)
	{
		this.MsProdAopBatchProcessModel = MsProdAopBatchProcessModel;
		this.formId='prodaopbatchprocessFrm';
		this.dataTable='#prodaopbatchprocessTbl';
		this.route=msApp.baseUrl()+"/prodaopbatchprocess"
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
		let prod_aop_batch_id=$('#prodaopbatchFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_aop_batch_id=prod_aop_batch_id;
		if(formObj.id){
			this.MsProdAopBatchProcessModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdAopBatchProcessModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		$('#prodaopbatchprocessFrm [id="production_process_id"]').combobox('setValue', '');
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopBatchProcessModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopBatchProcessModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsProdAopBatchProcess.get(d.prod_aop_batch_id);
		//$('#prodaopbatchprocessFrm  [name=id]').val(d.id);
		MsProdAopBatchProcess.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdAopBatchProcessModel.get(index,row);
        workReceive.then(function(response){
        				$('#prodaopbatchprocessFrm [id="production_process_id"]').combobox('setValue', response.data.fromData.production_process_id);

		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_aop_batch_id)
	{
		let data= axios.get(this.route+"?prod_aop_batch_id="+prod_aop_batch_id);
		data.then(function (response) {
			$('#prodaopbatchprocessTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdAopBatchProcess.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	machineWindow(){
		$('#prodaopbatchprocessmachineWindow').window('open');
	}

	machineGrid(data){
		let self = this;
		$('#prodaopbatchprocessmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodaopbatchprocessFrm [name=asset_quantity_cost_id]').val(row.id);
					$('#prodaopbatchprocessFrm [name=machine_no]').val(row.custom_no);
					$('#prodaopbatchprocessFrm [name=brand]').val(row.brand);
					$('#prodaopbatchprocessFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodaopbatchprocessmachineWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchMachine()
	{
		let params={};
		params.brand=$('#prodaopbatchprocessmachinesearchFrm  [name=brand]').val();
		params.machine_no=$('#prodaopbatchprocessmachinesearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#prodaopbatchprocessmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	supervisorWindow(){
		$('#prodaopbatchprocesssupervisorWindow').window('open');
	}

	supervisorGrid(data){
		let self = this;
		$('#prodaopbatchprocesssupervisorsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodaopbatchprocessFrm [name=supervisor_id]').val(row.id);
					$('#prodaopbatchprocessFrm [name=supervisor_name]').val(row.name);
					$('#prodaopbatchprocesssupervisorWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	
	getSupervisor()
	{
		let company_id=$('#prodaopbatchprocesssupervisorsearchFrm  [name=company_id]').val();
		let designation_id=$('#prodaopbatchprocesssupervisorsearchFrm  [name=designation_id]').val();
		let department_id=$('#prodaopbatchprocesssupervisorsearchFrm  [name=department_id]').val();
		let params={};
		params.company_id=company_id;
		params.designation_id=designation_id;
		params.department_id=department_id;
		let data= axios.get(this.route+"/getsupervisor",{params});
		data.then(function (response) {
			$('#prodaopbatchprocesssupervisorsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	

}
window.MsProdAopBatchProcess=new MsProdAopBatchProcessController(new MsProdAopBatchProcessModel());
MsProdAopBatchProcess.showGrid([]);
MsProdAopBatchProcess.machineGrid([]);
MsProdAopBatchProcess.supervisorGrid([]);

 
