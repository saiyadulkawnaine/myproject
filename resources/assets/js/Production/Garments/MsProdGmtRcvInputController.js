require('./../../datagrid-filter.js');
let MsProdGmtRcvInputModel = require('./MsProdGmtRcvInputModel');
class MsProdGmtRcvInputController {
	constructor(MsProdGmtRcvInputModel)
	{
		this.MsProdGmtRcvInputModel = MsProdGmtRcvInputModel;
		this.formId='prodgmtrcvinputFrm';
		this.dataTable='#prodgmtrcvinputTbl';
		this.route=msApp.baseUrl()+"/prodgmtrcvinput"
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
		/* let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsProdGmtRcvInputModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtRcvInputModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		} */
		let formData=$("#"+this.formId).serialize();
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsProdGmtRcvInputModel.save(this.route+"/"+formObj.id,'PUT',formData,this.response);
		}else{
			this.MsProdGmtRcvInputModel.save(this.route,'POST',formData ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtRcvInputModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtRcvInputModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtrcvinputTbl').datagrid('reload');
		$('#prodgmtrcvinputFrm  [name=id]').val(d.id);
		$('#prodgmtrcvinputFrm  [name=receive_no]').val(d.receive_no);
		//$('#prodgmtrcvinputFrm  [name=supplier_id]').val(d.supplier_id);
		msApp.resetForm('prodgmtrcvinputFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtRcvInputModel.get(index,row);
		
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtRcvInput.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openDlvInputWindow(){
		$('#opendlvinputwindow').window('open');
	}

	searchDlvInputGrid(){
		let data={};
			data.supplier_id = $('#dlvinputsearchFrm  [name=supplier_id]').val();
			data.delivery_date = $('#dlvinputsearchFrm  [name=delivery_date]').val();
			let self=this;
			var ex=$('#dlvinputsearchTbl').datagrid({
				method:'get',
				border:false,
				singleSelect:true,
				fit:true,
				queryParams:data,
				url:msApp.baseUrl()+"/prodgmtrcvinput/getdeliverychallan",
				onClickRow: function(index,row){
					$('#prodgmtrcvinputFrm  [name=prod_gmt_dlv_input_id]').val(row.id);
					$('#prodgmtrcvinputFrm  [name=challan_no]').val(row.challan_no);
					$('#prodgmtrcvinputFrm  [name=supplier_id]').val(row.supplier_id);
					$('#prodgmtrcvinputFrm  [name=supplier_name]').val(row.supplier_name);
					$('#prodgmtrcvinputFrm  [name=location_id]').val(row.location_id);
					//$('#dlvinputsearchTbl').datagrid('loadData', []);
					$('#opendlvinputwindow').window('close');
			}
		});
		ex.datagrid('enableFilter')/* .datagrid('loadData', data) */;
	}
	
}
window.MsProdGmtRcvInput=new MsProdGmtRcvInputController(new MsProdGmtRcvInputModel());
MsProdGmtRcvInput.showGrid();

 $('#prodgmtrcvinputtabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_rcv_input_id = $('#prodgmtrcvinputFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_rcv_input_id=prod_gmt_rcv_input_id;

	 if(index==1){
		 if(prod_gmt_rcv_input_id===''){
			 $('#prodgmtrcvinputtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  $('#prodgmtrcvinputqtyFrm  [name=prod_gmt_rcv_input_id]').val(prod_gmt_rcv_input_id);
		  MsProdGmtRcvInputQty.create(prod_gmt_rcv_input_id);
	  }

   }
}); 
