let MsJhuteStockItemModel = require('./MsJhuteStockItemModel');
class MsJhuteStockItemController {
	constructor(MsJhuteStockItemModel)
	{
		this.MsJhuteStockItemModel = MsJhuteStockItemModel;
		this.formId='jhutestockitemFrm';
		this.dataTable='#jhutestockitemTbl';
		this.route=msApp.baseUrl()+"/jhutestockitem"
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
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsJhuteStockItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsJhuteStockItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#jhutestockitemFrm [name=jhute_stock_id]').val($('#jhutestockFrm [name=id]').val());
		$('#jhutestockitemFrm [id="uom_id"]').combobox('setValue', '');
		$('#jhutestockitemFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsJhuteStockItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsJhuteStockItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#jhutestockitemTbl').datagrid('reload');
		msApp.resetForm('jhutestockitemFrm');
		$('#jhutestockitemFrm [name=jhute_stock_id]').val($('#jhutestockFrm [name=id]').val());
		$('#jhutestockitemFrm [id="uom_id"]').combobox('setValue', '');
		$('#jhutestockitemFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let item = this.MsJhuteStockItemModel.get(index,row);
		item.then(function (response) {
			$('#jhutestockitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
			$('#jhutestockitemFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', response.data.fromData.acc_chart_ctrl_head_id);
		}).catch(function (error) {
			console.log(error);
		})
	}

	showGrid(jhute_stock_id)
	{
		let self=this;
		var data={};
		 data.jhute_stock_id=jhute_stock_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess:function(data){
				var tQty = 0 ;
				var tAmount = 0 ;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
					}
				]);

			}
		}).datagrid('enableFilter');
	}


	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsJhuteStockItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsJhuteStockItem = new MsJhuteStockItemController(new MsJhuteStockItemModel());