let MsBuyerSizeModel = require('./MsBuyerSizeModel');
class MsBuyerSizeController {
	constructor(MsBuyerSizeModel)
	{
		this.MsBuyerSizeModel = MsBuyerSizeModel;
		this.formId='buyersizeFrm';
		this.dataTable='#buyersizeTbl';
		this.route=msApp.baseUrl()+"/buyersize"
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

		let formObj=msApp.get('buyersizeFrm');
		let i=1;
		$.each($('#buyersizeTbl').datagrid('getChecked'), function (idx, val) {
				formObj['buyer_id['+i+']']=val.id
				
			i++;
		});
		this.MsBuyerSizeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var size_id=$('#sizeFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/buyersize/create?size_id="+size_id);
		data.then(function (response) {
			$('#buyersizeTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				columns:[[
					{field:'ck',checkbox:true,width:40},
					{field:'name',title:'Available',width:100},
				]],
			}).datagrid('enableFilter');
		
			$('#buyersizesavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
					{field:'name',title:'Saved',width:100},
					{field:'action',title:'',width:60,formatter:MsBuyerSize.formatDetail},
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
		this.MsBuyerSizeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		//alert(id)
		this.MsBuyerSizeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsBuyerSize.create();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBuyerSizeModel.get(index,row);
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
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBuyerSize.delete(event,'+row.buyer_size_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsBuyerSize=new MsBuyerSizeController(new MsBuyerSizeModel());

