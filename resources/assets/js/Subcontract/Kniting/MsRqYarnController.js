let MsRqYarnModel = require('./MsRqYarnModel');
require('./../../datagrid-filter.js');
class MsRqYarnController {
	constructor(MsRqYarnModel)
	{
		this.MsRqYarnModel = MsRqYarnModel;
		this.formId='rqyarnFrm';
		this.dataTable='#rqyarnTbl';
		this.route=msApp.baseUrl()+"/rqyarn"
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
			this.MsRqYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsRqYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#rqyarnFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsRqYarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsRqYarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#rqyarnTbl').datagrid('reload');
		msApp.resetForm('rqyarnFrm');
		$('#rqyarnFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsRqYarnModel.get(index,row);
		data.then(function(response){
			$('#rqyarnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		}).catch(function(error){
			console.log(error);
		})
	}

	showGrid(){

		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsRqYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	approvedmsg(value,row,index)
	{
		if (row.approved_by){
		    return 'color:red;';
	    }
	}

	pdf(){
		var id= $('#rqyarnFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	searchRqYarn()
	{
		let params = {};
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
	 let data = axios.get(this.route + "/getsearchrqyarn", { params });
		data.then(function (response)
		{
			$('#rqyarnTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}
}
window.MsRqYarn=new MsRqYarnController(new MsRqYarnModel());
MsRqYarn.showGrid();

  $('#rqyarntabs').tabs({
	onSelect:function(title,index){
	 let rq_yarn_id = $('#rqyarnFrm  [name=id]').val();
	 let rq_yarn_fabrication_id = $('#rqyarnfabricationFrm  [name=id]').val();

	 if(index==1){
		 if(rq_yarn_id===''){
			 $('#rqyarntabs').tabs('select',0);
			 msApp.showError('Select a Plan First',0);
			 return;
		  }
		  msApp.resetForm('rqyarnfabricationFrm');
		 $('#rqyarnfabricationFrm  [name=rq_yarn_id]').val(rq_yarn_id);
		 MsRqYarnFab.get(rq_yarn_id);
	 }
	 if(index==2){
		 if(rq_yarn_fabrication_id===''){
			 $('#rqyarntabs').tabs('select',1);
			 msApp.showError('Select a Plan First',1);
			 return;
		  }
		  msApp.resetForm('rqyarnitemFrm');
		  $('#rqyarnitemFrm  [name=rq_yarn_fabrication_id]').val(rq_yarn_fabrication_id);
		  MsRqYarnItem.get(rq_yarn_fabrication_id);
	 }
	 
}
});  
