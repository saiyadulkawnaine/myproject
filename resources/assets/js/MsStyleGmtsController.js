let MsStyleGmtsModel = require('./MsStyleGmtsModel');
class MsStyleGmtsController {
	constructor(MsStyleGmtsModel)
	{
		this.MsStyleGmtsModel = MsStyleGmtsModel;
		this.formId='stylegmtsFrm';
		this.dataTable='#stylegmtsTbl';
		this.route=msApp.baseUrl()+"/stylegmts"
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
			this.MsStyleGmtsModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleGmtsModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleGmtsModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleGmtsModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylegmtsTbl').datagrid('reload');
		//$('#stylegmtsFrm  [name=id]').val(d.id);
		msApp.resetForm('stylegmtsFrm');
		$('#stylegmtsFrm  [name=style_ref]').val($('#styleFrm  [name=style_ref]').val());
		$('#stylegmtsFrm  [name=style_id]').val($('#styleFrm  [name=id]').val());

		msApp.resetForm('styleembelishmentFrm');
		$('#styleembelishmentFrm  [name=style_gmt_id]').val(d.id);
		$('#styleembelishmentFrm  [name=style_id]').val(d.style_id);





		msApp.resetForm('stylefabricationFrm');
		$('#stylefabricationFrm  [name=style_gmt_id]').val(d.id);
		$('#stylefabricationFrm  [name=style_id]').val(d.style_id);

		msApp.resetForm('stylesizemsureFrm');
		$('#stylesizemsureFrm  [name=style_gmt_id]').val(d.id);
		$('#stylesizemsureFrm  [name=style_id]').val(d.style_id);
		//MsStyleSizeMsure.GetGmtSizes (d.id)
		msApp.resetForm('stylesampleFrm');
		$('#stylesampleFrm  [name=style_gmt_id]').val(d.id);
		$('#stylesampleFrm  [name=style_id]').val(d.style_id);

		msApp.resetForm('stylepkgratioFrm');
		$('#stylepkgratioFrm  [name=style_gmt_id]').val(d.id);
		$('#stylepkgratioFrm  [name=style_id]').val(d.style_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsStyleGmtsModel.get(index,row);
		msApp.resetForm('styleembelishmentFrm');
		$('#styleembelishmentFrm  [name=style_gmt_id]').val(row.id);
		$('#styleembelishmentFrm  [name=style_id]').val(row.style_id);
		//MsStyleEmbelishment.showGrid(row.id);



		msApp.resetForm('stylefabricationFrm');
		$('#stylefabricationFrm  [name=style_gmt_id]').val(row.id);
		$('#stylefabricationFrm  [name=style_id]').val(row.style_id);
		//MsStyleFabrication.showGrid(row.id);

		msApp.resetForm('stylesizemsureFrm');
		$('#stylesizemsureFrm  [name=style_gmt_id]').val(row.id);
		$('#stylesizemsureFrm  [name=style_id]').val(row.style_id);
		//MsStyleSizeMsure.GetGmtSizes (row.id)
		//MsStyleSizeMsure.showGrid(row.id);

		msApp.resetForm('stylesampleFrm');
		$('#stylesampleFrm  [name=style_gmt_id]').val(row.id);
		$('#stylesampleFrm  [name=style_id]').val(row.style_id);
		//MsStyleSample.showGrid(row.id);

		msApp.resetForm('stylepkgratioFrm');
		$('#stylepkgratioFrm  [name=style_gmt_id]').val(row.id);
		$('#stylepkgratioFrm  [name=style_id]').val(row.style_id);
		//MsStylePkgRatio.showGrid(row.id);

		msApp.resetForm('stylepkgratioFrm');
		$('#stylegmtcolorsizeFrm  [name=style_gmt_id]').val(row.id);
		$('#stylegmtcolorsizeFrm  [name=style_id]').val(row.style_id);
        MsStyleGmtColorSizes.create(row.id);
		/*data.then(function (response) {
			$('#stylegmtcolorsizeTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.colorSize,
			 	columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'color_name',title:'Color',width:100},
				{field:'size_name',title:'Size',width:50},
				]],
			});
			
			$('#stylegmtcolorsizeeditTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.savedcolorSize,
				onLoadSuccess:function(data){
					 //var rowData = data.rows; 
					 //$.each(rowData, function (idx, val) { if (val.ck) { $("#stylegmtcolorsizeeditTbl").datagrid("selectRow", idx); } });
				},
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'color_name',title:'Color',width:100},
				{field:'size_name',title:'Size',width:50},
				]],
			});
		})
		.catch(function (error) {
		console.log(error);
		});*/
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
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStyleGmts.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	setCategory(item_account_id){
		let data={};
		data.id=item_account_id;
		let itemAccounts = msApp.getJson('itemaccount',data);
		itemAccounts.then(function (response) {
		$.each(response.data, function(key, value) {
		$('#stylegmtsFrm [name="gmt_catg"]').val( value.gmt_category);
		$('#stylegmtsFrm [name="gmt_catg_id"]').val( value.gmt_category);
		});
		})
		.catch(function (error) {
		console.log(error);
		});
	}
	
}
window.MsStyleGmts=new MsStyleGmtsController(new MsStyleGmtsModel());
//MsStyleGmts.showGrid();
