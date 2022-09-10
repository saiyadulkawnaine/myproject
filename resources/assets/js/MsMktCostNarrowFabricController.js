let MsMktCostNarrowFabricModel = require('./MsMktCostNarrowFabricModel');
class MsMktCostNarrowFabricController {
	constructor(MsMktCostNarrowFabricModel)
	{
		this.MsMktCostNarrowFabricModel = MsMktCostNarrowFabricModel;
		this.formId='mktcostnarrowfabricFrm';
		this.dataTable='#mktcostnarrowfabricTbl';
		this.route=msApp.baseUrl()+"/mktcostfabric"
	}

	LoadView(mkt_cost_id){
		let data= axios.get(this.route+"/create?mkt_cost_id="+mkt_cost_id);
		let g=data.then(function (response) {
		//$('#fabricdiv').html(response.data);
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	submit1(formObj,idx)
	{
		let self =this;
		//let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsMktCostNarrowFabricModel.saves(this.route+"/"+formObj.id,'put',msApp.qs.stringify(formObj),this.response);
		}else{
			let data=this.MsMktCostNarrowFabricModel.saves(this.route,'post',msApp.qs.stringify(formObj),this.response);
			data.then(function (response) {
				$('#dg').datagrid('updateRow',{
				index: idx,
				row: {
				id: response.data.id,
				}
				});
			})
		}
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
			this.MsMktCostNarrowFabricModel.saves(this.route+"/"+formObj.id,'put',msApp.qs.stringify(formObj),this.response);
		}else{
			let data=this.MsMktCostNarrowFabricModel.saves(this.route,'post',msApp.qs.stringify(formObj),this.response);
			data.then(function (response) {
				self.LoadView(response.data.mkt_cost_id);
			})
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostNarrowFabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostNarrowFabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		/*$('#mktcostnarrowfabricTbl').datagrid('reload');
		msApp.resetForm('mktcostnarrowfabricFrm');*/
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
	}
	openConsWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(this.route+"/"+id+'/edit');
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
	openYarnWindow(mkt_cost_id){
		if(!id){
			alert('Save First');
			return;
		}
		MsMktCostYarn.loadview(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsMktCostNarrowFabricModel.get(index,row);

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
		return '<a href="javascript:void(0)"  onClick="MsMktCostNarrowFabric.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsMktCostNarrowFabric=new MsMktCostNarrowFabricController(new MsMktCostNarrowFabricModel());
//MsMktCostNarrowFabric.showGrid();
