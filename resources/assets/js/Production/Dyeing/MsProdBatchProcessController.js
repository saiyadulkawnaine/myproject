let MsProdBatchProcessModel = require('./MsProdBatchProcessModel');
require('./../../datagrid-filter.js');
class MsProdBatchProcessController {
	constructor(MsProdBatchProcessModel)
	{
		this.MsProdBatchProcessModel = MsProdBatchProcessModel;
		this.formId='prodbatchprocessFrm';
		this.dataTable='#prodbatchprocessTbl';
		this.route=msApp.baseUrl()+"/prodbatchprocess"
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
		let prod_batch_id=$('#prodbatchFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_batch_id=prod_batch_id;
		if(formObj.id){
			this.MsProdBatchProcessModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsProdBatchProcessModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodbatchprocessFrm [id="production_process_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchProcessModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchProcessModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsProdBatchProcess.get(d.prod_batch_id)
		MsProdBatchProcess.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let workReceive = this.MsProdBatchProcessModel.get(index,row);
		workReceive.then(function(response){
		$('#prodbatchprocessFrm [id="production_process_id"]').combobox('setValue', response.data.fromData.production_process_id);
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_batch_id)
	{
		let data= axios.get(this.route+"?prod_batch_id="+prod_batch_id);
		data.then(function (response) {
			$('#prodbatchprocessTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdBatchProcess.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	
}
window.MsProdBatchProcess=new MsProdBatchProcessController(new MsProdBatchProcessModel());
MsProdBatchProcess.showGrid([]);
