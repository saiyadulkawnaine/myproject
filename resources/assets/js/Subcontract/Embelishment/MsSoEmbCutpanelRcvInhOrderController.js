let MsSoEmbCutpanelRcvInhOrderModel = require('./MsSoEmbCutpanelRcvInhOrderModel');

class MsSoEmbCutpanelRcvInhOrderController {
	constructor(MsSoEmbCutpanelRcvInhOrderModel)
	{
		this.MsSoEmbCutpanelRcvInhOrderModel = MsSoEmbCutpanelRcvInhOrderModel;
		this.formId='soembcutpanelrcvinhorderFrm';
		this.dataTable='#soembcutpanelrcvinhorderTbl';
		this.route=msApp.baseUrl()+"/soembcutpanelrcvinhorder"
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
			this.MsSoEmbCutpanelRcvInhOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoEmbCutpanelRcvInhOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#cutpanelreceivegmtcosi').html('');
		let so_emb_cutpanel_rcv_id = $('#soembcutpanelrcvinhFrm  [name=id]').val();
		$('#soembcutpanelrcvinhorderFrm  [name=so_emb_cutpanel_rcv_id]').val(so_emb_cutpanel_rcv_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbCutpanelRcvInhOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbCutpanelRcvInhOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoEmbCutpanelRcvInhOrder.resetForm();
		MsSoEmbCutpanelRcvInhOrder.get($('#soembcutpanelrcvinhFrm  [name=id]').val());
		$('#soembcutpanelrcvinhqtyFrm  [name=so_emb_cutpanel_rcv_order_id]').val($('#soembcutpanelrcvinhorderFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoEmbCutpanelRcvInhOrderModel.get(index,row);
		msApp.resetForm('soembcutpanelrcvinhqtyFrm');
		$('#soembcutpanelrcvinhqtyFrm  [name=so_emb_cutpanel_rcv_order_id]').val(row.id);
		MsSoEmbCutpanelRcvInhQty.get(row.id);
	}

	showGrid(data){
		let self=this;
		$('#soembcutpanelrcvinhorderTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoEmbCutpanelRcvInhOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openOrderCutpanelRcvWindow(){
		$('#opencutpanelorderwindow').window('open');
	}

	getParams(){
		let params={};
		params.so_no = $('#cutpanelordersearchFrm [name=so_no]').val();
		params.company_id = $('#cutpanelordersearchFrm [name=company_id]').val();
		params.buyer_id = $('#soembcutpanelrcvinhFrm  [name=buyer_id]').val();
		params.production_area_id = $('#soembcutpanelrcvinhFrm  [name=production_area_id]').val();
		return params;
	}
	searchCutpanelReceiveOrder(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getcutpanelorder',{params})
		.then(function(response){
			$('#cutpanelordersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showCutpanelOrderGrid(data){
		let self=this;
		$('#cutpanelordersearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#soembcutpanelrcvinhorderFrm [name=so_emb_id]').val(row.id);
				$('#soembcutpanelrcvinhorderFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#opencutpanelorderwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	get(so_emb_cutpanel_rcv_id){
		let data= axios.get(this.route+"?so_emb_cutpanel_rcv_id="+so_emb_cutpanel_rcv_id);
		data.then(function (response) {
			$('#soembcutpanelrcvinhorderTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	
}
window.MsSoEmbCutpanelRcvInhOrder=new MsSoEmbCutpanelRcvInhOrderController(new MsSoEmbCutpanelRcvInhOrderModel());
MsSoEmbCutpanelRcvInhOrder.showGrid([]);
MsSoEmbCutpanelRcvInhOrder.showCutpanelOrderGrid([]);