//require('./../../jquery.easyui.min.js');
let MsImpDocAcceptModel = require('./MsImpDocAcceptModel');
require('./../../datagrid-filter.js');
class MsImpDocAcceptController {
	constructor(MsImpDocAcceptModel)
	{
		this.MsImpDocAcceptModel = MsImpDocAcceptModel;
		this.formId='impdocacceptFrm';
		this.dataTable='#impdocacceptTbl';
		this.route=msApp.baseUrl()+"/impdocaccept"
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
			this.MsImpDocAcceptModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpDocAcceptModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpDocAcceptModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpDocAcceptModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#impdocacceptTbl').datagrid('reload');
		msApp.resetForm('impdocacceptFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsImpDocAcceptModel.get(index,row);	

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
				var doc_value=0;
				for(var i=0; i<data.rows.length; i++){
					doc_value+=data.rows[i]['doc_value'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				  { 
					doc_value: doc_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				  }
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsImpDocAccept.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openImpLcWndow(){
		$('#impLcWindow').window('open');
	}
	getParams(){
		let params = {};
		params.company_id=$('#implcFrm  [name=company_id]').val();
      	params.supplier_id=$('#implcFrm  [name=supplier_id]').val();
      	params.lc_type_id=$('#implcFrm  [name=lc_type_id]').val();
      	params.issuing_bank_branch_id=$('#implcFrm  [name=issuing_bank_branch_id]').val();
      	return params;
	}
	searchDocAcceptImpLc(){
		let params=MsImpDocAccept.getParams();
		let d=axios.get(this.route+"/getImportLc",{params})
		.then(function(response){
			$('#implcsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}
	showImpLcGrid(data){ 
      let self=this;
		$('#implcsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#impdocacceptFrm [name=imp_lc_id]').val(row.id);
				var lc_no_i=$('#implcFrm [name=lc_no_i]').val(lc_no_i);
				var lc_no_ii=$('#implcFrm [name=lc_no_ii]').val(lc_no_ii);
				var lc_no_iii=$('#implcFrm [name=lc_no_iii]').val(lc_no_iii);
				var lc_no_iv=$('#implcFrm [name=lc_no_iv]').val(lc_no_iv);
				var lc_no=lc_no_i+lc_no_ii+lc_no_iii+lc_no_iv;
				$('#impdocacceptFrm [name=lc_no]').val(row.lc_no);
				$('#impdocacceptFrm [name=pay_term_id]').val(row.pay_term_id);
				$('#impdocacceptFrm [name=supplier_name]').val(row.supplier_name);
				$('#impdocacceptFrm [name=company_id]').val(row.company_id);
				$('#impdocacceptFrm [name=company_name]').val(row.company_name);
				$('#impdocacceptFrm [name=lc_type_id]').val(row.lc_type_id);
				$('#impdocacceptFrm [name=issuing_bank_branch_id]').val(row.issuing_bank_branch_id);
				$('#impdocacceptFrm [name=issuing_bank_branch]').val(row.issuing_bank_branch);
				$('#impdocacceptFrm [name=tenor]').val(row.tenor);
				$('#implcsearchTbl').datagrid('loadData',[]);
				$('#impLcWindow').window('close')
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	mletter()
   {
		var id= $('#impdocacceptFrm  [name=id]').val();
		if(id==""){
		alert("Select a Document");
		return;
		}
		window.open(this.route+"/mlatter?id="+id);
    }

    bankWindowOpen() {
		$('#openbankaccountWindow').window('open');
	}

	searchbankAccount() {
		let params = {};
		params.issuing_bank_branch_id = $('#impdocacceptFrm [name=issuing_bank_branch_id]').val();
		params.company_id=$('#impdocacceptFrm [name=company_id]').val()
		params.branch_name = $('#bankaccountsearchFrm [name=branch_name]').val();
		params.account_no = $('#bankaccountsearchFrm [name=account_no]').val();
		let data = axios.get(this.route + "/getbankaccount", { params });
		data.then(function (response) {
			$("#bankaccountsearchTbl").datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showGridBankAccount(data) {
		$('#bankaccountsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				$('#impdocacceptFrm [name=bank_account_id]').val(row.id);
				$('#impdocacceptFrm [name=commercial_head_name]').val(row.commercial_head_name);
				$('#openbankaccountWindow').window('close');
				$('#bankaccountsearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsImpDocAccept=new MsImpDocAcceptController(new MsImpDocAcceptModel());
MsImpDocAccept.showGrid();
MsImpDocAccept.showImpLcGrid([]);
MsImpDocAccept.showGridBankAccount([]);

$('#impshippindoctabs').tabs({
	onSelect:function(title,index){
	 let imp_doc_accept_id = $('#impdocacceptFrm  [name=id]').val();

	 var data={};
	  data.imp_doc_accept_id=imp_doc_accept_id;

		if(index==1){
			if(imp_doc_accept_id===''){
				$('#impshippindoctabs').tabs('select',0);
				msApp.showError('Select Import Shipping Document Acceptance First',0);
				return;
			}
			$('#impacccomdetailFrm  [name=imp_doc_accept_id]').val(imp_doc_accept_id);
			MsImpAccComDetail.create(imp_doc_accept_id);
		}
		if(index==2){
			if(imp_doc_accept_id===''){
				$('#impshippindoctabs').tabs('select',0);
				msApp.showError('Select Import Shipping Document Acceptance First',0);
				return;
			}
			$('#impdocacceptmaturityFrm  [name=imp_doc_accept_id]').val(imp_doc_accept_id);
			MsImpDocAcceptMaturity.showGrid(imp_doc_accept_id);
		}
	
	}

});