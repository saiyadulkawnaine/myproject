let MsSoEmbCutpanelRcvInhQtyModel = require('./MsSoEmbCutpanelRcvInhQtyModel');

class MsSoEmbCutpanelRcvInhQtyController {
	constructor(MsSoEmbCutpanelRcvInhQtyModel)
	{
		this.MsSoEmbCutpanelRcvInhQtyModel = MsSoEmbCutpanelRcvInhQtyModel;
		this.formId='soembcutpanelrcvinhqtyFrm';	             
		this.dataTable='#soembcutpanelrcvinhqtyTbl';
		this.route=msApp.baseUrl()+"/soembcutpanelrcvinhqty"
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
		let so_emb_cutpanel_rcv_order_id=$('#soembcutpanelrcvinhorderFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.so_emb_cutpanel_rcv_order_id=so_emb_cutpanel_rcv_order_id;
		if(formObj.id){
			this.MsSoEmbCutpanelRcvInhQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoEmbCutpanelRcvInhQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soembcutpanelrcvinhqtyFrm  [name=so_emb_cutpanel_rcv_order_id]').val($('#soembcutpanelrcvinhorderFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbCutpanelRcvInhQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbCutpanelRcvInhQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoEmbCutpanelRcvInhQty.resetForm();	
		//$('#soembcutpanelrcvinhqtyFrm  [name=so_emb_cutpanel_rcv_order_id]').val($('#soembcutpanelrcvinhorderFrm  [name=id]').val());
		MsSoEmbCutpanelRcvInhQty.get($('#soembcutpanelrcvinhorderFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoEmbCutpanelRcvInhQtyModel.get(index,row);
	}

	get(so_emb_cutpanel_rcv_order_id){
		let data= axios.get(this.route+"?so_emb_cutpanel_rcv_order_id="+so_emb_cutpanel_rcv_order_id);
		data.then(function (response) {
			$('#soembcutpanelrcvinhqtyTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoEmbCutpanelRcvInhQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	OpenProdGmtEmbItemWindow()
	{
		$('#prodgmtsoembitemWindow').window("open");
		MsSoEmbCutpanelRcvInhQty.getProdGmtEmbItem();
	}

	getProdGmtEmbItem()
	{
		let params = {};
		params.so_emb_id = $("#soembcutpanelrcvinhorderFrm [name=so_emb_id]").val();
		let data = axios.get(this.route + "/getsoembitemref", { params });
		data.then(function (response)
		{
			$('#prodgmtsoembitemsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		})
	}

	showEmdItemGrid(data)
	{
		let self = this;
		$('#prodgmtsoembitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#soembcutpanelrcvinhqtyFrm [name=so_emb_ref_id]').val(row.id);
				$('#soembcutpanelrcvinhqtyFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#soembcutpanelrcvinhqtyFrm [name=item_desc]').val(row.item_desc);
				$('#soembcutpanelrcvinhqtyFrm [name=gmt_color]').val(row.gmt_color);
				$('#soembcutpanelrcvinhqtyFrm [name=gmtspart]').val(row.gmtspart);
				$('#prodgmtsoembitemWindow').window('close');
				$('#prodgmtsoembitemsearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsSoEmbCutpanelRcvInhQty=new MsSoEmbCutpanelRcvInhQtyController(new MsSoEmbCutpanelRcvInhQtyModel());
MsSoEmbCutpanelRcvInhQty.showGrid([]);
MsSoEmbCutpanelRcvInhQty.showEmdItemGrid([]);