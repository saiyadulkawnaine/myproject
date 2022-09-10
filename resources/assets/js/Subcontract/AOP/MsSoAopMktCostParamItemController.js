let MsSoAopMktCostParamItemModel = require('./MsSoAopMktCostParamItemModel');
require('./../../datagrid-filter.js');
class MsSoAopMktCostParamItemController {
	constructor(MsSoAopMktCostParamItemModel)
	{
		this.MsSoAopMktCostParamItemModel = MsSoAopMktCostParamItemModel;
		this.formId='soaopmktcostparamitemFrm';
		this.dataTable='#soaopmktcostparamitemTbl';
		this.route=msApp.baseUrl()+"/soaopmktcostparamitem"
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
		let so_aop_mkt_cost_param_id = $('#soaopmktcostparamFrm  [name=id]').val();	
		let formObj=msApp.get(this.formId);
		formObj.so_aop_mkt_cost_param_id=so_aop_mkt_cost_param_id;
		if(formObj.id){
			this.MsSoAopMktCostParamItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopMktCostParamItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let so_aop_mkt_cost_param_id = $('#soaopmktcostparamFrm  [name=id]').val();
		let fabric_wgt = $('#soaopmktcostparamFrm  [name=fabric_wgt]').val();
		let paste_wgt = $('#soaopmktcostparamFrm  [name=paste_wgt]').val();
		let currency_id = $('#soaopmktcostFrm  [name=currency_id]').val();
		$('#soaopmktcostparamitemFrm  [name=so_aop_mkt_cost_param_id]').val(so_aop_mkt_cost_param_id);
		$('#soaopmktcostparamitemFrm  [name=fabric_wgt]').val(fabric_wgt);
		$('#soaopmktcostparamitemFrm  [name=paste_wgt]').val(paste_wgt);
		$('#soaopmktcostparamitemFrm  [name=currency_id]').val(currency_id);
		$('#soaopmktcostqpricedtlcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopMktCostParamItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopMktCostParamItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopmktcostparamitemWindow').window('close');
		MsSoAopMktCostParamItem.get(d.so_aop_mkt_cost_param_id)
		MsSoAopMktCostParamItem.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopMktCostParamItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_aop_mkt_cost_param_id)
	{
		let data= axios.get(this.route+"?so_aop_mkt_cost_param_id="+so_aop_mkt_cost_param_id);
		data.then(function (response) {
			$('#soaopmktcostparamitemTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoAopMktCostParamItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#soaopmktcostparamitemWindow').window('open');

	}
	
	soaopmktcostparamitemsearchGrid(data){
		let self = this;
		$('#soaopmktcostparamitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopmktcostparamitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#soaopmktcostparamitemFrm [name=item_desc]').val(row.item_description);
				$('#soaopmktcostparamitemFrm [name=specification]').val(row.specification);
				$('#soaopmktcostparamitemFrm [name=item_category]').val(row.category_name);
				$('#soaopmktcostparamitemFrm [name=item_class]').val(row.class_name);
				$('#soaopmktcostparamitemFrm [name=uom_code]').val(row.uom_name);
				$('#soaopmktcostparamitemFrm [name=rate]').val(row.last_rcv_rate);
				$('#soaopmktcostparamitemFrm [name=last_receive_no]').val(row.last_receive_no);
				$('#soaopmktcostparamitemFrm [name=last_rcv_rate]').val(row.last_rcv_rate);
				$('#soaopmktcostparamitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem()
	{
		let item_category=$('#soaopmktcostparamitemsearchFrm [name=item_category]').val();
		let item_class=$('#soaopmktcostparamitemsearchFrm [name=item_class]').val();
		let so_aop_mkt_cost_id=$('#soaopmktcostFrm [name=id]').val();
		let params={};
		params.item_category=item_category;
		params.item_class=item_class;
		params.so_aop_mkt_cost_id=so_aop_mkt_cost_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#soaopmktcostparamitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_qty(field)
	{
		if(field=='rto_on_paste_wgt')
		{
			var paste_wgt= $('#soaopmktcostparamitemFrm  input[name=paste_wgt]').val();
			paste_wgt=paste_wgt*1;
			var rto_on_paste_wgt= $('#soaopmktcostparamitemFrm  input[name=rto_on_paste_wgt]').val();
			rto_on_paste_wgt=rto_on_paste_wgt*1;
			var qty=(paste_wgt*rto_on_paste_wgt)/100;
			$('#soaopmktcostparamitemFrm  input[name=qty]').val(qty);
		}
		MsSoAopMktCostParamItem.calculate_amount();
	}

	calculate_amount()
	{
		let qty=$('#soaopmktcostparamitemFrm input[name=qty]').val();
		let rate=$('#soaopmktcostparamitemFrm input[name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#soaopmktcostparamitemFrm input[name=amount]').val(amount)
	}

	openitemCopyWindow()
	{
		$('#soaopmktcostparamMasterCopyWindow').window('open');
		MsSoAopMktCostParamItem.getMaster();

	}

	getMaster(){
		let so_aop_mkt_cost_id=$('#soaopmktcostFrm [name=id]').val();
		let so_aop_mkt_cost_param_id=$('#soaopmktcostparamFrm [name=id]').val();
		let params={};
		params.so_aop_mkt_cost_id=so_aop_mkt_cost_id;
		params.so_aop_mkt_cost_param_id=so_aop_mkt_cost_param_id;
		let d=axios.get(this.route+'/getmastercopyparameter',{params})
		.then(function(response){
			$('#soaopmktcostparamMasterCopyTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	copyMasterfabricGrid(data){
		let self = this;
		$('#soaopmktcostparamMasterCopyTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopmktcostparamitemFrm [name=master_fab_id]').val(row.id);
				$('#soaopmktcostparamMasterCopyWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	itemCopy()
	{
		
		let so_aop_mkt_cost_param_id=$('#soaopmktcostparamFrm [name=id]').val();
		let master_fab_id=$('#soaopmktcostparamitemFrm [name=master_fab_id]').val();
		let params={};
		params.so_aop_mkt_cost_param_id=so_aop_mkt_cost_param_id;
		params.master_fab_id=master_fab_id;

		let d=axios.get(this.route+'/copyitem',{params})
		.then(function(response){
				if (response.data.success == true) {
				msApp.showSuccess(response.data.message)
					MsSoAopMktCostParamItem.resetForm();
					MsSoAopMktCostParamItem.get(response.data.so_aop_mkt_cost_param_id);
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
window.MsSoAopMktCostParamItem=new MsSoAopMktCostParamItemController(new MsSoAopMktCostParamItemModel());
MsSoAopMktCostParamItem.showGrid([]);
MsSoAopMktCostParamItem.soaopmktcostparamitemsearchGrid([]);
MsSoAopMktCostParamItem.copyMasterfabricGrid([]);