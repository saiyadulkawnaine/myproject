let MsJhuteSaleDlvItemModel = require('./MsJhuteSaleDlvItemModel');
class MsJhuteSaleDlvItemController {
	constructor(MsJhuteSaleDlvItemModel)
	{
		this.MsJhuteSaleDlvItemModel = MsJhuteSaleDlvItemModel;
		this.formId='jhutesaledlvitemFrm';
		this.dataTable='#jhutesaledlvitemTbl';
		this.route=msApp.baseUrl()+"/jhutesaledlvitem"
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
			this.MsJhuteSaleDlvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsJhuteSaleDlvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#jhutesaledlvitemFrm [name=jhute_sale_dlv_id]').val($('#jhutesaledlvFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsJhuteSaleDlvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsJhuteSaleDlvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#jhutesaledlvitemTbl').datagrid('reload');
		msApp.resetForm('jhutesaledlvitemFrm');
		$('#jhutesaledlvitemFrm [name=jhute_sale_dlv_id]').val($('#jhutesaledlvFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsJhuteSaleDlvItemModel.get(index,row);
	}

	showGrid(jhute_sale_dlv_id)
	{
		let self=this;
		var data={};
		 data.jhute_sale_dlv_id=jhute_sale_dlv_id;
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
		return '<a href="javascript:void(0)"  onClick="MsJhuteSaleDlvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateAmount() {
		let qty;
		let rate;
		qty=$('#jhutesaledlvitemFrm [name=qty]').val();
		rate=$('#jhutesaledlvitemFrm [name=rate]').val();
		let amount=qty*rate;
		$('#jhutesaledlvitemFrm [name=amount]').val(amount);
	}

 	openWindow() {
		$('#jhutesaledlvitemwindow').window('open');
	}

	searchJhuteSaleDlvItem() {
		let params = {};
		params.jhutesaledlvorderid = $('#jhutesaledlvFrm [name=jhute_sale_dlv_order_id]').val();
		params.item_description = $('#jhutesaledlvitemsearchFrm [name=item_description]').val();
		params.uom_id = $('#jhutesaledlvitemsearchFrm [name=uom_id]').val();
		let data = axios.get(this.route + '/getjhutesaledlvorderitem', { params }).then(function (response) {
			$('#jhutesaledlvitemsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showJhuteItemGrid(data) {
		$('#jhutesaledlvitemsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				$('#jhutesaledlvitemFrm [name=jhute_sale_dlv_order_item_id]').val(row.id);
				$('#jhutesaledlvitemFrm [name=acc_chart_ctrl_head_name]').val(row.acc_chart_ctrl_head_name);
				$('#jhutesaledlvitemFrm [name=uom_name]').val(row.uom_name);
				$('#jhutesaledlvitemFrm [name=qty]').val(row.balance_qty);
				$('#jhutesaledlvitemFrm [name=balance_qty]').val(row.balance_qty);
				$('#jhutesaledlvitemFrm [name=rate]').val(row.rate);
				$('#jhutesaledlvitemFrm [name=amount]').val(row.rate*row.balance_qty);
				$('#jhutesaledlvitemsearchTbl').datagrid('loadData', []);
				$('#jhutesaledlvitemwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsJhuteSaleDlvItem = new MsJhuteSaleDlvItemController(new MsJhuteSaleDlvItemModel());
MsJhuteSaleDlvItem.showJhuteItemGrid([]); 