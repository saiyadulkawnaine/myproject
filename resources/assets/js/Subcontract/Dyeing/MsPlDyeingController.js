let MsPlDyeingModel = require('./MsPlDyeingModel');
require('./../../datagrid-filter.js');
class MsPlDyeingController {
	constructor(MsPlDyeingModel)
	{
		this.MsPlDyeingModel = MsPlDyeingModel;
		this.formId='pldyeingFrm';
		this.dataTable='#pldyeingTbl';
		this.route=msApp.baseUrl()+"/pldyeing"
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
			this.MsPlDyeingModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsPlDyeingModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#pldyeingFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPlDyeingModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPlDyeingModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#pldyeingTbl').datagrid('reload');
		msApp.resetForm('pldyeingFrm');
		$('#pldyeingFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsPlDyeingModel.get(index,row);
		workReceive.then(function(response){
			//$('#pldyeingFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#pldyeingFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		}).catch(function(error){
			console.log(errors)
		});
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsPlDyeing.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openSubInbMktWindow(){
		$('#subinbmktwindow').window('open');
	}

	showMktGrid(){
		let data = {};
		data.company_id = $('#subinbmktsearchFrm [name="company_id"]').val();
		data.production_area_id = $('#subinbmktsearchFrm [name="production_area_id"]').val();
		data.buyer_id = $('#subinbmktsearchFrm [name="buyer_id"]').val();
		data.mkt_date = $('#subinbmktsearchFrm [name="mkt_date"]').val();
		let self = this;
		$('#subinbmktsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route+"/getmktref",
			onClickRow: function(index,row)
			{
				$('#pldyeingFrm [name=sub_inb_marketing_id]').val(row.id);
				$('#subinbmktwindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	pldyeingpoWindowOpen(){
		$('#pldyeingpoWindow').window('open');
		//$('#pldyeingposearchTbl').datagrid('loadData')
	}
	showpldyeingpoGrid(data){
		let self = this;
		$('#pldyeingposearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#pldyeingFrm [name=po_dyeing_service_id]').val(row.id);
				$('#pldyeingFrm [name=sales_order_no]').val(row.po_no);
				$('#pldyeingFrm [name=receive_date]').val(row.po_date);
				$('#pldyeingFrm [name=currency_id]').val(row.currency_id);
				$('#pldyeingpoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getpldyeingpo()
	{
		let po_no=$('#pldyeingposearchFrm  [name=po_no]').val();
		let buyer_id=$('#pldyeingFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getpo?po_no="+po_no+"&buyer_id="+buyer_id);
		data.then(function (response) {
			$('#pldyeingposearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	pldyeingmachineWindowOpen(){
		MsPlDyeing.showpldyeingmachineGrid([]);
		$('#pldyeingmachineWindow').window('open');
	}
	showpldyeingmachineGrid(data){
		let self = this;
		$('#pldyeingmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#pldyeingFrm [name=machine_id]').val(row.id);
					$('#pldyeingFrm [name=machine_no]').val(row.custom_no);
					$('#pldyeingFrm [name=brand]').val(row.brand);
					$('#pldyeingFrm [name=prod_capacity]').val(row.prod_capacity);
					//$('#pldyeingitemFrm [name=no_of_feeder]').val(row.no_of_feeder);
					$('#pldyeingmachineWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchMachine()
	{
		let params={};
		params.brand=$('#pldyeingmachinesearchFrm  [name=brand]').val();
		params.machine_no=$('#pldyeingmachinesearchFrm  [name=machine_no]').val();
		//params.no_of_feeder=$('#pldyeingitemsearchFrm  [name=no_of_feeder]').val();
		
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#pldyeingmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}
}
window.MsPlDyeing=new MsPlDyeingController(new MsPlDyeingModel());
MsPlDyeing.showGrid();
MsPlDyeing.showpldyeingpoGrid([]);
	$('#pldyeingtabs').tabs({
		onSelect:function(title,index){
		let pl_dyeing_id = $('#pldyeingFrm  [name=id]').val();
		let prod_capacity = $('#pldyeingFrm  [name=prod_capacity]').val();
		let pl_dyeing_item_id = $('#pldyeingitemFrm  [name=id]').val();

		var data={};
		data.pl_dyeing_id=pl_dyeing_id;
		if(index==1){
			if(pl_dyeing_id===''){
				$('#pldyeingtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			MsPlDyeingItem.resetForm();
			$('#pldyeingitemFrm  [name=pl_dyeing_id]').val(pl_dyeing_id);
			$('#pldyeingitemFrm  [name=capacity]').val(prod_capacity);
			MsPlDyeingItem.showGrid([]);
			MsPlDyeingItem.get(pl_dyeing_id);
		}
		if(index==2){
			if(pl_dyeing_item_id===''){
			$('#pldyeingtabs').tabs('select',1);
			msApp.showError('Select a Plan First',0);
			return;
			}
			MsPlDyeingItemQty.resetForm();
			$('#pldyeingitemqtyFrm  [name=pl_dyeing_item_id]').val(pl_dyeing_item_id);
			MsPlDyeingItemQty.get(pl_dyeing_item_id);
		}
	}
});