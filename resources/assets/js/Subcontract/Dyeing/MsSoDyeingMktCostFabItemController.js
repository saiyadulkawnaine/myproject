let MsSoDyeingMktCostFabItemModel = require('./MsSoDyeingMktCostFabItemModel');
require('./../../datagrid-filter.js');
class MsSoDyeingMktCostFabItemController {
	constructor(MsSoDyeingMktCostFabItemModel)
	{
		this.MsSoDyeingMktCostFabItemModel = MsSoDyeingMktCostFabItemModel;
		this.formId='sodyeingmktcostfabitemFrm';
		this.dataTable='#sodyeingmktcostfabitemTbl';
		this.route=msApp.baseUrl()+"/sodyeingmktcostfabitem"
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
		let so_dyeing_mkt_cost_fab_id = $('#sodyeingmktcostfabFrm  [name=id]').val();	
		let formObj=msApp.get(this.formId);
		formObj.so_dyeing_mkt_cost_fab_id=so_dyeing_mkt_cost_fab_id;
		if(formObj.id){
			this.MsSoDyeingMktCostFabItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingMktCostFabItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let so_dyeing_mkt_cost_fab_id = $('#sodyeingmktcostfabFrm  [name=id]').val();
		let fabric_wgt = $('#sodyeingmktcostfabFrm  [name=fabric_wgt]').val();
		let liqure_wgt = $('#sodyeingmktcostfabFrm  [name=liqure_wgt]').val();
		let currency_id = $('#sodyeingmktcostFrm  [name=currency_id]').val();
		$('#sodyeingmktcostfabitemFrm  [name=so_dyeing_mkt_cost_fab_id]').val(so_dyeing_mkt_cost_fab_id);
		$('#sodyeingmktcostfabitemFrm  [name=fabric_wgt]').val(fabric_wgt);
		$('#sodyeingmktcostfabitemFrm  [name=liqure_wgt]').val(liqure_wgt);
		$('#sodyeingmktcostfabitemFrm  [name=currency_id]').val(currency_id);
		$('#sodyeingmktcostqpricedtlcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingMktCostFabItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingMktCostFabItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingmktcostfabitemWindow').window('close');
		MsSoDyeingMktCostFabItem.get(d.so_dyeing_mkt_cost_fab_id)
		MsSoDyeingMktCostFabItem.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingMktCostFabItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_dyeing_mkt_cost_fab_id)
	{
		let data= axios.get(this.route+"?so_dyeing_mkt_cost_fab_id="+so_dyeing_mkt_cost_fab_id);
		data.then(function (response) {
			$('#sodyeingmktcostfabitemTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingMktCostFabItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#sodyeingmktcostfabitemWindow').window('open');

	}
	
	sodyeingmktcostfabitemsearchGrid(data){
		let self = this;
		$('#sodyeingmktcostfabitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingmktcostfabitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#sodyeingmktcostfabitemFrm [name=item_desc]').val(row.item_description);
				$('#sodyeingmktcostfabitemFrm [name=specification]').val(row.specification);
				$('#sodyeingmktcostfabitemFrm [name=item_category]').val(row.category_name);
				$('#sodyeingmktcostfabitemFrm [name=item_class]').val(row.class_name);
				$('#sodyeingmktcostfabitemFrm [name=uom_code]').val(row.uom_name);
				$('#sodyeingmktcostfabitemFrm [name=rate]').val(row.last_rcv_rate);
				$('#sodyeingmktcostfabitemFrm [name=last_receive_no]').val(row.last_receive_no);
				$('#sodyeingmktcostfabitemFrm [name=last_rcv_rate]').val(row.last_rcv_rate);
				$('#sodyeingmktcostfabitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem()
	{
		/*let so_dyeing_id=$('#sodyeingmktcostFrm  [name=so_dyeing_id]').val();
		let data= axios.get(this.route+"/getfabric?so_dyeing_id="+so_dyeing_id);
		data.then(function (response) {
			$('#sodyeingmktcostfabitemsearchTbl').datagrid('loadData', response.data);
			$('#sodyeingmktcostfabitemWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});*/

		let item_category=$('#sodyeingmktcostfabitemsearchFrm [name=item_category]').val();
		let item_class=$('#sodyeingmktcostfabitemsearchFrm [name=item_class]').val();
		let so_dyeing_mkt_cost_id=$('#sodyeingmktcostFrm [name=id]').val();
		let params={};
		params.item_category=item_category;
		params.item_class=item_class;
		params.so_dyeing_mkt_cost_id=so_dyeing_mkt_cost_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#sodyeingmktcostfabitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_qty(field)
	{
		if(field=='per_on_fabric_wgt')
		{
			$('#sodyeingmktcostfabitemFrm input[name=gram_per_ltr_liqure]').val('');
			let fabric_wgt=$('#sodyeingmktcostfabitemFrm input[name=fabric_wgt]').val();
			fabric_wgt=fabric_wgt*1;
			let per_on_fabric_wgt=$('#sodyeingmktcostfabitemFrm input[name=per_on_fabric_wgt]').val();
			per_on_fabric_wgt=per_on_fabric_wgt*1;
			let qty=fabric_wgt*(per_on_fabric_wgt/100);
			$('#sodyeingmktcostfabitemFrm input[name=qty]').val(qty);
		}
		if(field=='gram_per_ltr_liqure')
		{
			$('#sodyeingmktcostfabitemFrm input[name=per_on_fabric_wgt]').val('');
			let liqure_wgt=$('#sodyeingmktcostfabitemFrm input[name=liqure_wgt]').val();
			liqure_wgt=liqure_wgt*1;
			let gram_per_ltr_liqure=$('#sodyeingmktcostfabitemFrm input[name=gram_per_ltr_liqure]').val();
			gram_per_ltr_liqure=gram_per_ltr_liqure*1;
			let qty=(liqure_wgt*gram_per_ltr_liqure)/1000;
			$('#sodyeingmktcostfabitemFrm input[name=qty]').val(qty);
		}
		MsSoDyeingMktCostFabItem.calculate_amount();
	}

	calculate_amount()
	{
		let qty=$('#sodyeingmktcostfabitemFrm input[name=qty]').val();
		let rate=$('#sodyeingmktcostfabitemFrm input[name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#sodyeingmktcostfabitemFrm input[name=amount]').val(amount)
	}

	openitemCopyWindow()
	{
		$('#sodyeingmktcostfabMasterCopyWindow').window('open');
		MsSoDyeingMktCostFabItem.getMaster();

	}

	getMaster(){
		let so_dyeing_mkt_cost_id=$('#sodyeingmktcostFrm [name=id]').val();
		let so_dyeing_mkt_cost_fab_id=$('#sodyeingmktcostfabFrm [name=id]').val();
		let params={};
		params.so_dyeing_mkt_cost_id=so_dyeing_mkt_cost_id;
		params.so_dyeing_mkt_cost_fab_id=so_dyeing_mkt_cost_fab_id;
		let d=axios.get(this.route+'/getmastercopyfabric',{params})
		.then(function(response){
			$('#sodyeingmktcostfabMasterCopyTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	copyMasterfabricGrid(data){
		let self = this;
		$('#sodyeingmktcostfabMasterCopyTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingmktcostfabitemFrm [name=master_fab_id]').val(row.id);
				$('#sodyeingmktcostfabMasterCopyWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	itemCopy()
	{
		
		let so_dyeing_mkt_cost_fab_id=$('#sodyeingmktcostfabFrm [name=id]').val();
		let master_fab_id=$('#sodyeingmktcostfabitemFrm [name=master_fab_id]').val();
		let params={};
		params.so_dyeing_mkt_cost_fab_id=so_dyeing_mkt_cost_fab_id;
		params.master_fab_id=master_fab_id;

		let d=axios.get(this.route+'/copyitem',{params})
		.then(function(response){
				if (response.data.success == true) {
				msApp.showSuccess(response.data.message)
					MsSoDyeingMktCostFabItem.resetForm();
					MsSoDyeingMktCostFabItem.get(response.data.so_dyeing_mkt_cost_fab_id);
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
window.MsSoDyeingMktCostFabItem=new MsSoDyeingMktCostFabItemController(new MsSoDyeingMktCostFabItemModel());
MsSoDyeingMktCostFabItem.showGrid([]);
MsSoDyeingMktCostFabItem.sodyeingmktcostfabitemsearchGrid([]);
MsSoDyeingMktCostFabItem.copyMasterfabricGrid([]);