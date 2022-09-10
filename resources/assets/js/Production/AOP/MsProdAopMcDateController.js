let MsProdAopMcDateModel = require('./MsProdAopMcDateModel');
class MsProdAopMcDateController {
	constructor(MsProdAopMcDateModel) {
		this.MsProdAopMcDateModel = MsProdAopMcDateModel;
		this.formId = 'prodaopmcdateFrm';
		this.dataTable = '#prodaopmcdateTbl';
		this.route = msApp.baseUrl() + "/prodaopmcdate"
	}

	submit() {
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
			this.MsProdAopMcDateModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsProdAopMcDateModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}
	resetForm() {
		msApp.resetForm(this.formId);
		$('#prodaopmcdateFrm  [name=prod_aop_mc_setup_id]').val($('#prodaopmcsetupFrm  [name=id]').val());
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsProdAopMcDateModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsProdAopMcDateModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#prodaopmcdateTbl').datagrid('reload');
		msApp.resetForm(this.formId);
			$('#prodaopmcdateFrm  [name=prod_aop_mc_setup_id]').val($('#prodaopmcsetupFrm  [name=id]').val());
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		this.MsProdAopMcDateModel.get(index, row);
	}

	showGrid(prod_aop_mc_setup_id) {
		let self = this;
		var data = {};
		data.prod_aop_mc_setup_id = prod_aop_mc_setup_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			url: this.route,
			onClickRow: function (index, row) {
				self.edit(index, row);
			},
		}).datagrid('enableFilter');
	}

	formatDetail(value, row) {
		return '<a href="javascript:void(0)"  onClick="MsProdAopMcDate.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsProdAopMcDate=new MsProdAopMcDateController(new MsProdAopMcDateModel());
