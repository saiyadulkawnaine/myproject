let MsSoEmbPrintMcDtlOrdModel = require('./MsSoEmbPrintMcDtlOrdModel');

class MsSoEmbPrintMcDtlOrdController
{
	constructor(MsSoEmbPrintMcDtlOrdModel)
	{
		this.MsSoEmbPrintMcDtlOrdModel = MsSoEmbPrintMcDtlOrdModel;
		this.formId = 'soembprintmcdtlordFrm';
		this.dataTable = '#soembprintmcdtlordTbl';
		this.route = msApp.baseUrl() + "/soembprintmcdtlord"
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
			this.MsSoEmbPrintMcDtlOrdModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintMcDtlOrdModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintMcDtlOrdModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintMcDtlOrdModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		let so_emb_print_mc_dtl_id = $('#soembprintmcdtlFrm [name=id]').val();
		MsSoEmbPrintMcDtlOrd.get(so_emb_print_mc_dtl_id);
		msApp.resetForm('soembprintmcdtlordFrm');
		$('#soembprintmcdtlordFrm [name=so_emb_print_mc_dtl_id]').val(so_emb_print_mc_dtl_id);
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsSoEmbPrintMcDtlOrdModel.get(index, row);

	}
	get(so_emb_print_mc_dtl_id)
	{
		let params = {};
		params.so_emb_print_mc_dtl_id = so_emb_print_mc_dtl_id;
		let d = axios.get(this.route, { params })
			.then(function (response)
			{
				$('#soembprintmcdtlordTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});

	}

	showGrid(data)
	{
		let self = this;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintMcDtlOrd.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	opensalesOrder()
	{
		$('#opensalesorderwindow').window('open');
	}

	searchSalesOrder()
	{
		let params = {};
		params.sale_order_no = $('#salesorderFrm [name=sale_order_no]').val();
		params.buyer_id = $('#salesorderFrm [name=buyer_id]').val();
		let data = axios.get(this.route + "/getsalesorder", { params });
		data.then(function (response)
		{
			$('#salesorderTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}

	showSalesOrderGrid(data)
	{
		let self = this;
		var dg = $('#salesorderTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#soembprintmcdtlordFrm [name=gmtspart_id]').val(row.gmtspart_id);
				$('#soembprintmcdtlordFrm [name=gmtspart]').val(row.gmtspart);
				$('#soembprintmcdtlordFrm [name=item_account_id]').val(row.item_account_id);
				$('#soembprintmcdtlordFrm [name=item_desc]').val(row.item_desc);
				$('#soembprintmcdtlordFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#soembprintmcdtlordFrm [name=so_emb_ref_id]').val(row.id);
				$('#opensalesorderwindow').window('close');
				$('#salesorderTbl').datagrid('loadData', []);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsSoEmbPrintMcDtlOrd = new MsSoEmbPrintMcDtlOrdController(new MsSoEmbPrintMcDtlOrdModel());
MsSoEmbPrintMcDtlOrd.showGrid([]);
MsSoEmbPrintMcDtlOrd.showSalesOrderGrid([]);