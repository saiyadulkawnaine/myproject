//require('../jquery.easyui.min.js');
let MsAssetTechnicalFeatureModel = require('./MsAssetTechnicalFeatureModel');
//require('../datagrid-filter.js');
class MsAssetTechnicalFeatureController {
	constructor(MsAssetTechnicalFeatureModel)
	{
		this.MsAssetTechnicalFeatureModel = MsAssetTechnicalFeatureModel;
		this.formId='assettechfeatureFrm';
		this.dataTable='#assettechfeatureTbl';
		this.route=msApp.baseUrl()+"/assettechfeature"
	}

	submit()
	{
		// var id=$('#assettechfeatureFrm [name=id]').val();
		// var asset_acquisition_id=$('#assettechfeatureFrm [name=asset_acquisition_id]').val();
		// var die_width=$('#assettechfeatureFrm [name=die_width]').val();
		// var gauge=$('#assettechfeatureFrm [name=gauge]').val();
		// var extra_cylinder=$('#assettechfeatureFrm [name=extra_cylinder]').val();
		// var no_of_feeder=$('#assettechfeatureFrm [name=no_of_feeder]').val();
		// var attachment=$('#assettechfeatureFrm [name=attachment]').val();
		// var uploadfile=$('#assettechfeatureFrm [name=uploadfile]').val();
		// var image=$('#assettechfeatureFrm [name=image]').val();
		// // var data={};
		// // data.id=id;
		// // data.asset_acquisition_id=asset_acquisition_id;
		// // data.die_width=die_width;
		// // data.gauge=gauge;
		// // data.die_width=die_width;
		// // data.extra_cylinder=extra_cylinder;
		// // data.no_of_feeder=no_of_feeder;
		// // data.attachment=attachment;
		// // data.attachment=uploadfile;
		// // data.attachment=image;
		// var formData = new FormData();
		// //formData.append("id",id+"asset_acquisition_id",asset_acquisition_id+"die_width",die_width+"gauge",gauge+"extra_cylinder",extra_cylinder+"no_of_feeder",no_of_feeder+"attachment",attachment+"uploadfile",uploadfile+"image",image);
		// formData.append("id",id);
		// formData.append("asset_acquisition_id",asset_acquisition_id);
		// formData.append("die_width",die_width);
		// formData.append("gauge",gauge);
		// formData.append("extra_cylinder",extra_cylinder);
		// formData.append("no_of_feeder",no_of_feeder);
		// formData.append("attachment",attachment);
		// formData.append("uploadfile",uploadfile);
		// formData.append("image",image);

		// var uploadfile=document.getElementById("uploadfile");
		// var image=document.getElementById("image");
		// formData.append("uploadfile",uploadfile.files[0]);
		// formData.append("image",image.files[0]);
		//alert(formData);
		//this.MsAssetTechnicalFeatureModel.save(this.route+"/",'POST',formData,this.response);

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
			this.MsAssetTechnicalFeatureModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAssetTechnicalFeatureModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		// if(id){
		// 	this.MsAssetTechnicalFeatureModel.save(this.route+"/"+id,'PUT',formData,this.response);
		// }else{
		// 	this.MsAssetTechnicalFeatureModel.upload(this.route,'POST',formData,this.response);
		// }
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		msApp.get(this.formId);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAssetTechnicalFeatureModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#assettechfeatureTbl').datagrid('reload');
		msApp.resetForm('assettechfeatureFrm');
		$('#assettechfeatureFrm [name=asset_acquisition_id]').val($('#assetacquisitionFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsAssetTechnicalFeatureModel.get(index,row);

	}

	showGrid(asset_acquisition_id)
	{
		let self=this;
		var data={};
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAssetTechnicalFeature.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	//File Upload Image

	// loadFile (event){
	// 	var outputfile = document.getElementById('outputfile');
	// 	outputfile.src = URL.createObjectURL(event.target.files[0]);
	// }
	// loadImg (event){
	// 	var outputimage = document.getElementById('outputimage');
	// 	outputimage.src = URL.createObjectURL(event.target.files[0]);
	// }

}
window.MsAssetTechnicalFeature=new MsAssetTechnicalFeatureController(new MsAssetTechnicalFeatureModel());



