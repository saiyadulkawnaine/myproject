let MsExpDocSubmissionBuyerModel = require('./MsExpDocSubmissionBuyerModel');
require('./../../datagrid-filter.js');
class MsExpDocSubmissionBuyerController {
	constructor(MsExpDocSubmissionBuyerModel)
	{
		this.MsExpDocSubmissionBuyerModel = MsExpDocSubmissionBuyerModel;
		this.formId='expdocsubmissionbuyerFrm';
		this.dataTable='#expdocsubmissionbuyerTbl';
		this.route=msApp.baseUrl()+"/expdocsubmissionbuyer"
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
			this.MsExpDocSubmissionBuyerModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpDocSubmissionBuyerModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpDocSubmissionBuyerModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpDocSubmissionBuyerModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expdocsubmissionbuyerTbl').datagrid('reload');
		msApp.resetForm('expdocsubmissionbuyerFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpDocSubmissionBuyerModel.get(index,row);	

	}

	showGrid(){
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpDocSubmissionBuyer.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openDocSubmissionBuyerWindow(){
		$('#docsubmissionbuyerwindow').window('open');
	}
	searchBuyerContractGrid(){
		let data = {};
		data.company_id = $('#explcscbuyersearchFrm [name="beneficiary_id"]').val();
		data.lc_sc_no = $('#explcscbuyersearchFrm [name="lc_sc_no"]').val();
		data.lc_sc_date = $('#explcscbuyersearchFrm [name="lc_sc_date"]').val();
		let self = this;
		$('#explcscbuyersearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route+"/getdocsubbuyerlc",
			onClickRow: function(index,row){
					$('#expdocsubmissionbuyerFrm [name=exp_lc_sc_id]').val(row.id);
					$('#expdocsubmissionbuyerFrm [name=lc_sc_no]').val(row.lc_sc_no);
					$('#expdocsubmissionbuyerFrm [name=beneficiary_id]').val(row.beneficiary_id);
					$('#expdocsubmissionbuyerFrm [name=buyer_id]').val(row.buyer_id);
					$('#expdocsubmissionbuyerFrm [name=currency_id]').val(row.currency_id);
					$('#expdocsubmissionbuyerFrm [name=buyers_bank]').val(row.buyers_bank);
					$('#expdocsubmissionbuyerFrm [name=days_to_realize]').val(row.tenor);
					$('#docsubmissionbuyerwindow').window('close');
					$('#explcscbuyersearchTbl').datagrid('loadData',[]);
			}
			}).datagrid('enableFilter');
	}

	setPsRealizationDate(){
		let days_to_realize=$('#days_to_realize').val()*1;
         if(!days_to_realize){
            days_to_realize=0;
         }
         days_to_realize=days_to_realize-1;
         let bank_ref_date=new Date($('#bank_ref_date').val());
         let possible_realization_date= msApp.addDays(bank_ref_date,days_to_realize);
         $('#possible_realization_date').val(possible_realization_date);
	}
}
window.MsExpDocSubmissionBuyer=new MsExpDocSubmissionBuyerController(new MsExpDocSubmissionBuyerModel());
MsExpDocSubmissionBuyer.showGrid();

 $('#comexpdocsubbuyertabs').tabs({
	onSelect:function(title,index){
	 let exp_doc_submission_id = $('#expdocsubmissionbuyerFrm  [name=id]').val();

	 var data={};
	  data.exp_doc_submission_id=exp_doc_submission_id;

	 if(index==1){
		 if(exp_doc_submission_id===''){
			 $('#comexpdocsubbuyertabs').tabs('select',0);
			 msApp.showError('Select a document submission to Buyer First',0);
			 return;
		  }
		 $('#expdocsubbuyerinvoiceFrm  [name=exp_doc_submission_id]').val(exp_doc_submission_id);
		MsExpDocSubBuyerInvoice.create(exp_doc_submission_id);
	 }
}
}); 
