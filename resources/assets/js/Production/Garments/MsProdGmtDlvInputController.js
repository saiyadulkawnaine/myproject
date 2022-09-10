require('./../../datagrid-filter.js');
let MsProdGmtDlvInputModel = require('./MsProdGmtDlvInputModel');
class MsProdGmtDlvInputController {
	constructor(MsProdGmtDlvInputModel)
	{
		this.MsProdGmtDlvInputModel = MsProdGmtDlvInputModel;
		this.formId='prodgmtdlvinputFrm';
		this.dataTable='#prodgmtdlvinputTbl';
		this.route=msApp.baseUrl()+"/prodgmtdlvinput"
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
			this.MsProdGmtDlvInputModel.save(this.route+"/"+formObj.id,'PUT',formData,this.response);
		}else{
			this.MsProdGmtDlvInputModel.save(this.route,'POST',formData ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodgmtdlvinputFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtDlvInputModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtDlvInputModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtdlvinputTbl').datagrid('reload');
		$('#prodgmtdlvinputFrm  [name=id]').val(d.id);
		$('#prodgmtdlvinputFrm  [name=challan_no]').val(d.challan_no);
		msApp.resetForm('prodgmtdlvinputFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let dlvinput=this.MsProdGmtDlvInputModel.get(index,row);
		dlvinput.then(function(response){
			$('#prodgmtdlvinputFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtDlvInput.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf(){
		var id = $('#prodgmtdlvinputFrm [name=id]').val();
		if(id==''){
			alert("Select A Challan First");
			return;
		}
		window.open(this.route+"/inputpdf?id="+id);
	}
	
}
window.MsProdGmtDlvInput=new MsProdGmtDlvInputController(new MsProdGmtDlvInputModel());
MsProdGmtDlvInput.showGrid();

 $('#prodgmtdlvinputtabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_dlv_input_id = $('#prodgmtdlvinputFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_dlv_input_id=prod_gmt_dlv_input_id;

	 if(index==1){
		 if(prod_gmt_dlv_input_id===''){
			 $('#prodgmtdlvinputtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  $('#dlvinputgmtcosi').html('');
		  msApp.resetForm('prodgmtdlvinputorderFrm');
		  $('#prodgmtdlvinputorderFrm  [name=prod_gmt_dlv_input_id]').val(prod_gmt_dlv_input_id);
		  MsProdGmtDlvInputOrder.showGrid(prod_gmt_dlv_input_id);
	  }
   }
}); 
