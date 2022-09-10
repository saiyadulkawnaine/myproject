let MsLocalExpDocSubAcceptModel = require('./MsLocalExpDocSubAcceptModel');
require('./../../datagrid-filter.js');
class MsLocalExpDocSubAcceptController {
	constructor(MsLocalExpDocSubAcceptModel)
	{
		this.MsLocalExpDocSubAcceptModel = MsLocalExpDocSubAcceptModel;
		this.formId='localexpdocsubacceptFrm';
		this.dataTable='#localexpdocsubacceptTbl';
		this.route=msApp.baseUrl()+"/localexpdocsubaccept"
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
			this.MsLocalExpDocSubAcceptModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpDocSubAcceptModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpDocSubAcceptModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpDocSubAcceptModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexpdocsubacceptTbl').datagrid('reload');
		msApp.resetForm('localexpdocsubacceptFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsLocalExpDocSubAcceptModel.get(index,row);	

	}

	showGrid(){
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//nowrap:false,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsLocalExpDocSubAccept.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openDocSubAcceptWindow(){
		$('#docsubacceptwindow').window('open');
	}
	getParam(){
		let params={};
		params.company_id = $('#localexplcdocsearchFrm [name="beneficiary_id"]').val();
		params.lc_sc_no = $('#localexplcdocsearchFrm [name="lc_sc_no"]').val();
		params.lc_sc_date = $('#localexplcdocsearchFrm [name="lc_sc_date"]').val();
		return params;

	}
	searchLocalExportLc(){
		let params = this.getParam();
		let lc=axios.get(this.route+"/getlocalexportlc",{params})
		.then(function(response){
			$('#localexplcdocsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		return lc;
	}
	showLocalExportLcGrid(data){
		let self = this;
		$('#localexplcdocsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#localexpdocsubacceptFrm [name=local_exp_lc_id]').val(row.id);
				$('#localexpdocsubacceptFrm [name=local_lc_no]').val(row.local_lc_no);
				$('#localexpdocsubacceptFrm [name=beneficiary_id]').val(row.beneficiary_id);
				$('#localexpdocsubacceptFrm [name=buyer_id]').val(row.buyer_id);
				$('#localexpdocsubacceptFrm [name=currency_id]').val(row.currency_id);
				$('#localexpdocsubacceptFrm [name=buyers_bank]').val(row.buyers_bank);
				$('#localexpdocsubacceptFrm [name=days_to_realize]').val(row.tenor);
				$('#docsubacceptwindow').window('close');
				$('#localexplcdocsearchTbl').datagrid('loadData',[]);
				
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	openCI(){
		var id= $('#localexpdocsubacceptFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		
		let ci=axios.get(this.route+"/openCi?id="+id)
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

	cipdf(local_exp_invoice_id){
		window.open(this.route+"/getci?local_exp_invoice_id="+local_exp_invoice_id);
   	}

   	formatCIPdf(value,row){
		return '<a href="javascript:void(0)"  onClick="MsLocalExpDocSubAccept.cipdf('+row.local_exp_invoice_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>CI</span></a>';
	}

	dcpdf(local_exp_invoice_id){
		window.open(this.route+"/deliverychallan?local_exp_invoice_id="+local_exp_invoice_id);
   	}

   	formatDCPdf(value,row){
		return '<a href="javascript:void(0)"  onClick="MsLocalExpDocSubAccept.dcpdf('+row.local_exp_invoice_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>DC</span></a>';
	}

	cishortpdf(local_exp_invoice_id){
		window.open(this.route+"/getcishort?local_exp_invoice_id="+local_exp_invoice_id);
   	}

	formatCIShortPdf(value,row){
		return '<a href="javascript:void(0)"  onClick="MsLocalExpDocSubAccept.cishortpdf('+row.local_exp_invoice_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>CI-S</span></a>';
	}

	dcshortpdf(local_exp_invoice_id){
		window.open(this.route+"/getdcshort?local_exp_invoice_id="+local_exp_invoice_id);
   	}

	formatDCShortPdf(value,row){
		return '<a href="javascript:void(0)"  onClick="MsLocalExpDocSubAccept.dcshortpdf('+row.local_exp_invoice_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>DC-S</span></a>';
	}

	plshortpdf(){
		var id= $('#localexpdocsubacceptFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/packinglistshort?id="+id);
   	}

   	// formatPLPdf(value,row){
	// 	return '<a href="javascript:void(0)"  onClick="MsLocalExpDocSubAccept.plpdf('+row.local_exp_invoice_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PL</span></a>';
	// }
	boepdf(){
		var id= $('#localexpdocsubacceptFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/billofexchange?id="+id);
   	}

   	plpdf(){
		var id= $('#localexpdocsubacceptFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/packinglist?id="+id);
   	}
	coepdf(){
		var id= $('#localexpdocsubacceptFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/certificateoforigin?id="+id);
	   }
	   
	bcpdf(){
		var id= $('#localexpdocsubacceptFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/bnfcertificate?id="+id);
   	}

	forwardletter(){
		var id= $('#localexpdocsubacceptFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/forwardingletter?id="+id);
	}

}
window.MsLocalExpDocSubAccept=new MsLocalExpDocSubAcceptController(new MsLocalExpDocSubAcceptModel());
MsLocalExpDocSubAccept.showLocalExportLcGrid([]);
MsLocalExpDocSubAccept.showGridCI([]);
MsLocalExpDocSubAccept.showGrid();

$('#comexppartyaccepttabs').tabs({
	onSelect:function(title,index){
	 let local_exp_doc_sub_accept_id = $('#localexpdocsubacceptFrm  [name=id]').val();

	 var data={};
	  data.local_exp_doc_sub_accept_id=local_exp_doc_sub_accept_id;

	 if(index==1){
		 if(local_exp_doc_sub_accept_id===''){
			 $('#comexppartyaccepttabs').tabs('select',0);
			 msApp.showError('Select a document submission to Buyer First',0);
			 return;
		  }
		 $('#localexpdocsubinvoiceFrm  [name=local_exp_doc_sub_accept_id]').val(local_exp_doc_sub_accept_id);
		MsLocalExpDocSubInvoice.create(local_exp_doc_sub_accept_id);
	 }
}
}); 
