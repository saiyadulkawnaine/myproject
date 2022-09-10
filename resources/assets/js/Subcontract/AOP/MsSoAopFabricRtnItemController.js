let MsSoAopFabricRtnItemModel = require('./MsSoAopFabricRtnItemModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricRtnItemController {
	constructor(MsSoAopFabricRtnItemModel)
	{
		this.MsSoAopFabricRtnItemModel = MsSoAopFabricRtnItemModel;
		this.formId='soaopfabricrtnitemFrm';
		this.dataTable='#soaopfabricrtnitemTbl';
		this.route=msApp.baseUrl()+"/soaopfabricrtnitem"
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
		let so_aop_fabric_rtn_id=$('#soaopfabricrtnFrm  [name=id]').val();
		formObj.so_aop_fabric_rtn_id=so_aop_fabric_rtn_id;
		if(formObj.id){
			this.MsSoAopFabricRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoAopFabricRtnItem.get(d.so_aop_fabric_rtn_id)
		msApp.resetForm('soaopfabricrtnitemFrm');
					//$('#soaopfabricrtnitemFrm [id="uom_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopFabricRtnItemModel.get(index,row);
		workReceive.then(function(response){
			//$('#soaopfabricrtnitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
		}).catch(function(error){
			console.log(errors);
		});
	}

	get(so_aop_fabric_rtn_id)
	{
		let data= axios.get(this.route+"?so_aop_fabric_rtn_id="+so_aop_fabric_rtn_id);
		data.then(function (response) {
			$('#soaopfabricrtnitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tRate=0;
				var tAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tRate+=data.rows[i]['rate'].replace(/,/g,'')*1;
				tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	itemWindow(){
		$('#soaopfabricrtnitemWindow').window('open');
	}
	itemGrid(data){
		let self = this;
		$('#soaopfabricrtnitemsrchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
                $('#soaopfabricrtnitemFrm [name=so_aop_ref_id]').val(row.id);
                $('#soaopfabricrtnitemFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soaopfabricrtnitemFrm [name=fabriclooks]').val(row.fabriclooks);
				$('#soaopfabricrtnitemFrm [name=fabricshape]').val(row.fabricshape);				
				$('#soaopfabricrtnitemFrm [name=fabric_color_id]').val(row.fabric_color);				
				$('#soaopfabricrtnitemFrm [name=gsm_weight]').val(row.gsm_weight);				
				$('#soaopfabricrtnitemFrm [name=colorrange_id]').val(row.colorrange_id);				
				$('#soaopfabricrtnitemFrm [name=rate]').val(row.rate);
				$('#soaopfabricrtnitemFrm [name=autoyarn_id]').val(row.id);
				$('#soaopfabricrtnitemFrm [name=fabrication]').val(row.fabrication);

				$('#soaopfabricrtnitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getitem()
	{
		let sales_order_no=$('#soaopfabricrtnitemsearchFrm  [name=sales_order_no]').val();
		let so_aop_fabric_rtn_id=$('#soaopfabricrtnFrm  [name=id]').val();
		if(sales_order_no==''){
			alert('Please Insert Sales Order No');
			return;

		}
		let data= axios.get(this.route+"/getitem?sales_order_no="+sales_order_no+'&so_aop_fabric_rtn_id='+so_aop_fabric_rtn_id);
		data.then(function (response) {
			$('#soaopfabricrtnitemsrchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate()
	{
		let qty = $('#soaopfabricrtnitemFrm  [name=qty]').val();
		let rate = $('#soaopfabricrtnitemFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#soaopfabricrtnitemFrm  [name=amount]').val(amount);
	}
}
window.MsSoAopFabricRtnItem=new MsSoAopFabricRtnItemController(new MsSoAopFabricRtnItemModel());
MsSoAopFabricRtnItem.showGrid([]);
MsSoAopFabricRtnItem.itemGrid([]);

 
