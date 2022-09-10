let MsInvGeneralRcvModel = require('./MsInvGeneralRcvModel');
require('./../../datagrid-filter.js');
class MsInvGeneralRcvController {
	constructor(MsInvGeneralRcvModel)
	{
		this.MsInvGeneralRcvModel = MsInvGeneralRcvModel;
		this.formId='invgeneralrcvFrm';
		this.dataTable='#invgeneralrcvTbl';
		this.route=msApp.baseUrl()+"/invgeneralrcv"
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
			this.MsInvGeneralRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invgeneralrcvFrm [id="supplier_id"]').combobox('setValue', '');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgeneralrcvTbl').datagrid('reload');
		msApp.resetForm('invgeneralrcvFrm');
		$('#invgeneralrcvFrm [id="supplier_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGeneralRcvModel.get(index,row);
		data.then(function(response){
			$('#invgeneralrcvFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			msApp.resetForm('invgeneralrcvitemFrm');
			if(response.data.fromData.receive_against_id==8){
				$('#invgeneralrcvitemFrm  [name=rate]').attr("readonly",'readonly');
			}else{
				$('#invgeneralrcvitemFrm  [name=rate]').removeAttr("readonly");
			}
		}).catch(function(error){
			console.log(error);
		})
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralRcv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgeneralrcvFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvGeneralRcv=new MsInvGeneralRcvController(new MsInvGeneralRcvModel());
MsInvGeneralRcv.showGrid();

$('#invgeneralrcvtabs').tabs({
    onSelect:function(title,index){
        let inv_general_rcv_id = $('#invgeneralrcvFrm [name=inv_general_rcv_id]').val();
         let inv_general_rcv_item_id = $('#invgeneralrcvitemFrm [name=id]').val();
        if(index==1){
			if(inv_general_rcv_id===''){
				$('#invyarnrcvtabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invgeneralrcvitemFrm')
			$('#invgeneralrcvitemFrm  [name=inv_general_rcv_id]').val(inv_general_rcv_id);
			MsInvGeneralRcvItem.get(inv_general_rcv_id);
        }

        if(index==2){
			if(inv_general_rcv_item_id===''){
				$('#invyarnrcvtabs').tabs('select',1);
				msApp.showError('Select Item First',0);
				return;
		    }
		    msApp.resetForm('invgeneralrcvitemdtlFrm')
			$('#invgeneralrcvitemdtlFrm  [name=inv_general_rcv_item_id]').val(inv_general_rcv_item_id);
			MsInvGeneralRcvItemDtl.get(inv_general_rcv_item_id);
        }
    }
});

