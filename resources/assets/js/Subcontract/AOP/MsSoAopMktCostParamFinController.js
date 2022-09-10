let MsSoAopMktCostParamFinModel = require('./MsSoAopMktCostParamFinModel');

class MsSoAopMktCostParamFinController {
	constructor(MsSoAopMktCostParamFinModel)
	{
		this.MsSoAopMktCostParamFinModel = MsSoAopMktCostParamFinModel;
		this.formId='soaopmktcostparamfinFrm';
		this.dataTable='#soaopmktcostparamfinTbl';
		this.route=msApp.baseUrl()+"/soaopmktcostparamfin"
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
			this.MsSoAopMktCostParamFinModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoAopMktCostParamFinModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopMktCostParamFinModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopMktCostParamFinModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('soaopmktcostparamfinFrm');
		$('#soaopmktcostparamfinFrm [name=so_aop_mkt_cost_param_id]').val($('#soaopmktcostparamFrm [name=id]').val());
		MsSoAopMktCostParamFin.get($('#soaopmktcostparamFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoAopMktCostParamFinModel.get(index,row);
	}
	get(so_aop_mkt_cost_param_id)
	{
		let data= axios.get(this.route+"?so_aop_mkt_cost_param_id="+so_aop_mkt_cost_param_id);
		data.then(function (response) {
			$('#soaopmktcostparamfinTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoAopMktCostParamFin.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

}
window.MsSoAopMktCostParamFin=new MsSoAopMktCostParamFinController(new MsSoAopMktCostParamFinModel());
MsSoAopMktCostParamFin.showGrid([]);