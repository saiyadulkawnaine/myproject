let MsInvGeneralTransInModel = require('./MsInvGeneralTransInModel');
require('./../../datagrid-filter.js');
class MsInvGeneralTransInController {
	constructor(MsInvGeneralTransInModel)
	{
		this.MsInvGeneralTransInModel = MsInvGeneralTransInModel;
		this.formId='invgeneraltransinFrm';
		this.dataTable='#invgeneraltransinTbl';
		this.route=msApp.baseUrl()+"/invgeneraltransin"
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
			this.MsInvGeneralTransInModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralTransInModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralTransInModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralTransInModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgeneraltransinTbl').datagrid('reload');
		msApp.resetForm('invgeneraltransinFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGeneralTransInModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invgeneraltransinitemFrm');
			/*if(response.data.fromData.receive_against_id==8){
				$('#invgeneraltransinitemFrm  [name=rate]').attr("readonly",'readonly');
			}else{
				$('#invgeneraltransinitemFrm  [name=rate]').removeAttr("readonly");
			}*/
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralTransIn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgeneraltransinFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvGeneralTransIn=new MsInvGeneralTransInController(new MsInvGeneralTransInModel());
MsInvGeneralTransIn.showGrid();

$('#invgeneraltransintabs').tabs({
    onSelect:function(title,index){
        let inv_general_rcv_id = $('#invgeneraltransinFrm [name=inv_general_rcv_id]').val();
         let inv_general_rcv_item_id = $('#invgeneraltransinitemFrm [name=id]').val();
        if(index==1){
			if(inv_general_rcv_id===''){
				$('#invyarntransintabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invgeneraltransinitemFrm')
			$('#invgeneraltransinitemFrm  [name=inv_general_rcv_id]').val(inv_general_rcv_id);
			MsInvGeneralTransInItem.get(inv_general_rcv_id);
        }

        if(index==2){
			if(inv_general_rcv_item_id===''){
				$('#invyarntransintabs').tabs('select',1);
				msApp.showError('Select Item First',0);
				return;
		    }
		    msApp.resetForm('invgeneraltransinitemdtlFrm')
			$('#invgeneraltransinitemdtlFrm  [name=inv_general_rcv_item_id]').val(inv_general_rcv_item_id);
			MsInvGeneralTransInItemDtl.get(inv_general_rcv_item_id);
        }
    }
});

