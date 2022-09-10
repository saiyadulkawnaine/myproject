let MsAccTermLoanPaymentModel = require('./MsAccTermLoanPaymentModel');
class MsAccTermLoanPaymentController {
	constructor(MsAccTermLoanPaymentModel) {
		this.MsAccTermLoanPaymentModel = MsAccTermLoanPaymentModel;
		this.formId = 'acctermloanpaymentFrm';
		this.dataTable = '#acctermloanpaymentTbl';
		this.route = msApp.baseUrl() + "/acctermloanpayment"
	}

	submit() {
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


		let formObj = msApp.get(this.formId);
		if (formObj.id) {
			this.MsAccTermLoanPaymentModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAccTermLoanPaymentModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#acctermloanpaymentFrm [name=acc_term_loan_installment_id]').val($('#acctermloaninstallmentFrm [name=id]').val());
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsAccTermLoanPaymentModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsAccTermLoanPaymentModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#acctermloanpaymentTbl').datagrid('reload');
		MsAccTermLoanPayment.resetForm();
		$('#acctermloanpaymentFrm [name=acc_term_loan_installment_id]').val($('#acctermloaninstallmentFrm [name=id]').val());
		$('#acctermloanpaymentFrm [name=sort_id]').val($('#acctermloaninstallmentFrm [name=sort_id]').val());
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		this.MsAccTermLoanPaymentModel.get(index, row);
	}

	showGrid(acc_term_loan_installment_id) {
		let self = this;
		var data = {};
		data.acc_term_loan_installment_id = acc_term_loan_installment_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			url: this.route,
			showFooter:true,
			onClickRow: function (index, row) {
				self.edit(index, row);
			},
			onLoadSuccess: function(data){
				var tAmount=0;
				var tInterestAmount=0;
				var tDelayChargeAmount=0;
				var tOtherChargeAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					tInterestAmount+=data.rows[i]['interest_amount'].replace(/,/g,'')*1;
					tDelayChargeAmount+=data.rows[i]['delay_charge_amount'].replace(/,/g,'')*1;
					tOtherChargeAmount+=data.rows[i]['other_charge_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						interest_amount: tInterestAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						delay_charge_amount: tDelayChargeAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						other_charge_amount: tOtherChargeAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row) {
		return '<a href="javascript:void(0)" onClick="MsAccTermLoanPayment.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsAccTermLoanPayment = new MsAccTermLoanPaymentController(new MsAccTermLoanPaymentModel());