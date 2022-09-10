let MsInvDyeChemIsuRqAddModel = require('./MsInvDyeChemIsuRqAddModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemIsuRqAddController {
	constructor(MsInvDyeChemIsuRqAddModel)
	{
		this.MsInvDyeChemIsuRqAddModel = MsInvDyeChemIsuRqAddModel;
		this.formId='invdyechemisurqaddFrm';
		this.dataTable='#invdyechemisurqaddTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurqadd"
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
			this.MsInvDyeChemIsuRqAddModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRqAddModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRqAddModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRqAddModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemisurqaddTbl').datagrid('reload');
		msApp.resetForm('invdyechemisurqaddFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemIsuRqAddModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invdyechemisurqadditemFrm');
			if(response.data.fromData.receive_against_id==0){
				$('#invdyechemisurqadditemFrm  [name=rate]').removeAttr("readonly");
			}else{
				$('#invdyechemisurqadditemFrm  [name=rate]').attr("readonly",'readonly');
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRqAdd.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemisurqaddFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	openrequisitionWindow()
	{
		$('#invdyechemisurqaddrequisitionsearchwindow').window('open');
		$('#invdyechemisurqaddrequisitionsearchTbl').datagrid('loadData',[]);

	}

	requisitionSearchGrid(data){
		let self=this;
		$('#invdyechemisurqaddrequisitionsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqaddFrm [name=root_rq_no]').val(row.rq_no);
				$('#invdyechemisurqaddFrm [name=root_id]').val(row.id);
				$('#invdyechemisurqaddFrm [name=company_id]').val(row.company_id);
				$('#invdyechemisurqaddFrm [name=location_id]').val(row.location_id);
				$('#invdyechemisurqaddFrm [name=fabric_desc]').val(row.fabric_desc);
				$('#invdyechemisurqaddFrm [name=fabric_color]').val(row.fabric_color);
				$('#invdyechemisurqaddFrm [name=colorrange_id]').val(row.colorrange_id);
				$('#invdyechemisurqaddFrm [name=colorrange_id]').val(row.colorrange_id);
				$('#invdyechemisurqaddFrm [name=batch_no]').val(row.batch_no);
				$('#invdyechemisurqaddFrm [name=lap_dip_no]').val(row.lap_dip_no);
				$('#invdyechemisurqaddFrm [name=batch_wgt]').val(row.batch_wgt);
				$('#invdyechemisurqaddFrm [name=liqure_ratio]').val(row.liqure_ratio);
				$('#invdyechemisurqaddFrm [name=liqure_wgt]').val(row.liqure_wgt);
				$('#invdyechemisurqaddFrm [name=buyer_id]').val(row.buyer_id);
				$('#invdyechemisurqaddrequisitionsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachRequisition(){
		let rq_no=$('#invdyechemisurqaddrequisitionsearchFrm [name=rq_no]').val();
		let company_id=$('#invdyechemisurqaddrequisitionsearchFrm [name=company_id]').val();
		let batch_no=$('#invdyechemisurqaddrequisitionsearchFrm [name=batch_no]').val();
		let params={};
		params.rq_no=rq_no;
		params.company_id=company_id;
		params.batch_no=batch_no;
		let d=axios.get(this.route+'/getrequisition',{params})
		.then(function(response){
			$('#invdyechemisurqaddrequisitionsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	searchRq(){
		let params={};
		params.from_rq_date=$('#from_rq_date').val();
		params.to_rq_date=$('#to_rq_date').val();
		let data= axios.get(this.route+"/getrq",{params});
		data.then(function (response) {
			$('#invdyechemisurqaddTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsInvDyeChemIsuRqAdd=new MsInvDyeChemIsuRqAddController(new MsInvDyeChemIsuRqAddModel());
MsInvDyeChemIsuRqAdd.showGrid();
MsInvDyeChemIsuRqAdd.requisitionSearchGrid([]);

$('#invdyechemisurqaddtabs').tabs({
    onSelect:function(title,index){
        let inv_dye_chem_isu_rq_id = $('#invdyechemisurqaddFrm [name=id]').val();
        if(index==1){
			if(inv_dye_chem_isu_rq_id===''){
				$('#invyarnisurqaddtabs').tabs('select',0);
				msApp.showError('Select Entry First',0);
				return;
		    }
		    msApp.resetForm('invdyechemisurqadditemFrm');
			$('#invdyechemisurqadditemFrm  [name=inv_dye_chem_isu_rq_id]').val(inv_dye_chem_isu_rq_id);
			MsInvDyeChemIsuRqItemAdd.get(inv_dye_chem_isu_rq_id);
        }
    }
});

