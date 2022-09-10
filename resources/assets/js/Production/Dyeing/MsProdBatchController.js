require('./../../datagrid-filter.js');
let MsProdBatchModel = require('./MsProdBatchModel');
class MsProdBatchController {
	constructor(MsProdBatchModel)
	{
		this.MsProdBatchModel = MsProdBatchModel;
		this.formId='prodbatchFrm';
		this.dataTable='#prodbatchTbl';
		this.route=msApp.baseUrl()+"/prodbatch"
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
			this.MsProdBatchModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodbatchFrm [id="fabric_color_id"]').combobox('setValue', '');
		$('#prodbatchFrm [id="batch_color_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchTbl').datagrid('reload');
		$('#prodbatchFrm  [name=id]').val(d.id);
		MsProdBatch.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdBatchModel.get(index,row);
        workReceive.then(function(response){
			$('#prodbatchFrm [id="fabric_color_id"]').combobox('setValue', response.data.fromData.fabric_color_id);
			$('#prodbatchFrm [id="batch_color_id"]').combobox('setValue', response.data.fromData.batch_color_id);
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
		return '<a href="javascript:void(0)"  onClick="MsProdBatch.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	machineWindow(){
		$('#prodbatchmachineWindow').window('open');
	}

	showprodbatchmachineGrid(data){
		let self = this;
		$('#prodbatchmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchFrm [name=machine_id]').val(row.id);
					$('#prodbatchFrm [name=machine_no]').val(row.custom_no);
					$('#prodbatchFrm [name=brand]').val(row.brand);
					$('#prodbatchFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodbatchmachineWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchMachine()
	{
		let params={};
		params.brand=$('#prodbatchmachinesearchFrm  [name=brand]').val();
		params.machine_no=$('#prodbatchmachinesearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#prodbatchmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	searchBatch()
	{
		let params={};
		params.from_batch_date=$('#from_batch_date').val();
		params.to_batch_date=$('#to_batch_date').val();
		let data= axios.get(this.route+"/getbatch",{params});
		data.then(function (response) {
			$('#prodbatchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	pdf(){
		var id= $('#prodbatchFrm  [name=id]').val();
		if(id==""){
			alert("Select a Batch");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	pdfRoll(){
		var id= $('#prodbatchFrm  [name=id]').val();
		if(id==""){
			alert("Select a Batch");
			return;
		}
		window.open(this.route+"/reportroll?id="+id);
	}

}
window.MsProdBatch=new MsProdBatchController(new MsProdBatchModel());
MsProdBatch.showGrid();
MsProdBatch.showprodbatchmachineGrid([]);

 $('#prodbatchtabs').tabs({
	onSelect:function(title,index){
		let prod_batch_id = $('#prodbatchFrm  [name=id]').val();
		if(index==1){
			if(prod_batch_id===''){
				$('#prodbatchtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchRoll.resetForm();
			MsProdBatchRoll.get(prod_batch_id);
		}
		if(index==2){
			if(prod_batch_id===''){
				$('#prodbatchtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchTrim.resetForm();
			MsProdBatchTrim.get(prod_batch_id);
		}
		if(index==3){
			if(prod_batch_id===''){
				$('#prodbatchtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchProcess.resetForm();
			MsProdBatchProcess.get(prod_batch_id);
		}
	}
}); 
