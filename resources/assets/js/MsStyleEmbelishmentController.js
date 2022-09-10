let MsStyleEmbelishmentModel = require('./MsStyleEmbelishmentModel');
class MsStyleEmbelishmentController {
	constructor(MsStyleEmbelishmentModel)
	{
		this.MsStyleEmbelishmentModel = MsStyleEmbelishmentModel;
		this.formId='styleembelishmentFrm';
		this.dataTable='#styleembelishmentTbl';
		this.route=msApp.baseUrl()+"/styleembelishment"
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
			this.MsStyleEmbelishmentModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleEmbelishmentModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#styleembelishmentFrm [id="gmtspart_id"]').combobox('setValue', '');
		//$('#styleembelishmentFrm [id="embelishment_type_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleEmbelishmentModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleEmbelishmentModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#styleembelishmentTbl').datagrid('reload');
		msApp.resetForm('styleembelishmentFrm');
		$('#styleembelishmentFrm  [name=style_ref]').val($('#styleFrm  [name=style_ref]').val());
		$('#styleembelishmentFrm  [name=style_id]').val($('#styleFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=MsStyleEmbelishment.getembType (row.embelishment_id);
		data.then(function (response) {
		MsStyleEmbelishment.MsStyleEmbelishmentModel.get(index,row);
		$('#styleembelishmentFrm [id="gmtspart_id"]').combobox('setValue', '');
		$('#styleembelishmentFrm [id="embelishment_type_id"]').combobox('setValue', '');
		})
		.catch(function (error) {
			console.log(error);
		});
		
		/* let emb = this.MsStyleEmbelishmentModel.get(index,row);
		emb.then(function(response){
			$('#styleembelishmentFrm [id="gmtspart_id"]').combobox('setValue', response.data.fromData.gmtspart_id);
			$('#styleembelishmentFrm [id="embelishment_type_id"]').combobox('setValue', response.data.fromData.embelishment_type_id);
		}).catch(function(error){
			console.log(error);
		}); */
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
		return '<a href="javascript:void(0)"  onClick="MsStyleEmbelishment.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	embnameChange(embelishment_id){
		MsStyleEmbelishment.getembType (embelishment_id)
		//$('#washchargeFrm  [name=rate]').val('');
	}
	
	getembType (embelishment_id){
		let data= axios.get(this.route+"/embtype?embelishment_id="+embelishment_id)
		.then(function (response) {
			    $('#styleembelishmentFrm [name="embelishment_type_id"]').empty();
				$('#styleembelishmentFrm [name="embelishment_type_id"]').append('<option value="">-Select-</option>');
                $.each(response.data.embelishmenttype, function(key, value) {
					$('#styleembelishmentFrm [name="embelishment_type_id"]').append('<option value="'+ value.id +'">'+ value.name+'</option>');
                });
				$('#styleembelishmentFrm  [name=production_area_id]').val(response.data.embelishment.production_area_id);
				MsStyleEmbelishment.setClass(response.data.embelishment.production_area_id)
				
				
		})
		.catch(function (error) {
			console.log(error);
		});
		return data;
	}
	setClass(production_area_id)
	{
		if(production_area_id==45 ||  production_area_id==50)
		{
			$("#style_emb_embelishment_size").addClass("req-text");
		}
		else
		{
			$("#style_emb_embelishment_size").removeClass("req-text");

		}
	}
}
window.MsStyleEmbelishment=new MsStyleEmbelishmentController(new MsStyleEmbelishmentModel());
//MsStyleEmbelishment.showGrid();
