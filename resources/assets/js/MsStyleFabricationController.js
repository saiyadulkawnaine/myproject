let MsStyleFabricationModel = require('./MsStyleFabricationModel');
class MsStyleFabricationController {
	constructor(MsStyleFabricationModel)
	{
		this.MsStyleFabricationModel = MsStyleFabricationModel;
		this.formId='stylefabricationFrm';
		this.dataTable='#stylefabricationTbl';
		this.route=msApp.baseUrl()+"/stylefabrication"
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
			this.MsStyleFabricationModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleFabricationModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		let style_id=$('#stylefabricationFrm  [name=style_id]').val()
		let style_ref=$('#stylefabricationFrm  [name=style_ref]').val()
		msApp.resetForm(this.formId);
		$('#stylefabricationFrm  [name=style_id]').val(style_id);
		$('#stylefabricationFrm  [name=style_ref]').val(style_ref);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleFabricationModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleFabricationModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylefabricationTbl').datagrid('reload');
		$('#stylefabricationFrm  [name=id]').val(d.id);
		MsStyleFabrication.resetForm('stylefabricationstripeFrm');
		$('#stylefabricationstripeFrm  [name=style_fabrication_id]').val(d.id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStyleFabricationModel.get(index,row);
		MsStyleFabrication.setClass(row.fabric_look_id);
		msApp.resetForm('stylefabricationstripeFrm');
		$('#stylefabricationstripeFrm  [name=style_fabrication_id]').val(row.id);
		var data={};
		data.style_gmt_id=row.style_gmt_id;
		let stylegmtcolor = msApp.getJson('stylegmtcolorsize/getgmtcolor',data);
		stylegmtcolor.then(function (response) {
				$('#stylefabricationstripeFrm [name="style_color_id"]').empty();
				$('#stylefabricationstripeFrm [name="style_color_id"]').append('<option value="">-Select-</option>');
				$.each(response.data, function(key, value) {
				$('#stylefabricationstripeFrm [name="style_color_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
				});
				})
				.catch(function (error) {
				console.log(error);
				});
		MsStyleFabricationStripe.showGrid(row.id);
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
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}
	getGmtpartDetails(gmtpart_id){
		let data= axios.get(msApp.baseUrl()+"/gmtspart/"+gmtpart_id+"/edit");
			let g=data.then(function (response) {
				//alert(response.data.fromData.identity)
				if(response.data.fromData.part_type_id==4){
					$('#stylefabricationFrm  [name=is_narrow]').val(1);
				}else{
					$('#stylefabricationFrm  [name=is_narrow]').val(0);
				}


		})
		.catch(function (error) {
		 	console.log(error);
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStyleFabrication.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openFabricationWindow(){
		$('#styleFabricationWindow').window('open');
	}
	searchFabric(){
		let construction_name=$('#stylefabricsearchFrm  [name=construction_name]').val();
		let composition_name=$('#stylefabricsearchFrm  [name=composition_name]').val();
		let data= axios.get(this.route+"/getfabric?construction_name="+construction_name+"&composition_name="+composition_name);
		data.then(function (response) {
			$('#stylefabricsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	fabricSearchGrid(data)
	{
		var dg = $('#stylefabricsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){

				$('#stylefabricationFrm  [name=autoyarn_id]').val(row.id);
				$('#stylefabricationFrm  [name=fabrication]').val(row.name+","+row.composition_name);
				$('#styleFabricationWindow').window('close')
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);

	}
	
	fabricLookChange(fabric_look_id){
		MsStyleFabrication.setClass(fabric_look_id);
	}
	
	setClass(fabric_look_id)
	{
		if(fabric_look_id==25 )
		{
			$("#stylefabricationaoptype").addClass("req-text");
			$("#stylefabricationaopcoverage").addClass("req-text");
			$("#stylefabricationaopimpression").addClass("req-text");
		}else{
			$("#stylefabricationaoptype").removeClass("req-text");
			$("#stylefabricationaopcoverage").removeClass("req-text");
			$("#stylefabricationaopimpression").removeClass("req-text");
		}
		
	}
}
window.MsStyleFabrication=new MsStyleFabricationController(new MsStyleFabricationModel());
MsStyleFabrication.fabricSearchGrid([])
