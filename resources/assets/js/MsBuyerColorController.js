let MsBuyerColorModel = require('./MsBuyerColorModel');
class MsBuyerColorController {
	constructor(MsBuyerColorModel)
	{
		this.MsBuyerColorModel = MsBuyerColorModel;
		this.formId='buyercolorFrm';
		this.dataTable='#buyercolorTbl';
		this.route=msApp.baseUrl()+"/buyercolor"
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

		let formObj=msApp.get('buyercolorFrm');
		let i=1;
		$.each($('#buyercolorTbl').datagrid('getChecked'), function (idx, val) {
				formObj['buyer_id['+i+']']=val.id
				
			i++;
		});
		this.MsBuyerColorModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var color_id=$('#colorFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/buyercolor/create?color_id="+color_id);
			data.then(function (response) {
				$('#buyercolorTbl').datagrid({
					checkbox:true,
					rownumbers:true,
					data: response.data.unsaved,
					columns:[[
						{field:'ck',checkbox:true,width:40},
						{field:'name',title:'Available',width:100},
					]],
				}).datagrid('enableFilter');
				
				$('#buyercolorsavedTbl').datagrid({
					rownumbers:true,
					data: response.data.saved,
					columns:[[
						{field:'name',title:'Saved',width:100},
						{field:'action',title:'',width:60,formatter:MsBuyerColor.formatDetail},
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
		this.MsBuyerColorModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerColorModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsBuyerColor.create();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBuyerColorModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBuyerColor.delete(event,'+row.buyer_color_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsBuyerColor=new MsBuyerColorController(new MsBuyerColorModel());