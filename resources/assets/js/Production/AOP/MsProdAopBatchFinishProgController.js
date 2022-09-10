require('./../../datagrid-filter.js');
let MsProdAopBatchFinishProgModel = require('./MsProdAopBatchFinishProgModel');
class MsProdAopBatchFinishProgController {
	constructor(MsProdAopBatchFinishProgModel)
	{
		this.MsProdAopBatchFinishProgModel = MsProdAopBatchFinishProgModel;
		this.formId='prodaopbatchfinishprogFrm';
		this.dataTable='#prodaopbatchfinishprogTbl';
		this.route=msApp.baseUrl()+"/prodaopbatchfinishprog"
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
			this.MsProdAopBatchFinishProgModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdAopBatchFinishProgModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		let load_posting_date=$('#prodaopbatchfinishprogFrm  [name=load_posting_date]').val();
		msApp.resetForm(this.formId);
		$('#prodaopbatchfinishprogFrm  [name=load_posting_date]').val(load_posting_date);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopBatchFinishProgModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopBatchFinishProgModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodaopbatchfinishprogTbl').datagrid('reload');
		$('#prodaopbatchfinishprogFrm  [name=id]').val(d.id);
		MsProdAopBatchFinishProg.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdAopBatchFinishProgModel.get(index,row);
        workReceive.then(function(response){

		}).catch(function(error){
			console.log(errors)
		});
	}

	showGrid(){

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

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdAopBatchFinishProg.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	batchWindow(){
		$('#prodaopbatchfinishprogbatchWindow').window('open');
	}

	showbatchGrid(data){
		let self = this;
		$('#prodaopbatchfinishprogbatchsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodaopbatchfinishprogFrm [name=prod_aop_batch_id]').val(row.id);
					$('#prodaopbatchfinishprogFrm [name=batch_no]').val(row.batch_no);
					$('#prodaopbatchfinishprogFrm [name=batch_date]').val(row.batch_date);
					$('#prodaopbatchfinishprogFrm [name=company_id]').val(row.company_id);
					$('#prodaopbatchfinishprogFrm [id="batch_color_id"]').val(row.batch_color_id);
                    $('#prodaopbatchfinishprogFrm [name=batch_for]').val(row.batch_for);
                    $('#prodaopbatchfinishprogFrm [name=design_no]').val(row.design_no);
                    $('#prodaopbatchfinishprogFrm [name=fabric_wgt]').val(row.fabric_wgt);
                    $('#prodaopbatchfinishprogFrm [name=paste_wgt]').val(row.paste_wgt);
                    //$('#prodaopbatchfinishprogFrm [name=machine_no]').val(row.machine_no);
                    //$('#prodaopbatchfinishprogFrm [name=brand]').val(row.brand);
                    //$('#prodaopbatchfinishprogFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodaopbatchfinishprogbatchWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getBatch()
	{
		let params={};
		params.company_id=$('#prodaopbatchfinishprogbatchsearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodaopbatchfinishprogbatchsearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodaopbatchfinishprogbatchsearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getbatch",{params});
		data.then(function (response) {
			$('#prodaopbatchfinishprogbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	machineWindow(){
		$('#prodaopbatchfinishprogmachineWindow').window('open');
	}


	showmachineGrid(data){
		let self = this;
		$('#prodaopbatchfinishprogmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodaopbatchfinishprogFrm [name=machine_id]').val(row.id);
					$('#prodaopbatchfinishprogFrm [name=machine_no]').val(row.custom_no);
					$('#prodaopbatchfinishprogFrm [name=brand]').val(row.brand);
					$('#prodaopbatchfinishprogFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodaopbatchfinishprogmachineWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchMachine()
	{
		let params={};
		params.brand=$('#prodaopbatchfinishprogmachinesearchFrm  [name=brand]').val();
		params.machine_no=$('#prodaopbatchfinishprogmachinesearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#prodaopbatchfinishprogmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	opratorWindow(){
		$('#prodaopbatchfinishprogoperatorwindow').window('open');
	}

	getEmpOperatorParams(){
		let params={};
		params.company_id=$('#prodaopbatchfinishprogoperatorFrm [name=company_id]').val();
		params.designation_id=$('#prodaopbatchfinishprogoperatorFrm [name=designation_id]').val();
		params.department_id=$('#prodaopbatchfinishprogoperatorFrm [name=department_id]').val();
		return params;
	}

	searchEmpOperator(){
		let params=this.getEmpOperatorParams();
		let rpt = axios.get(this.route+"/operatoremployee",{params})
		.then(function(response){
			$('#prodaopbatchfinishprogoperatorTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmpOperatorGrid(data){
		let self=this;
		var pr=$('#prodaopbatchfinishprogoperatorTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodaopbatchfinishprogFrm  [name=operator_id]').val(row.id);
				$('#prodaopbatchfinishprogFrm  [name=operator_name]').val(row.employee_name);
				$('#prodaopbatchfinishprogoperatorwindow').window('close')
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}



	inchargeWindow(){
		$('#prodaopbatchfinishproginchargewindow').window('open');
	}

	getEmpInchargeParams(){
		let params={};
		params.company_id=$('#prodaopbatchfinishproginchargeFrm [name=company_id]').val();
		params.designation_id=$('#prodaopbatchfinishproginchargeFrm [name=designation_id]').val();
		params.department_id=$('#prodaopbatchfinishproginchargeFrm [name=department_id]').val();
		return params;
	}

	searchEmpIncharge(){
		let params=this.getEmpInchargeParams();
		let rpt = axios.get(this.route+"/operatoremployee",{params})
		.then(function(response){
			$('#prodaopbatchfinishproginchargeTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmpInchargeGrid(data){
		let self=this;
		var pr=$('#prodaopbatchfinishproginchargeTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodaopbatchfinishprogFrm  [name=incharge_id]').val(row.id);
				$('#prodaopbatchfinishprogFrm  [name=incharge_name]').val(row.employee_name);
				$('#prodaopbatchfinishproginchargewindow').window('close')
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}



	



	searchList()
	{
		let params={};
		params.from_batch_date=$('#from_batch_date').val();
		params.to_batch_date=$('#to_batch_date').val();
		params.from_load_posting_date=$('#from_load_posting_date').val();
		params.to_load_posting_date=$('#to_load_posting_date').val();
		let data= axios.get(this.route+"/getlist",{params});
		data.then(function (response) {
			$('#prodaopbatchfinishprogTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsProdAopBatchFinishProg=new MsProdAopBatchFinishProgController(new MsProdAopBatchFinishProgModel());
MsProdAopBatchFinishProg.showGrid();
MsProdAopBatchFinishProg.showbatchGrid([]);
MsProdAopBatchFinishProg.showmachineGrid([]);
MsProdAopBatchFinishProg.showEmpOperatorGrid([]);
MsProdAopBatchFinishProg.showEmpInchargeGrid([]);

 $('#prodaopbatchfinishprogtabs').tabs({
	onSelect:function(title,index){
		let prod_batch_finish_prog_id = $('#prodaopbatchfinishprogFrm  [name=id]').val();
		if(index==1){
			if(prod_batch_finish_prog_id===''){
				$('#prodaopbatchfinishprogtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdAopBatchFinishProgRoll.get(prod_batch_finish_prog_id);
		}
		if(index==2){
			if(prod_batch_finish_prog_id===''){
				$('#prodaopbatchfinishprogtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdAopBatchFinishProgChem.resetForm();
			MsProdAopBatchFinishProgChem.get(prod_batch_finish_prog_id);
		}
	}
}); 
