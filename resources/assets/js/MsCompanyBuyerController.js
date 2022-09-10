let MsCompanyBuyerModel = require('./MsCompanyBuyerModel');
class MsCompanyBuyerController {
	constructor(MsCompanyBuyerModel)
	{
		this.MsCompanyBuyerModel = MsCompanyBuyerModel;
		this.formId='companybuyerFrm';
		this.dataTable='#companybuyerTbl';
		this.route=msApp.baseUrl()+"/companybuyer"
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

		let formObj=msApp.get('companybuyerFrm');
		let i=1;
		$.each($('#companybuyerTbl').datagrid('getChecked'), function (idx, val) {
				formObj['company_id['+i+']']=val.id
				
			i++;
		});
		this.MsCompanyBuyerModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var buyer_id=$('#buyerFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/companybuyer/create?buyer_id="+buyer_id);
		data.then(function (response) {
		$('#companybuyerTbl').datagrid({
		checkbox:true,
		rownumbers:true,
		data: response.data.unsaved,
		
		columns:[[
		{field:'ck',checkbox:true,width:40},
		{field:'name',title:'Company',width:100},
		]],
		});
		
		$('#companybuyersavedTbl').datagrid({
		rownumbers:true,
		data: response.data.saved,
		columns:[[
		{field:'name',title:'Company',width:100},
		{field:'action',title:'',width:60,formatter:MsCompanyBuyer.formatDetail},
		]],
		});
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
		this.MsCompanyBuyerModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		alert(id)
		this.MsCompanyBuyerModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#buyernatureTbl').datagrid('reload');
		MsCompanyBuyer.create()
		//msApp.resetForm('buyernatureFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCompanyBuyerModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsCompanyBuyer.delete(event,'+row.company_buyer_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCompanyBuyer=new MsCompanyBuyerController(new MsCompanyBuyerModel());

