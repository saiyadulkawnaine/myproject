let MsInvGeneralIsuRtnModel = require('./MsInvGeneralIsuRtnModel');
require('./../../datagrid-filter.js');
class MsInvGeneralIsuRtnController {
	constructor(MsInvGeneralIsuRtnModel)
	{
		this.MsInvGeneralIsuRtnModel = MsInvGeneralIsuRtnModel;
		this.formId='invgeneralisurtnFrm';
		this.dataTable='#invgeneralisurtnTbl';
		this.route=msApp.baseUrl()+"/invgeneralisurtn"
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
			this.MsInvGeneralIsuRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralIsuRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralIsuRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralIsuRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgeneralisurtnTbl').datagrid('reload');
		msApp.resetForm('invgeneralisurtnFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGeneralIsuRtnModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invgeneralisurtnitemFrm');
			/*if(response.data.fromData.receive_against_id==8){
				$('#invgeneralisurtnitemFrm  [name=rate]').attr("readonly",'readonly');
			}else{
				$('#invgeneralisurtnitemFrm  [name=rate]').removeAttr("readonly");
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralIsuRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgeneralisurtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvGeneralIsuRtn=new MsInvGeneralIsuRtnController(new MsInvGeneralIsuRtnModel());
MsInvGeneralIsuRtn.showGrid();

$('#invgeneralisurtntabs').tabs({
    onSelect:function(title,index){
        let inv_general_rcv_id = $('#invgeneralisurtnFrm [name=inv_general_rcv_id]').val();
         let inv_general_rcv_item_id = $('#invgeneralisurtnitemFrm [name=id]').val();
        if(index==1){
			if(inv_general_rcv_id===''){
				$('#invyarnisurtntabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invgeneralisurtnitemFrm')
			$('#invgeneralisurtnitemFrm  [name=inv_general_rcv_id]').val(inv_general_rcv_id);
			MsInvGeneralIsuRtnItem.get(inv_general_rcv_id);
        }

        if(index==2){
			if(inv_general_rcv_item_id===''){
				$('#invyarnisurtntabs').tabs('select',1);
				msApp.showError('Select Item First',0);
				return;
		    }
		    msApp.resetForm('invgeneralisurtnitemdtlFrm')
			$('#invgeneralisurtnitemdtlFrm  [name=inv_general_rcv_item_id]').val(inv_general_rcv_item_id);
			MsInvGeneralIsuRtnItemDtl.get(inv_general_rcv_item_id);
        }
    }
});

