let MsSmpCostFabricProdModel = require('./MsSmpCostFabricProdModel');
class MsSmpCostAopController {
	constructor(MsSmpCostFabricProdModel)
	{
		this.MsSmpCostFabricProdModel = MsSmpCostFabricProdModel;
		this.formId='smpcostaopFrm';
		this.dataTable='#smpcostaopTbl';
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
		msApp.resetForm('smpcostaopFrm');
		let smp_cost_id = $('#smpcostFrm  [name=id]').val();
		$('#smpcostaopFrm  [name=smp_cost_id]').val(smp_cost_id);
		$('#smpcostaopFrm  [name=production_process_id]').val(61);
		$('#smpcostaopFrm  [name=production_area_id]').val(25);
		MsSmpCostAop.get(smp_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		MsSmpCostAop.setClass(row.production_area_id)
		let data=MsSmpCostAop.getyarncount (row.smp_cost_fabric_id)
		.then(function (response) {
			  MsSmpCostAop.MsSmpCostFabricProdModel.get(index,row);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get(smp_cost_id)
	{
		let data= axios.get(this.route+"?smp_cost_id="+smp_cost_id+'&production_area_id=25')
		.then(function (response) {
			MsSmpCostAop.showGrid(response.data)
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
		cons= $('#smpcostaopFrm  [name=cons]').val();
		rate=$('#smpcostaopFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#smpcostaopFrm  [name=amount]').val(amount)
	}
	
	fabricDropDown(smp_cost_id)
	{
		let data= axios.get(this.route+"/create?smp_cost_id="+smp_cost_id);
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
				$('#smpcostaopFrm [name="cons"]').val(value.req_cons);
				$('#smpcostaopFrm [name="req_cons"]').val(value.req_cons);
				MsSmpCostAop.calculatemount();
			});
		})
		.catch(function (error) {
			console.log(error);
		});
		MsSmpCostAop.getyarncount (smp_cost_fabric_id);
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
		let smp_cost_fabric_id=$('#smpcostaopFrm [name="smp_cost_fabric_id"]').val();
		let production_process_id=$('#smpcostaopFrm [name="production_process_id"]').val();
		let colorrange_id=$('#smpcostaopFrm [name="colorrange_id"]').val();
		let yarncount_id=$('#smpcostaopFrm [name="yarncount_id"]').val();
		let cons=$('#smpcostaopFrm [name="cons"]').val();
		let data= axios.get(this.route+"/getrate?smp_cost_fabric_id="+smp_cost_fabric_id+'&production_process_id='+production_process_id+'&colorrange_id='+colorrange_id+'&yarncount_id='+yarncount_id)
		.then(function (response) {
			if(response.data)
			{
			   $('#smpcostaopFrm [name="rate"]').val(response.data.rate) 
			   $('#smpcostaopFrm [name="amount"]').val(response.data.rate*cons) 
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
		$('#smpcostaopFrm [name="rate"]').val('') 		
		let production_process_id=$('#smpcostaopFrm [name="production_process_id"]').val();
		let data= axios.get(this.route+"/productionarea?production_process_id="+production_process_id)
		.then(function (response) {
			$('#smpcostaopFrm [name="production_area_id"]').val(response.data.production_area_id)
			MsSmpCostAop.setClass(response.data.production_area_id);
			MsSmpCostAop.setReadOnly(response.data.production_area_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setReadOnly(production_area_id){
		if(production_area_id==5 || production_area_id==10 || production_area_id==20 || production_area_id==25)
		{
			 $('#smpcostaopFrm [name="rate"]').attr("readonly", true);
		}
		else
		{
			$('#smpcostaopFrm [name="rate"]').attr("readonly", false);
		}
		

	}
	
	setClass(production_area_id)
	{
		if(production_area_id==5 )
		{
			$("#smpcost_fabprod_yarncount_id").addClass("req-text");
			$("#smpcost_fabprod_colorrange_id").addClass("req-text");
		}
		if(production_area_id==10)
		{
			$("#smpcost_fabprod_colorrange_id").removeClass("req-text");
			$("#smpcost_fabprod_yarncount_id").removeClass("req-text");
		}
		if(production_area_id==20)
		{
			$("#smpcost_fabprod_yarncount_id").removeClass("req-text");
			$("#smpcost_fabprod_colorrange_id").addClass("req-text");
		}
	}
	
	colorRangeChange()
	{
		$('#smpcostaopFrm [name="rate"]').val('') 
	}
	
	countChange()
	{
		$('#smpcostaopFrm [name="rate"]').val('') 
	}
}
window.MsSmpCostAop=new MsSmpCostAopController(new MsSmpCostFabricProdModel());
