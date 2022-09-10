//require('./../../jquery.easyui.min.js');
let MsExpDocSubmissionModel = require('./MsExpDocSubmissionModel');
require('./../../datagrid-filter.js');
class MsExpDocSubmissionController {
	constructor(MsExpDocSubmissionModel)
	{
		this.MsExpDocSubmissionModel = MsExpDocSubmissionModel;
		this.formId='expdocsubmissionFrm';
		this.dataTable='#expdocsubmissionTbl';
		this.route=msApp.baseUrl()+"/expdocsubmission"
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
			this.MsExpDocSubmissionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpDocSubmissionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpDocSubmissionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpDocSubmissionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expdocsubmissionTbl').datagrid('reload');
		msApp.resetForm('expdocsubmissionFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpDocSubmissionModel.get(index,row);	

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
		return '<a href="javascript:void(0)"  onClick="MsExpDocSubmission.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openDocSubmissionWindow(){
		$('#docsubmissionwindow').window('open');
	}
	searchContractGrid(){
		let data = {};
		data.beneficiary_id = $('#docsubexplcscsearchFrm [name="beneficiary_id"]').val();
		data.lc_sc_no = $('#docsubexplcscsearchFrm [name="lc_sc_no"]').val();
		data.lc_sc_date = $('#docsubexplcscsearchFrm [name="lc_sc_date"]').val();
		let self = this;
		$('#docsubexplcscsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route+"/getdocsublc",
			onClickRow: function(index,row){
					$('#expdocsubmissionFrm [name=exp_lc_sc_id]').val(row.id);
					$('#expdocsubmissionFrm [name=lc_sc_no]').val(row.lc_sc_no);
					$('#expdocsubmissionFrm [name=beneficiary_id]').val(row.beneficiary_id);
					$('#expdocsubmissionFrm [name=buyer_id]').val(row.buyer_id);
					$('#expdocsubmissionFrm [name=currency_id]').val(row.currency_id);
					$('#expdocsubmissionFrm [name=buyers_bank]').val(row.buyers_bank);
					$('#expdocsubmissionFrm [name=days_to_realize]').val(row.tenor);
					$('#docsubmissionwindow').window('close');
					$('#docsubexplcscsearchTbl').datagrid('loadData',[]);
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

   latter()
   {
		var id= $('#expdocsubmissionFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/latter?id="+id);
   }

   forward()
   {
		var id= $('#expdocsubmissionFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/forward?id="+id);
   }
   
   boe()
   {
		var id= $('#expdocsubmissionFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/billofexchange?id="+id);
   }
}
window.MsExpDocSubmission=new MsExpDocSubmissionController(new MsExpDocSubmissionModel());
MsExpDocSubmission.showGrid();

 $('#comexpdocsubtabs').tabs({
	onSelect:function(title,index){
	 let exp_doc_submission_id = $('#expdocsubmissionFrm  [name=id]').val();

	 var data={};
	  data.exp_doc_submission_id=exp_doc_submission_id;

	 if(index==1){
		 if(exp_doc_submission_id===''){
			 $('#comexpdocsubtabs').tabs('select',0);
			 msApp.showError('Select a document submission to bank First',0);
			 return;
		  }
		 $('#expdocsubinvoiceFrm  [name=exp_doc_submission_id]').val(exp_doc_submission_id);
		MsExpDocSubInvoice.create(exp_doc_submission_id);
	 }
	 if(index==2){
		 if(exp_doc_submission_id===''){
			 $('#comexpdocsubtabs').tabs('select',0);
			 msApp.showError('Select a document submission to bank First',0);
			 return;
		  }
		 $('#expdocsubtransectionFrm  [name=exp_doc_submission_id]').val(exp_doc_submission_id);
		MsExpDocSubTransection.showGrid(exp_doc_submission_id);
	 }
}
}); 
