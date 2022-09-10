let MsSoDyeingFabricRtnItemModel = require('./MsSoDyeingFabricRtnItemModel');
require('./../../datagrid-filter.js');
class MsSoDyeingFabricRtnItemController {
	constructor(MsSoDyeingFabricRtnItemModel)
	{
		this.MsSoDyeingFabricRtnItemModel = MsSoDyeingFabricRtnItemModel;
		this.formId='sodyeingfabricrtnitemFrm';
		this.dataTable='#sodyeingfabricrtnitemTbl';
		this.route=msApp.baseUrl()+"/sodyeingfabricrtnitem"
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
		let so_dyeing_fabric_rtn_id=$('#sodyeingfabricrtnFrm  [name=id]').val();
		formObj.so_dyeing_fabric_rtn_id=so_dyeing_fabric_rtn_id;
		if(formObj.id){
			this.MsSoDyeingFabricRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingFabricRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingFabricRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingFabricRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoDyeingFabricRtnItem.get(d.so_dyeing_fabric_rtn_id)
		msApp.resetForm('sodyeingfabricrtnitemFrm');
					//$('#sodyeingfabricrtnitemFrm [id="uom_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingFabricRtnItemModel.get(index,row);
		workReceive.then(function(response){
			//$('#sodyeingfabricrtnitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
		}).catch(function(error){
			console.log(errors);
		});
	}

	get(so_dyeing_fabric_rtn_id)
	{
		let data= axios.get(this.route+"?so_dyeing_fabric_rtn_id="+so_dyeing_fabric_rtn_id);
		data.then(function (response) {
			$('#sodyeingfabricrtnitemTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingFabricRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	itemWindow(){
		$('#sodyeingfabricrtnitemWindow').window('open');
	}
	itemGrid(data){
		let self = this;
		$('#sodyeingfabricrtnitemsrchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
                $('#sodyeingfabricrtnitemFrm [name=so_dyeing_ref_id]').val(row.id);
                $('#sodyeingfabricrtnitemFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#sodyeingfabricrtnitemFrm [name=fabriclooks]').val(row.fabriclooks);
				$('#sodyeingfabricrtnitemFrm [name=fabricshape]').val(row.fabricshape);				
				$('#sodyeingfabricrtnitemFrm [name=fabric_color_id]').val(row.fabric_color);				
				$('#sodyeingfabricrtnitemFrm [name=gsm_weight]').val(row.gsm_weight);				
				$('#sodyeingfabricrtnitemFrm [name=colorrange_id]').val(row.colorrange_id);				
				$('#sodyeingfabricrtnitemFrm [name=rate]').val(row.rate);
				$('#sodyeingfabricrtnitemFrm [name=autoyarn_id]').val(row.id);
				$('#sodyeingfabricrtnitemFrm [name=fabrication]').val(row.fabrication);

				$('#sodyeingfabricrtnitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getitem()
	{
		let sales_order_no=$('#sodyeingfabricrtnitemsearchFrm  [name=sales_order_no]').val();
		let so_dyeing_fabric_rtn_id=$('#sodyeingfabricrtnFrm  [name=id]').val();
		if(sales_order_no==''){
			alert('Please Insert Sales Order No');
			return;

		}
		let data= axios.get(this.route+"/getitem?sales_order_no="+sales_order_no+'&so_dyeing_fabric_rtn_id='+so_dyeing_fabric_rtn_id);
		data.then(function (response) {
			$('#sodyeingfabricrtnitemsrchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate()
	{
		let qty = $('#sodyeingfabricrtnitemFrm  [name=qty]').val();
		let rate = $('#sodyeingfabricrtnitemFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#sodyeingfabricrtnitemFrm  [name=amount]').val(amount);
	}
}
window.MsSoDyeingFabricRtnItem=new MsSoDyeingFabricRtnItemController(new MsSoDyeingFabricRtnItemModel());
MsSoDyeingFabricRtnItem.showGrid([]);
MsSoDyeingFabricRtnItem.itemGrid([]);

 
