//require('./../../jquery.easyui.min.js');
let MsExpAdvInvoiceModel = require('./MsExpAdvInvoiceModel');
require('./../../datagrid-filter.js');
class MsExpAdvInvoiceController {
	constructor(MsExpAdvInvoiceModel)
	{
		this.MsExpAdvInvoiceModel = MsExpAdvInvoiceModel;
		this.formId='expadvinvoiceFrm';
		this.dataTable='#expadvinvoiceTbl';
		this.route=msApp.baseUrl()+"/expadvinvoice"
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
			this.MsExpAdvInvoiceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpAdvInvoiceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpAdvInvoiceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpAdvInvoiceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#expadvinvoiceTbl').datagrid('reload');
		MsExpAdvInvoice.get();
		msApp.resetForm('expadvinvoiceFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpAdvInvoiceModel.get(index,row);	
	}

	get()
	{
		let d= axios.get(this.route)
		.then(function (response) {
			$('#expadvinvoiceTbl').datagrid('loadData', response.data);
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
				//var net_inv_value=0;
				
				for(var i=0; i<data.rows.length; i++){
					invoice_value+=data.rows[i]['invoice_value'].replace(/,/g,'')*1;
					//net_inv_value+=data.rows[i]['net_inv_value'].replace(/,/g,'')*1;
				}
				$('#expadvinvoiceTbl').datagrid('reloadFooter', [
					{
						invoice_value: invoice_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//net_inv_value: net_inv_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpAdvInvoice.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openExpAdvInvoiceWindow(){
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
	showExpAdvInvoiceLcScGrid(data){
		let self = this;
		$('#explcscsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
					$('#expadvinvoiceFrm [name=exp_lc_sc_id]').val(row.id);
					$('#expadvinvoiceFrm [name=lc_sc_no]').val(row.lc_sc_no);
					$('#expadvinvoiceFrm [name=beneficiary_id]').val(row.beneficiary_id);
					$('#expadvinvoiceFrm [name=buyer_id]').val(row.buyer_id);
					$('#expadvinvoiceFrm [name=lc_sc_no]').val(row.lc_sc_no);
					$('#expadvinvoiceFrm [name=lien_date]').val(row.lien_date);
					$('#expadvinvoiceFrm [name=hs_code]').val(row.hs_code);
					//$('#explcscdetailFrm [name=tenor]').val(row.tenor);
					$('#openlcscwindow').window('close');
					$('#explcscsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	boe()
   {
		var id= $('#expadvinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select an Invoice");
			return;
		}
		window.open(this.route+"/billofexchange?id="+id);
   }

    advCI(){
		var id= $('#expadvinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select an Invoice");
			return;
		}
		window.open(this.route+"/orderwiseinvoice?id="+id);
   	}

   	forward()
   	{
		var id= $('#expadvinvoiceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/forward?id="+id);
  	 }
	
}
window.MsExpAdvInvoice=new MsExpAdvInvoiceController(new MsExpAdvInvoiceModel());
MsExpAdvInvoice.showGrid([]);
MsExpAdvInvoice.get();
MsExpAdvInvoice.showExpAdvInvoiceLcScGrid([]);
//MsExpAdvInvoice.showGridCI([]);

 $('#comexpadvinvoicetabs').tabs({
	onSelect:function(title,index){
		let exp_adv_invoice_id = $('#expadvinvoiceFrm  [name=id]').val();
		
		var data={};
		data.exp_adv_invoice_id=exp_adv_invoice_id;

		if(index==1){
			if(exp_adv_invoice_id===''){
				$('#comexpadvinvoicetabs').tabs('select',0);
				msApp.showError('Select an Invoice & Lc Ref. First',0);
				return;
			}
			$('#expadvinvoiceorderFrm  [name=exp_adv_invoice_id]').val(exp_adv_invoice_id);
			MsExpAdvInvoiceOrder.create(exp_adv_invoice_id);	
		}
		
	} 
});
