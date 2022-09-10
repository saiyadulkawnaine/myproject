let MsSoEmbPrintQcDtlModel = require('./MsSoEmbPrintQcDtlModel');

class MsSoEmbPrintQcDtlController
{
	constructor(MsSoEmbPrintQcDtlModel)
	{
		this.MsSoEmbPrintQcDtlModel = MsSoEmbPrintQcDtlModel;
		this.formId = 'soembprintqcdtlFrm';
		this.dataTable = '#soembprintqcdtlTbl';
		this.route = msApp.baseUrl() + "/soembprintqcdtl"
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
			this.MsSoEmbPrintQcDtlModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintQcDtlModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		let so_emb_print_qc_id = $('#soembprintqcFrm  [name=id]').val();
		$('#soembprintqcdtlFrm  [name=so_emb_print_qc_id]').val(so_emb_print_qc_id);
		$('#soembprintqcdtlFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintQcDtlModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintQcDtlModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#soembprintqcdtlTbl').datagrid('reload');
		msApp.resetForm('soembprintqcdtlFrm');
		MsSoEmbPrintQcDtl.resetForm();
		$('#soembprintqcdtlFrm [name=so_emb_print_qc_id]').val($('#soembprintqcFrm [name=id]').val());

	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		let so = this.MsSoEmbPrintQcDtlModel.get(index, row);
		so.then(function (response)
		{
			$('#soembprintqcdtlFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
			.catch(function (error)
			{
				console.log(error);
			});

	}

	showGrid(so_emb_print_qc_id)
	{
		let self = this;
		let data = {};
		data.so_emb_print_qc_id = so_emb_print_qc_id;
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintQcDtl.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openSoEmbPrintWindow()
	{
		$('#opensoembentorderwindow').window('open');
	}

	getParams()
	{
		let params = {};
		params.sale_order_no = $('#opensoembentordersearchFrm [name=sale_order_no]').val();
		params.prod_source_id = $('#soembprintqcdtlFrm [name=prod_source_id]').val();
		params.buyer_id = $('#soembprintqcFrm [name=buyer_id]').val();
		if (params.prod_source_id === '') {
			alert("Select Souction Source");
		}
		return params;
	}
	searchSoEmbEntOrderGrid()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getsoembprint', { params })
			.then(function (response)
			{
				$('#opensoembentordersearchTbl').datagrid('loadData', response.data);
			}).catch(function (error)
			{
				console.log(error);
			});
	}
	showSoEmbEntOrderGrid(data)
	{
		let self = this;
		$('#opensoembentordersearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#soembprintqcdtlFrm [name=so_emb_ref_id]').val(row.so_emb_ref_id);
				$('#soembprintqcdtlFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soembprintqcdtlFrm [name=gmtspart]').val(row.gmtspart);
				$('#soembprintqcdtlFrm [name=item_desc]').val(row.item_desc);
				$('#soembprintqcdtlFrm [name=gmt_color]').val(row.gmt_color);
				$('#soembprintqcdtlFrm [name=design_no]').val(row.design_no);
				$('#opensoembentorderwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsSoEmbPrintQcDtl = new MsSoEmbPrintQcDtlController(new MsSoEmbPrintQcDtlModel());
MsSoEmbPrintQcDtl.showSoEmbEntOrderGrid([]);