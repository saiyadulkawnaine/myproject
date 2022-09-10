let MsAgreementModel = require('./MsAgreementModel');
require('./../datagrid-filter.js');
class MsAgreementController {
	constructor(MsAgreementModel)
	{
		this.MsAgreementModel = MsAgreementModel;
		this.formId='agreementFrm';
		this.dataTable='#agreementTbl';
		this.route=msApp.baseUrl()+"/agreement"
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
			this.MsAgreementModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAgreementModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#agreementFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAgreementModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAgreementModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#agreementTbl').datagrid('reload');
        msApp.resetForm('agreementFrm');
        $('#agreementFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		
		row.route=this.route;
		row.formId=this.formId;
		data=this.MsAgreementModel.get(index,row);
		data.then(function(response){
			$('#agreementFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		}).catch(function(error){
			console.log(error);
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAgreement.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsAgreement=new MsAgreementController(new MsAgreementModel());
MsAgreement.showGrid();

$('#agreementtabs').tabs({
    onSelect:function(title,index){
        let agreement_id = $('#agreementFrm [name=id]').val();
        
        var data={};
		    data.agreement_id=agreement_id;
        if(index==1){
				if(agreement_id===''){
					$('#agreementtabs').tabs('select',0);
					msApp.showError('Select An Agreement First',0);
					return;
				}
				msApp.resetForm('agreementfileFrm');
				$('#agreementfileFrm  [name=agreement_id]').val(agreement_id);
				MsAgreementFile.showGrid(agreement_id);
            }
        if(index==2){
				if(agreement_id===''){
					$('#agreementtabs').tabs('select',0);
					msApp.showError('Select An Agreement First',0);
					return;
				}
				msApp.resetForm('agreementpoFrm');
				$('#agreementpoFrm  [name=agreement_id]').val(agreement_id);
				//MsAgreementPo.showGrid();
				MsAgreementPo.get(agreement_id);

            }
    }
});

