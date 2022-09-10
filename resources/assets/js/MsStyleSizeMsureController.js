let MsStyleSizeMsureModel = require('./MsStyleSizeMsureModel');
class MsStyleSizeMsureController {
	constructor(MsStyleSizeMsureModel)
	{
		this.MsStyleSizeMsureModel = MsStyleSizeMsureModel;
		this.formId='stylesizemsureFrm';
		this.dataTable='#stylesizemsureTbl';
		this.route=msApp.baseUrl()+"/stylesizemsure"
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
		/*var str = $( "#"+ this.formId).serialize();
		axios.post(this.route,str)
		.then(function (response) {
			$('#stylesizemsureTbl').datagrid('reload');
		//$('#StyleSizeMsureFrm  [name=id]').val(d.id);
		msApp.resetForm('stylesizemsureFrm');
		let d=response.data;
					if (typeof d == 'object') {
						if (d.success == true) {
							msApp.showSuccess(d.message)
							callback(d);
						}
						else if (d.success == false) {
							msApp.showError(d.message);
						}else{
							let err=s.message(d);
							msApp.showError(err.message,err.key);

						}
					}
		})
		.catch(function (error) {
		console.log(error);
		});*/

		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsStyleSizeMsureModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleSizeMsureModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleSizeMsureModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleSizeMsureModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylesizemsureTbl').datagrid('reload');
		$('#stylesizemsureFrm  [name=id]').val(d.id);
		MsStyleSizeMsure.GetGmtSizes (d.style_gmt_id);
		//msApp.resetForm('stylesizemsureFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStyleSizeMsureModel.get(index,row);
		$('#stylesizemsurevalFrm  [name=style_size_msure_id]').val(row.id);
		$('#stylesizemsurevalFrm  [name=style_gmt_id]').val(row.style_gmt_id);
		$('#stylesizemsurevalFrm  [name=style_id]').val(row.style_id);

	}

	showGrid(style_id)
	{
		let self=this;
		var data={};
		data.style_id=style_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStyleSizeMsure.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	GetGmtSizes (styleGmtId){
		let data={};
		data.style_gmt_id=styleGmtId;
		msApp.getJson('stylesize',data)
		.then(function (response) {
			    $('#sizeMatrix').empty();
                $.each(response.data, function(key, value) {
                $('#sizeMatrix').append('<div class="row middle" ><div class="col-sm-4">'+value.size+'</div><div class="col-sm-8"><input type="text" name=\'size['+value.id+']\'/></div></div>');
                });
				/*$('#sizeMatrix').append('</tr><tr>');
				$.each(response.data, function(key, value) {
                $('#sizeMatrix').append('<td><input type="text" name=\'size['+value.id+']\'/></td>');
                });*/
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsStyleSizeMsure=new MsStyleSizeMsureController(new MsStyleSizeMsureModel());
//MsStyleSizeMsure.showGrid();
