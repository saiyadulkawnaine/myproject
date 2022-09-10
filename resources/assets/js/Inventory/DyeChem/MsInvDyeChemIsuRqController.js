let MsInvDyeChemIsuRqModel = require('./MsInvDyeChemIsuRqModel');
require('./../../datagrid-filter.js');
class MsInvDyeChemIsuRqController {
	constructor(MsInvDyeChemIsuRqModel)
	{
		this.MsInvDyeChemIsuRqModel = MsInvDyeChemIsuRqModel;
		this.formId='invdyechemisurqFrm';
		this.dataTable='#invdyechemisurqTbl';
		this.route=msApp.baseUrl()+"/invdyechemisurq"
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
			this.MsInvDyeChemIsuRqModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvDyeChemIsuRqModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invdyechemisurqFrm [id="buyer_id"]').combobox('setValue', '');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvDyeChemIsuRqModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvDyeChemIsuRqModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invdyechemisurqTbl').datagrid('reload');
		msApp.resetForm('invdyechemisurqFrm');
		$('#invdyechemisurqFrm [id="buyer_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvDyeChemIsuRqModel.get(index,row);
		data.then(function(response){
			$('#invdyechemisurqFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			msApp.resetForm('invdyechemisurqitemFrm');
			if(response.data.fromData.receive_against_id==0){
				$('#invdyechemisurqitemFrm  [name=rate]').removeAttr("readonly");
			}else{
				$('#invdyechemisurqitemFrm  [name=rate]').attr("readonly",'readonly');
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
		return '<a href="javascript:void(0)"  onClick="MsInvDyeChemIsuRq.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invdyechemisurqFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	openbatchWindow(){
		$('#invdyechemisurqbatchsearchwindow').window('open');
		$('#invdyechemisurqbatchsearchTbl').datagrid('loadData',[]);

	}
	batchSearchGrid(data){
		let self=this;
		$('#invdyechemisurqbatchsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqFrm [name=prod_batch_id]').val(row.id);
				$('#invdyechemisurqFrm [name=batch_no]').val(row.batch_no);
				$('#invdyechemisurqFrm [name=company_id]').val(row.company_id);
				$('#invdyechemisurqFrm [name=location_id]').val(row.location_id);
				$('#invdyechemisurqFrm [name=fabric_color]').val(row.color_name);
				$('#invdyechemisurqFrm [name=batch_color_name]').val(row.batch_color_name);
				$('#invdyechemisurqFrm [name=colorrange_id]').val(row.colorrange_id);
				$('#invdyechemisurqFrm [name=lap_dip_no]').val(row.lap_dip_no);
				$('#invdyechemisurqFrm [name=batch_wgt]').val(row.batch_wgt);
				$('#invdyechemisurqbatchsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachBatch(){
		let batch_date_from=$('#invdyechemisurqbatchsearchFrm [name=batch_date_from]').val();
		let batch_date_to=$('#invdyechemisurqbatchsearchFrm [name=batch_date_to]').val();
		let params={};
		params.batch_date_from=batch_date_from;
		params.batch_date_to=batch_date_to;
		let d=axios.get(this.route+'/getbatch',{params})
		.then(function(response){
			$('#invdyechemisurqbatchsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	openfabricWindow()
	{
		$('#invdyechemisurqfabricsearchwindow').window('open');
		$('#invdyechemisurqfabricsearchTbl').datagrid('loadData',[]);

	}



	fabricSearchGrid(data){
		let self=this;
		$('#invdyechemisurqfabricsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqFrm [name=fabrication_id]').val(row.id);
				$('#invdyechemisurqFrm [name=fabric_desc]').val(row.name+' '+row.composition_name);
				$('#invdyechemisurqfabricsearchwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachFabric(){
		let construction_name=$('#invdyechemisurqfabricsearchFrm [name=construction_name]').val();
		let composition_name=$('#invdyechemisurqfabricsearchFrm [name=composition_name]').val();
		let params={};
		params.construction_name=construction_name;
		params.composition_name=composition_name;
		let d=axios.get(this.route+'/getfabric',{params})
		.then(function(response){
			$('#invdyechemisurqfabricsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	calculate_liqure_wgt()
	{
		var batch_wgt= $('#invdyechemisurqFrm  [name=batch_wgt]').val();
		var liqure_ratio= $('#invdyechemisurqFrm  [name=liqure_ratio]').val();
		var liqure_wgt=batch_wgt*liqure_ratio;
		$('#invdyechemisurqFrm  [name=liqure_wgt]').val(liqure_wgt);
	}

	openEmpOperatorWindow(){
		$('#openempoperatorwindow').window('open');
	}

	getEmpOperatorParams(){
		let params={};
		params.company_id=$('#empoperatorFrm [name=company_id]').val();
		params.designation_id=$('#empoperatorFrm [name=designation_id]').val();
		params.department_id=$('#empoperatorFrm [name=department_id]').val();
		return params;
	}

	searchEmpOperator(){
		let params=this.getEmpOperatorParams();
		let rpt = axios.get(this.route+"/operatoremployee",{params})
		.then(function(response){
			$('#empoperatorTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showEmpOperatorGrid(data){
		let self=this;
		var pr=$('#empoperatorTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqFrm  [name=operator_id]').val(row.id);
				$('#invdyechemisurqFrm  [name=operator_name]').val(row.employee_name);
				$('#openempoperatorwindow').window('close')
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}

	openInchargeWindow(){
		$('#openinchargewindow').window('open');
	}

	getInchargeParams(){
		let params={};
		params.company_id=$('#inchargesearchFrm [name=company_id]').val();
		params.designation_id=$('#inchargesearchFrm [name=designation_id]').val();
		params.department_id=$('#inchargesearchFrm [name=department_id]').val();
		return params;
	}

	searchIncharge(){
		let params=this.getInchargeParams();
		let d = axios.get(this.route+"/employeeincharge",{params})
		.then(function(response){
			$('#inchargesearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		})
	}

	showInchargeGrid(data){
		let self=this;
		var sg=$('#inchargesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#invdyechemisurqFrm  [name=incharge_id]').val(row.id);
				$('#invdyechemisurqFrm  [name=incharge_name]').val(row.employee_name);
				$('#openinchargewindow').window('close')
			}
		});
		sg.datagrid('enableFilter').datagrid('loadData',data);
	}

	searchRq(){
		let params={};
		params.from_batch_date=$('#from_batch_date').val();
		params.to_batch_date=$('#to_batch_date').val();
		params.from_rq_date=$('#from_rq_date').val();
		params.to_rq_date=$('#to_rq_date').val();
		let data= axios.get(this.route+"/getrq",{params});
		data.then(function (response) {
			$('#invdyechemisurqTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


}
window.MsInvDyeChemIsuRq=new MsInvDyeChemIsuRqController(new MsInvDyeChemIsuRqModel());
MsInvDyeChemIsuRq.showGrid();
MsInvDyeChemIsuRq.batchSearchGrid([]);
MsInvDyeChemIsuRq.fabricSearchGrid([]);
MsInvDyeChemIsuRq.showEmpOperatorGrid([]);
MsInvDyeChemIsuRq.showInchargeGrid([]);


$('#invdyechemisurqtabs').tabs({
    onSelect:function(title,index){
        let inv_dye_chem_isu_rq_id = $('#invdyechemisurqFrm [name=id]').val();
        if(index==1){
			if(inv_dye_chem_isu_rq_id===''){
				$('#invyarnisurqtabs').tabs('select',0);
				msApp.showError('Select Entry First',0);
				return;
		    }
		    msApp.resetForm('invdyechemisurqitemFrm');
			$('#invdyechemisurqitemFrm  [name=inv_dye_chem_isu_rq_id]').val(inv_dye_chem_isu_rq_id);
			MsInvDyeChemIsuRqItem.get(inv_dye_chem_isu_rq_id);
        }
    }
});

