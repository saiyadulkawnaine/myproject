//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsMktCostModel = require('./MsMktCostModel');
class MsMktCostController {
	constructor(MsMktCostModel)
	{
		this.MsMktCostModel = MsMktCostModel;
		this.formId='mktcostFrm';
		this.dataTable='#mktcostTbl';
		this.route=msApp.baseUrl()+"/mktcost"
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
			this.MsMktCostModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#mktcostTbl').datagrid('reload');
		//$('#mktcostFrm  [name=id]').val(d.id);
		msApp.resetForm('mktcostFrm');
		MsMktCost.get();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsMktCostModel.get(index,row);
		data.then(function (response) {
			$('#mktcosttotalFrm  [name=total_cost]').val(response.data.totalcost);
			$('#mktcostpricebeforecommissionFrm  [name=price_before_commission]').val(response.data.price_before_commission);
			$('#mktcostpriceaftercommissionFrm  [name=price_after_commission]').val(response.data.price_after_commission);

			var costing_unit_id=$('#mktcostFrm  [name=costing_unit_id]').val()*1;
			if(costing_unit_id==12){
			var cunit='/Dzn';
			}else if (costing_unit_id==1){
			var cunit='/Pcs';
			}

			$( ".dzn-pcs" ).each(function( index ) {
				var dd=$(this).text().replace(cunit, " ");
				$(this).html(dd)
				$(this).append(cunit)
			});
			$('#mktcosttotalFrm  [name=total_cost_pcs]').val((response.data.totalcost*1/costing_unit_id).toFixed(4));
			$('#mktcostpricebeforecommissionFrm  [name=price_before_commission_pcs]').val((response.data.price_before_commission*1/costing_unit_id).toFixed(4));

			$('#mktcostpriceaftercommissionFrm  [name=price_after_commission_pcs]').val((response.data.price_after_commission*1/costing_unit_id).toFixed(4));
		})
		.catch(function (error) {
			console.log(error);
		});

		//MsMktCostFabric.LoadView(row.id);

		//MsMktCostYarn.get(row.id);

		/*msApp.resetForm('mktcostfabricprodFrm');
		$('#mktcostfabricprodFrm  [name=mkt_cost_id]').val(row.id);
		MsMktCostFabricProd.fabricDropDown(row.id);
		MsMktCostFabricProd.get(row.id)*/

		/*msApp.resetForm('mktcosttrimFrm');
		$('#mktcosttrimFrm  [name=mkt_cost_id]').val(row.id);
		MsMktCostTrim.get(row.id);*/

		/*msApp.resetForm('mktcostotherFrm');
		$('#mktcostotherFrm  [name=mkt_cost_id]').val(row.id);
		MsMktCostOther.get(row.id);*/

		/*$('#mktcostcmFrm  [name=mkt_cost_id]').val(row.id);
		$('#mktcostcmFrm  [name=amount]').val('');
		MsMktCostCm.get(row.id);*/

		/*msApp.resetForm('mktcostprofitFrm');
		$('#mktcostprofitFrm  [name=mkt_cost_id]').val(row.id);
		MsMktCostProfit.get(row.id);*/

		/*msApp.resetForm('mktcostcommercialFrm');
		$('#mktcostcommercialFrm  [name=mkt_cost_id]').val(row.id);
		MsMktCostCommercial.get(row.id);*/

		/*msApp.resetForm('mktcostcommissionFrm');
		$('#mktcostcommissionFrm  [name=mkt_cost_id]').val(row.id);
		MsMktCostCommission.get(row.id);*/

		/*msApp.resetForm('mktcostquotepriceFrm');
		$('#mktcostquotepriceFrm  [name=mkt_cost_id]').val(row.id);
		MsMktCostQuotePrice.get(row.id);*/

		/*msApp.resetForm('mktcosttargetpriceFrm');
		$('#mktcosttargetpriceFrm  [name=mkt_cost_id]').val(row.id);
		MsMktCostTargetPrice.get(row.id);*/




	}
	get(){
		let data= axios.get(this.route)
		.then(function (response) {
			MsMktCost.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCost.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculateLeadTime(){
		 var est_ship_date = $('#est_ship_date').datepicker('getDate');
         var op_date   = $('#op_date').datepicker('getDate');

		 $('#lead_time').val(msApp.dateDiffDays(op_date,est_ship_date));
		 this.setWeek();
	}
	setWeek(){
		var est_ship_date = $('#est_ship_date').datepicker('getDate');

		$('#week_no').val(msApp.weekno(est_ship_date));

	}
	openStyleWindow()
	{
		$('#w').window('open');
    }
	showStyleGrid()
	{
		let data={};
		data.buyer_id = $('#stylesearch  [name=buyer_id]').val();
		data.style_ref = $('#stylesearch  [name=style_ref]').val();
		data.style_description = $('#stylesearch  [name=style_description]').val();
		let self=this;
		var ff=$('#styleTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/style/getoldstyle",
			onClickRow: function(index,row){
				$('#mktcostFrm  [name=style_id]').val(row.id);
				$('#mktcostFrm  [name=style_ref]').val(row.style_ref);
				$('#mktcostFrm  [name=buyer_id]').val(row.buyer_id);
				$('#mktcostFrm  [name=uom_id]').val(row.uom_id);
				$('#mktcostFrm  [name=team_id]').val(row.team_id);
				$('#w').window('close')
			}
		});
		ff.datagrid('enableFilter');
	}

	pdf(){
		var id= $('#mktcostFrm  [name=id]').val();
		if(id==""){
			alert("Select a Costing");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	
	pdfquote(){
		var id= $('#mktcostFrm  [name=id]').val();
		if(id==""){
			alert("Select a Costing");
			return;
		}
		window.open(this.route+"/reportquote?id="+id);
	}

	reloadDetails(mkt_cost_id){
		let data= axios.get(this.route+'/'+mkt_cost_id+'/edit');
		data.then(function (response) {
			$('#mktcostpricebeforecommissionFrm  [name=price_before_commission]').val(response.data.price_before_commission);
			$('#mktcostpriceaftercommissionFrm  [name=price_after_commission]').val(response.data.price_after_commission);
			var costing_unit_id=$('#mktcostFrm  [name=costing_unit_id]').val()*1;
			$('#mktcostpricebeforecommissionFrm  [name=price_before_commission_pcs]').val((response.data.price_before_commission*1/costing_unit_id).toFixed(4));

			$('#mktcostpriceaftercommissionFrm  [name=price_after_commission_pcs]').val((response.data.price_after_commission*1/costing_unit_id).toFixed(4));
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	openBuyerDevelopmentOrderQtyWindow(){
		$('#buyerdevelopmentorderqtyWindow').window('open');
    }

	showBuyerDevelopmentOrderQtyGrid()
	{
		let data={};
		data.date_from = $('#buyerdevelopmentorderqtysearch  [name=date_from]').val();
		data.date_to = $('#buyerdevelopmentorderqtysearch  [name=date_to]').val();
		data.style_description = $('#buyerdevelopmentorderqtysearch  [name=style_description]').val();
		data.buyer_id = $('#mktcostFrm  [name=buyer_id]').val();
		let self=this;
		var ff=$('#buyerdevelopmentorderqtyTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/mktcost/getbuyerdevelopmentorderqty",
			onClickRow: function(index,row){
				$('#mktcostFrm  [name=buyer_development_order_qty_id]').val(row.id);
				$('#buyerdevelopmentorderqtyWindow').window('close')
			}
		});
		ff.datagrid('enableFilter');
	}

	searchMktCost() {
		let params = {};
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
		let data = axios(this.route + "/searchmktcost", {
			params
		});
		data.then(function (response) {
			$("#mktcostTbl").datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

}
window.MsMktCost=new MsMktCostController(new MsMktCostModel());
MsMktCost.get();

$('#mktCostAccordion').accordion({
	onSelect:function(title,index){
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		//alert(index);
		if(index==1){//fabric
			if(mkt_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',1);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			MsMktCostFabric.LoadView(mkt_cost_id);
		}
		if(index==2){//Narrow fabric
			if(mkt_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',2);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			MsMktCostFabric.LoadView(mkt_cost_id);
		}
		if(index==3){
			if(mkt_cost_id===''){//Yarn
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',3);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			MsMktCostYarn.get(mkt_cost_id);
		}
		if(index==4){
			if(mkt_cost_id===''){////fabric Prod
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',4);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			    msApp.resetForm('mktcostfabricprodFrm');
				$('#mktcostfabricprodFrm  [name=mkt_cost_id]').val(mkt_cost_id);
				MsMktCostFabricProd.fabricDropDown(mkt_cost_id);
				MsMktCostFabricProd.get(mkt_cost_id);
		}

		if(index==5){
			if(mkt_cost_id===''){////Trim
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',5);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('mktcosttrimFrm');
			$('#mktcosttrimFrm  [name=mkt_cost_id]').val(mkt_cost_id);
			MsMktCostTrim.get(mkt_cost_id);
		}

		if(index==6){//Emb
			
			if(mkt_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',6);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}

		}

		if(index==7){//Other
			if(mkt_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',7);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			    msApp.resetForm('mktcostotherFrm');
				$('#mktcostotherFrm  [name=mkt_cost_id]').val(mkt_cost_id);
				MsMktCostOther.get(mkt_cost_id);
		}

		if(index==8){////CM
			if(mkt_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',8);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('mktcostcmFrm');
			$('#mktcostcmFrm  [name=mkt_cost_id]').val(mkt_cost_id);
			MsMktCostCm.get(mkt_cost_id);
		}

		if(index==9){//Commercial
			if(mkt_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',9);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			    msApp.resetForm('mktcostcommercialFrm');
				$('#mktcostcommercialFrm  [name=mkt_cost_id]').val(mkt_cost_id);
				MsMktCostCommercial.get(mkt_cost_id);
		}

		if(index==10){////Total cost
			if(mkt_cost_id===''){
			msApp.showError('Select Cost First',0);
			$('#mktCostAccordion').accordion('unselect',10);
			$('#mktCostAccordion').accordion('select',0);
			return;
			}
		}

		if(index==11){//Profit 
			if(mkt_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',11);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			    msApp.resetForm('mktcostprofitFrm');
				$('#mktcostprofitFrm  [name=mkt_cost_id]').val(mkt_cost_id);
				MsMktCostProfit.get(mkt_cost_id);
		}
		if(index==12){//Price BFC 
			
			if(mkt_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',12);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}

		}
		if(index==13){//Commission 
			if(mkt_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#mktCostAccordion').accordion('unselect',13);
				$('#mktCostAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('mktcostcommissionFrm');
			$('#mktcostcommissionFrm  [name=mkt_cost_id]').val(mkt_cost_id);
			MsMktCostCommission.get(mkt_cost_id);

		}
		if(index==14){//Price AFC 
			if(mkt_cost_id===''){
			msApp.showError('Select Cost First',0);
			$('#mktCostAccordion').accordion('unselect',14);
			$('#mktCostAccordion').accordion('select',0);
			return;
			}

		}
		if(index==15){
			if(mkt_cost_id===''){
			msApp.showError('Select Cost First',0);
			$('#mktCostAccordion').accordion('unselect',15);
			$('#mktCostAccordion').accordion('select',0);
			return;
			}
			MsMktCostQuotePrice.resetForm();
			$('#mktcostquotepriceFrm  [name=mkt_cost_id]').val(mkt_cost_id);
			MsMktCostQuotePrice.get(mkt_cost_id);
		}
		
		if(index==16){
			if(mkt_cost_id===''){
			msApp.showError('Select Cost First',0);
			$('#mktCostAccordion').accordion('unselect',16);
			$('#mktCostAccordion').accordion('select',0);
			return;
			}
			msApp.resetForm('mktcosttargetpriceFrm');
			$('#mktcosttargetpriceFrm  [name=mkt_cost_id]').val(mkt_cost_id);
			MsMktCostTargetPrice.get(mkt_cost_id);
		}
	}
})
