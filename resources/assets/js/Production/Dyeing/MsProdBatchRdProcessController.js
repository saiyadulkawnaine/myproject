let MsProdBatchRdProcessModel = require('./MsProdBatchRdProcessModel');
require('./../../datagrid-filter.js');
class MsProdBatchRdProcessController {
	constructor(MsProdBatchRdProcessModel)
	{
		this.MsProdBatchRdProcessModel = MsProdBatchRdProcessModel;
		this.formId='prodbatchrdprocessFrm';
		this.dataTable='#prodbatchrdprocessTbl';
		this.route=msApp.baseUrl()+"/prodbatchrdprocess"
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
		let prod_batch_id=$('#prodbatchrdFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_batch_id=prod_batch_id;
		if(formObj.id){
			this.MsProdBatchRdProcessModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsProdBatchRdProcessModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodbatchrdprocessFrm [id="production_process_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdBatchRdProcessModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdBatchRdProcessModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsProdBatchRdProcess.get(d.prod_batch_id)
		MsProdBatchRdProcess.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let workReceive = this.MsProdBatchRdProcessModel.get(index,row);
		workReceive.then(function(response){
		$('#prodbatchrdprocessFrm [id="production_process_id"]').combobox('setValue', response.data.fromData.production_process_id);
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(prod_batch_id)
	{
		let data= axios.get(this.route+"?prod_batch_id="+prod_batch_id);
		data.then(function (response) {
			$('#prodbatchrdprocessTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsProdBatchRdProcess.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	
}
window.MsProdBatchRdProcess=new MsProdBatchRdProcessController(new MsProdBatchRdProcessModel());
MsProdBatchRdProcess.showGrid([]);
