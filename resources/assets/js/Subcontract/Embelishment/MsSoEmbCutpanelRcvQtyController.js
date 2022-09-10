let MsSoEmbCutpanelRcvQtyModel = require('./MsSoEmbCutpanelRcvQtyModel');

class MsSoEmbCutpanelRcvQtyController {
	constructor(MsSoEmbCutpanelRcvQtyModel)
	{
		this.MsSoEmbCutpanelRcvQtyModel = MsSoEmbCutpanelRcvQtyModel;
		this.formId='soembcutpanelrcvqtyFrm';	             
		this.dataTable='#soembcutpanelrcvqtyTbl';
		this.route=msApp.baseUrl()+"/soembcutpanelrcvqty"
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

		let formObj = msApp.get(this.formId);
		if(formObj.id){
			this.MsSoEmbCutpanelRcvQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoEmbCutpanelRcvQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$("#soembcutpanelrcvqtyFrm [name=so_emb_cutpanel_rcv_order_id]").val($("#soembcutpanelrcvorderFrm [name=id]")).val();
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbCutpanelRcvQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbCutpanelRcvQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoEmbCutpanelRcvQty.resetForm();
		$("#soembcutpanelrcvqtyFrm [name=so_emb_cutpanel_rcv_order_id]").val($("#soembcutpanelrcvorderFrm [name=id]").val());
		MsSoEmbCutpanelRcvQty.get($('#soembcutpanelrcvorderFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoEmbCutpanelRcvQtyModel.get(index, row);
		let data = this.MsSoEmbCutpanelRcvQtyModel.get(index, row);
		data.then(function (response) { }).
			catch(function (error)
			{
				console.log(error);
		})
	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoEmbCutpanelRcvQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	OpenEmbItemWindow()
	{
		$('#soembitemWindow').window("open");
		MsSoEmbCutpanelRcvQty.getEmbItem();
	}

	getEmbItem()
	{
		let params = {};
		params.so_emb_id = $("#soembcutpanelrcvorderFrm [name=so_emb_id]").val();
		let data = axios.get(this.route + "/getsoembitem", { params });
		data.then(function (response)
		{
			$('#soembitemsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		})
	}

	showEmdItemGrid(data)
	{
		let self = this;
		$('#soembitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#soembcutpanelrcvqtyFrm [name=so_emb_ref_id]').val(row.id);

				$('#soembcutpanelrcvqtyFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#soembcutpanelrcvqtyFrm [name=item_desc]').val(row.item_desc);
				$('#soembcutpanelrcvqtyFrm [name=gmt_color]').val(row.gmt_color);
				$('#soembcutpanelrcvqtyFrm [name=gmtspart]').val(row.gmtspart);
				$('#soembitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	get(so_emb_cutpanel_rcv_order_id)
	{
		let data = axios.get(this.route + "?so_emb_cutpanel_rcv_order_id=" + so_emb_cutpanel_rcv_order_id);
		data.then(function (response)
		{
			$('#soembcutpanelrcvqtyTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		})
	}

}
window.MsSoEmbCutpanelRcvQty = new MsSoEmbCutpanelRcvQtyController(new MsSoEmbCutpanelRcvQtyModel());
MsSoEmbCutpanelRcvQty.showEmdItemGrid([]);