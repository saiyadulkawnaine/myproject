let MsInvDyeChemTransInModel = require('./MsInvDyeChemTransInModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemTransInController {
	constructor(MsInvDyeChemTransInModel)
	{
		this.MsInvDyeChemTransInModel = MsInvDyeChemTransInModel;
		this.formId='invdyechemtransinFrm';
		this.dataTable='#invdyechemtransinTbl';
		this.route=msApp.baseUrl()+"/invdyechemtransin"
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
			this.MsInvDyeChemTransInModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemTransInModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemTransInModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemTransInModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemtransinTbl').datagrid('reload');
		msApp.resetForm('invdyechemtransinFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemTransInModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invdyechemtransinitemFrm');
			/*if(response.data.fromData.receive_against_id==8){
				$('#invdyechemtransinitemFrm  [name=rate]').attr("readonly",'readonly');
			}else{
				$('#invdyechemtransinitemFrm  [name=rate]').removeAttr("readonly");
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemTransIn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemtransinFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvDyeChemTransIn=new MsInvDyeChemTransInController(new MsInvDyeChemTransInModel());
MsInvDyeChemTransIn.showGrid();

$('#invdyechemtransintabs').tabs({
    onSelect:function(title,index){
        let inv_dye_chem_rcv_id = $('#invdyechemtransinFrm [name=inv_dye_chem_rcv_id]').val();
         let inv_dye_chem_rcv_item_id = $('#invdyechemtransinitemFrm [name=id]').val();
        if(index==1){
			if(inv_dye_chem_rcv_id===''){
				$('#invdyechemtransintabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invdyechemtransinitemFrm')
			$('#invdyechemtransinitemFrm  [name=inv_dye_chem_rcv_id]').val(inv_dye_chem_rcv_id);
			MsInvDyeChemTransInItem.get(inv_dye_chem_rcv_id);
        }

        if(index==2){
			if(inv_dye_chem_rcv_item_id===''){
				$('#invdyechemtransintabs').tabs('select',1);
				msApp.showError('Select Item First',0);
				return;
		    }
		    msApp.resetForm('invdyechemtransinitemdtlFrm')
			$('#invdyechemtransinitemdtlFrm  [name=inv_dye_chem_rcv_item_id]').val(inv_dye_chem_rcv_item_id);
			MsInvDyeChemTransInItemDtl.get(inv_dye_chem_rcv_item_id);
        }
    }
});

