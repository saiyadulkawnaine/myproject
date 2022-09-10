let MsProdFinishMcDateModel = require('./MsProdFinishMcDateModel');
class MsProdFinishMcDateController {
	constructor(MsProdFinishMcDateModel) {
		this.MsProdFinishMcDateModel = MsProdFinishMcDateModel;
		this.formId = 'prodfinishmcdateFrm';
		this.dataTable = '#prodfinishmcdateTbl';
		this.route = msApp.baseUrl() + "/prodfinishmcdate"
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
			this.MsProdFinishMcDateModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsProdFinishMcDateModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}
	
	resetForm() {
		msApp.resetForm(this.formId);
		$('#prodfinishmcdateFrm  [name=prod_finish_mc_setup_id]').val($('#prodfinishmcsetupFrm  [name=id]').val());
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsProdFinishMcDateModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsProdFinishMcDateModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#prodfinishmcdateTbl').datagrid('reload');
		msApp.resetForm(this.formId);
		$('#prodfinishmcdateFrm  [name=prod_finish_mc_setup_id]').val($('#prodfinishmcsetupFrm  [name=id]').val());
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		this.MsProdFinishMcDateModel.get(index, row);
	}

	showGrid(prod_finish_mc_setup_id) {
		let self = this;
		var data = {};
		data.prod_finish_mc_setup_id = prod_finish_mc_setup_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdFinishMcDate.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsProdFinishMcDate=new MsProdFinishMcDateController(new MsProdFinishMcDateModel());
