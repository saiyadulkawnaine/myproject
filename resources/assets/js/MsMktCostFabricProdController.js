let MsMktCostFabricProdModel = require('./MsMktCostFabricProdModel');
class MsMktCostFabricProdController {
	constructor(MsMktCostFabricProdModel)
	{
		this.MsMktCostFabricProdModel = MsMktCostFabricProdModel;
		this.formId='mktcostfabricprodFrm';
		this.dataTable='#mktcostfabricprodTbl';
		this.route=msApp.baseUrl()+"/mktcostfabricprod"
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
		if(formObj.id)
		{
			this.MsMktCostFabricProdModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else
		{
			this.MsMktCostFabricProdModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostFabricProdModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostFabricProdModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('mktcostfabricprodFrm');
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		$('#mktcostfabricprodFrm  [name=mkt_cost_id]').val(mkt_cost_id);
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsMktCostFabricProd.get(mkt_cost_id);
		MsMktCostCommercial.get(mkt_cost_id);
		MsMktCostProfit.get(mkt_cost_id);
		MsMktCostCommission.get(mkt_cost_id);
		MsMktCost.reloadDetails(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		MsMktCostFabricProd.setClass(row.production_area_id)
		let data=MsMktCostFabricProd.getyarncount (row.mkt_cost_fabric_id)
		.then(function (response) {
			  MsMktCostFabricProd.MsMktCostFabricProdModel.get(index,row);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get(mkt_cost_id)
	{
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsMktCostFabricProd.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostFabricProd.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	calculatemount()
	{
		let cons;
		let rate;
		cons= $('#mktcostfabricprodFrm  [name=cons]').val();
		rate=$('#mktcostfabricprodFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#mktcostfabricprodFrm  [name=amount]').val(amount)
	}
	
	fabricDropDown(mkt_cost_id)
	{
		let data= axios.get(this.route+"/create?mkt_cost_id="+mkt_cost_id);
		let g=data.then(function (response) {
			$('select[name="mkt_cost_fabric_id"]').empty();
			$('select[name="mkt_cost_fabric_id"]').append('<option value="">-Select-</option>');
			$.each(response.data, function(key, value) {
			$('select[name="mkt_cost_fabric_id"]').append('<option value="'+ key +'">'+ value +'</option>');
			});
		})
		.catch(function (error) {
		 	console.log(error);
		});
	}
	
	getFabricCons(mkt_cost_fabric_id)
	{
		let data= axios.get(this.route+"/cons?mkt_cost_fabric_id="+mkt_cost_fabric_id);
		let g=data.then(function (response) {
			$.each(response.data, function(key, value) {
				$('#mktcostfabricprodFrm [name="cons"]').val(value.req_cons);
				$('#mktcostfabricprodFrm [name="req_cons"]').val(value.req_cons);
				MsMktCostFabricProd.calculatemount();
			});
		})
		.catch(function (error) {
			console.log(error);
		});
		MsMktCostFabricProd.getyarncount (mkt_cost_fabric_id);
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	
	getyarncount (mkt_cost_fabric_id)
	{
		let data= axios.get(this.route+"/yarncount?mkt_cost_fabric_id="+mkt_cost_fabric_id)
		.then(function (response) {
			$('select[name="yarncount_id"]').empty();
			$('select[name="yarncount_id"]').append('<option value="">-Select-</option>');
			$.each(response.data, function(key, value) {
				$('select[name="yarncount_id"]').append('<option value="'+ value.id +'">'+ value.name+"/"+ value.symbol+'</option>');
			});
		})
		.catch(function (error) {
			console.log(error);
		});
		return data;
	}
	
	getRate()
	{
		let mkt_cost_fabric_id=$('#mktcostfabricprodFrm [name="mkt_cost_fabric_id"]').val();
		let production_process_id=$('#mktcostfabricprodFrm [name="production_process_id"]').val();
		let colorrange_id=$('#mktcostfabricprodFrm [name="colorrange_id"]').val();
		let yarncount_id=$('#mktcostfabricprodFrm [name="yarncount_id"]').val();
		let cons=$('#mktcostfabricprodFrm [name="cons"]').val();
		let data= axios.get(this.route+"/getrate?mkt_cost_fabric_id="+mkt_cost_fabric_id+'&production_process_id='+production_process_id+'&colorrange_id='+colorrange_id+'&yarncount_id='+yarncount_id)
		.then(function (response) {
			if(response.data)
			{
			   $('#mktcostfabricprodFrm [name="rate"]').val(response.data.rate) 
			   $('#mktcostfabricprodFrm [name="amount"]').val(response.data.rate*cons) 
			}
			else
			{
				alert("Rate not Found");
			}
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	processChange()
	{
		$('#mktcostfabricprodFrm [name="rate"]').val('') 		
		let production_process_id=$('#mktcostfabricprodFrm [name="production_process_id"]').val();
		let data= axios.get(this.route+"/productionarea?production_process_id="+production_process_id)
		.then(function (response) {
			$('#mktcostfabricprodFrm [name="production_area_id"]').val(response.data.production_area_id)
			MsMktCostFabricProd.setClass(response.data.production_area_id);
			MsMktCostFabricProd.setReadOnly(response.data.production_area_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setReadOnly(production_area_id){
		if(production_area_id==5 || production_area_id==10 || production_area_id==20 || production_area_id==25)
		{
			 //commented at 11-07-2020
			 //$('#mktcostfabricprodFrm [name="rate"]').attr("readonly", true);
		}
		else
		{
			//commented at 11-07-2020
			//$('#mktcostfabricprodFrm [name="rate"]').attr("readonly", false);
		}
		

	}
	
	setClass(production_area_id)
	{
		if(production_area_id==5 )
		{
			$("#mktcost_fabprod_yarncount_id").addClass("req-text");
			$("#mktcost_fabprod_colorrange_id").addClass("req-text");
		}
		if(production_area_id==10)
		{
			$("#mktcost_fabprod_colorrange_id").removeClass("req-text");
			$("#mktcost_fabprod_yarncount_id").removeClass("req-text");
		}
		if(production_area_id==20)
		{
			$("#mktcost_fabprod_yarncount_id").removeClass("req-text");
			$("#mktcost_fabprod_colorrange_id").addClass("req-text");
		}
	}
	
	colorRangeChange()
	{
		$('#mktcostfabricprodFrm [name="rate"]').val('') 
	}
	
	countChange()
	{
		$('#mktcostfabricprodFrm [name="rate"]').val('') 
	}
}
window.MsMktCostFabricProd=new MsMktCostFabricProdController(new MsMktCostFabricProdModel());
