let MsSoEmbFileModel = require('./MsSoEmbFileModel');
class MsSoEmbFileController {
	constructor(MsSoEmbFileModel)
	{
		this.MsSoEmbFileModel = MsSoEmbFileModel;
		this.formId='soembfileFrm';
		this.dataTable='#soembfileTbl';
		this.route=msApp.baseUrl()+"/soembfile"
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
		var id = $('#soembfileFrm [name=id]').val();
		var so_emb_id = $('#soembfileFrm [name=so_emb_id]').val();
		var formData = new FormData();
		formData.append('id',id);
		formData.append('so_emb_id',so_emb_id);
		var image = document.getElementById('file_src');
		formData.append('file_src',image.files[0]);
		this.MsSoEmbFileModel.upload(this.route,'POST',formData,this.response);
	}
	resetForm (){
		msApp.resetForm(this.formId);
	}
	remove(){
		let formObj=msApp.get(this.formId);
		this.MsSoEmbFileModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}
	delete(event,id){
		event.stopPropagation()
		this.MsSoEmbFileModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d){
		$('#soembfileTbl').datagrid('reload');
		msApp.resetForm('soembfileFrm');
		//alert('dd');
		$('#soembfileFrm [name=so_emb_id]').val($('#soembFrm [name=id]').val());
	}

	edit(index,row){
		row.route=this.route;
		row.formId=this.formId;		
		this.MsSoEmbFileModel.get(index,row);
	}

	showGrid(so_emb_id){
		let self=this;
		  let data={};
		  data.so_emb_id=so_emb_id;
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbFile.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
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
		var file = row.file_src;
		return '<a href="'+msApp.baseUrl()+'/images/'+row.file_src+'" target="_blank  onClick="MsSoEmbFile.imageWindow('+'\''+row.file_src+'\''+')">'+file+'</a>';
	}
	formatFile(value,row)
	{
		//return '<a href="'this.route+'/images/'+file_src+'" target="_blank download>download file</a>';
		var url = msApp.baseUrl()+"/images/"+row.file_src;
    	return '<a target="_blank" href="' + url + '" download><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>download</span></a>';
	}

	/* loadFile (event){
		var output = document.getElementById('output');
    	output.src = URL.createObjectURL(event.target.files[0]);
	} */

}
window.MsSoEmbFile=new MsSoEmbFileController(new MsSoEmbFileModel());
