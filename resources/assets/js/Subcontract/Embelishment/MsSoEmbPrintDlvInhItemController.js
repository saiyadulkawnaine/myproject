
let MsSoEmbPrintDlvInhItemModel = require('./MsSoEmbPrintDlvInhItemModel');

class MsSoEmbPrintDlvInhItemController
{
	constructor(MsSoEmbPrintDlvInhItemModel)
	{
		this.MsSoEmbPrintDlvInhItemModel = MsSoEmbPrintDlvInhItemModel;
		this.formId = 'soembprintdlvinhitemFrm';
		this.dataTable = '#soembprintdlvinhitemTbl';
		this.route = msApp.baseUrl() + "/soembprintdlvinhitem"
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
			this.MsSoEmbPrintDlvInhItemModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintDlvInhItemModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		let so_emb_print_dlv_id = $('#soembprintdlvinhFrm [name=id]').val();
		$('#soembprintdlvinhitemFrm [name=so_emb_print_dlv_id]').val(so_emb_print_dlv_id);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintDlvInhItemModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintDlvInhItemModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}


	response(d)
	{
		$('#soembprintdlvinhitemTbl').datagrid('reload');
		msApp.resetForm('soembprintdlvinhitemFrm');
		MsSoEmbPrintDlvInhItem.resetForm();
		$('#soembprintdlvinhitemFrm [name=so_emb_print_dlv_id]').val($('#soembprintdlvinhFrm [name=id]').val());
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsSoEmbPrintDlvInhItemModel.get(index, row);
	}

	showGrid(so_emb_print_dlv_id)
	{
		let self = this;
		let data = {};
		data.so_emb_print_dlv_id = so_emb_print_dlv_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			fitColumns: true,
			showFooter: true,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintDlvInhItem.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openProdEmbPrintOpen()
	{
		$('#soembprintdlvinhitemWindow').window('open');
	}

	searchProdEmbItem() 
	{
		let params = {};
		params.sale_order_no = $('#soembprintsearchFrm  [name=sale_order_no]').val();
		params.currency_id = $('#soembprintdlvinhFrm [name=currency_id]').val();
		let data = axios.get(this.route + "/getembsalesorder", { params });
		data.then(function (response)
		{
			$('#soembprintsearchTbl').datagrid('loadData', response.data);
		})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showProdEmbGridItem(data)
	{
		$('#soembprintsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#soembprintdlvinhitemFrm [name=so_emb_cutpanel_rcv_qty_id]').val(row.id);
				$('#soembprintdlvinhitemFrm  [name=gmtspart]').val(row.gmtspart);
				$('#soembprintdlvinhitemFrm  [name=emb_name]').val(row.emb_name);
				$('#soembprintdlvinhitemFrm  [name=emb_type]').val(row.emb_type);
				$('#soembprintdlvinhitemFrm  [name=emb_size]').val(row.emb_size);
				$('#soembprintdlvinhitemFrm  [name=item_desc]').val(row.item_desc);
				$('#soembprintdlvinhitemFrm  [name=gmt_color]').val(row.gmt_color);
				$('#soembprintdlvinhitemFrm  [name=gmt_size]').val(row.gmt_size);
				$('#soembprintdlvinhitemFrm  [name=uom_name]').val(row.uom_name);
				$('#soembprintdlvinhitemFrm  [name=rate]').val(row.rate);
				$('#soembprintdlvinhitemFrm  [name=buyer_name]').val(row.buyer_name);
				$('#soembprintdlvinhitemFrm  [name=style_ref]').val(row.style_ref);
				$('#soembprintdlvinhitemFrm  [name=sale_order_no]').val(row.sale_order_no);
				$('#soembprintdlvinhitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	calculate()
	{
		let dlv_qty = ($('#soembprintdlvinhitemFrm  [name=dlv_qty]').val()) * 1;
		let rate = ($('#soembprintdlvinhitemFrm  [name=rate]').val()) * 1;
		let additional_charge = ($('#soembprintdlvinhitemFrm  [name=additional_charge]').val()) * 1;
		let amount = (rate + additional_charge) * dlv_qty;
		$('#soembprintdlvinhitemFrm  [name=amount]').val(amount);
	}

}
window.MsSoEmbPrintDlvInhItem = new MsSoEmbPrintDlvInhItemController(new MsSoEmbPrintDlvInhItemModel());
MsSoEmbPrintDlvInhItem.showProdEmbGridItem([])