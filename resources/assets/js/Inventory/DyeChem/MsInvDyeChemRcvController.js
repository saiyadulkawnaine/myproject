let MsInvDyeChemRcvModel = require('./MsInvDyeChemRcvModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemRcvController {
	constructor(MsInvDyeChemRcvModel)
	{
		this.MsInvDyeChemRcvModel = MsInvDyeChemRcvModel;
		this.formId='invdyechemrcvFrm';
		this.dataTable='#invdyechemrcvTbl';
		this.route=msApp.baseUrl()+"/invdyechemrcv"
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
			this.MsInvDyeChemRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invdyechemrcvFrm [id="supplier_id"]').combobox('setValue', '');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemrcvTbl').datagrid('reload');
		msApp.resetForm('invdyechemrcvFrm');
		$('#invdyechemrcvFrm [id="supplier_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemRcvModel.get(index,row);
		data.then(function(response){
			$('#invdyechemrcvFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			msApp.resetForm('invdyechemrcvitemFrm');
			if(response.data.fromData.receive_against_id==7){
				$('#invdyechemrcvitemFrm  [name=rate]').attr("readonly",'readonly');
			}else{
				$('#invdyechemrcvitemFrm  [name=rate]').removeAttr("readonly");
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemRcv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemrcvFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvDyeChemRcv=new MsInvDyeChemRcvController(new MsInvDyeChemRcvModel());
MsInvDyeChemRcv.showGrid();

$('#invdyechemrcvtabs').tabs({
    onSelect:function(title,index){
        let inv_dye_chem_rcv_id = $('#invdyechemrcvFrm [name=inv_dye_chem_rcv_id]').val();
        var data={};
		data.inv_dye_chem_rcv_id=inv_dye_chem_rcv_id;
        if(index==1){
			if(inv_dye_chem_rcv_id===''){
				$('#invyarnrcvtabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
			$('#invdyechemrcvitemFrm  [name=inv_dye_chem_rcv_id]').val(inv_dye_chem_rcv_id);
			MsInvDyeChemRcvItem.get(inv_dye_chem_rcv_id);
        }
    }
});

