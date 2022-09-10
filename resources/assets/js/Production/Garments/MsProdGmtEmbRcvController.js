require('./../../datagrid-filter.js');
let MsProdGmtEmbRcvModel = require('./MsProdGmtEmbRcvModel');
class MsProdGmtEmbRcvController {
	constructor(MsProdGmtEmbRcvModel)
	{
		this.MsProdGmtEmbRcvModel = MsProdGmtEmbRcvModel;
		this.formId='prodgmtembrcvFrm';
		this.dataTable='#prodgmtembrcvTbl';
		this.route=msApp.baseUrl()+"/prodgmtembrcv"
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
		let formData=$("#"+this.formId).serialize();
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsProdGmtEmbRcvModel.save(this.route+"/"+formObj.id,'PUT',formData,this.response);
		}else{
			this.MsProdGmtEmbRcvModel.save(this.route,'POST',formData ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodgmtembrcvFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtEmbRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtEmbRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtembrcvTbl').datagrid('reload');
		$('#prodgmtembrcvFrm  [name=id]').val(d.id);
		$('#prodgmtembrcvFrm  [name=challan_no]').val(d.challan_no);
		msApp.resetForm('prodgmtembrcvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let embrcv=this.MsProdGmtEmbRcvModel.get(index,row);
		embrcv.then(function(response){
			$('#prodgmtembrcvFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		}).catch(function(error){
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
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtEmbRcv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openDlvToEmbWindow(){
		$('#opendlvtoembwindow').window('open');
	}
	getParams(){
		let params={};
		params.supplier_id = $('#dlvtoembsearchFrm  [name=supplier_id]').val();
		params.delivery_date = $('#dlvtoembsearchFrm  [name=delivery_date]').val();
		return params;
	}
	searchDlvToEmbGrid(){
		let params = this.getParams();
		let d = axios.get(this.route+'/getdlvtoemb',{params})
		.then(function(response){
			$('#dlvtoembsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}
	showDlvEmbGrid(data){
		let self=this;
		$('#dlvtoembsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow:function(index,row){
				$('#prodgmtembrcvFrm  [name=prod_gmt_dlv_to_emb_id]').val(row.id);
				$('#prodgmtembrcvFrm  [name=challan_no]').val(row.challan_no);
				$('#prodgmtembrcvFrm  [name=supplier_id]').val(row.supplier_id);
				$('#prodgmtembrcvFrm  [name=supplier_name]').val(row.supplier_name);
				$('#prodgmtembrcvFrm  [name=location_id]').val(row.location_id);
				$('#dlvtoembsearchTbl').datagrid('loadData', []);
				$('#opendlvtoembwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	
}
window.MsProdGmtEmbRcv=new MsProdGmtEmbRcvController(new MsProdGmtEmbRcvModel());
MsProdGmtEmbRcv.showGrid();
MsProdGmtEmbRcv.showDlvEmbGrid([]);

 $('#prodgmtembrcvtabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_emb_rcv_id = $('#prodgmtembrcvFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_emb_rcv_id=prod_gmt_emb_rcv_id;

	 if(index==1){
		 if(prod_gmt_emb_rcv_id===''){
			 $('#prodgmtembrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtembrcvorderFrm');
		  $('#prodgmtembrcvorderFrm  [name=prod_gmt_emb_rcv_id]').val(prod_gmt_emb_rcv_id);
		  MsProdGmtEmbRcvOrder.showGrid(prod_gmt_emb_rcv_id);
	  }

	 /*  if(index==2){
		 if(prod_gmt_emb_rcv_id===''){
			 $('#prodgmtembrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtembrcvqtyFrm');

		  $('#prodgmtembrcvqtyFrm  [name=prod_gmt_emb_rcv_id]').val(prod_gmt_emb_rcv_id);
		  MsProdGmtEmbRcvQty.showGrid(prod_gmt_emb_rcv_id);
	  } */
   }
}); 
