let MsAccTermLoanModel = require('./MsAccTermLoanModel');
require('./../datagrid-filter.js');
class MsAccTermLoanController {
	constructor(MsAccTermLoanModel)
	{
		this.MsAccTermLoanModel = MsAccTermLoanModel;
		this.formId='acctermloanFrm';
		this.dataTable='#acctermloanTbl';
		this.route=msApp.baseUrl()+"/acctermloan"
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
			this.MsAccTermLoanModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccTermLoanModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccTermLoanModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccTermLoanModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#acctermloanTbl').datagrid('reload');
		msApp.resetForm(this.formId);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccTermLoanModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tAmount=0;
				var tInstallAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					tInstallAmount+=data.rows[i]['installment_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						installment_amount: tInstallAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAccTermLoan.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	bankaccountWindowOpen(){
		$('#openbankaccountWindow').window('open');
	}

	searchBankAccount()
	{
		let name =$('#bankaccountsearchFrm  [name=name]').val();
		let account_no=$('#bankaccountsearchFrm  [name=account_no]').val();
		let data= axios.get(this.route+"/getbankaccount?name="+name+"&account_no="+account_no);
		data.then(function (response) {
			$('#bankaccountsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showBankAccountGrid(data){
		$('#bankaccountsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#acctermloanFrm [name=bank_account_id]').val(row.id);
				$('#acctermloanFrm  [name=account_no]').val(row.account_no);
				$('#acctermloanFrm  [name=company_id]').val(row.company_id);
				$('#acctermloanFrm  [name=account_type_id]').val(row.account_type_id);
				$('#acctermloanFrm  [name=bank_branch_id]').val(row.bank_branch_id);
				$('#openbankaccountWindow').window('close');
				$('#bankaccountsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsAccTermLoan=new MsAccTermLoanController(new MsAccTermLoanModel());
MsAccTermLoan.showGrid();
MsAccTermLoan.showBankAccountGrid([]);
$('#acctermloantabs').tabs({
    onSelect:function(title,index){
        let acc_term_loan_id = $('#acctermloanFrm [name=id]').val();
        let acc_term_loan_installment_id = $('#acctermloaninstallmentFrm [name=id]').val();
        
        var data={};
	    data.acc_term_loan_id=acc_term_loan_id;
	    data.acc_term_loan_installment_id=acc_term_loan_installment_id;

        if(index==1){
			if(acc_term_loan_id===''){
				$('#acctermloantabs').tabs('select',0);
				msApp.showError('Select a Loan Reference First',0);
				return;
		    }
			msApp.resetForm('acctermloaninstallmentFrm');
			$('#acctermloaninstallmentFrm  [name=acc_term_loan_id]').val(acc_term_loan_id);
			MsAccTermLoanInstallment.showGrid(acc_term_loan_id);
        }
        if(index==2){
			if(acc_term_loan_installment_id===''){
				$('#acctermloantabs').tabs('select',0);
				msApp.showError('Select an Installment Details First',0);
				return;
		    }
			msApp.resetForm('acctermloanpaymentFrm');
			$('#acctermloanpaymentFrm  [name=sort_id]').val($('#acctermloaninstallmentFrm [name=sort_id]').val());
			$('#acctermloanpaymentFrm  [name=acc_term_loan_installment_id]').val($('#acctermloaninstallmentFrm [name=id]').val());
			MsAccTermLoanPayment.showGrid(acc_term_loan_installment_id);
        }
    }
});
