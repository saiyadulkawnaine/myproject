require('./../datagrid-filter.js');
let MsPoDyeingServiceModel = require('./MsPoDyeingServiceModel');
class MsPoDyeingServiceController
{
	constructor(MsPoDyeingServiceModel)
	{
		this.MsPoDyeingServiceModel = MsPoDyeingServiceModel;
		this.formId = 'podyeingserviceFrm';
		this.dataTable = '#podyeingserviceTbl';
		this.route = msApp.baseUrl() + "/podyeingservice"
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
			this.MsPoDyeingServiceModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsPoDyeingServiceModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#podyeingserviceFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsPoDyeingServiceModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsPoDyeingServiceModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#podyeingserviceTbl').datagrid('reload');
		msApp.resetForm('podyeingserviceFrm');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		let podye = this.MsPoDyeingServiceModel.get(index, row);
		podye.then(function (response)
		{
			MsPoDyeingServiceItem.get(row.id);
			$('#podyeingserviceFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
			//fitColumns:true,
			showFooter: true,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			},
			onLoadSuccess: function (data)
			{
				//var tQty=0;
				var tAmout = 0;
				for (var i = 0; i < data.rows.length; i++) {
					//tQty+=data.rows[i]['item_qty'].replace(/,/g,'')*1;
					tAmout += data.rows[i]['amount'].replace(/,/g, '') * 1;
				}
				
				$(this).datagrid('reloadFooter', [
					{
						//item_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoDyeingService.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf()
	{
		var id = $('#podyeingserviceFrm  [name=id]').val();
		if (id == "") {
			alert("Select a Order");
			return;
		}
		window.open(this.route + "/report?id=" + id);
	}

	searchPoDyeingService(){
		let params = {};
		params.wo_no = $('#wo_no').val();
		params.supplier_search_id = $('#supplier_search_id').val();
		params.beneficiary_search_id = $('#beneficiary_search_id').val();
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
		let data = axios.get(this.route + "/getsearchpodyeing", { params });
		data.then(function (response)
		{
			$('#podyeingserviceTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}
	
}
window.MsPoDyeingService=new MsPoDyeingServiceController(new MsPoDyeingServiceModel());
MsPoDyeingService.showGrid();
 $('#podyeingserviceAccordion').accordion({
	onSelect:function(title,index){
		let po_dyeing_service_id = $('#podyeingserviceFrm  [name=id]').val();
		if(index==1){
			if(po_dyeing_service_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#podyeingserviceAccordion').accordion('unselect',1);
				$('#podyeingserviceAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(po_dyeing_service_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#podyeingserviceAccordion').accordion('unselect',1);
				$('#podyeingserviceAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_dyeing_service_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(6)
			MsPurchaseTermsCondition.get();
		}
	}
}) 
