let MsInvFinishFabIsuRtnModel = require('./MsInvFinishFabIsuRtnModel');
require('./../../datagrid-filter.js');
class MsInvFinishFabIsuRtnController {
	constructor(MsInvFinishFabIsuRtnModel)
	{
		this.MsInvFinishFabIsuRtnModel = MsInvFinishFabIsuRtnModel;
		this.formId='invfinishfabisurtnFrm';
		this.dataTable='#invfinishfabisurtnTbl';
		this.route=msApp.baseUrl()+"/invfinishfabisurtn"
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
			this.MsInvFinishFabIsuRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabIsuRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabIsuRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabIsuRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invfinishfabisurtnTbl').datagrid('reload');
		msApp.resetForm('invfinishfabisurtnFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvFinishFabIsuRtnModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invfinishfabisurtnitemFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsInvFinishFabIsuRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invfinishfabisurtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	showPdfTwo()
	{
		var id= $('#invfinishfabisurtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}

}
window.MsInvFinishFabIsuRtn=new MsInvFinishFabIsuRtnController(new MsInvFinishFabIsuRtnModel());
MsInvFinishFabIsuRtn.showGrid();

$('#invfinishfabisurtntabs').tabs({
    onSelect:function(title,index){
        let inv_finish_fab_rcv_id = $('#invfinishfabisurtnFrm [name=inv_finish_fab_rcv_id]').val();
         let inv_finish_fab_rcv_item_id = $('#invfinishfabisurtnitemFrm [name=id]').val();
        if(index==1){
			if(inv_finish_fab_rcv_id===''){
				$('#invfinishfabisurtntabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invfinishfabisurtnitemFrm')
			$('#invfinishfabisurtnitemFrm  [name=inv_finish_fab_rcv_id]').val(inv_finish_fab_rcv_id);
			MsInvFinishFabIsuRtnItem.get(inv_finish_fab_rcv_id);
        }

       /* if(index==2){
			if(inv_dye_chem_rcv_item_id===''){
				$('#invfinishfabisurtntabs').tabs('select',1);
				msApp.showError('Select Item First',0);
				return;
		    }
		    msApp.resetForm('invfinishfabisurtnitemdtlFrm')
			$('#invfinishfabisurtnitemdtlFrm  [name=inv_dye_chem_rcv_item_id]').val(inv_dye_chem_rcv_item_id);
			MsInvFinishFabIsuRtnItemDtl.get(inv_dye_chem_rcv_item_id);
        }*/
    }
});

