require('./../../datagrid-filter.js');
let MsProdBatchFinishProgChemModel = require('./MsProdBatchFinishProgChemModel');
class MsProdBatchFinishProgChemController {
	constructor(MsProdBatchFinishProgChemModel)
	{
		this.MsProdBatchFinishProgChemModel = MsProdBatchFinishProgChemModel;
		this.formId='prodbatchfinishprogchemFrm';
		this.dataTable='#prodbatchfinishprogchemTbl';
		this.route=msApp.baseUrl()+"/prodbatchfinishprogchem"
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
		let prod_batch_finish_prog_id=$('#prodbatchfinishprogFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_batch_finish_prog_id=prod_batch_finish_prog_id
		if(formObj.id){
			this.MsProdBatchFinishProgChemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdBatchFinishProgChemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		let load_posting_date=$('#prodbatchfinishprogchemFrm  [name=load_posting_date]').val();
		msApp.resetForm(this.formId);
		$('#prodbatchfinishprogchemFrm  [name=load_posting_date]').val(load_posting_date);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchFinishProgChemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchFinishProgChemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodbatchfinishprogchemTbl').datagrid('reload');
		$('#prodbatchfinishprogchemFrm  [name=id]').val(d.id);
		let prod_batch_finish_prog_id=$('#prodbatchfinishprogFrm  [name=id]').val();
		MsProdBatchFinishProgChem.resetForm();
		MsProdBatchFinishProgChem.get(prod_batch_finish_prog_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdBatchFinishProgChemModel.get(index,row);
        workReceive.then(function(response){

		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_batch_finish_prog_id)
	{
		let params={};
		params.prod_batch_finish_prog_id=prod_batch_finish_prog_id
		let data= axios.get(this.route,{params});
		data.then(function (response) {
			$('#prodbatchfinishprogchemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdBatchFinishProgChem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#prodbatchfinishprogchemitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#prodbatchfinishprogchemitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#prodbatchfinishprogchemFrm [name=item_account_id]').val(row.item_account_id);
				$('#prodbatchfinishprogchemFrm [name=item_desc]').val(row.item_description);
				$('#prodbatchfinishprogchemFrm [name=specification]').val(row.specification);
				$('#prodbatchfinishprogchemFrm [name=item_category]').val(row.category_name);
				$('#prodbatchfinishprogchemFrm [name=item_class]').val(row.class_name);
				$('#prodbatchfinishprogchemFrm [name=sub_class_name]').val(row.sub_class_name);
				$('#prodbatchfinishprogchemitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let item_category=$('#prodbatchfinishprogchemitemsearchFrm [name=item_category]').val();
		let item_class=$('#prodbatchfinishprogchemitemsearchFrm [name=item_class]').val();
		let params={};
		params.item_category=item_category;
		params.item_class=item_class;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#prodbatchfinishprogchemitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	genReq()
	{
		let prod_batch_finish_prog_id=$('#prodbatchfinishprogFrm  [name=id]').val();
		let params={};
		params.prod_batch_finish_prog_id=prod_batch_finish_prog_id;
		let data= axios.get(this.route+'/genreq',{params});
		data.then(function (response) {
			msApp.showSuccess(response.data.message);
			MsProdBatchFinishProgChem.showPdf(response.data.id)
		})
		.catch(function (error) {
			msApp.showError(response.data.message);
			console.log(error);
		});

	}

	showPdf(id)
	{
		//var id= $('#invdyechemisurqFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(msApp.baseUrl()+"/invdyechemisurq/report?id="+id);
	}
}
window.MsProdBatchFinishProgChem=new MsProdBatchFinishProgChemController(new MsProdBatchFinishProgChemModel());
MsProdBatchFinishProgChem.showGrid([]);
MsProdBatchFinishProgChem.itemSearchGrid([]);