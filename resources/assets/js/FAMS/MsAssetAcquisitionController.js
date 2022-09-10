let MsAssetAcquisitionModel = require('./MsAssetAcquisitionModel');
require('./../datagrid-filter.js');
class MsAssetAcquisitionController {
	constructor(MsAssetAcquisitionModel)
	{
		this.MsAssetAcquisitionModel = MsAssetAcquisitionModel;
		this.formId='assetacquisitionFrm';
		this.dataTable='#assetacquisitionTbl';
		this.route=msApp.baseUrl()+"/assetacquisition"
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
			this.MsAssetAcquisitionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAssetAcquisitionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#assetacquisitionFrm [id="location_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="division_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="department_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="section_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="subsection_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="type_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="supplier_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="uom_id"]').combobox('setValue', '');
		
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAssetAcquisitionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAssetAcquisitionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#assetacquisitionTbl').datagrid('reload');
		msApp.resetForm('assetacquisitionFrm');
		$('#assetacquisitionFrm [id="location_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="division_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="department_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="section_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="subsection_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="type_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="supplier_id"]').combobox('setValue', '');
		$('#assetacquisitionFrm [id="uom_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;	
		
		let asset=this.MsAssetAcquisitionModel.get(index,row);	
		asset.then(function (response) {	
			$('#assetquantitycostFrm  [name=asset_acquisition_id]').val(row.id);	
			$('#assetacquisitionFrm [id="location_id"]').combobox('setValue', response.data.fromData.location_id);
			$('#assetacquisitionFrm [id="division_id"]').combobox('setValue', response.data.fromData.division_id);
			$('#assetacquisitionFrm [id="department_id"]').combobox('setValue', response.data.fromData.department_id);
			$('#assetacquisitionFrm [id="section_id"]').combobox('setValue', response.data.fromData.section_id);
			$('#assetacquisitionFrm [id="subsection_id"]').combobox('setValue', response.data.fromData.subsection_id);
			$('#assetacquisitionFrm [id="type_id"]').combobox('setValue', response.data.fromData.type_id);
			$('#assetacquisitionFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#assetacquisitionFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
			MsAssetAcquisition.setClassArea(response.data.fromData.type_id);
			MsAssetAcquisition.rqSupplier(response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsAssetAcquisition.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	setClassArea(type_id){
		//alert(type_id)
		if(type_id==65){
			$(".areaCon").addClass("req-text");
		}else{
			$(".areaCon").removeClass("req-text");
		}
	}
	assetTypechange(type_id){
		//alert(type_id)
		MsAssetAcquisition.setClassArea(type_id);
	}
	rqSupplier(supplier_id){
		if(supplier_id==''){
			$(".suplreq").addClass("req-text");
		}else{
			$(".suplreq").removeClass("req-text");
		}
	}
	assetSupplychange(supplier_id){
		MsAssetAcquisition.rqSupplier(supplier_id);
	}
	create(data){
		let asset = msApp.getHtml("/assetquantitycost/create",data);
		asset.then(function(response){
			$('#assetqty').html(response.data);
		});
	}
	openqtywindow()
	{
		 let data = {};
		 let id = $('#assetacquisitionFrm [name=id]').val();
		 let qty = $('#assetacquisitionFrm [name=qty]').val();
		 data.id = id;
		 data.qty = qty;
		 this.create(data);

		 $('#assetquantitycostFrm [name=asset_acquisition_id]').val(id);
		 $('#OpenQuantityWindow').window('open');

	}
}
window.MsAssetAcquisition=new MsAssetAcquisitionController(new MsAssetAcquisitionModel());
MsAssetAcquisition.showGrid()

$('#famsAssettabs').tabs({
	onSelect:function(title,index){
		let asset_acquisition_id = $('#assetacquisitionFrm [name=id]').val();
		 
		var data={};
		data.asset_acquisition_id=asset_acquisition_id;
		if(index==1){
			if(asset_acquisition_id===''){
				$('#famsAssettabs').tabs('select',0);
				msApp.showError('Select an Asset Acquisition First',0);
				return;
			}
			$('#assetdepreciationFrm [name=id]').val(asset_acquisition_id);
			MsAssetDepreciation.showGrid(asset_acquisition_id);
		}
		if(index==2){
			if(asset_acquisition_id===''){
				$('#famsAssettabs').tabs('select',0);
				msApp.showError('Select an Asset Acquisition First',0);
				return;
			}
			$('#assettechfeatureFrm  [name=asset_acquisition_id]').val(asset_acquisition_id);
			MsAssetTechnicalFeature.showGrid(asset_acquisition_id);
		}
		if(index==3){
			if(asset_acquisition_id===''){
				$('#famsAssettabs').tabs('select',0);
				msApp.showError('Select an Asset Acquisition First',0);
				return;
			}
			$('#assettechimageFrm [name=asset_acquisition_id]').val(asset_acquisition_id);
			MsAssetTechImage.showGrid(asset_acquisition_id);
					
		}
		
		if(index==4){
				if(asset_acquisition_id===''){
					$('#famsAssettabs').tabs('select',0);
					msApp.showError('Select an Asset Acquisition First',0);
					return;
				}					
			$('#assettechfileuploadFrm [name=asset_acquisition_id]').val(asset_acquisition_id);
			MsAssetTechFileUpload.showGrid(asset_acquisition_id);
		}
				
		if(index==5){
				if(asset_acquisition_id===''){
					$('#famsAssettabs').tabs('select',0);
					msApp.showError('Select an Asset Acquisition First',0);
					return;
				}
			$('#assetutilitydetailFrm  [name=asset_acquisition_id]').val(asset_acquisition_id);
			MsAssetUtilityDetail.showGrid(asset_acquisition_id);
		}
		if(index==6){
				if(asset_acquisition_id===''){
					$('#famsAssettabs').tabs('select',0);
					msApp.showError('Select an Asset Acquisition First',0);
					return;
				}
			$('#assetmaintenanceFrm  [name=asset_acquisition_id]').val(asset_acquisition_id);
			MsAssetMaintenance.showGrid(asset_acquisition_id);
		}

		if(index==7){
			if(asset_acquisition_id===''){
				$('#famsAssettabs').tabs('select',0);
				msApp.showError('Select an Asset Acquisition First',0);
				return;
			}
		$('#assetmanpowerFrm  [name=asset_acquisition_id]').val(asset_acquisition_id);
		MsAssetManpower.showGrid(asset_acquisition_id);
	}
					
	}
});
