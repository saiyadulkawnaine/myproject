require('./../../datagrid-filter.js');
let MsProdBatchRdModel = require('./MsProdBatchRdModel');
class MsProdBatchRdController {
	constructor(MsProdBatchRdModel)
	{
		this.MsProdBatchRdModel = MsProdBatchRdModel;
		this.formId='prodbatchrdFrm';
		this.dataTable='#prodbatchrdTbl';
		this.route=msApp.baseUrl()+"/prodbatchrd"
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
			this.MsProdBatchRdModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchRdModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodbatchrdFrm [id="fabric_color_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchRdModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchRdModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchrdTbl').datagrid('reload');
		$('#prodbatchrdFrm  [name=id]').val(d.id);
		MsProdBatchRd.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdBatchRdModel.get(index,row);
        workReceive.then(function(response){
			$('#prodbatchrdFrm [id="fabric_color_id"]').combobox('setValue', response.data.fromData.fabric_color_id);
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
		return '<a href="javascript:void(0)"  onClick="MsProdBatchRd.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	machineWindow(){
		$('#prodbatchrdmachineWindow').window('open');
	}

	showprodbatchmachineGrid(data){
		let self = this;
		$('#prodbatchrdmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchrdFrm [name=machine_id]').val(row.id);
					$('#prodbatchrdFrm [name=machine_no]').val(row.custom_no);
					$('#prodbatchrdFrm [name=brand]').val(row.brand);
					$('#prodbatchrdFrm [name=prod_capacity]').val(row.prod_capacity);
					$('#prodbatchrdmachineWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchMachine()
	{
		let params={};
		params.brand=$('#prodbatchrdmachinesearchFrm  [name=brand]').val();
		params.machine_no=$('#prodbatchrdmachinesearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#prodbatchrdmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	rootbatchWindow(){
		$('#prodbatchrdrootbatchWindow').window('open');
	}

	showprodbatchrootbatchGrid(data){
		let self = this;
		$('#prodbatchrdrootbatchsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodbatchrdFrm [name=root_batch_id]').val(row.id);
					$('#prodbatchrdFrm [name=root_batch_no]').val(row.batch_no);
					$('#prodbatchrdFrm [name=company_id]').val(row.company_id);
					$('#prodbatchrdFrm [name=location_id]').val(row.location_id);
					$('#prodbatchrdFrm [name=batch_color_id]').val(row.batch_color_id);
					$('#prodbatchrdFrm [id="fabric_color_id"]').combobox('setValue', row.fabric_color_id);
                    $('#prodbatchrdFrm [name=batch_for]').val(row.batch_for);
                    $('#prodbatchrdFrm [name=colorrange_id]').val(row.colorrange_id);
                    $('#prodbatchrdFrm [name=lap_dip_no]').val(row.lap_dip_no);
					$('#prodbatchrdrootbatchWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchRootbatch()
	{
		let params={};
		params.company_id=$('#prodbatchrdrootbatchsearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodbatchrdrootbatchsearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodbatchrdrootbatchsearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getrootbatch",{params});
		data.then(function (response) {
			$('#prodbatchrdrootbatchsearchTbl').datagrid('loadData', response.data);
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
			$('#prodbatchrdTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	pdf()
	{
		var id= $('#prodbatchrdFrm  [name=id]').val();
		if(id==""){
			alert("Select a Batch");
			return;
		}
		window.open(msApp.baseUrl()+"/prodbatch/report?id="+id);
	}

	pdfRoll(){
		var id= $('#prodbatchrdFrm  [name=id]').val();
		if(id==""){
			alert("Select a Batch");
			return;
		}
		window.open(msApp.baseUrl()+"/prodbatch/reportroll?id="+id);
	}

}
window.MsProdBatchRd=new MsProdBatchRdController(new MsProdBatchRdModel());
MsProdBatchRd.showGrid();
MsProdBatchRd.showprodbatchmachineGrid([]);
MsProdBatchRd.showprodbatchrootbatchGrid([]);

 $('#prodbatchrdtabs').tabs({
	onSelect:function(title,index){
		let prod_batch_id = $('#prodbatchrdFrm  [name=id]').val();
		if(index==1){
			if(prod_batch_id===''){
				$('#prodbatchrdtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchRdRoll.resetForm();
			MsProdBatchRdRoll.get(prod_batch_id);
		}
		if(index==2){
			if(prod_batch_id===''){
				$('#prodbatchrdtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchRdTrim.resetForm();
			MsProdBatchRdTrim.get(prod_batch_id);
		}
		if(index==3){
			if(prod_batch_id===''){
				$('#prodbatchrdtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsProdBatchRdProcess.resetForm();
			MsProdBatchRdProcess.get(prod_batch_id);
		}
	}
}); 
