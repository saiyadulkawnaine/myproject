require('./../../datagrid-filter.js');
let MsProdBatchFinishProgModel = require('./MsProdBatchFinishProgModel');
class MsProdBatchFinishProgController {
	constructor(MsProdBatchFinishProgModel)
	{
		this.MsProdBatchFinishProgModel = MsProdBatchFinishProgModel;
		this.formId='prodbatchfinishprogFrm';
		this.dataTable='#prodbatchfinishprogTbl';
		this.route=msApp.baseUrl()+"/prodbatchfinishprog"
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
			this.MsProdBatchFinishProgModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchFinishProgModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		let load_posting_date=$('#prodbatchfinishprogFrm  [name=load_posting_date]').val();
		msApp.resetForm(this.formId);
		$('#prodbatchfinishprogFrm  [name=load_posting_date]').val(load_posting_date);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchFinishProgModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchFinishProgModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchfinishprogTbl').datagrid('reload');
		$('#prodbatchfinishprogFrm  [name=id]').val(d.id);
		MsProdBatchFinishProg.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdBatchFinishProgModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsProdBatchFinishProg.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	batchWindow(){
		$('#prodbatchfinishprogbatchWindow').window('open');
	}

	showprodbatchbatchGrid(data){
		let self = this;
		$('#prodbatchfinishprogbatchsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchfinishprogFrm [name=prod_batch_id]').val(row.id);
					$('#prodbatchfinishprogFrm [name=batch_no]').val(row.batch_no);
					$('#prodbatchfinishprogFrm [name=batch_date]').val(row.batch_date);
					$('#prodbatchfinishprogFrm [name=company_id]').val(row.company_id);
					$('#prodbatchfinishprogFrm [name=location_id]').val(row.location_id);
					$('#prodbatchfinishprogFrm [id="fabric_color_id"]').val(row.fabric_color_id);
					$('#prodbatchfinishprogFrm [id="batch_color_id"]').val(row.batch_color_id);
                    $('#prodbatchfinishprogFrm [name=batch_for]').val(row.batch_for);
                    $('#prodbatchfinishprogFrm [name=colorrange_id]').val(row.colorrange_id);
                    $('#prodbatchfinishprogFrm [name=lap_dip_no]').val(row.lap_dip_no);
                    $('#prodbatchfinishprogFrm [name=fabric_wgt]').val(row.fabric_wgt);
                    $('#prodbatchfinishprogFrm [name=batch_wgt]').val(row.batch_wgt);
                    //$('#prodbatchfinishprogFrm [name=machine_no]').val(row.machine_no);
                    //$('#prodbatchfinishprogFrm [name=brand]').val(row.brand);
                    //$('#prodbatchfinishprogFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodbatchfinishprogbatchWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getBatch()
	{
		let params={};
		params.company_id=$('#prodbatchfinishprogbatchsearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodbatchfinishprogbatchsearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodbatchfinishprogbatchsearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getbatch",{params});
		data.then(function (response) {
			$('#prodbatchfinishprogbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	machineWindow(){
		$('#prodbatchfinishprogmachineWindow').window('open');
	}


	showprodbatchmachineGrid(data){
		let self = this;
		$('#prodbatchfinishprogmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchfinishprogFrm [name=machine_id]').val(row.id);
					$('#prodbatchfinishprogFrm [name=machine_no]').val(row.custom_no);
					$('#prodbatchfinishprogFrm [name=brand]').val(row.brand);
					$('#prodbatchfinishprogFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodbatchfinishprogmachineWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchMachine()
	{
		let params={};
		params.brand=$('#prodbatchfinishprogmachinesearchFrm  [name=brand]').val();
		params.machine_no=$('#prodbatchfinishprogmachinesearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#prodbatchfinishprogmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	opratorWindow(){
		$('#prodbatchfinishprogoperatorwindow').window('open');
	}

	getEmpOperatorParams(){
		let params={};
		params.company_id=$('#prodbatchfinishprogoperatorFrm [name=company_id]').val();
		params.designation_id=$('#prodbatchfinishprogoperatorFrm [name=designation_id]').val();
		params.department_id=$('#prodbatchfinishprogoperatorFrm [name=department_id]').val();
		return params;
	}

	searchEmpOperator(){
		let params=this.getEmpOperatorParams();
		let rpt = axios.get(this.route+"/operatoremployee",{params})
		.then(function(response){
			$('#prodbatchfinishprogoperatorTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmpOperatorGrid(data){
		let self=this;
		var pr=$('#prodbatchfinishprogoperatorTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodbatchfinishprogFrm  [name=operator_id]').val(row.id);
				$('#prodbatchfinishprogFrm  [name=operator_name]').val(row.employee_name);
				$('#prodbatchfinishprogoperatorwindow').window('close')
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}



	inchargeWindow(){
		$('#prodbatchfinishproginchargewindow').window('open');
	}

	getEmpInchargeParams(){
		let params={};
		params.company_id=$('#prodbatchfinishproginchargeFrm [name=company_id]').val();
		params.designation_id=$('#prodbatchfinishproginchargeFrm [name=designation_id]').val();
		params.department_id=$('#prodbatchfinishproginchargeFrm [name=department_id]').val();
		return params;
	}

	searchEmpIncharge(){
		let params=this.getEmpInchargeParams();
		let rpt = axios.get(this.route+"/operatoremployee",{params})
		.then(function(response){
			$('#prodbatchfinishproginchargeTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmpInchargeGrid(data){
		let self=this;
		var pr=$('#prodbatchfinishproginchargeTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodbatchfinishprogFrm  [name=incharge_id]').val(row.id);
				$('#prodbatchfinishprogFrm  [name=incharge_name]').val(row.employee_name);
				$('#prodbatchfinishproginchargewindow').window('close')
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
			$('#prodbatchfinishprogTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsProdBatchFinishProg=new MsProdBatchFinishProgController(new MsProdBatchFinishProgModel());
MsProdBatchFinishProg.showGrid();
MsProdBatchFinishProg.showprodbatchbatchGrid([]);
MsProdBatchFinishProg.showprodbatchmachineGrid([]);
MsProdBatchFinishProg.showEmpOperatorGrid([]);
MsProdBatchFinishProg.showEmpInchargeGrid([]);

 $('#prodbatchfinishprogtabs').tabs({
	onSelect:function(title,index){
		let prod_batch_finish_prog_id = $('#prodbatchfinishprogFrm  [name=id]').val();
		if(index==1){
			if(prod_batch_finish_prog_id===''){
				$('#prodbatchfinishprogtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchFinishProgRoll.get(prod_batch_finish_prog_id);
		}
		if(index==2){
			if(prod_batch_finish_prog_id===''){
				$('#prodbatchfinishprogtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchFinishProgChem.resetForm();
			MsProdBatchFinishProgChem.get(prod_batch_finish_prog_id);
		}
	}
}); 
