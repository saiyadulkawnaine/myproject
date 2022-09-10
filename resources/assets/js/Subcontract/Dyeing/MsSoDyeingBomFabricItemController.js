let MsSoDyeingBomFabricItemModel = require('./MsSoDyeingBomFabricItemModel');
require('./../../datagrid-filter.js');
class MsSoDyeingBomFabricItemController {
	constructor(MsSoDyeingBomFabricItemModel)
	{
		this.MsSoDyeingBomFabricItemModel = MsSoDyeingBomFabricItemModel;
		this.formId='sodyeingbomfabricitemFrm';
		this.dataTable='#sodyeingbomfabricitemTbl';
		this.route=msApp.baseUrl()+"/sodyeingbomfabricitem"
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
		let so_dyeing_bom_fabric_id = $('#sodyeingbomfabricFrm  [name=id]').val();	
		let formObj=msApp.get(this.formId);
		formObj.so_dyeing_bom_fabric_id=so_dyeing_bom_fabric_id;
		if(formObj.id){
			this.MsSoDyeingBomFabricItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingBomFabricItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let so_dyeing_bom_fabric_id = $('#sodyeingbomfabricFrm  [name=id]').val();
		let fabric_wgt = $('#sodyeingbomfabricFrm  [name=fabric_wgt]').val();
		let liqure_wgt = $('#sodyeingbomfabricFrm  [name=liqure_wgt]').val();
		let currency_id = $('#sodyeingbomFrm  [name=currency_id]').val();
		$('#sodyeingbomfabricitemFrm  [name=so_dyeing_bom_fabric_id]').val(so_dyeing_bom_fabric_id);
		$('#sodyeingbomfabricitemFrm  [name=fabric_wgt]').val(fabric_wgt);
		$('#sodyeingbomfabricitemFrm  [name=liqure_wgt]').val(liqure_wgt);
		$('#sodyeingbomfabricitemFrm  [name=currency_id]').val(currency_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingBomFabricItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingBomFabricItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingbomfabricitemWindow').window('close');
		MsSoDyeingBomFabricItem.get(d.so_dyeing_bom_fabric_id)
		MsSoDyeingBomFabricItem.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingBomFabricItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_dyeing_bom_fabric_id)
	{
		let data= axios.get(this.route+"?so_dyeing_bom_fabric_id="+so_dyeing_bom_fabric_id);
		data.then(function (response) {
			$('#sodyeingbomfabricitemTbl').datagrid('loadData', response.data);
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
			showFooter:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingBomFabricItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#sodyeingbomfabricitemWindow').window('open');

	}

	

	
	sodyeingbomfabricitemsearchGrid(data){
		let self = this;
		$('#sodyeingbomfabricitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingbomfabricitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#sodyeingbomfabricitemFrm [name=item_desc]').val(row.item_description);
				$('#sodyeingbomfabricitemFrm [name=specification]').val(row.specification);
				$('#sodyeingbomfabricitemFrm [name=item_category]').val(row.category_name);
				$('#sodyeingbomfabricitemFrm [name=item_class]').val(row.class_name);
				$('#sodyeingbomfabricitemFrm [name=uom_code]').val(row.uom_name);
				$('#sodyeingbomfabricitemFrm [name=rate]').val(row.rate);
				$('#sodyeingbomfabricitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem()
	{
		/*let so_dyeing_id=$('#sodyeingbomFrm  [name=so_dyeing_id]').val();
		let data= axios.get(this.route+"/getfabric?so_dyeing_id="+so_dyeing_id);
		data.then(function (response) {
			$('#sodyeingbomfabricitemsearchTbl').datagrid('loadData', response.data);
			$('#sodyeingbomfabricitemWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});*/

		let item_category=$('#sodyeingbomfabricitemsearchFrm [name=item_category]').val();
		let item_class=$('#sodyeingbomfabricitemsearchFrm [name=item_class]').val();
		let so_dyeing_bom_id=$('#sodyeingbomFrm [name=id]').val();
		let params={};
		params.item_category=item_category;
		params.item_class=item_class;
		params.so_dyeing_bom_id=so_dyeing_bom_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#sodyeingbomfabricitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_qty(field)
	{
		if(field=='per_on_fabric_wgt')
		{
             $('#sodyeingbomfabricitemFrm input[name=gram_per_ltr_liqure]').val('');
             let fabric_wgt=$('#sodyeingbomfabricitemFrm input[name=fabric_wgt]').val();
             fabric_wgt=fabric_wgt*1;
             let per_on_fabric_wgt=$('#sodyeingbomfabricitemFrm input[name=per_on_fabric_wgt]').val();
             per_on_fabric_wgt=per_on_fabric_wgt*1;
             let qty=fabric_wgt*(per_on_fabric_wgt/100);
             $('#sodyeingbomfabricitemFrm input[name=qty]').val(qty);


		}
		if(field=='gram_per_ltr_liqure')
		{
             $('#sodyeingbomfabricitemFrm input[name=per_on_fabric_wgt]').val('');
             let liqure_wgt=$('#sodyeingbomfabricitemFrm input[name=liqure_wgt]').val();
             liqure_wgt=liqure_wgt*1;
             let gram_per_ltr_liqure=$('#sodyeingbomfabricitemFrm input[name=gram_per_ltr_liqure]').val();
             gram_per_ltr_liqure=gram_per_ltr_liqure*1;
             let qty=(liqure_wgt*gram_per_ltr_liqure)/1000;
             $('#sodyeingbomfabricitemFrm input[name=qty]').val(qty);


		}
		MsSoDyeingBomFabricItem.calculate_amount();
	}

	calculate_amount()
	{
		let qty=$('#sodyeingbomfabricitemFrm input[name=qty]').val();
		let rate=$('#sodyeingbomfabricitemFrm input[name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#sodyeingbomfabricitemFrm input[name=amount]').val(amount)
	}

	openitemCopyWindow()
	{
		$('#sodyeingbomfabricMasterCopyWindow').window('open');
		MsSoDyeingBomFabricItem.getMaster();

	}

	getMaster(){
		let so_dyeing_bom_id=$('#sodyeingbomFrm [name=id]').val();
		let so_dyeing_bom_fabric_id=$('#sodyeingbomfabricFrm [name=id]').val();
		let params={};
		params.so_dyeing_bom_id=so_dyeing_bom_id;
		params.so_dyeing_bom_fabric_id=so_dyeing_bom_fabric_id;
		let d=axios.get(this.route+'/getmastercopyfabric',{params})
		.then(function(response){
			$('#sodyeingbomfabricMasterCopyTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	copyMasterfabricGrid(data){
		let self = this;
		$('#sodyeingbomfabricMasterCopyTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingbomfabricitemFrm [name=master_fab_id]').val(row.id);
				$('#sodyeingbomfabricMasterCopyWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	itemCopy()
	{
		
		let so_dyeing_bom_fabric_id=$('#sodyeingbomfabricFrm [name=id]').val();
		let master_fab_id=$('#sodyeingbomfabricitemFrm [name=master_fab_id]').val();
		let params={};
		params.so_dyeing_bom_fabric_id=so_dyeing_bom_fabric_id;
		params.master_fab_id=master_fab_id;

		let d=axios.get(this.route+'/copyitem',{params})
		.then(function(response){
				if (response.data.success == true) {
				msApp.showSuccess(response.data.message)
					MsSoDyeingBomFabricItem.resetForm();
					MsSoDyeingBomFabricItem.get(response.data.so_dyeing_bom_fabric_id);
				}
				else if (response.data.success == false) {
				msApp.showError(response.data.message);
				}

		}).catch(function(error){
			//alert('Copied Not Successfully');
			msApp.showError(error);
			console.log(error);
		})
		
	}
	
}
window.MsSoDyeingBomFabricItem=new MsSoDyeingBomFabricItemController(new MsSoDyeingBomFabricItemModel());
MsSoDyeingBomFabricItem.showGrid([]);
MsSoDyeingBomFabricItem.sodyeingbomfabricitemsearchGrid([]);
MsSoDyeingBomFabricItem.copyMasterfabricGrid([]);