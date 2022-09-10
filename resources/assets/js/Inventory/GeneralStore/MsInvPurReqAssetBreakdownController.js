let MsInvPurReqAssetBreakdownModel = require('./MsInvPurReqAssetBreakdownModel');
class MsInvPurReqAssetBreakdownController {
	constructor(MsInvPurReqAssetBreakdownModel)
	{
		this.MsInvPurReqAssetBreakdownModel = MsInvPurReqAssetBreakdownModel;
		this.formId='invpurreqassetbreakdownFrm';
		this.dataTable='#invpurreqassetbreakdownTbl';
		this.route=msApp.baseUrl()+"/invpurreqassetbreakdown"
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
			this.MsInvPurReqAssetBreakdownModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvPurReqAssetBreakdownModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invpurreqassetbreakdownFrm [id="user_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvPurReqAssetBreakdownModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvPurReqAssetBreakdownModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invpurreqassetbreakdownTbl').datagrid('reload');
		msApp.resetForm('invpurreqassetbreakdownFrm');
		$('#invpurreqassetbreakdownFrm [name=inv_pur_req_id]').val($('#invpurreqFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvPurReqAssetBreakdownModel.get(index,row);
		
	}
	 showGrid(inv_pur_req_id)
	 {
		 let self=this;
		 var data={};
		 data.inv_pur_req_id=inv_pur_req_id;
		 $(this.dataTable).datagrid({
			 method:'get',
			 border:false,
			 singleSelect:true,
			 fit:true,
			 fitColumns:true,
			 queryParams:data,
			 url:this.route,
			 onClickRow: function(index,row){
				 self.edit(index,row);
			 }
		 }).datagrid('enableFilter');
	 }

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvPurReqAssetBreakdown.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openAssetBreakdownWindow(){
		$('#openassetbreakdownwindow').window('open');
	}

	getParams(){
		let params={};
		params.from_date=$('#assetbreakdownsearchFrm [name=from_date]').val();
		params.to_date=$('#assetbreakdownsearchFrm [name=to_date]').val();
		params.custom_no=$('#assetbreakdownsearchFrm [name=custom_no]').val();
		params.asset_name=$('#assetbreakdownsearchFrm [name=asset_name]').val();
		return params;
	}

	searchAssetBreakdown(){
		let params=this.getParams();
		let rpt = axios.get(this.route+"/getassetbreakdown",{params})
		.then(function(response){
			$('#assetbreakdownsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showAssetBreakdownGrid(data){
		let self=this;
		var pr=$('#assetbreakdownsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#invpurreqassetbreakdownFrm  [name=asset_breakdown_id]').val(row.id);
				$('#invpurreqassetbreakdownFrm  [name=custom_no]').val(row.custom_no);
				$('#invpurreqassetbreakdownFrm  [name=breakdown_date]').val(row.breakdown_date);
				$('#invpurreqassetbreakdownFrm  [name=breakdown_time]').val(row.breakdown_time);
				$('#invpurreqassetbreakdownFrm  [name=remarks]').val(row.remarks);
				$('#invpurreqassetbreakdownFrm  [name=reason_id]').val(row.reason_id);
				$('#invpurreqassetbreakdownFrm  [name=decision_id]').val(row.decision_id);
				$('#invpurreqassetbreakdownFrm  [name=asset_name]').val(row.asset_name);
				$('#openassetbreakdownwindow').window('close');
				$('#assetbreakdownsearchTbl').datagrid('loadData',[]);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}
	
}
window.MsInvPurReqAssetBreakdown=new MsInvPurReqAssetBreakdownController(new MsInvPurReqAssetBreakdownModel());
MsInvPurReqAssetBreakdown.showAssetBreakdownGrid([]);