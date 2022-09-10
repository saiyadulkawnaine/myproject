let MsSubInbOrderFileModel = require('./MsSubInbOrderFileModel');
class MsSubInbOrderFileController {
	constructor(MsSubInbOrderFileModel)
	{
		this.MsSubInbOrderFileModel = MsSubInbOrderFileModel;
		this.formId='subinborderfileFrm';
		this.dataTable='#subinborderfileTbl';
		this.route=msApp.baseUrl()+"/subinborderfile"
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
		var id = $('#subinborderfileFrm [name=id]').val();
		var sub_inb_order_id = $('#subinborderfileFrm [name=sub_inb_order_id]').val();
		var formData = new FormData();
		formData.append('id',id);
		formData.append('sub_inb_order_id',sub_inb_order_id);
		var image = document.getElementById('file_src');
		formData.append('file_src',image.files[0]);
		this.MsSubInbOrderFileModel.upload(this.route,'POST',formData,this.response);
	}
	resetForm (){
		msApp.resetForm(this.formId);
	}
	remove(){
		let formObj=msApp.get(this.formId);
		this.MsSubInbOrderFileModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}
	delete(event,id){
		event.stopPropagation()
		this.MsSubInbOrderFileModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d){
		$('#subinborderfileTbl').datagrid('reload');
		msApp.resetForm('subinborderfileFrm');
		//alert('dd');
		$('#subinborderfileFrm [name=sub_inb_order_id]').val($('#subinborderFrm [name=id]').val());
	}

	edit(index,row){
		row.route=this.route;
		row.formId=this.formId;		
		this.MsSubInbOrderFileModel.get(index,row);
	}

	showGrid(sub_inb_order_id){
		let self=this;
		  let data={};
		  data.sub_inb_order_id=sub_inb_order_id;
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
		return '<a href="javascript:void(0)"  onClick="MsSubInbOrderFile.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
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
		//return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.file_src+'" onClick="MsSubInbImage.imageWindow('+'\''+row.file_src+'\''+')"/>';
		var file = row.file_src;
		return '<a href="'+msApp.baseUrl()+'/images/'+row.file_src+'" target="_blank  onClick="MsSubInbOrderFile.imageWindow('+'\''+row.file_src+'\''+')">'+file+'</a>';
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
window.MsSubInbOrderFile=new MsSubInbOrderFileController(new MsSubInbOrderFileModel());
