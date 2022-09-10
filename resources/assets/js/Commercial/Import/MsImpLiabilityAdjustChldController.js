
let MsImpLiabilityAdjustChldModel = require('./MsImpLiabilityAdjustChldModel');
class MsImpLiabilityAdjustChldController {
	constructor(MsImpLiabilityAdjustChldModel)
	{
		this.MsImpLiabilityAdjustChldModel = MsImpLiabilityAdjustChldModel;
		this.formId='impliabilityadjustchldFrm';
		this.dataTable='#impliabilityadjustchldTbl';
		this.route=msApp.baseUrl()+"/impliabilityadjustchld"
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
			this.MsImpLiabilityAdjustChldModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpLiabilityAdjustChldModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#impliabilityadjustchldFrm [name=imp_liability_adjust_id]').val($('#impliabilityadjustFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpLiabilityAdjustChldModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpLiabilityAdjustChldModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#impliabilityadjustchldTbl').datagrid('reload');
		msApp.resetForm('impliabilityadjustchldFrm');
		$('#impliabilityadjustchldFrm [name=imp_liability_adjust_id]').val($('#impliabilityadjustFrm  [name=id]').val());
		
	
	}

	// getData(){
	// 	let params={};
	// 	params.imp_liability_adjust_id=$('#impliabilityadjustFrm [name=id]').val();
	// 	axios.get(MsImpLiabilityAdjustChld.route,{params})
	// 	.then(function (response) {
	// 		$('#impliabilityadjustchldTbl').datagrid('loadData', response.data);
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsImpLiabilityAdjustChldModel.get(index,row);
	}

	showGrid(imp_liability_adjust_id){
		let self=this;
		var data={};
		data.imp_liability_adjust_id=imp_liability_adjust_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
            queryParams:data,
			url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['dom_currency'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ dom_currency: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
				]);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsImpLiabilityAdjustChld.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculateAmount(){
		let amount;
		let exch_rate;
		amount=$('#impliabilityadjustchldFrm [name=amount]').val();
		exch_rate=$('#impliabilityadjustchldFrm [name=exch_rate]').val();
		let dom_currency=amount*exch_rate;
		$('#impliabilityadjustchldFrm [name=dom_currency]').val(dom_currency);
	}

	bankWindowOpen() {
		$('#openadjbankaccountWindow').window('open');
	}

	getParams(){
		let params = {};
		params.branch_name = $('#adjbankaccountsearchFrm [name=branch_name]').val();
		params.account_no = $('#adjbankaccountsearchFrm [name=account_no]').val();
		params.imp_doc_accept_id = $('#impliabilityadjustFrm [name=imp_doc_accept_id]').val();
		return params;
	}

	searchAdjBankAccount() {
		let params = this.getParams();
		let data = axios.get(msApp.baseUrl()+"/impliabilityadjustchld/impgetbankaccount", { params });
		data.then(function (response) {
			$('#adjbankaccountsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showAdjBankAccountGrid(data){
		let self=this;
		$('#adjbankaccountsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#impliabilityadjustchldFrm [name=bank_account_id]').val(row.id);
				$('#impliabilityadjustchldFrm [name=commercial_head_name]').val(row.commercial_head_name);
				$('#openadjbankaccountWindow').window('close');
				$('#adjbankaccountsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	
}
window.MsImpLiabilityAdjustChld=new MsImpLiabilityAdjustChldController(new MsImpLiabilityAdjustChldModel());
MsImpLiabilityAdjustChld.showAdjBankAccountGrid([]);