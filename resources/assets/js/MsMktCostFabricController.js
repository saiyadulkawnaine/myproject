let MsMktCostFabricModel = require('./MsMktCostFabricModel');
class MsMktCostFabricController {
	constructor(MsMktCostFabricModel)
	{
		this.MsMktCostFabricModel = MsMktCostFabricModel;
		this.formId='mktcostfabricFrm';
		this.dataTable='#mktcostfabricTbl';
		this.route=msApp.baseUrl()+"/mktcostfabric"
	}

	LoadView(mkt_cost_id){
		let data= axios.get(this.route+"/create?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			for(var key in response.data.dropDown){
				msApp.setHtml(key,response.data.dropDown[key]);
			}
		})
		.catch(function (error) {
			console.log(error);
		});
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
		let self =this;
		let formObj=msApp.get(this.formId);
		let mkt_cost_id=$('#mktcostFrm  [name=id]').val();
		formObj.mkt_cost_id=mkt_cost_id;
		if(formObj.id){
			let data=this.MsMktCostFabricModel.saves(this.route+"/"+formObj.id,'put',msApp.qs.stringify(formObj),this.response);
			data.then(function (response) {
				self.LoadView(response.data.mkt_cost_id);
				MsMktCost.reloadDetails(response.data.mkt_cost_id);
			})
		}else{
			let data=this.MsMktCostFabricModel.saves(this.route,'post',msApp.qs.stringify(formObj),this.response);
			data.then(function (response) {
				self.LoadView(response.data.mkt_cost_id);
				MsMktCost.reloadDetails(response.data.mkt_cost_id);
			})
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostFabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostFabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
	}
	openConsWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/mktcostfabriccon/create?mkt_cost_fabric_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#consWindow').window('open');
		})
	}
	openYarnWindow(mkt_cost_fabric_id){
		if(!mkt_cost_id){
			alert('Save First');
			return;
		}
		MsMktCostYarn.loadview(mkt_cost_fabric_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsMktCostFabricModel.get(index,row);

	}

	showGrid()
	{
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostFabric.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsMktCostFabric=new MsMktCostFabricController(new MsMktCostFabricModel());
