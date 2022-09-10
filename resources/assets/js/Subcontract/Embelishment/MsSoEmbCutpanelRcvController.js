let MsSoEmbCutpanelRcvModel = require('./MsSoEmbCutpanelRcvModel');
require('../../datagrid-filter.js');
class MsSoEmbCutpanelRcvController {
	constructor(MsSoEmbCutpanelRcvModel)
	{
		this.MsSoEmbCutpanelRcvModel = MsSoEmbCutpanelRcvModel;
		this.formId='soembcutpanelrcvFrm';
		this.dataTable='#soembcutpanelrcvTbl';
		this.route=msApp.baseUrl()+"/soembcutpanelrcv"
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
			this.MsSoEmbCutpanelRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoEmbCutpanelRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soembcutpanelrcvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbCutpanelRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbCutpanelRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soembcutpanelrcvTbl').datagrid('reload');
		$('#soembcutpanelrcvFrm [id="buyer_id"]').combobox('setValue', '');
		msApp.resetForm('soembcutpanelrcvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoEmbCutpanelRcvModel.get(index,row);
		workReceive.then(function(response){
				$('#soembcutpanelrcvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(error)
		});
	}

	showGrid()
	{
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbCutpanelRcv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSoEmbCutpanelRcv=new MsSoEmbCutpanelRcvController(new MsSoEmbCutpanelRcvModel());
MsSoEmbCutpanelRcv.showGrid();
 $('#soembcutpanelrcvtabs').tabs({
	onSelect:function(title,index){
			let so_emb_cutpanel_rcv_id = $('#soembcutpanelrcvFrm  [name=id]').val();
			var data = {};
			data.so_emb_cutpanel_rcv_id = so_emb_cutpanel_rcv_id;
	 if(index==1){
		 if(so_emb_cutpanel_rcv_id===''){
			 $('#soembcutpanelrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
			}
			MsSoEmbCutpanelRcvOrder.resetForm();
			MsSoEmbCutpanelRcvQty.resetForm();
			MsSoEmbCutpanelRcvQty.showGrid([]);
			$('#soembcutpanelrcvorderFrm  [name=so_emb_cutpanel_rcv_id]').val(so_emb_cutpanel_rcv_id);
			MsSoEmbCutpanelRcvOrder.get(so_emb_cutpanel_rcv_id);
			}
}
}); 
