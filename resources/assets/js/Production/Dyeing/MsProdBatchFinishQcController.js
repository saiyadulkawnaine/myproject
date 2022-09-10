require('./../../datagrid-filter.js');
let MsProdBatchFinishQcModel = require('./MsProdBatchFinishQcModel');
class MsProdBatchFinishQcController {
	constructor(MsProdBatchFinishQcModel)
	{
		this.MsProdBatchFinishQcModel = MsProdBatchFinishQcModel;
		this.formId='prodbatchfinishqcFrm';
		this.dataTable='#prodbatchfinishqcTbl';
		this.route=msApp.baseUrl()+"/prodbatchfinishqc"
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
			this.MsProdBatchFinishQcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchFinishQcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		let load_posting_date=$('#prodbatchfinishqcFrm  [name=load_posting_date]').val();
		msApp.resetForm(this.formId);
		$('#prodbatchfinishqcFrm  [name=load_posting_date]').val(load_posting_date);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchFinishQcModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchFinishQcModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchfinishqcTbl').datagrid('reload');
		$('#prodbatchfinishqcFrm  [name=id]').val(d.id);
		MsProdBatchFinishQc.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdBatchFinishQcModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsProdBatchFinishQc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	batchWindow(){
		$('#prodbatchfinishqcbatchWindow').window('open');
	}

	showprodbatchbatchGrid(data){
		let self = this;
		$('#prodbatchfinishqcbatchsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchfinishqcFrm [name=prod_batch_id]').val(row.id);
					$('#prodbatchfinishqcFrm [name=batch_no]').val(row.batch_no);
					$('#prodbatchfinishqcFrm [name=batch_date]').val(row.batch_date);
					$('#prodbatchfinishqcFrm [name=company_id]').val(row.company_id);
					$('#prodbatchfinishqcFrm [name=location_id]').val(row.location_id);
					$('#prodbatchfinishqcFrm [id="fabric_color_id"]').val(row.fabric_color_id);
					$('#prodbatchfinishqcFrm [id="batch_color_id"]').val(row.batch_color_id);
                    $('#prodbatchfinishqcFrm [name=batch_for]').val(row.batch_for);
                    $('#prodbatchfinishqcFrm [name=colorrange_id]').val(row.colorrange_id);
                    $('#prodbatchfinishqcFrm [name=lap_dip_no]').val(row.lap_dip_no);
                    $('#prodbatchfinishqcFrm [name=fabric_wgt]').val(row.fabric_wgt);
                    $('#prodbatchfinishqcFrm [name=batch_wgt]').val(row.batch_wgt);
					$('#prodbatchfinishqcbatchWindow').window('close');
					$('#prodbatchfinishqcbatchsearchTbl').datagrid('loadData', []);
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getBatch()
	{
		let params={};
		params.company_id=$('#prodbatchfinishqcbatchsearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodbatchfinishqcbatchsearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodbatchfinishqcbatchsearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getbatch",{params});
		data.then(function (response) {
			$('#prodbatchfinishqcbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	machineWindow(){
		$('#prodbatchfinishqcmachineWindow').window('open');
	}


	showprodbatchmachineGrid(data){
		let self = this;
		$('#prodbatchfinishqcmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchfinishqcFrm [name=machine_id]').val(row.id);
					$('#prodbatchfinishqcFrm [name=machine_no]').val(row.custom_no);
					$('#prodbatchfinishqcFrm [name=brand]').val(row.brand);
					$('#prodbatchfinishqcFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodbatchfinishqcmachineWindow').window('close');
					$('#prodbatchfinishqcmachinesearchTbl').datagrid('loadData', []);
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchMachine()
	{
		let params={};
		params.brand=$('#prodbatchfinishqcmachinesearchFrm  [name=brand]').val();
		params.machine_no=$('#prodbatchfinishqcmachinesearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#prodbatchfinishqcmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	



	qcByWindow(){
		$('#prodbatchfinishqcinchargewindow').window('open');
	}

	getEmpInchargeParams(){
		let params={};
		params.company_id=$('#prodbatchfinishqcinchargeFrm [name=company_id]').val();
		params.designation_id=$('#prodbatchfinishqcinchargeFrm [name=designation_id]').val();
		params.department_id=$('#prodbatchfinishqcinchargeFrm [name=department_id]').val();
		return params;
	}

	searchEmpIncharge(){
		let params=this.getEmpInchargeParams();
		let rpt = axios.get(this.route+"/operatoremployee",{params})
		.then(function(response){
			$('#prodbatchfinishqcinchargeTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmpInchargeGrid(data){
		let self=this;
		var pr=$('#prodbatchfinishqcinchargeTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodbatchfinishqcFrm  [name=qc_by_id]').val(row.id);
				$('#prodbatchfinishqcFrm  [name=qc_by_name]').val(row.employee_name);
				$('#prodbatchfinishqcinchargewindow').window('close')
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
			$('#prodbatchfinishqcTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	exportcsv()
	{
		let id=$('#prodbatchfinishqcFrm [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/exportcsv?id="+id);
	}
}
window.MsProdBatchFinishQc=new MsProdBatchFinishQcController(new MsProdBatchFinishQcModel());
MsProdBatchFinishQc.showGrid();
MsProdBatchFinishQc.showprodbatchbatchGrid([]);
MsProdBatchFinishQc.showprodbatchmachineGrid([]);
MsProdBatchFinishQc.showEmpInchargeGrid([]);

 $('#prodbatchfinishqctabs').tabs({
	onSelect:function(title,index){
		let prod_batch_finish_qc_id = $('#prodbatchfinishqcFrm  [name=id]').val();
		if(index==1){
			if(prod_batch_finish_qc_id===''){
				$('#prodbatchfinishqctabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchFinishQcRoll.get(prod_batch_finish_qc_id);
		}
	}
}); 
