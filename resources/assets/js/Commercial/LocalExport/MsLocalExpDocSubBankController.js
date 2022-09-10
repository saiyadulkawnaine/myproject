let MsLocalExpDocSubBankModel = require('./MsLocalExpDocSubBankModel');
require('./../../datagrid-filter.js');
class MsLocalExpDocSubBankController {
	constructor(MsLocalExpDocSubBankModel)
	{
		this.MsLocalExpDocSubBankModel = MsLocalExpDocSubBankModel;
		this.formId='localexpdocsubbankFrm';
		this.dataTable='#localexpdocsubbankTbl';
		this.route=msApp.baseUrl()+"/localexpdocsubbank"
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
			this.MsLocalExpDocSubBankModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpDocSubBankModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpDocSubBankModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpDocSubBankModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexpdocsubbankTbl').datagrid('reload');
		msApp.resetForm('localexpdocsubbankFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsLocalExpDocSubBankModel.get(index,row);	

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
		return '<a href="javascript:void(0)"  onClick="MsLocalExpDocSubBank.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openLocalDocSubAcceptWindow(){
		$('#localdocsubacceptwindow').window('open');
	}

	getParams(){
		let params={};
		params.beneficiary_id = $('#localdocsubacceptsearchFrm [name="beneficiary_id"]').val();
		params.local_lc_no = $('#localdocsubacceptsearchFrm [name="local_lc_no"]').val();
		params.lc_date = $('#localdocsubacceptsearchFrm [name="lc_date"]').val();
		return params;
	}

	searchDocSubAccept(){
		let params=this.getParams();
		let accept=axios.get(this.route+"/getlocaldocsubaccept",{params})
		.then(function(response){
			$('#localdocsubacceptsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		return accept;

	}
		
	showDocAcceptGrid(data){
		let self = this;
		$('#localdocsubacceptsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#localexpdocsubbankFrm [name=local_exp_doc_sub_accept_id]').val(row.local_exp_doc_sub_accept_id);
				$('#localexpdocsubbankFrm [name=local_lc_no_accept_id]').val(row.local_lc_no_accept_id);
				$('#localexpdocsubbankFrm [name=beneficiary_id]').val(row.beneficiary_id);
				$('#localexpdocsubbankFrm [name=buyer_id]').val(row.buyer_id);
				$('#localexpdocsubbankFrm [name=currency_id]').val(row.currency_id);
				$('#localexpdocsubbankFrm [name=buyers_bank]').val(row.buyers_bank);
				$('#localexpdocsubbankFrm [name=local_invoice_value]').val(row.local_invoice_value);
				$('#localdocsubacceptwindow').window('close');
				$('#localdocsubacceptsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

//    latter()
//    {
// 		var id= $('#localexpdocsubbankFrm  [name=id]').val();
// 		if(id==""){
// 			alert("Select a Document");
// 			return;
// 		}
// 		window.open(this.route+"/latter?id="+id);
//    }

}
window.MsLocalExpDocSubBank=new MsLocalExpDocSubBankController(new MsLocalExpDocSubBankModel());
MsLocalExpDocSubBank.showGrid();
MsLocalExpDocSubBank.showDocAcceptGrid([]);

 $('#comlocalexpdocsubtabs').tabs({
	onSelect:function(title,index){
	 let local_exp_doc_sub_bank_id = $('#localexpdocsubbankFrm  [name=id]').val();
	 let negotiation_date = $('#localexpdocsubbankFrm  [name=negotiation_date]').val();
	 let bank_ref_bill_no = $('#localexpdocsubbankFrm  [name=bank_ref_bill_no]').val();
	 let bank_ref_date = $('#localexpdocsubbankFrm  [name=bank_ref_date]').val();

	 var data={};
	  data.local_exp_doc_sub_bank_id=local_exp_doc_sub_bank_id;
	  data.negotiation_date=negotiation_date;
	  data.bank_ref_bill_no=bank_ref_bill_no;
	  data.bank_ref_date=bank_ref_date;

	if(index==1){
			if(local_exp_doc_sub_bank_id===''){
				if (negotiation_date=='' && bank_ref_bill_no=='' && bank_ref_date=='') {
					$('#comlocalexpdocsubtabs').tabs('select',0);
					msApp.showError('Please add a Negotiation Date, Bank Ref Bill No and Bank Ref Date  First',0);
					return;
				}else{
					$('#comlocalexpdocsubtabs').tabs('select',0);
					msApp.showError('Select a document submission to bank First',0);
					return;
				}
				
			 }
			$('#localexpdocsubtransFrm  [name=local_exp_doc_sub_bank_id]').val(local_exp_doc_sub_bank_id);
		   MsLocalExpDocSubTrans.showGrid(local_exp_doc_sub_bank_id);
		} 
	}
}); 
