let MsLocalExpInvoiceModel = require('./MsLocalExpInvoiceModel');
require('./../../datagrid-filter.js');
class MsLocalExpInvoiceController {
	constructor(MsLocalExpInvoiceModel)
	{
		this.MsLocalExpInvoiceModel = MsLocalExpInvoiceModel;
		this.formId='localexpinvoiceFrm';
		this.dataTable='#localexpinvoiceTbl';
		this.route=msApp.baseUrl()+"/localexpinvoice"
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
			this.MsLocalExpInvoiceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpInvoiceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpInvoiceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpInvoiceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexpinvoiceTbl').datagrid('reload');
		msApp.resetForm('localexpinvoiceFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsLocalExpInvoiceModel.get(index,row);	
	}

	showGrid(){
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var local_invoice_value=0;
				for(var i=0; i<data.rows.length; i++){
					local_invoice_value+=data.rows[i]['local_invoice_value'].replace(/,/g,'')*1;
				}
				$(this.dataTable).datagrid('reloadFooter', [
					{
						local_invoice_value: local_invoice_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsLocalExpInvoice.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openlocalExpInvoiceWindow(){
		$('#openlocallcwindow').window('open');
	}
	getParams(){
		let params = {};
		params.local_lc_no = $('#localexplcsearchFrm [name="local_lc_no"]').val();
		params.lc_date = $('#localexplcsearchFrm [name="lc_date"]').val();
		return params;
	}
	
	searchLocalExpLcGrid(){
		let params = this.getParams();
		let d=axios.get(this.route+"/getlocallc",{params})
		.then(function(response){
			$('#localexplcsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	showlocalLcGrid(data){
		let self = this;
		$('#localexplcsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#localexpinvoiceFrm [name=local_exp_lc_id]').val(row.id);
				$('#localexpinvoiceFrm [name=local_lc_no]').val(row.local_lc_no);
				$('#localexpinvoiceFrm [name=beneficiary_id]').val(row.beneficiary_id);
				$('#localexpinvoiceFrm [name=buyer_id]').val(row.buyer_id);
				$('#localexpinvoiceFrm [name=lien_date]').val(row.lien_date);
				$('#localexpinvoiceFrm [name=hs_code]').val(row.hs_code);
				$('#openlocallcwindow').window('close');
				$('#localexplcsearchTbl').datagrid('loadData',[]);
			}
			}).datagrid('enableFilter').datagrid('loadData',data);
	}

	calDiscount()
	{
		let invoice_amount=$('#localexpinvoiceadjdetailFrm [name=invoice_amount]').val();
		let discount_per=$('#localexpinvoiceadjdetailFrm [name=discount_per]').val();
		let discount_amount=(invoice_amount*discount_per)/100;
		$('#localexpinvoiceadjdetailFrm [name=discount_amount]').val(discount_amount);
		this.calNetValue();
	}
	calAnualBonus()
	{
		let invoice_amount=$('#localexpinvoiceadjdetailFrm [name=invoice_amount]').val();
		let annual_bonus_per=$('#localexpinvoiceadjdetailFrm [name=annual_bonus_per]').val();
		let bonus_amount=(invoice_amount*annual_bonus_per)/100;
		$('#localexpinvoiceadjdetailFrm [name=bonus_amount]').val(bonus_amount);
		this.calNetValue();
	}
	calClaim()
	{
		let invoice_amount=$('#localexpinvoiceadjdetailFrm [name=invoice_amount]').val();
		let claim_per=$('#localexpinvoiceadjdetailFrm [name=claim_per]').val();
		let claim_amount=(invoice_amount*claim_per)/100;
		$('#localexpinvoiceadjdetailFrm [name=claim_amount]').val(claim_amount);
		this.calNetValue();
	}
	calNetValue()
	{
		let invoice_amount=$('#localexpinvoiceadjdetailFrm [name=invoice_amount]').val();
		let discount_amount=$('#localexpinvoiceadjdetailFrm [name=discount_amount]').val();
		let bonus_amount=$('#localexpinvoiceadjdetailFrm [name=bonus_amount]').val();
		let claim_amount=$('#localexpinvoiceadjdetailFrm [name=claim_amount]').val();
		let commission=$('#localexpinvoiceadjdetailFrm [name=commission]').val();
		let net_amount= invoice_amount*1-(discount_amount*1+bonus_amount*1+claim_amount*1+commission*1);
		$('#localexpinvoiceadjdetailFrm [name=net_inv_value]').val(net_amount);
	}
}
window.MsLocalExpInvoice=new MsLocalExpInvoiceController(new MsLocalExpInvoiceModel());
MsLocalExpInvoice.showGrid();
MsLocalExpInvoice.showlocalLcGrid([]);

 $('#comlocalexpinvoicetabs').tabs({
	onSelect:function(title,index){
	 let local_exp_invoice_id = $('#localexpinvoiceFrm  [name=id]').val();	 

	 var data={};
	  data.local_exp_invoice_id=local_exp_invoice_id;

	 if(index==1){
		 if(local_exp_invoice_id===''){
			 $('#comlocalexpinvoicetabs').tabs('select',0);
			 msApp.showError('Select an Invoice & Lc Ref. First',0);
			 return;
		  }
		 $('#localexpinvoiceorderFrm  [name=local_exp_invoice_id]').val(local_exp_invoice_id);
		 MsLocalExpInvoiceOrder.create(local_exp_invoice_id);
		 
	 }
	} 
});
