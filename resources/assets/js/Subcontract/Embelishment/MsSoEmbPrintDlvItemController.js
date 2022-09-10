
let MsSoEmbPrintDlvItemModel = require('./MsSoEmbPrintDlvItemModel');

class MsSoEmbPrintDlvItemController
{
	constructor(MsSoEmbPrintDlvItemModel)
	{
		this.MsSoEmbPrintDlvItemModel = MsSoEmbPrintDlvItemModel;
		this.formId = 'soembprintdlvitemFrm';
		this.dataTable = '#soembprintdlvitemTbl';
		this.route = msApp.baseUrl() + "/soembprintdlvitem"
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
			this.MsSoEmbPrintDlvItemModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintDlvItemModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		let so_emb_print_dlv_id = $('#soembprintdlvFrm [name=id]').val();
		$('#soembprintdlvitemFrm [name=so_emb_print_dlv_id]').val(so_emb_print_dlv_id);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintDlvItemModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintDlvItemModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}


	response(d)
	{
		$('#soembprintdlvitemTbl').datagrid('reload');
		msApp.resetForm('soembprintdlvitemFrm');
		MsSoEmbPrintDlvItem.resetForm();
		$('#soembprintdlvitemFrm [name=so_emb_print_dlv_id]').val($('#soembprintdlvFrm [name=id]').val());
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsSoEmbPrintDlvItemModel.get(index, row);
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintDlvItem.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openProdEmbPrintOpen()
	{
		$('#soembprintdlvitemWindow').window('open');
	}

	searchProdEmbItem() 
	{
		let params = {};
		params.sale_order_no = $('#soembprintsearchFrm  [name=sale_order_no]').val();
		params.currency_id = $('#soembprintdlvFrm [name=currency_id]').val();
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
				$('#soembprintdlvitemFrm [name=so_emb_cutpanel_rcv_qty_id]').val(row.id);
				$('#soembprintdlvitemFrm  [name=gmtspart]').val(row.gmtspart);
				$('#soembprintdlvitemFrm  [name=emb_name]').val(row.emb_name);
				$('#soembprintdlvitemFrm  [name=emb_type]').val(row.emb_type);
				$('#soembprintdlvitemFrm  [name=emb_size]').val(row.emb_size);
				$('#soembprintdlvitemFrm  [name=item_desc]').val(row.item_desc);
				$('#soembprintdlvitemFrm  [name=gmt_color]').val(row.gmt_color);
				$('#soembprintdlvitemFrm  [name=gmt_size]').val(row.gmt_size);
				$('#soembprintdlvitemFrm  [name=uom_name]').val(row.uom_name);
				$('#soembprintdlvitemFrm  [name=rate]').val(row.rate);
				$('#soembprintdlvitemFrm  [name=buyer_name]').val(row.buyer_name);
				$('#soembprintdlvitemFrm  [name=style_ref]').val(row.style_ref);
				$('#soembprintdlvitemFrm  [name=sale_order_no]').val(row.sale_order_no);
				$('#soembprintdlvitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	calculate()
	{
		let dlv_qty = ($('#soembprintdlvitemFrm  [name=dlv_qty]').val()) * 1;
		let rate = ($('#soembprintdlvitemFrm  [name=rate]').val()) * 1;
		let additional_charge = ($('#soembprintdlvitemFrm  [name=additional_charge]').val()) * 1;
		let amount = (rate + additional_charge) * dlv_qty;
		$('#soembprintdlvitemFrm  [name=amount]').val(amount);
	}

}
window.MsSoEmbPrintDlvItem = new MsSoEmbPrintDlvItemController(new MsSoEmbPrintDlvItemModel());
MsSoEmbPrintDlvItem.showProdEmbGridItem([])