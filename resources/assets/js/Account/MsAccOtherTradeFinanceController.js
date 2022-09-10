let MsAccOtherTradeFinanceModel = require('./MsAccOtherTradeFinanceModel');
require('./../datagrid-filter.js');
class AccOtherTradeFinanceController {
	constructor(MsAccOtherTradeFinanceModel)
	{
		this.MsAccOtherTradeFinanceModel = MsAccOtherTradeFinanceModel;
		this.formId='accothertradefinanceFrm';
		this.dataTable='#accothertradefinanceTbl';
		this.route=msApp.baseUrl()+"/accothertradefinance"
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
			this.MsAccOtherTradeFinanceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccOtherTradeFinanceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
		resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccOtherTradeFinanceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccOtherTradeFinanceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#accothertradefinanceTbl').datagrid('reload');
		msApp.resetForm(this.formId);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccOtherTradeFinanceModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsAccOtherTradeFinance.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	setMaturityDate(){
		let tenor=$('#grace_period').val()*1;
         if(!tenor){
            tenor=0;
         }
         tenor=tenor-1;
         let loan_date=new Date($('#loan_date').val());
         let maturity_date= msApp.addDays(loan_date,tenor);
         $('#maturity_date').val(maturity_date);
	}

	
	otherbankaccountWindowOpen(){
		$('#openotherbankaccountWindow').window('open');
	}

	searchOtherBankAccount()
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

	showOtherBankAccountGrid(data){
		$('#bankaccountsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#accothertradefinanceFrm [name=bank_account_id]').val(row.id);
				$('#accothertradefinanceFrm  [name=account_no]').val(row.account_no);
				$('#accothertradefinanceFrm  [name=company_id]').val(row.company_id);
				$('#accothertradefinanceFrm  [name=account_type_id]').val(row.account_type_id);
				$('#accothertradefinanceFrm  [name=bank_branch_id]').val(row.bank_branch_id);
				$('#openotherbankaccountWindow').window('close');
				$('#bankaccountsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsAccOtherTradeFinance=new AccOtherTradeFinanceController(new MsAccOtherTradeFinanceModel());
MsAccOtherTradeFinance.showGrid();
MsAccOtherTradeFinance.showOtherBankAccountGrid([]);

// $('#acctermloantabs').tabs({
//     onSelect:function(title,index){
//         let acc_term_loan_id = $('#accothertradefinanceFrm [name=id]').val();
//         // let acc_term_installment_id = $('#accterminstallmentFrm [name=id]').val();

//         var data={};
// 	    data.acc_term_loan_id=acc_term_loan_id;
// 	    // data.acc_term_installment_id=acc_term_installment_id;

//         if(index==1){
// 			if(acc_term_loan_id===''){
// 				$('#acctermloantabs').tabs('select',0);
// 				msApp.showError('Select a Loan Reference First',0);
// 				return;
// 		    }
// 			msApp.resetForm('accterminstallmentFrm');
// 			$('#accterminstallmentFrm  [name=acc_term_loan_id]').val(acc_term_loan_id);
// 			MsAccTermInstallment.showGrid(acc_term_loan_id);
//         }
//         if(index==2){
// 			if(acc_term_installment_id===''){
// 				$('#acctermloantabs').tabs('select',0);
// 				msApp.showError('Select an Installment Details First',0);
// 				return;
// 		    }
// 			msApp.resetForm('acctermpaymentFrm');
// 			$('#acctermpaymentFrm  [name=sort_id]').val($('#accterminstallmentFrm [name=sort_id]').val());
// 			$('#acctermpaymentFrm  [name=acc_term_installment_id]').val($('#accterminstallmentFrm [name=id]').val());
// 			MsAccTermPayment.showGrid(acc_term_installment_id);
//         }
//     }
// });
