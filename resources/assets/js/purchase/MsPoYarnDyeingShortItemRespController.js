require('./../datagrid-filter.js');
let MsPoYarnDyeingShortItemRespModel = require('./MsPoYarnDyeingShortItemRespModel');
class MsPoYarnDyeingShortItemRespController
{
	constructor(MsPoYarnDyeingShortItemRespModel)
	{
		this.MsPoYarnDyeingShortItemRespModel = MsPoYarnDyeingShortItemRespModel;
		this.formId = 'poyarndyeingshortitemrespFrm';
		this.dataTable = '#poyarndyeingshortitemrespTbl';
		this.route = msApp.baseUrl() + "/poyarndyeingshortitemresp"
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
			this.MsPoYarnDyeingShortItemRespModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsPoYarnDyeingShortItemRespModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
		let po_yarn_dyeing_item_id = $('#poyarndyeingshortitemFrm  [name=id]').val();
		$('#poyarndyeingshortitemrespFrm  [name=po_yarn_dyeing_item_id]').val(po_yarn_dyeing_item_id);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsPoYarnDyeingShortItemRespModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation();
		this.MsPoYarnDyeingShortItemRespModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		MsPoYarnDyeingShortItemResp.resetForm();
		let po_yarn_dyeing_item_id = $('#poyarndyeingshortitemFrm  [name=id]').val();
		MsPoYarnDyeingShortItemResp.get(po_yarn_dyeing_item_id);
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsPoYarnDyeingShortItemRespModel.get(index, row);
	}

	get(po_yarn_dyeing_item_id)
	{
		let data = axios.get(this.route + "?po_yarn_dyeing_item_id=" + po_yarn_dyeing_item_id)
			.then(function (response)
			{
				$('#poyarndyeingshortitemrespTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGrid(data)
	{
		let self = this;
		var dg = $('#poyarndyeingshortitemrespTbl');
		dg.datagrid({
			border: false,
			fit: true,
			singleSelect: true,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			},
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	deleteButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnDyeingShortItemResp.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openDyeingResponsibleEmpHrWindow()
	{
		$('#openrespemployhrwindow').window('open');
	}

	getParams()
	{
		let params = {};
		params.company_id = $('#employeerespsearchFrm [name=company_id]').val();
		params.designation_id = $('#employeerespsearchFrm [name=designation_id]').val();
		params.department_id = $('#employeerespsearchFrm [name=department_id]').val();
		return params;
	}

	searchResponsibleEmployeeHr()
	{
		let params = this.getParams();
		let rpt = axios.get(this.route + "/getemployeehr", { params })
			.then(function (response)
			{
				$('#employeerespsearchTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
		return rpt;
	}

	showResponsibleEmployeeGrid(data)
	{
		let self = this;
		var pr = $('#employeerespsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#poyarndyeingshortitemrespFrm  [name=employee_h_r_id]').val(row.id);
				$('#poyarndyeingshortitemrespFrm  [name=employee_name]').val(row.employee_name);
				$('#openrespemployhrwindow').window('close')
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsPoYarnDyeingShortItemResp = new MsPoYarnDyeingShortItemRespController(new MsPoYarnDyeingShortItemRespModel());
MsPoYarnDyeingShortItemResp.showResponsibleEmployeeGrid([])
MsPoYarnDyeingShortItemResp.showGrid([])