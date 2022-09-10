let MsSoKnitDlvModel = require('./MsSoKnitDlvModel');
require('./../../datagrid-filter.js');
class MsSoKnitDlvController {
	constructor(MsSoKnitDlvModel)
	{
		this.MsSoKnitDlvModel = MsSoKnitDlvModel;
		this.formId='soknitdlvFrm';
		this.dataTable='#soknitdlvTbl';
		this.route=msApp.baseUrl()+"/soknitdlv"
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
			this.MsSoKnitDlvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoKnitDlvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soknitdlvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitDlvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitDlvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soknitdlvTbl').datagrid('reload');
		msApp.resetForm('soknitdlvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoKnitDlvModel.get(index,row);
		workReceive.then(function(response){
			//$('#soknitdlvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
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
		return '<a href="javascript:void(0)"  onClick="MsSoKnitDlv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	showDc()
	{
		var id= $('#soknitdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/dlvchalan?id="+id);
	}
	showBill()
	{
		var id= $('#soknitdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/bill?id="+id);
	}
}
window.MsSoKnitDlv=new MsSoKnitDlvController(new MsSoKnitDlvModel());
MsSoKnitDlv.showGrid();
//MsSoKnitDlv.soknitdlvsoGrid([]);
 $('#soknitdlvtabs').tabs({
	onSelect:function(title,index){
	 let so_knit_dlv_id = $('#soknitdlvFrm  [name=id]').val();
	 let so_knit_dlv_item_id = $('#soknitdlvitemFrm  [name=id]').val();
	 if(index==1){
		 if(so_knit_dlv_id===''){
			 $('#soknitdlvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soknitdlvitemFrm  [name=so_knit_dlv_id]').val(so_knit_dlv_id);
		 MsSoKnitDlvItem.get(so_knit_dlv_id);
	 }

	 if(index==2){
		 if(so_knit_dlv_item_id===''){
			 $('#soknitdlvtabs').tabs('select',1);
			 msApp.showError('Select a Item First',1);
			 return;
		  }
		 $('#soknitdlvitemyarnFrm  [name=so_knit_dlv_item_id]').val(so_knit_dlv_item_id);
		 MsSoKnitDlvItemYarn.get(so_knit_dlv_item_id);
	 }
}
}); 
