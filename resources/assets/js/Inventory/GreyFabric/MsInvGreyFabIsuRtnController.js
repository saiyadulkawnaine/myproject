let MsInvGreyFabIsuRtnModel = require('./MsInvGreyFabIsuRtnModel');
require('./../../datagrid-filter.js');
class MsInvGreyFabIsuRtnController {
	constructor(MsInvGreyFabIsuRtnModel)
	{
		this.MsInvGreyFabIsuRtnModel = MsInvGreyFabIsuRtnModel;
		this.formId='invgreyfabisurtnFrm';
		this.dataTable='#invgreyfabisurtnTbl';
		this.route=msApp.baseUrl()+"/invgreyfabisurtn"
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
			this.MsInvGreyFabIsuRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabIsuRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabIsuRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabIsuRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgreyfabisurtnTbl').datagrid('reload');
		msApp.resetForm('invgreyfabisurtnFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGreyFabIsuRtnModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invgreyfabisurtnitemFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabIsuRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgreyfabisurtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	showPdfTwo()
	{
		var id= $('#invgreyfabisurtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}

}
window.MsInvGreyFabIsuRtn=new MsInvGreyFabIsuRtnController(new MsInvGreyFabIsuRtnModel());
MsInvGreyFabIsuRtn.showGrid();

$('#invgreyfabisurtntabs').tabs({
    onSelect:function(title,index){
        let inv_grey_fab_rcv_id = $('#invgreyfabisurtnFrm [name=inv_grey_fab_rcv_id]').val();
         let inv_grey_fab_rcv_item_id = $('#invgreyfabisurtnitemFrm [name=id]').val();
        if(index==1){
			if(inv_grey_fab_rcv_id===''){
				$('#invgreyfabisurtntabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invgreyfabisurtnitemFrm')
			$('#invgreyfabisurtnitemFrm  [name=inv_grey_fab_rcv_id]').val(inv_grey_fab_rcv_id);
			MsInvGreyFabIsuRtnItem.get(inv_grey_fab_rcv_id);
        }

       /* if(index==2){
			if(inv_dye_chem_rcv_item_id===''){
				$('#invgreyfabisurtntabs').tabs('select',1);
				msApp.showError('Select Item First',0);
				return;
		    }
		    msApp.resetForm('invgreyfabisurtnitemdtlFrm')
			$('#invgreyfabisurtnitemdtlFrm  [name=inv_dye_chem_rcv_item_id]').val(inv_dye_chem_rcv_item_id);
			MsInvGreyFabIsuRtnItemDtl.get(inv_dye_chem_rcv_item_id);
        }*/
    }
});

