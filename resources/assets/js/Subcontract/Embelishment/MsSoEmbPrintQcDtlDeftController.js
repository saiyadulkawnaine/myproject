let MsSoEmbPrintQcDtlDeftModel = require('./MsSoEmbPrintQcDtlDeftModel');
class MsSoEmbPrintQcDtlDeftController
{
	constructor(MsSoEmbPrintQcDtlDeftModel)
	{
		this.MsSoEmbPrintQcDtlDeftModel = MsSoEmbPrintQcDtlDeftModel;
		this.formId = 'soembprintqcdtldeftFrm';
		this.dataTable = '#soembprintqcdtldeftTbl';
		this.route = msApp.baseUrl() + "/soembprintqcdtldeft"
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
		let so_emb_print_qc_dtl_id = $('#soembprintqcdtlFrm  [name=id]').val();
		let formObj = msApp.get(this.formId);
		formObj.so_emb_print_qc_dtl_id = so_emb_print_qc_dtl_id;
		// alert(so_emb_print_qc_dtl_id)
		if (formObj.id) {
			this.MsSoEmbPrintQcDtlDeftModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintQcDtlDeftModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintQcDtlDeftModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintQcDtlDeftModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#soembprintqcdtldeftTbl').datagrid('reload');
		msApp.resetForm('soembprintqcdtldeftFrm');
		$('#soembprintqcdtldeftFrm  [name=so_emb_print_qc_dtl_id]').val($('#soembprintqcdtlFrm  [name=id]').val());
		MsSoEmbPrintQcDtlDeft.create(d.so_emb_print_qc_dtl_id);

	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsSoEmbPrintQcDtlDeftModel.get(index, row);
	}

	showGrid(so_emb_print_qc_dtl_id)
	{
		let self = this;
		var data = {};
		data.so_emb_print_qc_dtl_id = so_emb_print_qc_dtl_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			fitColumns: true,
			queryParams: data,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			}
		});
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintQcDtlDeft.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	create(so_emb_print_qc_dtl_id)
	{
		let data = axios.get(this.route + "/create" + "?so_emb_print_qc_dtl_id=" + so_emb_print_qc_dtl_id)
			.then(function (response)
			{
				$('#soembprintqcdtldeftmatrix').html(response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}
	calculate(i)
	{
		let qty = $('#soembprintqcdtldeftFrm [name="qty[' + i + ']"]').val();
		let rate = $('#soembprintqcdtldeftFrm  [name="rate[' + i + ']"]').val();
		let amount = msApp.multiply(qty, rate);
		$('#soembprintqcdtldeftFrm  [name="amount[' + i + ']"]').val(amount);
	}
}
window.MsSoEmbPrintQcDtlDeft = new MsSoEmbPrintQcDtlDeftController(new MsSoEmbPrintQcDtlDeftModel());
