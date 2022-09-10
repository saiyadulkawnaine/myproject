require('./../../datagrid-filter.js');
let MsProdAopBatchFinishQcModel = require('./MsProdAopBatchFinishQcModel');
class MsProdAopBatchFinishQcController {
	constructor(MsProdAopBatchFinishQcModel)
	{
		this.MsProdAopBatchFinishQcModel = MsProdAopBatchFinishQcModel;
		this.formId='prodaopbatchfinishqcFrm';
		this.dataTable='#prodaopbatchfinishqcTbl';
		this.route=msApp.baseUrl()+"/prodaopbatchfinishqc"
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
			this.MsProdAopBatchFinishQcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdAopBatchFinishQcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		let load_posting_date=$('#prodaopbatchfinishqcFrm  [name=load_posting_date]').val();
		msApp.resetForm(this.formId);
		$('#prodaopbatchfinishqcFrm  [name=load_posting_date]').val(load_posting_date);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopBatchFinishQcModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopBatchFinishQcModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodaopbatchfinishqcTbl').datagrid('reload');
		$('#prodaopbatchfinishqcFrm  [name=id]').val(d.id);
		MsProdAopBatchFinishQc.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdAopBatchFinishQcModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsProdAopBatchFinishQc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	batchWindow(){
		$('#prodaopbatchfinishqcbatchWindow').window('open');
	}

	showprodaopbatchbatchGrid(data){
		let self = this;
		$('#prodaopbatchfinishqcbatchsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodaopbatchfinishqcFrm [name=prod_aop_batch_id]').val(row.id);
					$('#prodaopbatchfinishqcFrm [name=batch_no]').val(row.batch_no);
					$('#prodaopbatchfinishqcFrm [name=batch_date]').val(row.batch_date);
					$('#prodaopbatchfinishqcFrm [name=company_id]').val(row.company_id);
					$('#prodaopbatchfinishqcFrm [name=customer_id]').val(row.customer_id);
					$('#prodaopbatchfinishqcFrm [id="batch_color_id"]').val(row.batch_color_id);
                    $('#prodaopbatchfinishqcFrm [name=batch_for]').val(row.batch_for);
                    $('#prodaopbatchfinishqcFrm [name=design_no]').val(row.design_no);
                    $('#prodaopbatchfinishqcFrm [name=fabric_wgt]').val(row.fabric_wgt);
                    $('#prodaopbatchfinishqcFrm [name=paste_wgt]').val(row.paste_wgt);
					$('#prodaopbatchfinishqcbatchWindow').window('close');
					$('#prodaopbatchfinishqcbatchsearchTbl').datagrid('loadData', []);
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getBatch()
	{
		let params={};
		params.company_id=$('#prodaopbatchfinishqcbatchsearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodaopbatchfinishqcbatchsearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodaopbatchfinishqcbatchsearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getbatch",{params});
		data.then(function (response) {
			$('#prodaopbatchfinishqcbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	

	



	qcByWindow(){
		$('#prodaopbatchfinishqcinchargewindow').window('open');
	}

	getEmpInchargeParams(){
		let params={};
		params.company_id=$('#prodaopbatchfinishqcinchargeFrm [name=company_id]').val();
		params.designation_id=$('#prodaopbatchfinishqcinchargeFrm [name=designation_id]').val();
		params.department_id=$('#prodaopbatchfinishqcinchargeFrm [name=department_id]').val();
		return params;
	}

	searchEmpIncharge(){
		let params=this.getEmpInchargeParams();
		let rpt = axios.get(this.route+"/operatoremployee",{params})
		.then(function(response){
			$('#prodaopbatchfinishqcinchargeTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmpInchargeGrid(data){
		let self=this;
		var pr=$('#prodaopbatchfinishqcinchargeTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodaopbatchfinishqcFrm  [name=qc_by_id]').val(row.id);
				$('#prodaopbatchfinishqcFrm  [name=qc_by_name]').val(row.employee_name);
				$('#prodaopbatchfinishqcinchargewindow').window('close')
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
			$('#prodaopbatchfinishqcTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	exportcsv()
	{
		let id=$('#prodaopbatchfinishqcFrm [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/exportcsv?id="+id);
	}
}
window.MsProdAopBatchFinishQc=new MsProdAopBatchFinishQcController(new MsProdAopBatchFinishQcModel());
MsProdAopBatchFinishQc.showGrid();
MsProdAopBatchFinishQc.showprodaopbatchbatchGrid([]);
MsProdAopBatchFinishQc.showEmpInchargeGrid([]);

 $('#prodaopbatchfinishqctabs').tabs({
	onSelect:function(title,index){
		let prod_aop_batch_finish_qc_id = $('#prodaopbatchfinishqcFrm  [name=id]').val();
		if(index==1){
			if(prod_aop_batch_finish_qc_id===''){
				$('#prodaopbatchfinishqctabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdAopBatchFinishQcRoll.get(prod_aop_batch_finish_qc_id);
		}
	}
}); 
