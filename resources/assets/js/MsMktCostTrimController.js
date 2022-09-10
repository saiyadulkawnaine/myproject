let MsMktCostTrimModel = require('./MsMktCostTrimModel');
class MsMktCostTrimController {
	constructor(MsMktCostTrimModel)
	{
		this.MsMktCostTrimModel = MsMktCostTrimModel;
		this.formId='mktcosttrimFrm';
		this.dataTable='#mktcosttrimTbl';
		this.route=msApp.baseUrl()+"/mktcosttrim"
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
		formObj['mkt_cost_id']=$('#mktcostFrm  [name=id]').val();
		if(formObj.id){
			this.MsMktCostTrimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostTrimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostTrimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostTrimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#mktcosttrimTbl').datagrid('reload');
		//$('#mktcosttrimFrm  [name=id]').val(d.id);
		msApp.resetForm('mktcosttrimFrm');
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		$('#mktcosttrimFrm  [name=mkt_cost_id]').val(mkt_cost_id);
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsMktCostTrim.get(mkt_cost_id);
		MsMktCostCommercial.get(mkt_cost_id);
		MsMktCostProfit.get(mkt_cost_id);
		MsMktCostCommission.get(mkt_cost_id);
		MsMktCost.reloadDetails(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostTrimModel.get(index,row);
	}
	get(mkt_cost_id){
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsMktCostTrim.showGrid(response.data)
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
			showFooter:true,
			fit:true,
			singleSelect:true,
			data: data,
			//queryParams:data,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostTrim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculatemount(){
		let cons;
		let rate;
		cons= $('#mktcosttrimFrm  [name=cons]').val();
		rate=$('#mktcosttrimFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#mktcosttrimFrm  [name=amount]').val(amount)

	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	setUom(itemclass_id){
     let data= axios.get(msApp.baseUrl()+"/mktcosttrim/setuom?itemclass_id="+itemclass_id);
     let g=data.then(function (response) {
     	$('#mktcosttrimFrm  [name=uom_id]').val(response.data.costing_uom_id);
     	$('#mktcosttrimFrm  [name=uom_name]').val(response.data.costing_uom_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsMktCostTrim=new MsMktCostTrimController(new MsMktCostTrimModel());
//MsMktCostTrim.showGrid();
