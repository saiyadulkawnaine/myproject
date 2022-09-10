let MsSoEmbMktCostParamModel = require('./MsSoEmbMktCostParamModel');

class MsSoEmbMktCostParamController {
	constructor(MsSoEmbMktCostParamModel)
	{
		this.MsSoEmbMktCostParamModel = MsSoEmbMktCostParamModel;
		this.formId='soembmktcostparamFrm';
		this.dataTable='#soembmktcostparamTbl';
		this.route=msApp.baseUrl()+"/soembmktcostparam"
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
		//let so_aop_mkt_cost_id=$('#soembmktcostFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		//formObj.so_aop_mkt_cost_id=so_aop_mkt_cost_id;
		if(formObj.id){
			this.MsSoEmbMktCostParamModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoEmbMktCostParamModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soembmktcostparamFrm [name=so_aop_mkt_cost_id]').val($('#soembmktcostFrm [name=id]').val());
		$('#soembmktcostqpricedtlcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbMktCostParamModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbMktCostParamModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		msApp.resetForm('soembmktcostparamFrm');
		$('#soembmktcostparamFrm [name=so_aop_mkt_cost_id]').val($('#soembmktcostFrm [name=id]').val());
		MsSoEmbMktCostParam.get($('#soembmktcostFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoEmbMktCostParamModel.get(index,row);
	}
	
	get(so_aop_mkt_cost_id)
	{
		let data= axios.get(this.route+"?so_aop_mkt_cost_id="+so_aop_mkt_cost_id);
		data.then(function (response) {
			$('#soembmktcostparamTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoEmbMktCostParam.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }


}
window.MsSoEmbMktCostParam=new MsSoEmbMktCostParamController(new MsSoEmbMktCostParamModel());
MsSoEmbMktCostParam.showGrid([]);