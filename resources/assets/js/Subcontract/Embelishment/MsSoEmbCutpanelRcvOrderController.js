let MsSoEmbCutpanelRcvOrderModel = require('./MsSoEmbCutpanelRcvOrderModel');
class MsSoEmbCutpanelRcvOrderController
{
	constructor(MsSoEmbCutpanelRcvOrderModel)
	{
		this.MsSoEmbCutpanelRcvOrderModel = MsSoEmbCutpanelRcvOrderModel;
		this.formId = 'soembcutpanelrcvorderFrm';
		this.dataTable = '#soembcutpanelrcvorderTbl';
		this.route = msApp.baseUrl() + "/soembcutpanelrcvorder"
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
			this.MsSoEmbCutpanelRcvOrderModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		}
		else {
			this.MsSoEmbCutpanelRcvOrderModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#soembcutpanelrcvorderFrm [name=so_emb_cutpanel_rcv_id]').val($('#soembcutpanelrcvFrm [name=id]').val());
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbCutpanelRcvOrderModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbCutpanelRcvOrderModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		MsSoEmbCutpanelRcvOrder.resetForm();
		$('#soembcutpanelrcvorderFrm [name=id]').val(d.id);
		$('#soembcutpanelrcvorderFrm [name=so_emb_cutpanel_rcv_id]').val($('#soembcutpanelrcvFrm [name=id]').val());
		MsSoEmbCutpanelRcvOrder.get($('#soembcutpanelrcvFrm [name=id]').val());
		$('#soembcutpanelrcvqtyFrm [name=so_emb_cutpanel_rcv_order_id]').val($('#soembcutpanelrcvorderFrm [name=id]').val());
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsSoEmbCutpanelRcvOrderModel.get(index, row);
		$('#soembcutpanelrcvqtyFrm [name=so_emb_cutpanel_rcv_order_id]').val(row.id);
		MsSoEmbCutpanelRcvQty.get(row.id);
	}

	showGrid(data)
	{
		let self = this;
		$(this.dataTable).datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			fitColumns: true,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			},
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoEmbCutpanelRcvOrder.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	soWindow()
	{
		$('#soembWindow').window('open');
	}

	getsubconorder()
	{
		let params = {};
		params.company_id = $('#soembsearchFrm [name=company_id]').val();
		params.so_no = $('#soembsearchFrm [name=so_no]').val();

		params.buyer_id = $('#soembcutpanelrcvFrm  [name=buyer_id]').val();
		params.production_area_id = $('#soembcutpanelrcvFrm  [name=production_area_id]').val();

		let data = axios.get(this.route + "/getsoemb", { params });
		data.then(function (response)
		{
			$('#soembsearchTbl').datagrid('loadData', response.data);
		})
			.catch(function (error)
			{
				console.log(error);
			});
	}


	soEmbSearchGrid(data)
	{
		let self = this;
		$('#soembsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#soembcutpanelrcvorderFrm [name=so_emb_id]').val(row.id);
				$('#soembcutpanelrcvorderFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soembWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	get(so_emb_cutpanel_rcv_id)
	{
		let data = axios.get(this.route + "?so_emb_cutpanel_rcv_id=" + so_emb_cutpanel_rcv_id);
		data.then(function (response)
		{
			$('#soembcutpanelrcvorderTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}
}
window.MsSoEmbCutpanelRcvOrder = new MsSoEmbCutpanelRcvOrderController(new MsSoEmbCutpanelRcvOrderModel());
MsSoEmbCutpanelRcvOrder.showGrid([]);
MsSoEmbCutpanelRcvOrder.soEmbSearchGrid([]);