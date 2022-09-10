//require('./../../jquery.easyui.min.js');
let MsLocalExpProRlzModel = require('./MsLocalExpProRlzModel');
require('./../../datagrid-filter.js');
class MsLocalExpProRlzController {
	constructor(MsLocalExpProRlzModel)
	{
		this.MsLocalExpProRlzModel = MsLocalExpProRlzModel;
		this.formId='localexpprorlzFrm';
		this.dataTable='#localexpprorlzTbl';
		this.route=msApp.baseUrl()+"/localexpprorlz"
	}

	submit()
	{	
        let bank_ref_amount=$('#localexpprorlzFrm [name=bank_ref_amount]').val();
        let total_doc_value=$('#total_doc_value').val();
        if( total_doc_value*1 > bank_ref_amount*1){
        	alert('Submission amount '+bank_ref_amount+' not equel to realization amount '+total_doc_value);
        	return;
        }

		let formObj=this.getdata();

		if(formObj.id){
			this.MsLocalExpProRlzModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpProRlzModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	getdata(){
		let formObj=msApp.get('localexpprorlzFrm');
		let i=1;
		$.each($('#frdeTbl').datagrid('getRows'), function (idx, val) {
			$('#frdeTbl').datagrid('endEdit', idx);
				formObj['commercial_head_id['+i+']']=val.commercial_head_id;
				formObj['doc_value['+i+']']=val.doc_value;
				formObj['exch_rate['+i+']']=val.exch_rate;
				formObj['dom_value['+i+']']=val.dom_value;
				
			i++;
		});
        let j=1;
		$.each($('#framTbl').datagrid('getRows'), function (idx, val) {
			$('#framTbl').datagrid('endEdit', idx);
				formObj['a_commercial_head_id['+j+']']=val.commercial_head_id;
				formObj['a_doc_value['+j+']']=val.doc_value;
				formObj['a_exch_rate['+j+']']=val.exch_rate;
				formObj['a_dom_value['+j+']']=val.dom_value;
				formObj['a_ac_loan_no['+j+']']=val.ac_loan_no;
				
			j++;
		});
		return formObj;
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpProRlzModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpProRlzModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexpprorlzTbl').datagrid('reload');
		msApp.resetForm('localexpprorlzFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let data=this.MsLocalExpProRlzModel.get(index,row);	
		data.then(function (response) {
			MsLocalExpProRlzDeduct.showGrid(response.data);
			MsLocalExpProRlzAmount.showGrid(response.data);
		})
		.catch(function (error) {
		console.log(error);
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
		return '<a href="javascript:void(0)"  onClick="MsLocalExpProRlz.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openDocSubBankWindow(){
		$('#localexpdocsubbankwindow').window('open');
	}

	getDocSubParams(){
		let params = {};
		params.bank_ref_bill_no = $('#localexpdocsubsearchFrm [name="bank_ref_bill_no"]').val();
		params.date_from = $('#localexpdocsubsearchFrm [name="date_from"]').val();
		params.date_to = $('#localexpdocsubsearchFrm [name="date_to"]').val();
		return params;
	}

	searchLocalDocSubBank(){
		let params=this.getDocSubParams();
		let dsb=axios.get(this.route+"/importdocsubbank/",{params})
		.then(function(response){
			$('#localexpdocsubbanksearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	showDocSubmBankGrid(data){
		
		let self = this;
		$('#localexpdocsubbanksearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#localexpprorlzFrm [name=id]').val(row.id);
				$('#localexpprorlzFrm [name=local_exp_doc_sub_bank_id]').val(row.local_exp_doc_sub_bank_id);
				$('#localexpprorlzFrm [name=bank_ref_bill_no]').val(row.bank_ref_bill_no);
				$('#localexpprorlzFrm [name=bank_ref_date]').val(row.bank_ref_date);
				$('#localexpprorlzFrm [name=bank_ref_amount]').val(row.bank_ref_amount);
				$('#localexpprorlzFrm [name=negotiated_amount]').val(row.negotiated_amount);
				$('#localexpprorlzFrm [name=currency_id]').val(row.currency_id);
				$('#localexpprorlzFrm [name=local_lc_no]').val(row.local_lc_no);
				$('#localexpprorlzFrm [name=buyer_id]').val(row.buyer_id);
				$('#localexpprorlzFrm [name=beneficiary_id]').val(row.beneficiary_id); 
				$('#localexpprorlzFrm [name=realization_date]').val(row.realization_date);
				$('#localexpprorlzFrm [name=remarks]').val(row.remarks);
				$('#localexpdocsubbankwindow').window('close');
				//MsLocalExpProRlzDeduct.showGrid([]);
				//MsLocalExpProRlzAmount.showGrid([]);
				if(row.id){
					self.edit(index,row);
				}else{
					MsLocalExpProRlz.loadSubPage(row.local_exp_doc_sub_bank_id);
				}	
			}
		}).datagrid('enableFilter');
	}
	

	loadSubPage(local_exp_doc_sub_bank_id){
		var d= axios.get(msApp.baseUrl()+"/localexpprorlz/gethead?local_exp_doc_sub_bank_id="+local_exp_doc_sub_bank_id)
		.then(function (response) {
			MsLocalExpProRlzDeduct.showGrid(response.data);
			MsLocalExpProRlzAmount.showGrid(response.data);
		})
		.catch(function (error) {
		console.log(error);
		});
	}
	



}
window.MsLocalExpProRlz=new MsLocalExpProRlzController(new MsLocalExpProRlzModel());
MsLocalExpProRlz.showGrid();
MsLocalExpProRlz.showDocSubmBankGrid([]);


$('#comLocalExpRlzTabs').tabs({
	onSelect:function(title,index){
	let local_exp_pro_rlz_id = $('#localexpprorlz  [name=id]').val();

	var data={};
	data.local_exp_pro_rlz_id=local_exp_pro_rlz_id;

	 if(index==1){
		if(local_exp_pro_rlz_id===''){
		$('#comLocalExpRlzTabs').tabs('select',0);
			msApp.showError('Select an ExpPI First',0);
		return;
		}
		$('#localexpprorlzdecuctFrm  [name=local_exp_pro_rlz_id]').val(local_exp_pro_rlz_id)
		MsLocalExpProRlz.showGrid();
	 }
	 if(index==2){
		if(local_exp_pro_rlz_id===''){
			$('#comLocalExpRlzTabs').tabs('select',0);
			msApp.showError('Select a Local ExpPI First',0);
			return;
		}
		$('#localexpprorlzamountFrm  [name=local_exp_pro_rlz_id]').val(local_exp_pro_rlz_id)
		MsLocalExpProRlzAmount.showGrid(local_exp_pro_rlz_id);
	 }
}
});
