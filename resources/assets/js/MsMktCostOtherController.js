let MsMktCostOtherModel = require('./MsMktCostOtherModel');
class MsMktCostOtherController {
	constructor(MsMktCostOtherModel)
	{
		this.MsMktCostOtherModel = MsMktCostOtherModel;
		this.formId='mktcostotherFrm';
		this.dataTable='#mktcostotherTbl';
		this.route=msApp.baseUrl()+"/mktcostother"
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
			this.MsMktCostOtherModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostOtherModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostOtherModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostOtherModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#mktcostotherTbl').datagrid('reload');

		msApp.resetForm('mktcostotherFrm');
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		$('#mktcostotherFrm  [name=mkt_cost_id]').val(mkt_cost_id);
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsMktCostOther.get(mkt_cost_id);
		MsMktCostProfit.get(mkt_cost_id);
		MsMktCostCommission.get(mkt_cost_id);
		MsMktCost.reloadDetails(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostOtherModel.get(index,row);
	}

	get(mkt_cost_id){
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsMktCostOther.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		//var data={};
		//data.mkt_cost_id=mkt_cost_id;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			//queryParams:data,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostOther.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsMktCostOther=new MsMktCostOtherController(new MsMktCostOtherModel());
