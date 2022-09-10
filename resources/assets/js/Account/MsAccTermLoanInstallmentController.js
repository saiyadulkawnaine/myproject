let MsAccTermLoanInstallmentModel = require('./MsAccTermLoanInstallmentModel');

class MsAccTermLoanInstallmentController {
	constructor(MsAccTermLoanInstallmentModel)
	{
		this.MsAccTermLoanInstallmentModel = MsAccTermLoanInstallmentModel;
		this.formId='acctermloaninstallmentFrm';
		this.dataTable='#acctermloaninstallmentTbl';
		this.route=msApp.baseUrl()+"/acctermloaninstallment"
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
			this.MsAccTermLoanInstallmentModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccTermLoanInstallmentModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#acctermloaninstallmentFrm  [name=acc_term_loan_id]').val($('#acctermloanFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccTermLoanInstallmentModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccTermLoanInstallmentModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#acctermloaninstallmentTbl').datagrid('reload');
		MsAccTermLoanInstallment.resetForm();
		//$('#acctermloaninstallmentFrm  [name=acc_term_loan_id]').val($('#acctermloanFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccTermLoanInstallmentModel.get(index,row);
	}

	showGrid(acc_term_loan_id) {
		let self = this;
		var data = {};
		data.acc_term_loan_id = acc_term_loan_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			showFooter:true,
			queryParams: data,
			url: this.route,
			onClickRow: function (index, row) {
				self.edit(index, row);
			},
			onLoadSuccess: function(data){
				var tAmount=0;
				var tPaidAmount=0;
				var tBalanceAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					tPaidAmount+=data.rows[i]['paid_amount'].replace(/,/g,'')*1;
					tBalanceAmount+=data.rows[i]['balance_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						paid_amount: tPaidAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						balance_amount: tBalanceAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAccTermLoanInstallment.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsAccTermLoanInstallment=new MsAccTermLoanInstallmentController(new MsAccTermLoanInstallmentModel());