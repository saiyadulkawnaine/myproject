let MsBankAccountModel = require('./MsBankAccountModel');
class MsBankAccountController {
	constructor(MsBankAccountModel)
	{
		this.MsBankAccountModel = MsBankAccountModel;
		this.formId='bankaccountFrm';
		this.dataTable='#bankaccountTbl';
		this.route=msApp.baseUrl()+"/bankaccount"
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
			this.MsBankAccountModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBankAccountModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#bankaccountFrm  [name=bank_branch_id]').val($('#bankbranchFrm  [name=id]').val());
		$('#bankaccountFrm [id="account_type_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBankAccountModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBankAccountModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsBankAccount.resetForm();
		MsBankAccount.get($('#bankbranchFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsBankAccountModel.get(index,row);
		data.then(function(response){
			$('#bankaccountFrm [id="account_type_id"]').combobox('setValue', response.data.fromData.account_type_id);
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(bank_branch_id){
		let data= axios.get(this.route+"?bank_branch_id="+bank_branch_id);
		data.then(function (response) {
			$('#bankaccountTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBankAccount.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsBankAccount=new MsBankAccountController(new MsBankAccountModel());
MsBankAccount.showGrid([]);