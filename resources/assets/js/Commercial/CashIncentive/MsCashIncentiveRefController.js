let MsCashIncentiveRefModel = require('./MsCashIncentiveRefModel');
require('./../../datagrid-filter.js');
class MsCashIncentiveRefController {
	constructor(MsCashIncentiveRefModel)
	{
		this.MsCashIncentiveRefModel = MsCashIncentiveRefModel;
		this.formId='cashincentiverefFrm';
		this.dataTable='#cashincentiverefTbl';
		this.route=msApp.baseUrl()+"/cashincentiveref"
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
			this.MsCashIncentiveRefModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCashIncentiveRefModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCashIncentiveRefModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCashIncentiveRefModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cashincentiverefTbl').datagrid('reload');
		msApp.resetForm('cashincentiverefFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsCashIncentiveRefModel.get(index,row);
		
	}

	showGrid(){
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveRef.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	
	openCashIncentiveLcScWindow(){
		$('#openCashIncentivelcscWindow').window('open');
	}
	getParams(){
		let params = {};
		params.lc_sc_no = $('#cashincentivelcsearchFrm [name="lc_sc_no"]').val();
		params.lc_sc_date = $('#cashincentivelcsearchFrm [name="lc_sc_date"]').val();
		params.beneficiary_id = $('#cashincentivelcsearchFrm [name="beneficiary_id"]').val();
		return params;
	}
	searchIncentiveLcGrid(){
		let params = this.getParams();
		let d = axios.get(this.route+"/explccashref",{params})
		.then(function(response){
			$('#cashincentivelcsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
		
	}
	showIncentiveLcGrid(data){
		let self = this;
		$('#cashincentivelcsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#cashincentiverefFrm [name=exp_lc_sc_id]').val(row.id);
				$('#cashincentiverefFrm [name=lc_sc_no]').val(row.lc_sc_no);
				$('#cashincentiverefFrm [name=lc_sc_value]').val(row.lc_sc_value);
				$('#cashincentiverefFrm [name=file_no]').val(row.file_no);
				$('#cashincentiverefFrm [name=currency_id]').val(row.currency_id);
				$('#cashincentiverefFrm [name=buyer_id]').val(row.buyer_id);
				$('#cashincentiverefFrm [name=company_id]').val(row.beneficiary_id);
				$('#cashincentiverefFrm [name=company_name]').val(row.company_name);
				$('#cashincentiverefFrm [name=exporter_branch_name]').val(row.exporter_branch_name);
				$('#openCashIncentivelcscWindow').window('close');
				$('#cashincentivelcsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}



	getCop(){
		var id= $('#cashincentiverefFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/getcop?id="+id);
	}

	forwardLetter(){
		var id= $('#cashincentiverefFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/forwardletter?id="+id);
	}

	declareLetter(){
		var id= $('#cashincentiverefFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/getdeclare?id="+id);
	}

	
	khaForm(){
		var id= $('#cashincentiverefFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/getkhaform?id="+id);
	}

	netwgt(){
		var id= $('#cashincentiverefFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/getnetwgt?id="+id);
	}

	getBtbCertificate(){
		var id= $('#cashincentiverefFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/btbcertificate?id="+id);
	}

	undertaking(){
		var id= $('#cashincentiverefFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/undertaking?id="+id);
	}


}
window.MsCashIncentiveRef=new MsCashIncentiveRefController(new MsCashIncentiveRefModel());
MsCashIncentiveRef.showGrid();
MsCashIncentiveRef.showIncentiveLcGrid([]);

 $('#cashincentivetabs').tabs({
	onSelect:function(title,index){
	let cash_incentive_ref_id = $('#cashincentiverefFrm  [name=id]').val();
	
	 var data={};
     data.cash_incentive_ref_id=cash_incentive_ref_id;
     //data.exporter_branch_name=exporter_branch_name;

	 if(index==1){
		 if(cash_incentive_ref_id===''){
			 $('#cashincentivetabs').tabs('select',0);
			 msApp.showError('Select a Cash Incentive Reference First',0);
			 return;
		  }
		$('#cashincentivedocprepFrm  [name=cash_incentive_ref_id]').val(cash_incentive_ref_id);
			let index=null;
			let row={};
			row.id=cash_incentive_ref_id;

		MsCashIncentiveDocPrep.edit(index,row);
		MsCashIncentiveDocPrep.showGrid(cash_incentive_ref_id);
	 }
	 if(index==2){
		if(cash_incentive_ref_id===''){
			$('#cashincentivetabs').tabs('select',0);
			msApp.showError('Select a Cash Incentive Reference First',0);
			return;
		 }
		$('#cashincentiveyarnbtblcFrm  [name=cash_incentive_ref_id]').val(cash_incentive_ref_id);
		MsCashIncentiveYarnBtbLc.showGrid(cash_incentive_ref_id);
	}
	 if(index==3){
		 if(cash_incentive_ref_id===''){
			 $('#cashincentivetabs').tabs('select',0);
			 msApp.showError('Select a Cash Incentive Reference First',0);
			 return;
		  }
		$('#cashincentiveclaimFrm  [name=cash_incentive_ref_id]').val(cash_incentive_ref_id);
		//let avg_rate = $('#cashincentiverefFrm  [name=avg_rate]').val();
		//$('#cashincentiveclaimFrm  [name=avg_rate]').val(avg_rate);
		MsCashIncentiveClaim.showGrid(cash_incentive_ref_id);
	 }
	 if(index==4){
		 if(cash_incentive_ref_id===''){
			$('#cashincentivetabs').tabs('select',0);
			msApp.showError('Select a Cash Incentive Reference First',0);
			return;
		}

		$('#cashincentiveloanFrm  [name=cash_incentive_ref_id]').val(cash_incentive_ref_id)
		let exporter_branch_name = $('#cashincentiverefFrm  [name=exporter_branch_name]').val();
		//alert(exporter_branch_name)
		$('#cashincentiveloanFrm  [name=exporter_branch_name]').val(exporter_branch_name)
		MsCashIncentiveLoan.getCash(cash_incentive_ref_id);
		MsCashIncentiveLoan.showGrid(cash_incentive_ref_id);
	 }
	 if(index==5){
		if(cash_incentive_ref_id===''){
			$('#cashincentivetabs').tabs('select',0);
			msApp.showError('Select a Cash Incentive Reference First',0);
			return;
		 }
	   	$('#cashincentivefileFrm  [name=cash_incentive_ref_id]').val(cash_incentive_ref_id);
		   let index=null;
			let row={};
			row.id=cash_incentive_ref_id;

		MsCashIncentiveFile.edit(index,row);
	   	MsCashIncentiveFile.showGrid(cash_incentive_ref_id);
		}
	}
}); 
