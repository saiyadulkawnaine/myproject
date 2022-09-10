let MsInvDyeChemIsuRqSrpModel = require('./MsInvDyeChemIsuRqSrpModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemIsuRqSrpController {
	constructor(MsInvDyeChemIsuRqSrpModel)
	{
		this.MsInvDyeChemIsuRqSrpModel = MsInvDyeChemIsuRqSrpModel;
		this.formId='invdyechemisurqsrpFrm';
		this.dataTable='#invdyechemisurqsrpTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurqsrp"
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
			this.MsInvDyeChemIsuRqSrpModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRqSrpModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invdyechemisurqsrpFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRqSrpModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRqSrpModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemisurqsrpTbl').datagrid('reload');
		msApp.resetForm('invdyechemisurqsrpFrm');
		$('#invdyechemisurqsrpFrm [id="buyer_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemIsuRqSrpModel.get(index,row);
		data.then(function(response){
			$('#invdyechemisurqsrpFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			msApp.resetForm('invdyechemisurqitemsrpFrm');
			if(response.data.fromData.receive_against_id==0){
				$('#invdyechemisurqitemsrpFrm  [name=rate]').removeAttr("readonly");
			}else{
				$('#invdyechemisurqitemsrpFrm  [name=rate]').attr("readonly",'readonly');
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRqSrp.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemisurqsrpFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	searchRq(){
		let params={};
		params.from_rq_date=$('#from_rq_date').val();
		params.to_rq_date=$('#to_rq_date').val();
		let data= axios.get(this.route+"/getrq",{params});
		data.then(function (response) {
			$('#invdyechemisurqsrpTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsInvDyeChemIsuRqSrp=new MsInvDyeChemIsuRqSrpController(new MsInvDyeChemIsuRqSrpModel());
MsInvDyeChemIsuRqSrp.showGrid();

$('#invdyechemisurqsrptabs').tabs({
    onSelect:function(title,index){
        let inv_dye_chem_isu_rq_id = $('#invdyechemisurqsrpFrm [name=id]').val();
        if(index==1){
			if(inv_dye_chem_isu_rq_id===''){
				$('#invyarnisurqaoptabs').tabs('select',0);
				msApp.showError('Select Entry First',0);
				return;
		    }
		    msApp.resetForm('invdyechemisurqitemsrpFrm');
			$('#invdyechemisurqitemsrpFrm  [name=inv_dye_chem_isu_rq_id]').val(inv_dye_chem_isu_rq_id);
			MsInvDyeChemIsuRqItemSrp.get(inv_dye_chem_isu_rq_id);
			//MsInvDyeChemIsuRqItemSrp.getPrintType(inv_dye_chem_isu_rq_id);
        }
    }
});

