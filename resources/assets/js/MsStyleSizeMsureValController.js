let MsStyleSizeMsureValModel = require('./MsStyleSizeMsureValModel');
class MsStyleSizeMsureValController {
	constructor(MsStyleSizeMsureValModel)
	{
		this.MsStyleSizeMsureValModel = MsStyleSizeMsureValModel;
		this.formId='stylesizemsurevalFrm';
		this.dataTable='#stylesizemsurevalTbl';
		this.route=msApp.baseUrl()+"/stylesizemsureval"
	}

	submit()
	{
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
			this.MsStyleSizeMsureValModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleSizeMsureValModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleSizeMsureValModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleSizeMsureValModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylesizemsurevalTbl').datagrid('reload');
		//$('#stylesizemsurevalFrm  [name=id]').val(d.id);
		//MsStyleSizeMsureVal.GetGmtSizes (d.style_gmt_id);
		msApp.resetForm('stylesizemsurevalFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStyleSizeMsureValModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStyleSizeMsureVal.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
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
window.MsStyleSizeMsureVal=new MsStyleSizeMsureValController(new MsStyleSizeMsureValModel());
//MsStyleSizeMsureVal.showGrid();
