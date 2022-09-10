require('./../../datagrid-filter.js');
let MsSoEmbCutpanelRcvInhModel = require('./MsSoEmbCutpanelRcvInhModel');
class MsSoEmbCutpanelRcvInhController {
	constructor(MsSoEmbCutpanelRcvInhModel)
	{
		this.MsSoEmbCutpanelRcvInhModel = MsSoEmbCutpanelRcvInhModel;
		this.formId='soembcutpanelrcvinhFrm';
		this.dataTable='#soembcutpanelrcvinhTbl';
		this.route=msApp.baseUrl()+"/soembcutpanelrcvinh"
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
			this.MsSoEmbCutpanelRcvInhModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoEmbCutpanelRcvInhModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soembcutpanelrcvinhFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbCutpanelRcvInhModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbCutpanelRcvInhModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soembcutpanelrcvinhTbl').datagrid('reload');
		MsSoEmbCutpanelRcvInh.resetForm()
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let rcv =this.MsSoEmbCutpanelRcvInhModel.get(index,row);
		rcv.then(function(response){
			$('#soembcutpanelrcvinhFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(error);
		})
		
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbCutpanelRcvInh.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	
	openPartyChallanWindow(){
		$('#opendlvpartychallanwindow').window('open');
	}
	
	getParams(){
		let params={};
		params.production_area_id=$('#soembcutpanelrcvinhFrm [name=production_area_id]').val();
		params.supplier_id = $('#dlvpartychallansearchFrm  [name=supplier_id]').val();
		params.delivery_date = $('#dlvpartychallansearchFrm  [name=delivery_date]').val();
		return params;
	}

	searchDlvPartyChallanGrid(){
		let params = this.getParams();
		let d = axios.get(this.route+'/getpartychallan',{params})
		.then(function(response){
			$('#dlvpartychallansearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		
		return d;
	}

	showDlvPartyChallanGrid(data){
		let self=this;
		$('#dlvpartychallansearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			//emptyMsg:'No Embelishment Sales Order Found Against Selected WO/Challan',
			onClickRow:function(index,row){
				$('#soembcutpanelrcvinhFrm  [name=prod_gmt_party_challan_id]').val(row.id);
				$('#soembcutpanelrcvinhFrm  [name=party_challan_no]').val(row.challan_no);
				$('#soembcutpanelrcvinhFrm  [name=company_name]').val(row.company_name);
				$('#soembcutpanelrcvinhFrm  [name=supplier_name]').val(row.supplier_name);
				$('#opendlvpartychallanwindow').window('close');
				$('#dlvpartychallansearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsSoEmbCutpanelRcvInh=new MsSoEmbCutpanelRcvInhController(new MsSoEmbCutpanelRcvInhModel());
MsSoEmbCutpanelRcvInh.showGrid();
MsSoEmbCutpanelRcvInh.showDlvPartyChallanGrid([]);

 $('#soembcutpanelrcvinhtabs').tabs({
	onSelect:function(title,index){
	 let so_emb_cutpanel_rcv_id = $('#soembcutpanelrcvinhFrm  [name=id]').val();
	 var data={};
	  data.so_emb_cutpanel_rcv_id=so_emb_cutpanel_rcv_id;

	 if(index==1){
		 if(so_emb_cutpanel_rcv_id===''){
			 $('#soembcutpanelrcvinhtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('soembcutpanelrcvinhorderFrm');
		  $('#soembcutpanelrcvinhorderFrm  [name=so_emb_cutpanel_rcv_id]').val(so_emb_cutpanel_rcv_id);
		  MsSoEmbCutpanelRcvInhOrder.get(so_emb_cutpanel_rcv_id);
	  }

   }
});
