let MsJhuteSaleDlvOrderItemModel = require('./MsJhuteSaleDlvOrderItemModel');
class MsJhuteSaleDlvOrderItemController {
	constructor(MsJhuteSaleDlvOrderItemModel)
	{
		this.MsJhuteSaleDlvOrderItemModel = MsJhuteSaleDlvOrderItemModel;
		this.formId='jhutesaledlvorderitemFrm';
		this.dataTable='#jhutesaledlvorderitemTbl';
		this.route=msApp.baseUrl()+"/jhutesaledlvorderitem"
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
			this.MsJhuteSaleDlvOrderItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsJhuteSaleDlvOrderItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#jhutesaledlvorderitemFrm [name=jhute_sale_dlv_order_id]').val($('#jhutesaledlvorderFrm [name=id]').val());
		$('#jhutesaledlvorderitemFrm [id="uom_id"]').combobox('setValue', '');
		$('#jhutesaledlvorderitemFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsJhuteSaleDlvOrderItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsJhuteSaleDlvOrderItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#jhutesaledlvorderitemTbl').datagrid('reload');
		msApp.resetForm('jhutesaledlvorderitemFrm');
		$('#jhutesaledlvorderitemFrm [name=jhute_sale_dlv_order_id]').val($('#jhutesaledlvorderFrm [name=id]').val());
		$('#jhutesaledlvorderitemFrm [id="uom_id"]').combobox('setValue', '');
		$('#jhutesaledlvorderitemFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let item = this.MsJhuteSaleDlvOrderItemModel.get(index,row);
		item.then(function (response) {
			$('#jhutesaledlvorderitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
			$('#jhutesaledlvorderitemFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', response.data.fromData.acc_chart_ctrl_head_id);
		}).catch(function (error) {
			console.log(error);
		})
	}

	showGrid(jhute_sale_dlv_order_id)
	{
		let self=this;
		var data={};
		 data.jhute_sale_dlv_order_id=jhute_sale_dlv_order_id;
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
		return '<a href="javascript:void(0)"  onClick="MsJhuteSaleDlvOrderItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculateAmount() {
				let qty;
		let rate;
		qty=$('#jhutesaledlvorderitemFrm [name=qty]').val();
		rate=$('#jhutesaledlvorderitemFrm [name=rate]').val();
		let amount=qty*rate;
		$('#jhutesaledlvorderitemFrm [name=amount]').val(amount);
	}

}
window.MsJhuteSaleDlvOrderItem = new MsJhuteSaleDlvOrderItemController(new MsJhuteSaleDlvOrderItemModel());