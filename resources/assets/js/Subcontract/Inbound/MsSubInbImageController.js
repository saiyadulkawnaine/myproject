let MsSubInbImageModel = require('./MsSubInbImageModel');
class MsSubInbImageController {
	constructor(MsSubInbImageModel){
		this.MsSubInbImageModel = MsSubInbImageModel;
		this.formId='subinbimageFrm';
		this.dataTable='#subinbimageTbl';
		this.route=msApp.baseUrl()+"/subinbimage"
	}
	submit(){
		// $.blockUI({
		// 	message: '<i class="icon-spinner4 spinner">Saving...</i>',
		// 	overlayCSS: {
		// 		backgroundColor: '#1b2024',
		// 		opacity: 0.8,
		// 		zIndex: 999999,
		// 		cursor: 'wait'
		// 	},
		// 	css: {
		// 		border: 0,
		// 		color: '#fff',
		// 		padding: 0,
		// 		zIndex: 9999999,
		// 		backgroundColor: 'transparent'
		// 	}
		// });	
		var id = $('#subinbimageFrm [name=id]').val();
		var sub_inb_marketing_id = $('#subinbimageFrm [name=sub_inb_marketing_id]').val();
		var formData = new FormData();
		formData.append('id',id);
		formData.append('sub_inb_marketing_id',sub_inb_marketing_id);
		var image = document.getElementById('file_src');
		var doc_file = document.getElementById('doc_file_src');
		formData.append('file_src',image.files[0]);
		formData.append('doc_file_src',doc_file.files[0]);
		this.MsSubInbImageModel.upload(this.route,'POST',formData,this.response);
	}
	resetForm (){
		msApp.resetForm(this.formId);
	}
	remove(){
		let formObj=msApp.get(this.formId);
		this.MsSubInbImageModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}
	delete(event,id){
		event.stopPropagation()
		this.MsSubInbImageModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d){
		$('#subinbimageTbl').datagrid('reload');
		msApp.resetForm('subinbimageFrm');
		//alert('dd');
		$('#subinbimageFrm [name=sub_inb_marketing_id]').val($('#subinbmarketingFrm [name=id]').val());
	}

	edit(index,row){
		row.route=this.route;
		row.formId=this.formId;		
		this.MsSubInbImageModel.get(index,row);
	}

	showGrid(sub_inb_marketing_id){
		let self=this;
		  let data={};
		  data.sub_inb_marketing_id=sub_inb_marketing_id;
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
		return '<a href="javascript:void(0)"  onClick="MsSubInbImage.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
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
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.file_src+'" onClick="MsSubInbImage.imageWindow('+'\''+row.file_src+'\''+')"/>';
	}

	formatfile(value,row){
		return '<a target="_blank" href="' + msApp.baseUrl()+"/file/"+row.doc_file_src+ '">'+row.doc_file_src+'</a>';
	}

}
window.MsSubInbImage=new MsSubInbImageController(new MsSubInbImageModel());
