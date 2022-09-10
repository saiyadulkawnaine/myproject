let MsAssetBreakdownModel = require('./MsAssetBreakdownModel');
require('./../datagrid-filter.js');
class MsAssetBreakdownController {
	constructor(MsAssetBreakdownModel)
	{
		this.MsAssetBreakdownModel = MsAssetBreakdownModel;
		this.formId='assetbreakdownFrm';
		this.dataTable='#assetbreakdownTbl';
		this.route=msApp.baseUrl()+"/assetbreakdown"
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
			this.MsAssetBreakdownModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAssetBreakdownModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#assetbreakdownFrm [id="reason_id"]').combobox('setValue', '');
		
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAssetBreakdownModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAssetBreakdownModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//MsAssetBreakdown.get();
		$('#assetbreakdownTbl').datagrid('reload');
		msApp.resetForm('assetbreakdownFrm');
		$('#assetbreakdownFrm [id="reason_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;	
		let breakdown=this.MsAssetBreakdownModel.get(index,row);
		breakdown.then(function (response) {
			$('#assetbreakdownFrm [id="reason_id"]').combobox('setValue', response.data.fromData.reason_id);
		}).catch(function (error) {
			console.log(error);
		});
	}

	// get()
	// {
	// 	let d= axios.get(this.route)
	// 	.then(function (response) {
	// 		$('#assetbreakdownTbl').datagrid('loadData', response.data);
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

	showGrid(){
		let self=this;
		$('#assetbreakdownTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsAssetBreakdown.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }
    
    openAssetDtlWindow(){
		$('#openassetdtlwindow').window('open');
	}

	getParams(){
		let params={};
		params.asset_no=$('#assetdtlsearchFrm [name=asset_no]').val();
		params.custom_no=$('#assetdtlsearchFrm [name=custom_no]').val();
		params.asset_name=$('#assetdtlsearchFrm [name=asset_name]').val();
		return params;
	}

	searchAssetDtl(){
		let params=this.getParams();
		let rpt = axios.get(this.route+"/getassetdtls",{params})
		.then(function(response){
			$('#assetdtlsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showAssetDtlGrid(data){
		let self=this;
		var pr=$('#assetdtlsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#assetbreakdownFrm  [name=asset_quantity_cost_id]').val(row.id);
				$('#assetbreakdownFrm  [name=custom_no]').val(row.custom_no);
				$('#assetbreakdownFrm  [name=asset_no]').val(row.asset_no);
				$('#assetbreakdownFrm  [name=employee_name]').val(row.employee_name);
				$('#assetbreakdownFrm  [name=asset_name]').val(row.asset_name);
				$('#assetbreakdownFrm  [name=production_area_id]').val(row.production_area_id);
				$('#assetbreakdownFrm  [name=asset_group]').val(row.asset_group);
				//$('#assetbreakdownFrm  [name=store_id]').val(row.store_id);
				$('#assetbreakdownFrm  [name=brand]').val(row.brand);
				$('#assetbreakdownFrm  [name=purchase_date]').val(row.purchase_date);
				$('#assetbreakdownFrm  [name=prod_capacity]').val(row.prod_capacity);
				$('#assetbreakdownFrm  [name=serial_no]').val(row.serial_no);
				$('#assetbreakdownFrm  [name=origin]').val(row.origin);
				$('#openassetdtlwindow').window('close');
				$('#assetdtlsearchTbl').datagrid('loadData',[]);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}

	openBreakdownEmployee(){
		$('#openbreakdownemployeewindow').window('open');
	}

	getHrEmpParams(){
		let params = {}
		params.designation_id=$('#assetempsearchFrm [name=designation_id]').val();
		params.department_id=$('#assetempsearchFrm [name=department_id]').val();
		params.company_id=$('#assetempsearchFrm [name=company_id]').val();
		return params;
	}
	searchEmployeeGrid(){
		let params=this.getHrEmpParams();
		let emp= axios.get(this.route+'/getemployee',{params})
		.then(function(response){
			$('#assetempsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		
	}

	showEmployeeGrid(data){
		let self = this;
		$('#assetempsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
				$('#assetbreakdownFrm [name=employee_h_r_id]').val(row.id);
				$('#assetbreakdownFrm [name=name]').val(row.name);
				$('#assetempsearchTbl').datagrid('loadData',[]);
				$('#openbreakdownemployeewindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchBreakdown()
	{
		let params={};
		params.from_date=$('#from_date').val();
		params.to_date=$('#to_date').val();
		let data= axios.get(this.route+"/getbreakdownlist",{params});
		data.then(function (response) {
			$('#assetbreakdownTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsAssetBreakdown=new MsAssetBreakdownController(new MsAssetBreakdownModel());
MsAssetBreakdown.showGrid();
//MsAssetBreakdown.get();
MsAssetBreakdown.showAssetDtlGrid([]);
MsAssetBreakdown.showEmployeeGrid([]);

$('#famsAssetBreakdowntabs').tabs({
	onSelect:function(title,index){
		let asset_breakdown_id = $('#assetbreakdownFrm [name=id]').val();
		 
		var data={};
		data.asset_breakdown_id=asset_breakdown_id;

		if(index==1){
			if(asset_breakdown_id===''){
				$('#famsAssetBreakdowntabs').tabs('select',0);
				msApp.showError('Select an Asset Breakdown First',0);
				return;
			}
			$('#assetrecoveryFrm [name=id]').val(asset_breakdown_id);
			$('#assetrecoveryFrm [name=custom_no]').val($('#assetbreakdownFrm [name=custom_no]').val());
			$('#assetrecoveryFrm [name=asset_name]').val($('#assetbreakdownFrm [name=asset_name]').val());
			$('#assetrecoveryFrm [name=type_id]').val($('#assetbreakdownFrm [name=type_id]').val());
			$('#assetrecoveryFrm [name=production_area_id]').val($('#assetbreakdownFrm [name=production_area_id]').val());
			$('#assetrecoveryFrm [name=asset_group]').val($('#assetbreakdownFrm [name=asset_group]').val());
			$('#assetrecoveryFrm [name=brand]').val($('#assetbreakdownFrm [name=brand]').val());
			MsAssetRecovery.showGrid(asset_breakdown_id);
		}
					
	}
});
