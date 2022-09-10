require('./../../datagrid-filter.js');
let MsProdAopBatchFinishProgChemModel = require('./MsProdAopBatchFinishProgChemModel');
class MsProdAopBatchFinishProgChemController {
	constructor(MsProdAopBatchFinishProgChemModel)
	{
		this.MsProdAopBatchFinishProgChemModel = MsProdAopBatchFinishProgChemModel;
		this.formId='prodaopbatchfinishprogchemFrm';
		this.dataTable='#prodaopbatchfinishprogchemTbl';
		this.route=msApp.baseUrl()+"/prodaopbatchfinishprogchem"
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
		let prod_batch_finish_prog_id=$('#prodaopbatchfinishprogFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_batch_finish_prog_id=prod_batch_finish_prog_id
		if(formObj.id){
			this.MsProdAopBatchFinishProgChemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdAopBatchFinishProgChemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		let load_posting_date=$('#prodaopbatchfinishprogchemFrm  [name=load_posting_date]').val();
		msApp.resetForm(this.formId);
		$('#prodaopbatchfinishprogchemFrm  [name=load_posting_date]').val(load_posting_date);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopBatchFinishProgChemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopBatchFinishProgChemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodaopbatchfinishprogchemTbl').datagrid('reload');
		$('#prodaopbatchfinishprogchemFrm  [name=id]').val(d.id);
		let prod_batch_finish_prog_id=$('#prodaopbatchfinishprogFrm  [name=id]').val();
		MsProdAopBatchFinishProgChem.resetForm();
		MsProdAopBatchFinishProgChem.get(prod_batch_finish_prog_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
        let workReceive=this.MsProdAopBatchFinishProgChemModel.get(index,row);
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
			$('#prodaopbatchfinishprogchemTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsProdAopBatchFinishProgChem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#prodaopbatchfinishprogchemitemsearchwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#prodaopbatchfinishprogchemitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#prodaopbatchfinishprogchemFrm [name=item_account_id]').val(row.item_account_id);
				$('#prodaopbatchfinishprogchemFrm [name=item_desc]').val(row.item_description);
				$('#prodaopbatchfinishprogchemFrm [name=specification]').val(row.specification);
				$('#prodaopbatchfinishprogchemFrm [name=item_category]').val(row.category_name);
				$('#prodaopbatchfinishprogchemFrm [name=item_class]').val(row.class_name);
				$('#prodaopbatchfinishprogchemFrm [name=sub_class_name]').val(row.sub_class_name);
				$('#prodaopbatchfinishprogchemitemsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let item_category=$('#prodaopbatchfinishprogchemitemsearchFrm [name=item_category]').val();
		let item_class=$('#prodaopbatchfinishprogchemitemsearchFrm [name=item_class]').val();
		let params={};
		params.item_category=item_category;
		params.item_class=item_class;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#prodaopbatchfinishprogchemitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
}
window.MsProdAopBatchFinishProgChem=new MsProdAopBatchFinishProgChemController(new MsProdAopBatchFinishProgChemModel());
MsProdAopBatchFinishProgChem.showGrid([]);
MsProdAopBatchFinishProgChem.itemSearchGrid([]);