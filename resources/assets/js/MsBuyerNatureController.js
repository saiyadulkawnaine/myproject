let MsBuyerNatureModel = require('./MsBuyerNatureModel');
class MsBuyerNatureController {
	constructor(MsBuyerNatureModel)
	{
		this.MsBuyerNatureModel = MsBuyerNatureModel;
		this.formId='buyernatureFrm';
		this.dataTable='#buyernatureTbl';
		this.route=msApp.baseUrl()+"/buyernature"
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

		let formObj=msApp.get('buyernatureFrm');
		let i=1;
		$.each($('#buyernatureTbl').datagrid('getChecked'), function (idx, val) {
				formObj['contact_nature_id['+i+']']=val.id
				
			i++;
		});
		this.MsBuyerNatureModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var buyer_id=$('#buyerFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/buyernature/create?buyer_id="+buyer_id);
				data.then(function (response) {
				$('#buyernatureTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Nature',width:100},
				]],
				}).datagrid('enableFilter');
				
				$('#buyernaturesavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Nature',width:100},
				{field:'action',title:'',width:60,formatter:MsBuyerNature.formatDetail},
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
		this.MsBuyerNatureModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		alert(id)
		this.MsBuyerNatureModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#buyernatureTbl').datagrid('reload');
		MsBuyerNature.create()
		//msApp.resetForm('buyernatureFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBuyerNatureModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBuyerNature.delete(event,'+row.buyer_nature_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsBuyerNature=new MsBuyerNatureController(new MsBuyerNatureModel());
//MsBuyerNature.showGrid();
