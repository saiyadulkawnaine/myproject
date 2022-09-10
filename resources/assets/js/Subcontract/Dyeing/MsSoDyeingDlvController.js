let MsSoDyeingDlvModel = require('./MsSoDyeingDlvModel');
require('./../../datagrid-filter.js');
class MsSoDyeingDlvController
{
	constructor(MsSoDyeingDlvModel)
	{
		this.MsSoDyeingDlvModel = MsSoDyeingDlvModel;
		this.formId = 'sodyeingdlvFrm';
		this.dataTable = '#sodyeingdlvTbl';
		this.route = msApp.baseUrl() + "/sodyeingdlv"
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
			this.MsSoDyeingDlvModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		}
		else {
			this.MsSoDyeingDlvModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#sodyeingdlvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoDyeingDlvModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoDyeingDlvModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#sodyeingdlvTbl').datagrid('reload');
		msApp.resetForm('sodyeingdlvFrm');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		workReceive = this.MsSoDyeingDlvModel.get(index, row);
		workReceive.then(function (response)
		{
			$('#sodyeingdlvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function (error)
		{
			console.log(errors)
		});
	}

	showGrid()
	{
		let self = this;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			fitColumns: true,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingDlv.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showDc()
	{
		var id = $('#sodyeingdlvFrm  [name=id]').val();
		if (id == "") {
			alert("Select a GIN");
			return;
		}
		window.open(this.route + "/dlvchalan?id=" + id);
	}
	showBill()
	{
		var id = $('#sodyeingdlvFrm  [name=id]').val();
		if (id == "") {
			alert("Select a GIN");
			return;
		}
		window.open(this.route + "/bill?id=" + id);
	}

	showBillTk()
	{
		var id = $('#sodyeingdlvFrm [name=id]').val();
		if (id == "") {
			alert("Select a GIN");
			return;
		}
		window.open(this.route + "/billtk?id=" + id);
	}

	searchSoDyeingDlvList()
	{
		let params = {};
		params.customer_id = $('#customer_id').val();
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
		let data = axios.get(this.route + "/getsodyeingdlvlist", { params });
		data.then(function (response)
		{
			$('#sodyeingdlvTbl').datagrid('loadData', response.data);
		})
			.catch(function (error)
			{
				console.log(error);
			});
	}




}
window.MsSoDyeingDlv = new MsSoDyeingDlvController(new MsSoDyeingDlvModel());
MsSoDyeingDlv.showGrid();
$('#sodyeingdlvtabs').tabs({
	onSelect: function (title, index)
	{
		let so_dyeing_dlv_id = $('#sodyeingdlvFrm  [name=id]').val();
		var data = {};
		data.so_dyeing_dlv_id = so_dyeing_dlv_id;
		if (index == 1) {
			if (so_dyeing_dlv_id === '') {
				$('#sodyeingdlvtabs').tabs('select', 0);
				msApp.showError('Select a Start Up First', 0);
				return;
			}
			$('#sodyeingdlvitemFrm  [name=so_dyeing_dlv_id]').val(so_dyeing_dlv_id);
			MsSoDyeingDlvItem.get(so_dyeing_dlv_id);
		}
	}
}); 
