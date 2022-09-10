let MsCashIncentiveRealizeRcvModel = require('./MsCashIncentiveRealizeRcvModel');
class MsCashIncentiveRealizeRcvController
{
	constructor(MsCashIncentiveRealizeRcvModel)
	{
		this.MsCashIncentiveRealizeRcvModel = MsCashIncentiveRealizeRcvModel;
		this.formId = 'cashincentiverealizercvFrm';
		this.dataTable = '#cashincentiverealizercvTbl';
		this.route = msApp.baseUrl() + "/cashincentiverealizercv"
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
			this.MsCashIncentiveRealizeRcvModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsCashIncentiveRealizeRcvModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#cashincentiverealizercvFrm  [name=cash_incentive_realize_id]').val($('#cashincentiverealizeFrm  [name=id]').val());
		$('#cashincentiverealizercvFrm [id="commercial_head_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsCashIncentiveRealizeRcvModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsCashIncentiveRealizeRcvModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#cashincentiverealizercvTbl').datagrid('reload');
		// msApp.resetForm('cashincentiverealizercvFrm');
		$('#cashincentiverealizercvFrm  [name=cash_incentive_realize_id]').val($('#cashincentiverealizeFrm  [name=id]').val());
		$('#cashincentiverealizercvFrm [name=sanctioned_amount]').val($('#cashincentiverealizeFrm [name=sanctioned_amount]').val());

		$('#cashincentiverealizercvFrm [name=id]').val('');
		$('#cashincentiverealizercvFrm [name=commercial_head_id]').val('');
		$('#cashincentiverealizercvFrm [name=amount]').val('');
		$('#cashincentiverealizercvFrm [name=remarks]').val('');

		$('#cashincentiverealizercvFrm [id="commercial_head_id"]').combobox('setValue', '');

	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		cashincentiverealizercv = this.MsCashIncentiveRealizeRcvModel.get(index, row);
		cashincentiverealizercv.then(function (response)
		{
			$('#cashincentiverealizercvFrm [id="commercial_head_id"]').combobox('setValue', response.data.fromData.commercial_head_id);
		})
	}

	showGrid(cash_incentive_realize_id)
	{
		let self = this;
		var data = {};
		data.cash_incentive_realize_id = cash_incentive_realize_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			//fitColumns:true,
			showFooter: true,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			},
			onLoadSuccess: function (data)
			{
				var tAmount = 0;
				var i;
				for (i = 0; i < data.rows.length; i++) {
					tAmount += data.rows[i]['amount'].replace(/,/g, '') * 1;
				}
				$(this).datagrid('reloadFooter', [
					{
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}



	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveRealizeRcv.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateReceiveAmount()
	{
		let tax_percent = $('#cashincentiverealizercvFrm [name=tax_percent]').val();
		let sanctioned_amount = $('#cashincentiverealizercvFrm [name=sanctioned_amount]').val();
		// let commercial_head_id = $('#cashincentiverealizercvFrm [name=commercial_head_id]').val();
		//if (commercial_head_id == 22) {
		let amount = (sanctioned_amount * (tax_percent / 100)).toFixed(2);
		$('#cashincentiverealizercvFrm [name=amount]').val(amount);

		//	} 
	}

}
window.MsCashIncentiveRealizeRcv = new MsCashIncentiveRealizeRcvController(new MsCashIncentiveRealizeRcvModel());