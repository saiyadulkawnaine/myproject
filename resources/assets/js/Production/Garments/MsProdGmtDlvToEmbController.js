require('./../../datagrid-filter.js');
let MsProdGmtDlvToEmbModel = require('./MsProdGmtDlvToEmbModel');
class MsProdGmtDlvToEmbController {
	constructor(MsProdGmtDlvToEmbModel)
	{
		this.MsProdGmtDlvToEmbModel = MsProdGmtDlvToEmbModel;
		this.formId='prodgmtdlvtoembFrm';
		this.dataTable='#prodgmtdlvtoembTbl';
		this.route=msApp.baseUrl()+"/prodgmtdlvtoemb"
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
			this.MsProdGmtDlvToEmbModel.save(this.route+"/"+formObj.id,'PUT',formData,this.response);
		}else{
			this.MsProdGmtDlvToEmbModel.save(this.route,'POST',formData ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodgmtdlvtoembFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtDlvToEmbModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtDlvToEmbModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtdlvtoembTbl').datagrid('reload');
		$('#prodgmtdlvtoembFrm  [name=id]').val(d.id);
		$('#prodgmtdlvtoembFrm  [name=challan_no]').val(d.challan_no);
		msApp.resetForm('prodgmtdlvtoembFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let dlvtoemb=this.MsProdGmtDlvToEmbModel.get(index,row);
		dlvtoemb.then(function(response){
			$('#prodgmtdlvtoembFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtDlvToEmb.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf(){
		var id = $('#prodgmtdlvtoembFrm [name=id]').val();
		if(id==''){
			alert("Select A Challan First");
			return;
		}
		window.open(this.route+"/embpdf?id="+id);
	}
	
}
window.MsProdGmtDlvToEmb=new MsProdGmtDlvToEmbController(new MsProdGmtDlvToEmbModel());
MsProdGmtDlvToEmb.showGrid();

 $('#prodgmtdlvtoembtabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_dlv_to_emb_id = $('#prodgmtdlvtoembFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_dlv_to_emb_id=prod_gmt_dlv_to_emb_id;

	 if(index==1){
		 if(prod_gmt_dlv_to_emb_id===''){
			 $('#prodgmtdlvtoembtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtdlvtoemborderFrm');
		  $('#prodgmtdlvtoemborderFrm  [name=prod_gmt_dlv_to_emb_id]').val(prod_gmt_dlv_to_emb_id);
		  MsProdGmtDlvToEmbOrder.showGrid(prod_gmt_dlv_to_emb_id);
	  }

	 /*  if(index==2){
		 if(prod_gmt_dlv_to_emb_id===''){
			 $('#prodgmtdlvtoembtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtdlvtoembqtyFrm');

		  $('#prodgmtdlvtoembqtyFrm  [name=prod_gmt_dlv_to_emb_id]').val(prod_gmt_dlv_to_emb_id);
		  MsProdGmtDlvToEmbQty.showGrid(prod_gmt_dlv_to_emb_id);
	  } */
   }
}); 
