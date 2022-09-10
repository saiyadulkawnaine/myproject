let MsAccTermLoanAdjustmentModel = require('./MsAccTermLoanAdjustmentModel');
require('./../datagrid-filter.js');
class MsAccTermLoanAdjustmentController {
	constructor(MsAccTermLoanAdjustmentModel)
	{
		this.MsAccTermLoanAdjustmentModel = MsAccTermLoanAdjustmentModel;
		this.formId='acctermloanadjustmentFrm';
		this.dataTable='#acctermloanadjustmentTbl';
		this.route=msApp.baseUrl()+"/acctermloanadjustment"
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
			this.MsAccTermLoanAdjustmentModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccTermLoanAdjustmentModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
		resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccTermLoanAdjustmentModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccTermLoanAdjustmentModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#acctermloanadjustmentTbl').datagrid('reload');
		msApp.resetForm(this.formId);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccTermLoanAdjustmentModel.get(index,row);
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
			// onLoadSuccess: function(data){
			// 	var tAmount=0;
			// 	var tInstallAmount=0;
			// 	for(var i=0; i<data.rows.length; i++){
			// 		tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
			// 	}
			// 	// $(this).datagrid('reloadFooter', [
			// 	// 	{
			// 	// 		amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),

			// 	// 	}
			// 	// ]);
			// }
		}).datagrid('enableFilter');
	}



	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAccTermLoanAdjustment.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	// TermLoanSearchWindow
	TermLoanWindow(){
		$('#acctermloanWindow').window('open');
	}

	getParams(){
		let params={};
		params.commercial_head_id=$('#acctermloanadjustmentFrm  [name=commercial_head_id]').val();
		params.company_id=$('#acctermloansearchFrm  [name=company_id]').val();
		params.bank_branch_id=$('#acctermloansearchFrm  [name=bank_branch_id]').val();
		return params;
	}


	searchTermLoan()
	{
		let params=this.getParams();
		if (params.commercial_head_id=='') {
			alert('Select a Loan Name First');
			return;
		}
		let data= axios.get(this.route+"/gettermloan",{params});
		data.then(function (response) {
			$('#acctermloanSearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showTermLoanGrid(data){
		let self = this;
		$('#acctermloanSearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				// self.edit(index,row);
				$('#acctermloanadjustmentFrm [name=other_loan_ref_id]').val(row.id);
				$('#acctermloanadjustmentFrm [name=loan_ref_no]').val(row.loan_ref_no);
				$('#acctermloanadjustmentFrm [name=maturity_date]').val(row.maturity_date);
				$('#acctermloanWindow').window('close');
				$('#acctermloanSearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter');
	}


}
window.MsAccTermLoanAdjustment=new MsAccTermLoanAdjustmentController(new MsAccTermLoanAdjustmentModel());
MsAccTermLoanAdjustment.showGrid();
MsAccTermLoanAdjustment.showTermLoanGrid([]);


