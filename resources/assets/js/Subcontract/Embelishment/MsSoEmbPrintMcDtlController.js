let MsSoEmbPrintMcDtlModel = require('./MsSoEmbPrintMcDtlModel');

class MsSoEmbPrintMcDtlController
{
	constructor(MsSoEmbPrintMcDtlModel)
	{
		this.MsSoEmbPrintMcDtlModel = MsSoEmbPrintMcDtlModel;
		this.formId = 'soembprintmcdtlFrm';
		this.dataTable = '#soembprintmcdtlTbl';
		this.route = msApp.baseUrl() + "/soembprintmcdtl"
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
			this.MsSoEmbPrintMcDtlModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintMcDtlModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#soembprintmcdtlFrm [name=wstudy_line_setup_id]').val($('#soembprintmcFrm [name=id]').val());
		$('#soembprintmcdtlFrm [name=sewing_start_at]').val('08:00:00 AM');
		$('#soembprintmcdtlFrm [name=sewing_end_at]').val('05:00:00 PM');
		$('#soembprintmcdtlFrm [name=lunch_start_at]').val('01:00:00 PM');
		$('#soembprintmcdtlFrm [name=lunch_end_at]').val('02:00:00 PM');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintMcDtlModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintMcDtlModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#soembprintmcdtlTbl').datagrid('reload');
		msApp.resetForm('soembprintmcdtlFrm');
		$('#soembprintmcdtlFrm [name=so_emb_print_mc_id]').val($('#soembprintmcFrm [name=id]').val());
		$('#soembprintmcdtlFrm [name=printing_start_at]').val('08:00:00 AM');
		$('#soembprintmcdtlFrm [name=printing_end_at]').val('05:00:00 PM');
		$('#soembprintmcdtlFrm [name=lunch_start_at]').val('01:00:00 PM');
		$('#soembprintmcdtlFrm [name=lunch_end_at]').val('02:00:00 PM');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsSoEmbPrintMcDtlModel.get(index, row);

	}

	showGrid(so_emb_print_mc_id)
	{
		let self = this;
		var data = {};
		data.so_emb_print_mc_id = so_emb_print_mc_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			fitColumns: true,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			}
		}).datagrid('enableFilter');
	}

	openLineSetupStyleRefWindow()
	{
		$('#openlinesetupstylerefwindow').window('open');
	}


	calculateTotalmnt()
	{
		let self = this;
		let operator;
		let helper;
		let working_hour;
		let overtime_hour;
		operator = ($('#soembprintmcdtlFrm [name=operator]').val()) * 1;
		helper = ($('#soembprintmcdtlFrm [name=helper]').val()) * 1;
		working_hour = ($('#soembprintmcdtlFrm [name=working_hour]').val()) * 1;
		overtime_hour = ($('#soembprintmcdtlFrm [name=overtime_hour]').val()) * 1;
		let total_mnt = (operator + helper) * (working_hour + overtime_hour) * 60;
		$('#soembprintmcdtlFrm [name=total_mnt]').val(total_mnt);
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintMcDtl.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openEmployeeWindow()
	{
		$("#openemployeesearchwindow").window('open');
	}
	searchEmployee()
	{
		let params = {};
		params.designation_id = $('#employeesearchFrm [name=designation_id]').val();
		params.department_id = $('#employeesearchFrm [name=department_id]').val();
		params.company_id = $('#soembprintmcFrm [name=company_id]').val();
		let data = axios.get(this.route + "/getemployee", { params });
		data.then(function (response)
		{
			$('#employeesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}

	showEmployeeGrid(data)
	{
		let self = this;
		var dg = $('#employeesearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#soembprintmcdtlFrm [name=employee_h_r_id]').val(row.id);
				$('#soembprintmcdtlFrm [name=employee_name]').val(row.employee_name);
				$('#openemployeesearchwindow').window('close');
				$('#employeesearchTbl').datagrid('loadData', []);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsSoEmbPrintMcDtl = new MsSoEmbPrintMcDtlController(new MsSoEmbPrintMcDtlModel());
MsSoEmbPrintMcDtl.showEmployeeGrid([]);