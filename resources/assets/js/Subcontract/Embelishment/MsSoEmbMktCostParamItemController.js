let MsSoEmbMktCostParamItemModel = require('./MsSoEmbMktCostParamItemModel');
require('./../../datagrid-filter.js');
class MsSoEmbMktCostParamItemController {
	constructor(MsSoEmbMktCostParamItemModel)
	{
		this.MsSoEmbMktCostParamItemModel = MsSoEmbMktCostParamItemModel;
		this.formId='soembmktcostparamitemFrm';
		this.dataTable='#soembmktcostparamitemTbl';
		this.route=msApp.baseUrl()+"/soembmktcostparamitem"
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
		let so_aop_mkt_cost_param_id = $('#soembmktcostparamFrm  [name=id]').val();	
		let formObj=msApp.get(this.formId);
		formObj.so_aop_mkt_cost_param_id=so_aop_mkt_cost_param_id;
		if(formObj.id){
			this.MsSoEmbMktCostParamItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoEmbMktCostParamItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let so_aop_mkt_cost_param_id = $('#soembmktcostparamFrm  [name=id]').val();
		let fabric_wgt = $('#soembmktcostparamFrm  [name=fabric_wgt]').val();
		let paste_wgt = $('#soembmktcostparamFrm  [name=paste_wgt]').val();
		let currency_id = $('#soembmktcostFrm  [name=currency_id]').val();
		$('#soembmktcostparamitemFrm  [name=so_aop_mkt_cost_param_id]').val(so_aop_mkt_cost_param_id);
		$('#soembmktcostparamitemFrm  [name=fabric_wgt]').val(fabric_wgt);
		$('#soembmktcostparamitemFrm  [name=paste_wgt]').val(paste_wgt);
		$('#soembmktcostparamitemFrm  [name=currency_id]').val(currency_id);
		$('#soembmktcostqpricedtlcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbMktCostParamItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbMktCostParamItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soembmktcostparamitemWindow').window('close');
		MsSoEmbMktCostParamItem.resetForm();
		MsSoEmbMktCostParamItem.get(d.so_aop_mkt_cost_param_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoEmbMktCostParamItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_aop_mkt_cost_param_id)
	{
		let data= axios.get(this.route+"?so_aop_mkt_cost_param_id="+so_aop_mkt_cost_param_id);
		data.then(function (response) {
			$('#soembmktcostparamitemTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbMktCostParamItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openitemWindow()
	{
		$('#soembmktcostparamitemWindow').window('open');

	}
	
	soembmktcostparamitemsearchGrid(data){
		let self = this;
		$('#soembmktcostparamitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soembmktcostparamitemFrm [name=item_account_id]').val(row.item_account_id);
				$('#soembmktcostparamitemFrm [name=item_desc]').val(row.item_description);
				$('#soembmktcostparamitemFrm [name=specification]').val(row.specification);
				$('#soembmktcostparamitemFrm [name=item_category]').val(row.category_name);
				$('#soembmktcostparamitemFrm [name=item_class]').val(row.class_name);
				$('#soembmktcostparamitemFrm [name=uom_code]').val(row.uom_name);
				$('#soembmktcostparamitemFrm [name=rate]').val(row.last_rcv_rate);
				$('#soembmktcostparamitemFrm [name=last_receive_no]').val(row.last_receive_no);
				$('#soembmktcostparamitemFrm [name=last_rcv_rate]').val(row.last_rcv_rate);
				$('#soembmktcostparamitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem()
	{
		let item_category=$('#soembmktcostparamitemsearchFrm [name=item_category]').val();
		let item_class=$('#soembmktcostparamitemsearchFrm [name=item_class]').val();
		let so_aop_mkt_cost_id=$('#soembmktcostFrm [name=id]').val();
		let params={};
		params.item_category=item_category;
		params.item_class=item_class;
		params.so_aop_mkt_cost_id=so_aop_mkt_cost_id;
		let d=axios.get(this.route+'/getitem',{params})
		.then(function(response){
			$('#soembmktcostparamitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_qty(field)
	{
		if(field=='rto_on_paste_wgt')
		{
			var paste_wgt= $('#soembmktcostparamitemFrm  input[name=paste_wgt]').val();
			paste_wgt=paste_wgt*1;
			var rto_on_paste_wgt= $('#soembmktcostparamitemFrm  input[name=rto_on_paste_wgt]').val();
			rto_on_paste_wgt=rto_on_paste_wgt*1;
			var qty=(paste_wgt*rto_on_paste_wgt)/100;
			$('#soembmktcostparamitemFrm  input[name=qty]').val(qty);
		}
		MsSoEmbMktCostParamItem.calculate_amount();
	}

	calculate_amount()
	{
		let qty=$('#soembmktcostparamitemFrm input[name=qty]').val();
		let rate=$('#soembmktcostparamitemFrm input[name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#soembmktcostparamitemFrm input[name=amount]').val(amount)
	}

	openitemCopyWindow()
	{
		$('#soembmktcostparamMasterCopyWindow').window('open');
		MsSoEmbMktCostParamItem.getMaster();

	}

	getMaster(){
		let so_aop_mkt_cost_id=$('#soembmktcostFrm [name=id]').val();
		let so_aop_mkt_cost_param_id=$('#soembmktcostparamFrm [name=id]').val();
		let params={};
		params.so_aop_mkt_cost_id=so_aop_mkt_cost_id;
		params.so_aop_mkt_cost_param_id=so_aop_mkt_cost_param_id;
		let d=axios.get(this.route+'/getmastercopyparameter',{params})
		.then(function(response){
			$('#soembmktcostparamMasterCopyTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	copyMasterfabricGrid(data){
		let self = this;
		$('#soembmktcostparamMasterCopyTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soembmktcostparamitemFrm [name=master_fab_id]').val(row.id);
				$('#soembmktcostparamMasterCopyWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	itemCopy()
	{
		
		let so_aop_mkt_cost_param_id=$('#soembmktcostparamFrm [name=id]').val();
		let master_fab_id=$('#soembmktcostparamitemFrm [name=master_fab_id]').val();
		let params={};
		params.so_aop_mkt_cost_param_id=so_aop_mkt_cost_param_id;
		params.master_fab_id=master_fab_id;

		let d=axios.get(this.route+'/copyitem',{params})
		.then(function(response){
				if (response.data.success == true) {
				msApp.showSuccess(response.data.message)
					MsSoEmbMktCostParamItem.resetForm();
					MsSoEmbMktCostParamItem.get(response.data.so_aop_mkt_cost_param_id);
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
window.MsSoEmbMktCostParamItem=new MsSoEmbMktCostParamItemController(new MsSoEmbMktCostParamItemModel());
MsSoEmbMktCostParamItem.showGrid([]);
MsSoEmbMktCostParamItem.soembmktcostparamitemsearchGrid([]);
MsSoEmbMktCostParamItem.copyMasterfabricGrid([]);