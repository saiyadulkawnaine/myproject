let MsCompanyProfitcenterModel = require('./MsCompanyProfitcenterModel');
class MsCompanyProfitcenterController {
	constructor(MsCompanyProfitcenterModel)
	{
		this.MsCompanyProfitcenterModel = MsCompanyProfitcenterModel;
		this.formId='companyprofitcenterFrm';
		this.dataTable='#companyprofitcenterTbl';
		this.route=msApp.baseUrl()+"/companyprofitcenter"
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

		let formObj=msApp.get('companyprofitcenterFrm');
		let i=1;
		$.each($('#companyprofitcenterTbl').datagrid('getChecked'), function (idx, val) {
				formObj['company_id['+i+']']=val.id
				
			i++;
		});
		this.MsCompanyProfitcenterModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var profitcenter_id=$('#profitcenterFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/companyprofitcenter/create?profitcenter_id="+profitcenter_id);
				data.then(function (response) {
				$('#companyprofitcenterTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Company',width:100},
				]],
				});
				
				$('#companyprofitcentersavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Company',width:100},
				{field:'action',title:'',width:60,formatter:MsCompanyProfitcenter.formatDetail},
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
		this.MsCompanyProfitcenterModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		alert(id)
		this.MsCompanyProfitcenterModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsCompanyProfitcenter.create()
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCompanyProfitcenterModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsCompanyProfitcenter.delete(event,'+row.company_profitcenter_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCompanyProfitcenter=new MsCompanyProfitcenterController(new MsCompanyProfitcenterModel());

