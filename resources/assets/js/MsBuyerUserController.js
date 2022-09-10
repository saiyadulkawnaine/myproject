let MsBuyerUserModel = require('./MsBuyerUserModel');
class MsBuyerUserController {
	constructor(MsBuyerUserModel)
	{
		this.MsBuyerUserModel = MsBuyerUserModel;
		this.formId='buyeruserFrm';
		this.dataTable='#buyeruserTbl';
		this.route=msApp.baseUrl()+"/buyeruser"
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

		let formObj=msApp.get('buyeruserFrm');
		let i=1;
		$.each($('#buyeruserTbl').datagrid('getChecked'), function (idx, val) {
				formObj['buyer_id['+i+']']=val.id
				
			i++;
		});
		this.MsBuyerUserModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var user_id=$('#userFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/buyeruser/create?user_id="+user_id);
				data.then(function (response) {
				$('#buyeruserTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Buyer',width:100},
				]],
				}).datagrid('enableFilter');
				
				$('#buyerusersavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Buyer',width:100},
				{field:'action',title:'',width:60,formatter:MsBuyerUser.formatDetail},
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
		this.MsBuyerUserModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerUserModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#buyernatureTbl').datagrid('reload');
		MsBuyerUser.create()
		//msApp.resetForm('buyernatureFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBuyerUserModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBuyerUser.delete(event,'+row.buyer_user_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsBuyerUser=new MsBuyerUserController(new MsBuyerUserModel());

