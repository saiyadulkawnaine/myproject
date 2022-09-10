let MsSoEmbMktCostParamFinModel = require('./MsSoEmbMktCostParamFinModel');

class MsSoEmbMktCostParamFinController {
	constructor(MsSoEmbMktCostParamFinModel)
	{
		this.MsSoEmbMktCostParamFinModel = MsSoEmbMktCostParamFinModel;
		this.formId='soembmktcostparamfinFrm';
		this.dataTable='#soembmktcostparamfinTbl';
		this.route=msApp.baseUrl()+"/soembmktcostparamfin"
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
			this.MsSoEmbMktCostParamFinModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoEmbMktCostParamFinModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbMktCostParamFinModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbMktCostParamFinModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('soembmktcostparamfinFrm');
		$('#soembmktcostparamfinFrm [name=so_aop_mkt_cost_param_id]').val($('#soembmktcostparamFrm [name=id]').val());
		MsSoEmbMktCostParamFin.get($('#soembmktcostparamFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoEmbMktCostParamFinModel.get(index,row);
	}
	get(so_aop_mkt_cost_param_id)
	{
		let data= axios.get(this.route+"?so_aop_mkt_cost_param_id="+so_aop_mkt_cost_param_id);
		data.then(function (response) {
			$('#soembmktcostparamfinTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbMktCostParamFin.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

}
window.MsSoEmbMktCostParamFin=new MsSoEmbMktCostParamFinController(new MsSoEmbMktCostParamFinModel());
MsSoEmbMktCostParamFin.showGrid([]);