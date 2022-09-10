let MsInvDyeChemIsuRtnModel = require('./MsInvDyeChemIsuRtnModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemIsuRtnController {
	constructor(MsInvDyeChemIsuRtnModel)
	{
		this.MsInvDyeChemIsuRtnModel = MsInvDyeChemIsuRtnModel;
		this.formId='invdyechemisurtnFrm';
		this.dataTable='#invdyechemisurtnTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurtn"
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
			this.MsInvDyeChemIsuRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemisurtnTbl').datagrid('reload');
		msApp.resetForm('invdyechemisurtnFrm');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemIsuRtnModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invdyechemisurtnitemFrm');
			/*if(response.data.fromData.receive_against_id==8){
				$('#invdyechemisurtnitemFrm  [name=rate]').attr("readonly",'readonly');
			}else{
				$('#invdyechemisurtnitemFrm  [name=rate]').removeAttr("readonly");
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemisurtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvDyeChemIsuRtn=new MsInvDyeChemIsuRtnController(new MsInvDyeChemIsuRtnModel());
MsInvDyeChemIsuRtn.showGrid();

$('#invdyechemisurtntabs').tabs({
    onSelect:function(title,index){
        let inv_dye_chem_rcv_id = $('#invdyechemisurtnFrm [name=inv_dye_chem_rcv_id]').val();
         let inv_dye_chem_rcv_item_id = $('#invdyechemisurtnitemFrm [name=id]').val();
        if(index==1){
			if(inv_dye_chem_rcv_id===''){
				$('#invdyechemisurtntabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
		    msApp.resetForm('invdyechemisurtnitemFrm')
			$('#invdyechemisurtnitemFrm  [name=inv_dye_chem_rcv_id]').val(inv_dye_chem_rcv_id);
			MsInvDyeChemIsuRtnItem.get(inv_dye_chem_rcv_id);
        }

        if(index==2){
			if(inv_dye_chem_rcv_item_id===''){
				$('#invdyechemisurtntabs').tabs('select',1);
				msApp.showError('Select Item First',0);
				return;
		    }
		    msApp.resetForm('invdyechemisurtnitemdtlFrm')
			$('#invdyechemisurtnitemdtlFrm  [name=inv_dye_chem_rcv_item_id]').val(inv_dye_chem_rcv_item_id);
			MsInvDyeChemIsuRtnItemDtl.get(inv_dye_chem_rcv_item_id);
        }
    }
});

