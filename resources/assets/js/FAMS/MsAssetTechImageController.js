let MsAssetTechImageModel = require('./MsAssetTechImageModel');
class MsAssetTechImageController {
	constructor(MsAssetTechImageModel){
		this.MsAssetTechImageModel = MsAssetTechImageModel;
		this.formId='assettechimageFrm';
		this.dataTable='#assettechimageTbl';
		this.route=msApp.baseUrl()+"/assettechimage"
	}
	submit(){
		var id = $('#assettechimageFrm [name=id]').val();
		var asset_acquisition_id = $('#assettechimageFrm [name=asset_acquisition_id]').val();
		var formData = new FormData();
		formData.append('id',id);
		formData.append('asset_acquisition_id',asset_acquisition_id);
		var image = document.getElementById('file_src');
		formData.append('file_src',image.files[0]);
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

		this.MsAssetTechImageModel.upload(this.route,'POST',formData,this.response);
	}
	resetForm (){
		msApp.resetForm(this.formId);
	}
	remove(){
		let formObj=msApp.get(this.formId);
		this.MsAssetTechImageModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}
	delete(event,id){
		event.stopPropagation()
		this.MsAssetTechImageModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d){
		$('#assettechimageTbl').datagrid('reload');
		msApp.resetForm('assettechimageFrm');
		//alert('dd');
		$('#assettechimageFrm [name=asset_acquisition_id]').val($('#assetacquisitionFrm [name=id]').val());
		//$('#assettechimageFrm [name=asset_technical_feature_id]').val($('#assettechfeatureFrm [name=id]').val());
	}

	edit(index,row){
		row.route=this.route;
		row.formId=this.formId;		
		this.MsAssetTechImageModel.get(index,row);
	}

	showGrid(asset_acquisition_id){
		let self=this;
		  let data={};
		  data.asset_acquisition_id=asset_acquisition_id;
		  $(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsAssetTechImage.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

//image pop ups

	imageWindow(file_src){
		var output = document.getElementById('assetImageWindowoutput');
					var fp=msApp.baseUrl()+"/images/"+file_src;
    	            output.src =  fp;
			$('#assetImageWindow').window('open');
	}
	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.file_src+'" onClick="MsAssetTechImage.imageWindow('+'\''+row.file_src+'\''+')"/>';
	}
	/* loadFile (event){
		var output = document.getElementById('output');
    	output.src = URL.createObjectURL(event.target.files[0]);
	} */

}
window.MsAssetTechImage=new MsAssetTechImageController(new MsAssetTechImageModel());
