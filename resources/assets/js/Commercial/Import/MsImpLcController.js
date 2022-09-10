//require('./../../jquery.easyui.min.js');
let MsImpLcModel = require('./MsImpLcModel');
require('./../../datagrid-filter.js');
class MsImpLcController {
	constructor(MsImpLcModel)
	{
		this.MsImpLcModel = MsImpLcModel;
		this.formId='implcFrm';
		this.dataTable='#implcTbl';
		this.route=msApp.baseUrl()+"/implc"
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
			this.MsImpLcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpLcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#implcFrm [id="supplier_id"]').combobox('setValue', '');
		$('#implcFrm [id="lc_to_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpLcModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpLcModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#implcTbl').datagrid('reload');
		msApp.resetForm('implcFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let importLc=this.MsImpLcModel.get(index,row);
		importLc.then(function(response){
			$('#implcFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#implcFrm [id="lc_to_id"]').combobox('setValue', response.data.fromData.lc_to_id);
		})	

	}

	showGrid(){
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var lc_amount=0;
				for(var i=0; i<data.rows.length; i++){
					lc_amount+=data.rows[i]['lc_amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
					lc_amount: lc_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')	}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsImpLc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateAmount(){
		let qty;
		let rate;
		qty=$('#imporderFrm [name=qty]').val();
		rate=$('#imporderFrm [name=rate]').val();
		let amount=qty*rate;
		$('#imporderFrm [name=amount]').val(amount);
	}

	setExpiryDate(){
		let tenor=10*1;
		if(!tenor){
		   tenor=0;
		}
		let last_delivery_date=new Date($('#last_delivery_date').val());
		let expiry_date= msApp.addDays(last_delivery_date,tenor);
		$('#expiry_date').val(expiry_date);
   }

   latter()
   {
		var id= $('#implcFrm  [name=id]').val();
		if(id==""){
		alert("Select a LC");
		return;
		}
		window.open(this.route+"/latter?id="+id);
   }

   	creditletter()
   	{
		var id= $('#implcFrm  [name=id]').val();
		if(id==""){
		alert("Select a LC");
		return;
		}
		window.open(this.route+"/creditlatter?id="+id);
   	}

   	setBankAccount(company_id,issuing_bank_branch_id){
   		let companyid=$('#implcFrm  [name=company_id]').val();
		let issuingbankbranchid=$('#implcFrm  [name=issuing_bank_branch_id]').val();
		$('#implcFrm  [name=bank_account_id]').val('');
		$('#implcFrm  [name=commercial_head_name]').val('');
		MsImpLc.getDebitAccount(companyid,issuingbankbranchid);
	}

	getDebitAccount (company_id,issuing_bank_branch_id){
		let data={};
		data.company_id=company_id;
		data.issuing_bank_branch_id=issuing_bank_branch_id;
		let cdaccount=msApp.getJson('bankaccount/getdebitaccount',data)
		.then(function (response) {
			    $('select[name="debit_ac_id"]').empty();
				$('select[name="debit_ac_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
					$('select[name="debit_ac_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
		})
		.catch(function (error) {
			console.log(error);
		});
		return cdaccount;
	}

   	implcBankWindowOpen() {
		$('#implcopenimplcbankaccountWindow').window('open');
	}

	implcSearchBankAccount() {
		let params = {};
		params.issuing_bank_branch_id = $('#implcFrm [name=issuing_bank_branch_id]').val();
		params.lc_type_id = $('#implcFrm [name=lc_type_id]').val();
		params.company_id = $('#implcFrm [name=company_id]').val();
		params.branch_name = $('#implcbankaccountsearchFrm [name=branch_name]').val();
		params.account_no = $('#implcbankaccountsearchFrm [name=account_no]').val();
		if(params.issuing_bank_branch_id=='' && params.company_id==''){
			alert('Select Issueing Bank and Company First');
			return;
		}
		let data = axios.get(this.route + "/getimplcbankaccount", {params});
		data.then(function (response) {
			$('#implcbankaccountsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	implcShowGridAccount(data) {
		$('#implcbankaccountsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				$('#implcFrm [name=bank_account_id]').val(row.id);
				$('#implcFrm [name=commercial_head_name]').val(row.commercial_head_name);
				$('#implcopenimplcbankaccountWindow').window('close');
				$('#implcbankaccountsearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsImpLc=new MsImpLcController(new MsImpLcModel());
MsImpLc.showGrid();
MsImpLc.implcShowGridAccount([]);

 $('#comimportlctabs').tabs({
	onSelect:function(title,index){
	 let imp_lc_id = $('#implcFrm  [name=id]').val();

	 var data={};
	  data.imp_lc_id=imp_lc_id;

		if(index==1){
			if(imp_lc_id===''){
				$('#comimportlctabs').tabs('select',0);
				msApp.showError('Select an Import LC First',0);
				return;
			}
			$('#implcpoFrm  [name=imp_lc_id]').val(imp_lc_id);
			MsImpLcPo.showGrid(imp_lc_id);
		}
		if(index==2){
			let pos=$('#implcpoTbl').datagrid('getRows')
			if(imp_lc_id===''){
				$('#comimportlctabs').tabs('select',0);
				msApp.showError('Select an Import LC First',0);
				return;
			}
			if(pos.length==0){
				$('#comimportlctabs').tabs('select',0);
				msApp.showError('Please Tag Po First',0);
				return;
			}
			$('#impbackedexplcscFrm  [name=imp_lc_id]').val(imp_lc_id);
			MsImpBackedExpLcSc.showGrid(imp_lc_id);
		}
		if(index==3){
			if(imp_lc_id===''){
				$('#comimportlctabs').tabs('select',0);
				msApp.showError('Select an Import LC First',0);
				return;
			}
			$('#impshippingmarkFrm  [name=imp_lc_id]').val(imp_lc_id);
			MsImpShippingMark.showGrid(imp_lc_id);
		}
		if(index==4){
			if(imp_lc_id===''){
				$('#comimportlctabs').tabs('select',0);
				msApp.showError('Select an Import LC First',0);
				return;
			}
			$('#impbankchargeFrm  [name=imp_lc_id]').val(imp_lc_id);
			MsImpBankCharge.showGrid(imp_lc_id);
		}
		if(index==5){
			if(imp_lc_id===''){
				$('#comimportlctabs').tabs('select',0);
				msApp.showError('Select an Import LC First',0);
				return;
			}
			$('#implcfileFrm  [name=imp_lc_id]').val(imp_lc_id);
			MsImpLcFile.showGrid(imp_lc_id);
		}
	}
}); 
