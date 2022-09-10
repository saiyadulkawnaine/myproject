let MsSmpCostFabricProdModel = require('./MsSmpCostFabricProdModel');
class MsSmpCostFabricProdController {
	constructor(MsSmpCostFabricProdModel)
	{
		this.MsSmpCostFabricProdModel = MsSmpCostFabricProdModel;
		this.formId='smpcostfabricprodFrm';
		this.dataTable='#smpcostfabricprodTbl';
		this.route=msApp.baseUrl()+"/smpcostfabricprod"
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
			this.MsSmpCostFabricProdModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else
		{
			this.MsSmpCostFabricProdModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostFabricProdModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostFabricProdModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('smpcostfabricprodFrm');
		let mkt_cost_id = $('#smpcostFrm  [name=id]').val();
		$('#smpcostfabricprodFrm  [name=mkt_cost_id]').val(mkt_cost_id);
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsSmpCostFabricProd.get(mkt_cost_id);
		MsMktCostCommercial.get(mkt_cost_id);
		MsMktCostProfit.get(mkt_cost_id);
		MsMktCostCommission.get(mkt_cost_id);
		MsMktCost.reloadDetails(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		MsSmpCostFabricProd.setClass(row.production_area_id)
		let data=MsSmpCostFabricProd.getyarncount (row.smp_cost_fabric_id)
		.then(function (response) {
			  MsSmpCostFabricProd.MsSmpCostFabricProdModel.get(index,row);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get(mkt_cost_id)
	{
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsSmpCostFabricProd.showGrid(response.data)
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
		return '<a href="javascript:void(0)"  onClick="MsSmpCostFabricProd.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	calculatemount()
	{
		let cons;
		let rate;
		cons= $('#smpcostfabricprodFrm  [name=cons]').val();
		rate=$('#smpcostfabricprodFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#smpcostfabricprodFrm  [name=amount]').val(amount)
	}
	
	fabricDropDown(mkt_cost_id)
	{
		let data= axios.get(this.route+"/create?mkt_cost_id="+mkt_cost_id);
		let g=data.then(function (response) {
			$('select[name="smp_cost_fabric_id"]').empty();
			$('select[name="smp_cost_fabric_id"]').append('<option value="">-Select-</option>');
			$.each(response.data, function(key, value) {
			$('select[name="smp_cost_fabric_id"]').append('<option value="'+ key +'">'+ value +'</option>');
			});
		})
		.catch(function (error) {
		 	console.log(error);
		});
	}
	
	getFabricCons(smp_cost_fabric_id)
	{
		let data= axios.get(this.route+"/cons?smp_cost_fabric_id="+smp_cost_fabric_id);
		let g=data.then(function (response) {
			$.each(response.data, function(key, value) {
				$('#smpcostfabricprodFrm [name="cons"]').val(value.req_cons);
				$('#smpcostfabricprodFrm [name="req_cons"]').val(value.req_cons);
				MsSmpCostFabricProd.calculatemount();
			});
		})
		.catch(function (error) {
			console.log(error);
		});
		MsSmpCostFabricProd.getyarncount (smp_cost_fabric_id);
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	
	getyarncount (smp_cost_fabric_id)
	{
		let data= axios.get(this.route+"/yarncount?smp_cost_fabric_id="+smp_cost_fabric_id)
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
		let smp_cost_fabric_id=$('#smpcostfabricprodFrm [name="smp_cost_fabric_id"]').val();
		let production_process_id=$('#smpcostfabricprodFrm [name="production_process_id"]').val();
		let colorrange_id=$('#smpcostfabricprodFrm [name="colorrange_id"]').val();
		let yarncount_id=$('#smpcostfabricprodFrm [name="yarncount_id"]').val();
		let cons=$('#smpcostfabricprodFrm [name="cons"]').val();
		let data= axios.get(this.route+"/getrate?smp_cost_fabric_id="+smp_cost_fabric_id+'&production_process_id='+production_process_id+'&colorrange_id='+colorrange_id+'&yarncount_id='+yarncount_id)
		.then(function (response) {
			if(response.data)
			{
			   $('#smpcostfabricprodFrm [name="rate"]').val(response.data.rate) 
			   $('#smpcostfabricprodFrm [name="amount"]').val(response.data.rate*cons) 
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
		$('#smpcostfabricprodFrm [name="rate"]').val('') 		
		let production_process_id=$('#smpcostfabricprodFrm [name="production_process_id"]').val();
		let data= axios.get(this.route+"/productionarea?production_process_id="+production_process_id)
		.then(function (response) {
			$('#smpcostfabricprodFrm [name="production_area_id"]').val(response.data.production_area_id)
			MsSmpCostFabricProd.setClass(response.data.production_area_id);
			MsSmpCostFabricProd.setReadOnly(response.data.production_area_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setReadOnly(production_area_id){
		if(production_area_id==5 || production_area_id==10 || production_area_id==20 || production_area_id==25)
		{
			 $('#smpcostfabricprodFrm [name="rate"]').attr("readonly", true);
		}
		else
		{
			$('#smpcostfabricprodFrm [name="rate"]').attr("readonly", false);
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
		$('#smpcostfabricprodFrm [name="rate"]').val('') 
	}
	
	countChange()
	{
		$('#smpcostfabricprodFrm [name="rate"]').val('') 
	}

	formatAddCons(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsSmpCostFabricProd.openConsWindow(event,'+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}

	openConsWindow(event,id){
		if(!id){
			alert('Save First');
			return;
		}

		if (!event) var event = window.event;                // Get the window event
		event.cancelBubble = true;                       // IE Stop propagation
		if (event.stopPropagation) event.stopPropagation();

		let data= axios.get(msApp.baseUrl()+"/smpcostfabricprodcon/create?smp_cost_fabric_prod_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#SmpCostFabricProdConsWindow').window('open');
		})
	}
}
window.MsSmpCostFabricProd=new MsSmpCostFabricProdController(new MsSmpCostFabricProdModel());
