let MsProdGmtCuttingModel = require('./MsProdGmtCuttingModel');
require('./../../datagrid-filter.js');
class MsProdGmtCuttingController {
	constructor(MsProdGmtCuttingModel)
	{
		this.MsProdGmtCuttingModel = MsProdGmtCuttingModel;
		this.formId='prodgmtcuttingFrm';
		this.dataTable='#prodgmtcuttingTbl';
		this.route=msApp.baseUrl()+"/prodgmtcutting"
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
			this.MsProdGmtCuttingModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtCuttingModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtCuttingModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtCuttingModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtcuttingTbl').datagrid('reload');
		msApp.resetForm('prodgmtcuttingFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtCuttingModel.get(index,row);
		
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtCutting.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	
}
window.MsProdGmtCutting=new MsProdGmtCuttingController(new MsProdGmtCuttingModel());
MsProdGmtCutting.showGrid();

 $('#prodgmtcuttingtabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_cutting_id = $('#prodgmtcuttingFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_cutting_id=prod_gmt_cutting_id;

	 if(index==1){
		 if(prod_gmt_cutting_id===''){
			 $('#prodgmtcuttingtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtcuttingorderFrm');
		  $('#prodgmtcuttingorderFrm  [name=prod_gmt_cutting_id]').val(prod_gmt_cutting_id);
		  MsProdGmtCuttingOrder.showGrid(prod_gmt_cutting_id);
	  }

	 /*  if(index==2){
		 if(prod_gmt_cutting_id===''){
			 $('#prodgmtcuttingtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtcuttingqtyFrm');

		  $('#prodgmtcuttingqtyFrm  [name=prod_gmt_cutting_id]').val(prod_gmt_cutting_id);
		  MsProdGmtCuttingQty.showGrid(prod_gmt_cutting_id);
	  } */
   }
}); 
