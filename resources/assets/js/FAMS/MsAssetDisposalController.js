let MsAssetDisposalModel = require('./MsAssetDisposalModel');
require('./../datagrid-filter.js');
class MsAssetDisposalController {
	constructor(MsAssetDisposalModel)
	{
		this.MsAssetDisposalModel = MsAssetDisposalModel;
		this.formId='assetdisposalFrm';
		this.dataTable='#assetdisposalTbl';
		this.route=msApp.baseUrl()+"/assetdisposal"
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
			this.MsAssetDisposalModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAssetDisposalModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#assetdisposalFrm [id="buyer_id"]').combobox('setValue', '');
		
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAssetDisposalModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAssetDisposalModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#assetdisposalTbl').datagrid('reload');
		msApp.resetForm('assetdisposalFrm');
		$('#assetdisposalFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;	
		let asset=this.MsAssetDisposalModel.get(index,row);	
		asset.then(function (response) {		
			$('#assetdisposalFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(){
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
		return '<a href="javascript:void(0)"  onClick="MsAssetDisposal.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openAssetWindow() {
		$("#openassetwindow").window('open');
	}

	getParams(){
		let params={};
		params.asset_no=$('#assetsearchFrm [name=asset_no]').val();
		params.custom_no=$('#assetsearchFrm [name=custom_no]').val();
		params.asset_name=$('#assetsearchFrm [name=asset_name]').val();
		return params;
	}

	searchAsset() {
		let params=this.getParams();
		let rpt = axios.get(this.route+"/getasset",{params})
		.then(function(response){
			$('#assetsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showAssetGrid(data){
		let self=this;
		var pr=$('#assetsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#assetdisposalFrm  [name=asset_quantity_cost_id]').val(row.id);
				$('#assetdisposalFrm  [name=custom_no]').val(row.custom_no);
				$('#assetdisposalFrm  [name=asset_no]').val(row.asset_no);
				// $('#assetdisposalFrm  [name=employee_name]').val(row.employee_name);
				$('#assetdisposalFrm  [name=asset_name]').val(row.asset_name);
				$('#assetdisposalFrm  [name=production_area_id]').val(row.production_area_id);
				$('#assetdisposalFrm  [name=asset_group]').val(row.asset_group);
				//$('#assetdisposalFrm  [name=store_id]').val(row.store_id);
				$('#assetdisposalFrm  [name=brand]').val(row.brand);
				$('#assetdisposalFrm  [name=purchase_date]').val(row.purchase_date);
				$('#assetdisposalFrm  [name=company_id]').val(row.company_id);
				$('#assetdisposalFrm  [name=location_id]').val(row.location_id);
				$('#assetdisposalFrm  [name=type_id]').val(row.type_id);
				// $('#assetdisposalFrm  [name=prod_capacity]').val(row.prod_capacity);
				$('#assetdisposalFrm  [name=serial_no]').val(row.serial_no);
				$('#assetdisposalFrm  [name=origin]').val(row.origin);
				$("#assetdisposalFrm [name=salvage_value]").val(row.salvage_value);
				$("#assetdisposalFrm [name=origin_cost]").val(row.origin_cost);
				$("#assetdisposalFrm [name=depreciation_method_id]").val(row.depreciation_method_id);
				$("#assetdisposalFrm [name=depreciation_rate]").val(row.depreciation_rate);
				$("#assetdisposalFrm [name=accumulated_dep]").val(row.accumulated_dep);
				$("#assetdisposalFrm [name=written_down_value]").val(row.written_down_value);
				$('#openassetwindow').window('close');
				$('#assetsearchTbl').datagrid('loadData',[]);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}

	calculateGainLoss() {
		let self = this;
		let sold_amount = ($('#assetdisposalFrm [name=sold_amount]').val())*1;
		let written_down_value = ($('#assetdisposalFrm [name=written_down_value]').val())*1;
		let gain_loss = (sold_amount - written_down_value);
		$('#assetdisposalFrm [name=gain_loss]').val(gain_loss);
	}
	
}
window.MsAssetDisposal=new MsAssetDisposalController(new MsAssetDisposalModel());
MsAssetDisposal.showGrid();
MsAssetDisposal.showAssetGrid([]);