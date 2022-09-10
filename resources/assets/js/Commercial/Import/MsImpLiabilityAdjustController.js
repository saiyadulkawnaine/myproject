let MsImpLiabilityAdjustModel = require('./MsImpLiabilityAdjustModel');
require('./../../datagrid-filter.js');
class MsImpLiabilityAdjustController {
	constructor(MsImpLiabilityAdjustModel)
	{
		this.MsImpLiabilityAdjustModel = MsImpLiabilityAdjustModel;
		this.formId='impliabilityadjustFrm';
		this.dataTable='#impliabilityadjustTbl';
		this.route=msApp.baseUrl()+"/impliabilityadjust"
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
			this.MsImpLiabilityAdjustModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpLiabilityAdjustModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpLiabilityAdjustModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpLiabilityAdjustModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#impliabilityadjustTbl').datagrid('reload');
		msApp.resetForm('impliabilityadjustFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsImpLiabilityAdjustModel.get(index,row);
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
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsImpLiabilityAdjust.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openBankRefWndow(){
		$('#bankRefWindow').window('open');
	}

	getParams(){
		let params={};
		params.invoice_no=$('#impdocacceptsearchFrm  [name=invoice_no]').val();
		params.invoice_date=$('#impdocacceptsearchFrm  [name=invoice_date]').val();
		params.shipment_date=$('#impdocacceptsearchFrm  [name=shipment_date]').val();
		return params;
	}
	searchLiabilityAdjustGrid(){
		let params = this.getParams();
		let d = axios.get(this.route+"/GetImpDocAccept",{params})
		.then(function(response){
			$('#impacceptsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
		return d;
	}

	showLiabilityAdjustGrid(data)
	{
		let self=this;
		$('#impacceptsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#impliabilityadjustFrm [name=imp_doc_accept_id]').val(row.imp_doc_accept_id);
				$('#impliabilityadjustFrm [name=bank_ref]').val(row.bank_ref);
				$('#impliabilityadjustFrm [name=lc_no]').val(row.lc_no);
				$('#impliabilityadjustFrm [name=supplier_name]').val(row.supplier_name);
				$('#impliabilityadjustFrm [name=menu_id]').val(row.menu_id);
				$('#impliabilityadjustFrm [name=issuing_bank_branch_id]').val(row.issuing_bank_branch_id);
				$('#impliabilityadjustFrm [name=acceptance_value]').val(row.acceptance_value);
				$('#bankRefWindow').window('close');
				$('#impacceptsearchTbl').datagrid('loadData',[]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsImpLiabilityAdjust=new MsImpLiabilityAdjustController(new MsImpLiabilityAdjustModel());
MsImpLiabilityAdjust.showGrid();
MsImpLiabilityAdjust.showLiabilityAdjustGrid([]);

$('#impliabitytabs').tabs({
	onSelect:function(title,index){
	let imp_liability_adjust_id = $('#impliabilityadjustFrm  [name=id]').val();

	var data={};
	data.imp_liability_adjust_id=imp_liability_adjust_id;

		if(index==1){
			if(imp_liability_adjust_id===''){
				$('#impliabitytabs').tabs('select',0);
				msApp.showError('Select Import a Liability Adjustment First',0);
				return;
			}
			$('#impliabilityadjustchldFrm  [name=imp_liability_adjust_id]').val(imp_liability_adjust_id);
			MsImpLiabilityAdjustChld.showGrid(imp_liability_adjust_id);
		}
	
	}

});