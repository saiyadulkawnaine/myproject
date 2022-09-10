let MsCashIncentiveRealizeModel = require('./MsCashIncentiveRealizeModel');
require('./../../datagrid-filter.js');
class MsCashIncentiveRealizeController
{
	constructor(MsCashIncentiveRealizeModel)
	{
		this.MsCashIncentiveRealizeModel = MsCashIncentiveRealizeModel;
		this.formId = 'cashincentiverealizeFrm';
		this.dataTable = '#cashincentiverealizeTbl';
		this.route = msApp.baseUrl() + "/cashincentiverealize"
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

		let formObj = msApp.get(this.formId);
		if (formObj.id) {
			this.MsCashIncentiveRealizeModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsCashIncentiveRealizeModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsCashIncentiveRealizeModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsCashIncentiveRealizeModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#cashincentiverealizeTbl').datagrid('reload');
		msApp.resetForm('cashincentiverealizeFrm');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsCashIncentiveRealizeModel.get(index, row);

	}

	showGrid()
	{
		let self = this;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			//fitColumns:true,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveRealize.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	openIncentiveRealizWindow()
	{
		$('#openRefWindow').window('open');
	}
	getParams()
	{
		let params = {};
		params.lc_sc_no = $('#cashrefsearchFrm [name="lc_sc_no"]').val();
		params.claim_sub_date = $('#cashrefsearchFrm [name="claim_sub_date"]').val();
		params.incentive_no = $('#cashrefsearchFrm [name="incentive_no"]').val();
		return params;
	}
	searchIncentiveRefGrid()
	{
		let params = this.getParams();
		let d = axios.get(this.route + "/cashreference", { params })
			.then(function (response)
			{
				$('#cashrefsearchTbl').datagrid('loadData', response.data);
			}).catch(function (error)
			{
				console.log(error);
			})

	}
	showIncentiveRefGrid(data)
	{
		let self = this;
		$('#cashrefsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#cashincentiverealizeFrm [name=cash_incentive_ref_id]').val(row.id);
				$('#cashincentiverealizeFrm [name=bank_file_no]').val(row.bank_file_no);
				$('#cashincentiverealizeFrm [name=incentive_no]').val(row.incentive_no);
				$('#cashincentiverealizeFrm [name=lc_sc_no]').val(row.lc_sc_no);
				$('#cashincentiverealizeFrm [name=claim_amount]').val(row.claim_amount);
				$('#cashincentiverealizeFrm [name=advance_amount_tk]').val(row.advance_amount_tk);
				$('#cashincentiverealizeFrm [name=loan_ref_no]').val(row.loan_ref_no);
				$('#cashincentiverealizeFrm [name=currency_id]').val(row.currency_id);
				$('#cashincentiverealizeFrm [name=buyer_id]').val(row.buyer_id);
				$('#cashincentiverealizeFrm [name=company_id]').val(row.beneficiary_id);
				$('#cashincentiverealizeFrm [name=company_name]').val(row.company_name);
				$('#cashincentiverealizeFrm [name=exporter_branch_name]').val(row.exporter_branch_name);
				$('#openRefWindow').window('close');
				$('#cashincentivelcsearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}




}
window.MsCashIncentiveRealize = new MsCashIncentiveRealizeController(new MsCashIncentiveRealizeModel());
MsCashIncentiveRealize.showGrid();
MsCashIncentiveRealize.showIncentiveRefGrid([]);

$('#realizetabs').tabs({
	onSelect: function (title, index)
	{
		let cash_incentive_realize_id = $('#cashincentiverealizeFrm  [name=id]').val();
		let sanctioned_amount = $('#cashincentiverealizeFrm [name=sanctioned_amount]').val();
		var data = {};
		data.cash_incentive_realize_id = cash_incentive_realize_id;
		data.sanctioned_amount = sanctioned_amount;

		if (index == 1) {
			if (cash_incentive_realize_id === '') {
				$('#realizetabs').tabs('select', 0);
				msApp.showError('Select a Cash Incentive Realization First', 0);
				return;
			}
			MsCashIncentiveRealizeRcv.resetForm();
			$('#cashincentiverealizercvFrm  [name=cash_incentive_realize_id]').val(cash_incentive_realize_id);
			$('#cashincentiverealizercvFrm  [name=sanctioned_amount]').val(sanctioned_amount);
			MsCashIncentiveRealizeRcv.showGrid(cash_incentive_realize_id);
		}
	}
}); 
