//require('./../../jquery.easyui.min.js');
let MsExpInvoiceModel = require('./MsExpInvoiceModel');
require('./../../datagrid-filter.js');
class MsExpInvoiceController {
	constructor(MsExpInvoiceModel)
	{
		this.MsExpInvoiceModel = MsExpInvoiceModel;
		this.formId='expinvoiceFrm';
		this.dataTable='#expinvoiceTbl';
		this.route=msApp.baseUrl()+"/expinvoice"
	}

	submit()
	{
		let formObj=msApp.get(this.formId);
		let valAdj=msApp.get('expinvoiceadjdetailFrm');
		formObj.discount_per=valAdj.discount_per;
		formObj.discount_amount=valAdj.discount_amount;
		formObj.annual_bonus_per=valAdj.annual_bonus_per;
		formObj.bonus_amount=valAdj.bonus_amount;
		formObj.claim_per=valAdj.claim_per;
		formObj.claim_amount=valAdj.claim_amount;
		formObj.commission=valAdj.commission;
		formObj.net_inv_value=valAdj.net_inv_value;
		formObj.discount_remarks=valAdj.discount_remarks;
		formObj.bonus_remarks=valAdj.bonus_remarks;
		formObj.claim_remarks=valAdj.claim_remarks;
		formObj.commision_remarks=valAdj.commision_remarks;
		formObj.up_charge_amount=valAdj.up_charge_amount;
		formObj.up_charge_remarks=valAdj.up_charge_remarks;
		let otherShip=msApp.get('expinvoiceshipdetailFrm');
		formObj.bl_cargo_no=otherShip.bl_cargo_no;
		formObj.bl_cargo_date=otherShip.bl_cargo_date;
		formObj.origin_bl_rev_date=otherShip.origin_bl_rev_date;
		formObj.etd_port=otherShip.etd_port;
		formObj.feeder_vessel=otherShip.feeder_vessel;
		formObj.mother_vessel=otherShip.mother_vessel;
		formObj.eta_port=otherShip.eta_port;
		formObj.ic_recv_date=otherShip.ic_recv_date;
		formObj.ship_mode_id=otherShip.ship_mode_id;
		formObj.incoterm_id=otherShip.incoterm_id;
		formObj.incoterm_place=otherShip.incoterm_place;
		formObj.port_of_entry=otherShip.port_of_entry;
		formObj.port_of_loading=otherShip.port_of_loading;
		formObj.port_of_discharge=otherShip.port_of_discharge;
		formObj.shipping_bill_no=otherShip.shipping_bill_no;
		formObj.shipping_bill_date=otherShip.shipping_bill_date;
		formObj.ex_factory_date=otherShip.ex_factory_date;
		formObj.freight_by_supplier=otherShip.freight_by_supplier;
		formObj.freight_by_buyer=otherShip.freight_by_buyer;
		formObj.paid_amount=otherShip.paid_amount;
		formObj.total_ctn_qty=otherShip.total_ctn_qty;
		formObj.advice_date=otherShip.advice_date;
		formObj.advice_amount=otherShip.advice_amount;
		formObj.submit_to_id=otherShip.submit_to_id;
		formObj.shipping_mark=otherShip.shipping_mark;
		
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
		
		if(formObj.id){
			this.MsExpInvoiceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpInvoiceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpInvoiceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpInvoiceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#expinvoiceTbl').datagrid('reload');
		MsExpInvoice.get();
		msApp.resetForm('expinvoiceFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let data=this.MsExpInvoiceModel.get(index,row);	
		data.then(function (response) {
		 let index=0;
		 let row={};
		 row.formId='expinvoiceadjdetailFrm';
		 msApp.set(index,row,response.data);
		 row.formId='expinvoiceshipdetailFrm';
		 msApp.set(index,row,response.data)
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	get()
	{
		let d= axios.get(this.route)
		.then(function (response) {
			$('#expinvoiceTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var invoice_value=0;
				var net_inv_value=0;
				
				for(var i=0; i<data.rows.length; i++){
					invoice_value+=data.rows[i]['invoice_value'].replace(/,/g,'')*1;
					net_inv_value+=data.rows[i]['net_inv_value'].replace(/,/g,'')*1;
					
				}
				$('#expinvoiceTbl').datagrid('reloadFooter', [
					{
						invoice_value: invoice_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						net_inv_value: net_inv_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpInvoice.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openExpInvoiceWindow(){
		$('#openlcscwindow').window('open');
	}
	getParams(){
		let params = {};
		params.lc_sc_no = $('#explcscsearchFrm [name="lc_sc_no"]').val();
		params.lc_sc_date = $('#explcscsearchFrm [name="lc_sc_date"]').val();
		return params;

	}
	searchExpSalesContractGrid(){
		let params = this.getParams();
		let d=axios.get(this.route+"/getlcsc",{params})
		.then(function(response){
			$('#explcscsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		
	}
	showExpInvoiceLcScGrid(data){
		let self = this;
		$('#explcscsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
					$('#expinvoiceFrm [name=exp_lc_sc_id]').val(row.id);
					$('#expinvoiceFrm [name=lc_sc_no]').val(row.lc_sc_no);
					$('#expinvoiceFrm [name=beneficiary_id]').val(row.beneficiary_id);
					$('#expinvoiceFrm [name=buyer_id]').val(row.buyer_id);
					$('#expinvoiceFrm [name=lc_sc_no]').val(row.lc_sc_no);
					$('#expinvoiceFrm [name=lien_date]').val(row.lien_date);
					$('#expinvoiceFrm [name=hs_code]').val(row.hs_code);
					$('#expinvoiceFrm [name=notifying_party_id]').val(row.notifying_party_id);
					$('#expinvoiceFrm [name=second_notifying_party_id]').val(row.second_notifying_party_id);
					$('#expinvoiceFrm [name=consignee_id]').val(row.consignee_id);
					//$('#explcscdetailFrm [name=tenor]').val(row.tenor);
					$('#openlcscwindow').window('close');
					$('#explcscsearchTbl').datagrid('loadData',[]);
			}
			}).datagrid('enableFilter').datagrid('loadData',data);
	}
	calDiscount()
	{
		let invoice_amount=$('#expinvoiceadjdetailFrm [name=invoice_amount]').val();
		let discount_per=$('#expinvoiceadjdetailFrm [name=discount_per]').val();
		let discount_amount=(invoice_amount*discount_per)/100;
		$('#expinvoiceadjdetailFrm [name=discount_amount]').val(discount_amount);
		this.calNetValue();
	}
	calAnualBonus()
	{
		let invoice_amount=$('#expinvoiceadjdetailFrm [name=invoice_amount]').val();
		let annual_bonus_per=$('#expinvoiceadjdetailFrm [name=annual_bonus_per]').val();
		let bonus_amount=(invoice_amount*annual_bonus_per)/100;
		$('#expinvoiceadjdetailFrm [name=bonus_amount]').val(bonus_amount);
		this.calNetValue();
	}
	calClaim()
	{
		let invoice_amount=$('#expinvoiceadjdetailFrm [name=invoice_amount]').val();
		let claim_per=$('#expinvoiceadjdetailFrm [name=claim_per]').val();
		let claim_amount=(invoice_amount*claim_per)/100;
		$('#expinvoiceadjdetailFrm [name=claim_amount]').val(claim_amount);
		this.calNetValue();
	}
	calNetValue()
	{
		let invoice_amount=$('#expinvoiceadjdetailFrm [name=invoice_amount]').val();
		let discount_amount=$('#expinvoiceadjdetailFrm [name=discount_amount]').val();
		let bonus_amount=$('#expinvoiceadjdetailFrm [name=bonus_amount]').val();
		let claim_amount=$('#expinvoiceadjdetailFrm [name=claim_amount]').val();
		let commission=$('#expinvoiceadjdetailFrm [name=commission]').val();
		let up_charge_amount=$('#expinvoiceadjdetailFrm [name=up_charge_amount]').val()*1;
		let net_amount= invoice_amount*1-(discount_amount*1+bonus_amount*1+claim_amount*1+commission*1);
		$('#expinvoiceadjdetailFrm [name=net_inv_value]').val(net_amount+up_charge_amount);
	}

	openAdvInvoiceWindow(){
		$('#openadvinvoicewindow').window('open');
	}
	getAdvParams(){
		let params = {};
		params.invoice_no = $('#expadvinvoicesearchFrm [name="invoice_no"]').val();
		params.invoice_date = $('#expadvinvoicesearchFrm [name="invoice_date"]').val();
		return params;

	}

	searchAdvInvoiceGrid(){
		let params = this.getAdvParams();
		let adv=axios.get(this.route+"/getadvanceinvoice",{params})
		.then(function(response){
			$('#exadvinvoicesearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		
	}
	showExpAdvInvoiceGrid(data){
		let self = this;
		$('#exadvinvoicesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#expinvoiceFrm [name=exp_adv_invoice_id]').val(row.id);
				$('#expinvoiceFrm [name=adv_invoice_no]').val(row.invoice_no);
				$('#openadvinvoicewindow').window('close');
				$('#exadvinvoicesearchTbl').datagrid('loadData',[]);
			}
			}).datagrid('enableFilter').datagrid('loadData',data);
	}

	openCI(){
		var id= $('#expinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		
		let ci=axios.get(this.route+"/openexpci?id="+id)
		.then(function(response){
			$('#invoiceSearchTbl').datagrid('loadData',response.data);
			$('#invoiceWindow').window('open');
		}).catch(function(error){
			console.log(error);
		});
		return ci;
	}

	showGridCI(data)
	{
		var ci = $('#invoiceSearchTbl');
		ci.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'

		});
		ci.datagrid('loadData', data);
	}

	ordercipdf(id){
		window.open(this.route+"/orderwiseinvoice?id="+id);
   	}

   	formatOrderCIPdf(value,row){
		return '<a href="javascript:void(0)" onClick="MsExpInvoice.ordercipdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>OW</span></a>';
	}

	colorsizecipdf(id){
		window.open(this.route+"/colorsizeinvoice?id="+id);
   	}

   	formatColorSizeCIPdf(value,row){
		return '<a href="javascript:void(0)" onClick="MsExpInvoice.colorsizecipdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>C&SW</span></a>';
	}

	sizewisecipdf(id){
		window.open(this.route+"/sizewiseinvoice?id="+id);
   	}

   	formatSizeWiseCIPdf(value,row){
		return '<a href="javascript:void(0)" onClick="MsExpInvoice.sizewisecipdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>SW</span></a>';
	}

	colorwisecipdf(id){
		window.open(this.route+"/colorwiseinvoice?id="+id);
   	}

   	formatColorWiseCIPdf(value,row){
		return '<a href="javascript:void(0)" onClick="MsExpInvoice.colorwisecipdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>CW</span></a>';
	}

	bnfdeclaration()
   {
		var id= $('#expinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/bnfdeclaration?id="+id);
   }
   confirmletter()
   {
		var id= $('#expinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/confirmletter?id="+id);
   }
   
   shipperconfirm()
   {
		var id= $('#expinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/shipperconfirm?id="+id);
   }
   
   shipcertifydeclare()
   {
		var id= $('#expinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/shippercertificatedeclare?id="+id);
   }

   bnfconfirmazo()
   {
		var id= $('#expinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/bnfconfirmazo?id="+id);
   }
   
   certifybanazo()
   {
		var id= $('#expinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/certifybanazo?id="+id);
   }
}
window.MsExpInvoice=new MsExpInvoiceController(new MsExpInvoiceModel());
MsExpInvoice.showGrid([]);
MsExpInvoice.get();
MsExpInvoice.showExpInvoiceLcScGrid([]);
MsExpInvoice.showExpAdvInvoiceGrid([]);
MsExpInvoice.showGridCI([]);

$('#comexpinvoicetabs').tabs({
	onSelect:function(title,index){
	 let exp_invoice_id = $('#expinvoiceFrm  [name=id]').val();
	 

	 var data={};
	  data.exp_invoice_id=exp_invoice_id;

	 if(index==1){
		 if(exp_invoice_id===''){
			 $('#comexpinvoicetabs').tabs('select',0);
			 msApp.showError('Select an Invoice & Lc Ref. First',0);
			 return;
		  }
		 $('#expinvoiceorderFrm  [name=exp_invoice_id]').val(exp_invoice_id);
		 MsExpInvoiceOrder.create(exp_invoice_id);
		 
	 }
	 if(index==2){
		if($('#expinvoiceFrm  [name=id]').val() ===''){
			$('#comexpinvoicetabs').tabs('select',0);
			msApp.showError('Select an Invoice & Lc Ref. First',0);
			return;
		 }
		//$('#expinvoiceadjdetailFrm  [name=exp_pre_credit_id]').val(exp_pre_credit_id);
		//MsExpInvoice.showGrid();
		var row = $('#expinvoiceTbl').datagrid('getSelected');
		var rowIndex = $("#expinvoiceTbl").datagrid("getRowIndex", row);
		MsExpInvoice.edit(rowIndex,row);
	}
	if(index==3){
		if($('#expinvoiceFrm  [name=id]').val() ===''){
		$('#comexpinvoicetabs').tabs('select',0);
		msApp.showError('Select an Invoice & Lc Ref. First',0);
		return;
		}
		var row = $('#expinvoiceTbl').datagrid('getSelected');
		var rowIndex = $("#expinvoiceTbl").datagrid("getRowIndex", row);
		MsExpInvoice.edit(rowIndex,row);
		MsExpInvoice.get();
		//MsExpInvoice.showGrid();
	}
} 
});
