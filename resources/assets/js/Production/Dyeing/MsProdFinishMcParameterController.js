let MsProdFinishMcParameterModel = require('./MsProdFinishMcParameterModel');
class MsProdFinishMcParameterController {
	constructor(MsProdFinishMcParameterModel) {
		this.MsProdFinishMcParameterModel = MsProdFinishMcParameterModel;
		this.formId = 'prodfinishmcparameterFrm';
		this.dataTable = '#prodfinishmcparameterTbl';
		this.route = msApp.baseUrl() + "/prodfinishmcparameter"
	}

	submit() {
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


		let formObj = msApp.get(this.formId);
		if (formObj.id) {
			this.MsProdFinishMcParameterModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsProdFinishMcParameterModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#prodfinishmcparameterFrm [name=prod_finish_mc_date_id]').val($('#prodfinishmcdateFrm [name=id]').val());
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsProdFinishMcParameterModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsProdFinishMcParameterModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#prodfinishmcparameterTbl').datagrid('reload');
		msApp.resetForm('prodfinishmcparameterFrm');
		$('#prodfinishmcparameterFrm [name=prod_finish_mc_date_id]').val($('#prodfinishmcdateFrm [name=id]').val());

	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		this.MsProdFinishMcParameterModel.get(index, row);
	}

	showGrid(prod_finish_mc_date_id) {
		let self = this;
		var data = {};
		data.prod_finish_mc_date_id = prod_finish_mc_date_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			url: this.route,
			showFooter:true,
			onClickRow: function (index, row) {
				self.edit(index, row);

			},
			onLoadSuccess:function(data){
				var tWorkingMinute = 0 ;
				for(var i=0; i<data.rows.length; i++){
					tWorkingMinute+=data.rows[i]['working_minute'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{
						working_minute: tWorkingMinute.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);

			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row) {
		return '<a href="javascript:void(0)" onClick="MsProdFinishMcParameter.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	prodFinishParameterWindow(){
		$('#prodfinishmcparameterWindow').window('open');
	}

	getBatch()
	{
		let params={};
		params.company_id=$('#prodfinishmcparametersearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodfinishmcparametersearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodfinishmcparametersearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getfinishmcparameterbatch",{params});
		data.then(function (response) {
			$('#prodfinishmcparametersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


 
	showProdFinishMcParameterBatchGrid(data){
		let self = this;
		$('#prodfinishmcparametersearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodfinishmcparameterFrm [name=prod_batch_id]').val(row.id);
					$('#prodfinishmcparameterFrm [name=batch_no]').val(row.batch_no);
     				$('#prodfinishmcparameterFrm [name=batch_wgt]').val(row.batch_wgt);
					$('#prodfinishmcparameterWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	openManpowerEmployee(){
		$('#prodFinishMcParameterEmployeeWindow').window('open');
	}

	getParams(){
		let params = {}
		params.prodfinishmcsetupId=$('#prodfinishmcsetupFrm [name=id]').val();
		params.designation_id=$('#prodFinishMcParametersearchFrm [name=designation_id]').val();
		params.department_id=$('#prodFinishMcParametersearchFrm [name=department_id]').val();
		params.company_id=$('#prodFinishMcParametersearchFrm [name=company_id]').val();
		return params;
	}
	
	searchEmployeeGrid(){
		let params=this.getParams();
		let emp = axios.get(this.route + '/getemployee', { params }).then(function(response){
			$('#prodFinishMcParameterSearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
		
	}

	showEmployeeGrid(data){
		let self = this;
		$('#prodFinishMcParameterSearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				// self.edit(index,row);
				$('#prodfinishmcparameterFrm [name=employee_h_r_id]').val(row.id);
				$('#prodfinishmcparameterFrm [name=employee_name]').val(row.name);
				$('#prodFinishMcParameterSearchTbl').datagrid('loadData',[]);
				$('#prodFinishMcParameterEmployeeWindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	prodFinishMcParameterCalculateWorkingMinute() {
		let rmp;
		let gsm_weight;
		let dia;
		let batch_wgt;
		rmp = $('#prodfinishmcparameterFrm [name=rmp]').val();
		gsm_weight = $('#prodfinishmcparameterFrm [name=gsm_weight]').val();
		dia = $('#prodfinishmcparameterFrm [name=dia]').val();
		batch_wgt = $('#prodfinishmcparameterFrm [name=batch_wgt]').val();
		let working_minute = (batch_wgt * 1000 * 39.37) / (rmp * gsm_weight * dia);
		$('#prodfinishmcparameterFrm [name=working_minute]').val(working_minute)
	}
}
window.MsProdFinishMcParameter = new MsProdFinishMcParameterController(new MsProdFinishMcParameterModel());
MsProdFinishMcParameter.showProdFinishMcParameterBatchGrid([]);
MsProdFinishMcParameter.showEmployeeGrid([]);
