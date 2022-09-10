let MsStyleGmtColorSizeModel = require('./MsStyleGmtColorSizeModel');
class MsStyleGmtColorSizeController {
	constructor(MsStyleGmtColorSizeModel)
	{
		this.MsStyleGmtColorSizeModel = MsStyleGmtColorSizeModel;
		this.formId='stylegmtcolorsizeFrm';
		this.dataTable='#stylegmtcolorsizeTbl';
		this.route=msApp.baseUrl()+"/stylegmtcolorsize"
	}
	
	create(style_gmt_id){
		let data= axios.get(this.route+"/create?style_gmt_id="+style_gmt_id);
		data.then(function (response) {
			$('#stylegmtcolorsizeTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.colorSize,
			  /*  onLoadSuccess:function(data){
					 var rowData = data.rows; 
					 $.each(rowData, function (idx, val) { 
						 if (val.ck) 
						 { 
							 $("#stylegmtcolorsizeTbl").datagrid("selectRow", idx); 
						 } 
					 });
				},*/
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'color_name',title:'Color',width:100},
				{field:'size_name',title:'Size',width:50},
				]],
			});
			
			$('#stylegmtcolorsizeeditTbl').datagrid({
				//checkbox:true,
				rownumbers:true,
				data: response.data.savedcolorSize,
				onLoadSuccess:function(data){
					 //var rowData = data.rows; 
					 //$.each(rowData, function (idx, val) { if (val.ck) { $("#stylegmtcolorsizeeditTbl").datagrid("selectRow", idx); } });
				},
				columns:[[
				//{field:'ck',checkbox:true,width:40},
				{field:'color_name',title:'Color',width:100},
				{field:'size_name',title:'Size',width:50},
				{field:'action',title:'',width:60,formatter:MsStyleGmtColorSizes.formatDetail},
				]],
			});
		})
		.catch(function (error) {
		console.log(error);
		});

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

		let formObj=msApp.get('stylegmtcolorsizeFrm');
		let i=1;
		$.each($('#stylegmtcolorsizeTbl').datagrid('getChecked'), function (idx, val) {
				formObj['style_color_id['+i+']']=val.style_color_id
				formObj['style_size_id['+i+']']=val.style_size_id
			i++;
		});
		this.MsStyleGmtColorSizeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		alert(msApp.qs.stringify(formObj));
		this.MsStyleGmtColorSizeModel.save(this.route+"/"+formObj.style_gmt_id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleGmtColorSizeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#stylegmtcolorsizeTbl').datagrid('reload');
		//$('#stylegmtcolorsizeeditTbl').datagrid('reload');
		//msApp.resetForm('stylegmtcolorsizeFrm');
		MsStyleGmtColorSizes.create(d.style_gmt_id)
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStyleGmtColorSizeModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsStyleGmtColorSizes.delete(event,'+row.style_gmt_color_size_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsStyleGmtColorSizes=new MsStyleGmtColorSizeController(new MsStyleGmtColorSizeModel());
