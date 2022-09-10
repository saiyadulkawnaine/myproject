//require('./../../jquery.easyui.min.js');
let MsExpPreCreditModel = require('./MsExpPreCreditModel');
require('./../../datagrid-filter.js');
class MsExpPreCreditController {
	constructor(MsExpPreCreditModel)
	{
		this.MsExpPreCreditModel = MsExpPreCreditModel;
		this.formId='expprecreditFrm';
		this.dataTable='#expprecreditTbl';
		this.route=msApp.baseUrl()+"/expprecredit"
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
			this.MsExpPreCreditModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpPreCreditModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#expprecreditFrm [id="commercial_head_id"]').combobox('setValue', '');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpPreCreditModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpPreCreditModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expprecreditTbl').datagrid('reload');
		msApp.resetForm('expprecreditFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let data=this.MsExpPreCreditModel.get(index,row);	
		data.then(function(response){
			$('#expprecreditFrm [id="commercial_head_id"]').combobox('setValue', response.data.fromData.commercial_head_id);
			//MsExpPreCredit.getCompany(response.data.fromData.bank_account_id);
			//MsExpPreCredit.getCompany(response.data.fromData.commercial_head_name);
		}).catch(function(error){
			console.log(errors)
		});

	}

	showGrid(){
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

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpPreCredit.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	setMaturityDate(){
		let tenor=$('#tenor').val()*1;
         if(!tenor){
            tenor=0;
         }
         tenor=tenor-1;
         let cr_date=new Date($('#cr_date').val());
         let maturity_date= msApp.addDays(cr_date,tenor);
         $('#maturity_date').val(maturity_date);
	}

	getpackingcredit()
	{
		var id= $('#expprecreditFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/getpc?id="+id);
	}

	/*getCompany(company_id){
		if (company_id) {
			$('#expprecreditFrm  [name=bank_account_id]').val('');
			$('#expprecreditFrm  [name=commercial_head_name]').val('');
		}
	}*/
	setBankAccount(company_id){
		$('#expprecreditFrm  [name=bank_account_id]').val('');
		$('#expprecreditFrm  [name=commercial_head_name]').val('');
	}

	bankaccountWindowOpen(){
		$('#openbankaccountWindow').window('open');
	}

	searchbankAccount()
	{
		let company_id=$('#expprecreditFrm  [name=company_id]').val();
		let account_type_id=$('#bankaccountsearchFrm  [name=account_type_id]').val();
		let account_no=$('#bankaccountsearchFrm  [name=account_no]').val();
		let data= axios.get(this.route+"/getbankaccount?account_type_id="+account_type_id+"&account_no="+account_no+"&company_id="+company_id);
		data.then(function (response) {
			$('#bankaccountsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridBankAccount(data){
		$('#bankaccountsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#expprecreditFrm [name=bank_account_id]').val(row.id);
				$('#expprecreditFrm [name=commercial_head_name]').val(row.commercial_head_name);
				$('#openbankaccountWindow').window('close');
				$('#bankaccountsearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsExpPreCredit=new MsExpPreCreditController(new MsExpPreCreditModel());
MsExpPreCredit.showGrid();
MsExpPreCredit.showGridBankAccount([]);

 $('#comexpcredittabs').tabs({
	onSelect:function(title,index){
	 let exp_pre_credit_id = $('#expprecreditFrm  [name=id]').val();
	 var data={};
	 data.exp_pre_credit_id=exp_pre_credit_id;
	 if(index==1){
		 if(exp_pre_credit_id===''){
			 $('#comexpcredittabs').tabs('select',0);
			 msApp.showError('Select Pre Export Credit First',0);
			 return;
		  }
		 $('#expprecreditlcscFrm  [name=exp_pre_credit_id]').val(exp_pre_credit_id);
		MsExpPreCreditLcSc.showGrid(exp_pre_credit_id);
	 }
}
}); 
