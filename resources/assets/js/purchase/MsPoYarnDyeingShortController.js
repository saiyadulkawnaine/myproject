require('./../datagrid-filter.js');
let MsPoYarnDyeingShortModel = require('./MsPoYarnDyeingShortModel');
class MsPoYarnDyeingShortController
{
	constructor(MsPoYarnDyeingShortModel)
	{
		this.MsPoYarnDyeingShortModel = MsPoYarnDyeingShortModel;
		this.formId = 'poyarndyeingshortFrm';
		this.dataTable = '#poyarndyeingshortTbl';
		this.route = msApp.baseUrl() + "/poyarndyeingshort"
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
			this.MsPoYarnDyeingShortModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsPoYarnDyeingShortModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#poyarndyeingshortFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsPoYarnDyeingShortModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsPoYarnDyeingShortModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#poyarndyeingshortTbl').datagrid('reload');
		msApp.resetForm('poyarndyeingshortFrm');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		let poyarndye = this.MsPoYarnDyeingShortModel.get(index, row);
		poyarndye.then(function (response)
		{
			$('#poyarndyeingshortFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
			.catch(function (error)
			{
				console.log(error);
			})
	}

	showGrid()
	{
		let self = this;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			showFooter: true,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			},
			onLoadSuccess: function (data)
			{
				var tQty = 0;
				var tAmout = 0;
				for (var i = 0; i < data.rows.length; i++) {
					tQty += data.rows[i]['item_qty'].replace(/,/g, '') * 1;
					tAmout += data.rows[i]['amount'].replace(/,/g, '') * 1;
				}

				$(this).datagrid('reloadFooter', [
					{
						item_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);


			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnDyeingShort.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf()
	{
		var id = $('#poyarndyeingshortFrm  [name=id]').val();
		if (id == "") {
			alert("Select an Order");
			return;
		}
		window.open(this.route + "/report?id=" + id);
	}
}
window.MsPoYarnDyeingShort = new MsPoYarnDyeingShortController(new MsPoYarnDyeingShortModel());
MsPoYarnDyeingShort.showGrid();

$('#poyarndyeingshorttabs').tabs({
	onSelect: function (title, index)
	{
		let po_yarn_dyeing_id = $('#poyarndyeingshortFrm  [name=id]').val();
		if (index == 1) {
			if (po_yarn_dyeing_id === '') {
				$('#poyarndyeingshorttabs').tabs('select', 0);
				msApp.showError('Select Purchase Order First', 0);
				return;
			}
			msApp.resetForm('poyarndyeingshortitemFrm');
			$('#poyarndyeingshortitemFrm [name=po_yarn_dyeing_id]').val(po_yarn_dyeing_id);
			MsPoYarnDyeingShortItem.get(po_yarn_dyeing_id);
		}
		if (index == 2) {
			let po_yarn_dyeing_item_id = $('#poyarndyeingshortitemFrm  [name=id]').val();
			msApp.resetForm('poyarndyeingshortitemresp');
			if (po_yarn_dyeing_item_id === '') {
				$('#poyarndyeingshorttabs').tabs('select', 1);
				msApp.showError('Select Yarn First', 0);
				return;
			}
			MsPoYarnDyeingShortItemResp.get(po_yarn_dyeing_item_id);
		}
		if (index == 3) {
			let po_yarn_dyeing_item_id = $('#poyarndyeingshortitemFrm  [name=id]').val();
			msApp.resetForm('poyarndyeingshortitembomqtyFrm');
			if (po_yarn_dyeing_item_id === '') {
				$('#poyarndyeingshorttabs').tabs('select', 1);
				msApp.showError('Select Yarn First', 0);
				return;
			}
			MsPoYarnDyeingShortItemBomQty.get(po_yarn_dyeing_item_id);
		}
		if (index == 4) {
			if (po_yarn_dyeing_id === '') {
				$('#poyarndyeingshorttabs').tabs('select', 0);
				msApp.showError('Select Purchase Order First', 0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_yarn_dyeing_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(9)
			MsPurchaseTermsCondition.get();
		}
	}
})