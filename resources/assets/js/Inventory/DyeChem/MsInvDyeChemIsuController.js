let MsInvDyeChemIsuModel = require('./MsInvDyeChemIsuModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemIsuController {
	constructor(MsInvDyeChemIsuModel)
	{
		this.MsInvDyeChemIsuModel = MsInvDyeChemIsuModel;
		this.formId='invdyechemisuFrm';
		this.dataTable='#invdyechemisuTbl';
		this.route=msApp.baseUrl()+"/invdyechemisu"
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
			this.MsInvDyeChemIsuModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemisuTbl').datagrid('reload');
		msApp.resetForm('invdyechemisuFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemIsuModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invdyechemisuitemFrm');
			if(response.data.fromData.receive_against_id==0){
				$('#invdyechemisuitemFrm  [name=rate]').removeAttr("readonly");
			}else{
				$('#invdyechemisuitemFrm  [name=rate]').attr("readonly",'readonly');
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsu.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemisuFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	openrequisitionWindow()
	{
		$('#invdyechemisurequisitionsearchwindow').window('open');
		$('#invdyechemisurequisitionsearchTbl').datagrid('loadData',[]);

	}

	requisitionSearchGrid(data){
		let self=this;
		$('#invdyechemisurequisitionsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisuFrm [name=rq_no]').val(row.rq_no);
				$('#invdyechemisuFrm [name=inv_dye_chem_isu_rq_id]').val(row.id);
				$('#invdyechemisuFrm [name=company_id]').val(row.company_id);
				$('#invdyechemisuFrm [name=location_id]').val(row.location_id);
				//$('#invdyechemisuFrm [name=fabric_desc]').val(row.fabric_desc);
				$('#invdyechemisuFrm [name=fabric_color]').val(row.fabric_color);
				$('#invdyechemisuFrm [name=batch_no]').val(row.batch_no);
				$('#invdyechemisuFrm [name=lap_dip_no]').val(row.lap_dip_no);
				$('#invdyechemisuFrm [name=isu_against_id]').val(row.menu_id);
				$('#invdyechemisurequisitionsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachRequisition(){
		let rq_no=$('#invdyechemisurequisitionsearchFrm [name=rq_no]').val();
		let company_id=$('#invdyechemisuFrm [name=company_id]').val();
		let location_id=$('#invdyechemisuFrm [name=location_id]').val();
		let batch_no=$('#invdyechemisurequisitionsearchFrm [name=batch_no]').val();
		let params={};
		params.rq_no=rq_no;
		params.company_id=company_id;
		params.location_id=location_id;
		params.batch_no=batch_no;
		let d=axios.get(this.route+'/getrequisition',{params})
		.then(function(response){
			$('#invdyechemisurequisitionsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	searchInvDyeChemIsuReq() {
		let params={};
		params.date_from=$('#date_from').val();
		params.date_to = $('#date_to').val();
		let data= axios.get(this.route+"/getinvdyechemisulist",{params});
		data.then(function (response) {
			$('#invdyechemisuTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsInvDyeChemIsu=new MsInvDyeChemIsuController(new MsInvDyeChemIsuModel());
MsInvDyeChemIsu.showGrid();
MsInvDyeChemIsu.requisitionSearchGrid([]);

$('#invdyechemisutabs').tabs({
    onSelect:function(title,index){
        let inv_dye_chem_isu_id = $('#invdyechemisuFrm [name=id]').val();
        if(index==1){
			if(inv_dye_chem_isu_id===''){
				$('#invyarnisutabs').tabs('select',0);
				msApp.showError('Select Entry First',0);
				return;
		    }
		    msApp.resetForm('invdyechemisuitemFrm');
			$('#invdyechemisuitemFrm  [name=inv_dye_chem_isu_id]').val(inv_dye_chem_isu_id);
			MsInvDyeChemIsuItem.get(inv_dye_chem_isu_id);
        }
    }
});

