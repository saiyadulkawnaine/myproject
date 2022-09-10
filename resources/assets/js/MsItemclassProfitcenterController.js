let MsItemclassProfitcenterModel = require('./MsItemclassProfitcenterModel');
class MsItemclassProfitcenterController {
	constructor(MsItemclassProfitcenterModel)
	{
		this.MsItemclassProfitcenterModel = MsItemclassProfitcenterModel;
		this.formId='itemclassprofitcenterFrm';
		this.dataTable='#itemclassprofitcenterTbl';
		this.route=msApp.baseUrl()+"/itemclassprofitcenter"
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

		let formObj=msApp.get('itemclassprofitcenterFrm');
		let i=1;
		$.each($('#itemclassprofitcenterTbl').datagrid('getChecked'), function (idx, val) {
				formObj['profitcenter_id['+i+']']=val.id
				
			i++;
		});
		this.MsItemclassProfitcenterModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var itemclass_id=$('#itemclassFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/itemclassprofitcenter/create?itemclass_id="+itemclass_id);
			data.then(function (response) {
			$('#itemclassprofitcenterTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
			
				columns:[[
					{field:'ck',checkbox:true,width:40},
					{field:'name',title:'Profit Center',width:150},
				]],
			}).datagrid('enableFilter');
			
			$('#itemclassprofitcentersavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
					{field:'name',title:'Tagged List',width:150},
					{field:'action',title:'',width:60,formatter:MsItemclassProfitcenter.formatDetail},
					]],
				}).datagrid('enableFilter');
			})
			.catch(function (error) {
			console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsItemclassProfitcenterModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		//alert(id)
		this.MsItemclassProfitcenterModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsItemclassProfitcenter.create()
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsItemclassProfitcenterModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsItemclassProfitcenter.delete(event,'+row.itemclass_profitcenter_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsItemclassProfitcenter=new MsItemclassProfitcenterController(new MsItemclassProfitcenterModel());

