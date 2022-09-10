let MsProdFinishAopMcParameterModel = require('./MsProdFinishAopMcParameterModel');
class MsProdFinishAopMcParameterController {
	constructor(MsProdFinishAopMcParameterModel) {
		this.MsProdFinishAopMcParameterModel = MsProdFinishAopMcParameterModel;
		this.formId = 'prodfinishaopmcparameterFrm';
		this.dataTable = '#prodfinishaopmcparameterTbl';
		this.route = msApp.baseUrl() + "/prodfinishaopmcparameter"
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
			this.MsProdFinishAopMcParameterModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsProdFinishAopMcParameterModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#prodfinishaopmcparameterFrm [name=prod_finish_aop_mc_date_id]').val($('#prodfinishaopmcdateFrm [name=id]').val());
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsProdFinishAopMcParameterModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsProdFinishAopMcParameterModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#prodfinishaopmcparameterTbl').datagrid('reload');
		MsProdFinishAopMcParameter.resetForm();
		$('#prodfinishaopmcparameterFrm [name=prod_finish_aop_mc_date_id]').val($('#prodfinishaopmcdateFrm [name=id]').val());

	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		this.MsProdFinishAopMcParameterModel.get(index, row);
	}

	showGrid(prod_finish_aop_mc_date_id) {
		let self = this;
		var data = {};
		data.prod_finish_aop_mc_date_id = prod_finish_aop_mc_date_id;
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
		return '<a href="javascript:void(0)" onClick="MsProdFinishAopMcParameter.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	prodFinishAopParameterBatchWindow(){
		$('#prodfinishaopmcbatchWindow').window('open');
	}

	getAopBatch()
	{
		let params={};
		params.company_id=$('#prodfinishaopmcbatchparametersearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodfinishaopmcbatchparametersearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodfinishaopmcbatchparametersearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getaopbatch",{params});
		data.then(function (response) {
			$('#prodfinishaopmcbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	showbatchGrid(data){
		let self = this;
		$('#prodfinishaopmcbatchsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodfinishaopmcparameterFrm [name=prod_aop_batch_id]').val(row.id);
					$('#prodfinishaopmcparameterFrm [name=batch_no]').val(row.batch_no);
					$('#prodfinishaopmcparameterFrm [name=fabric_wgt]').val(row.fabric_wgt);
					$('#prodfinishaopmcbatchWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}



	openProdFinishEmployee(){
		$('#prodfinishaopEmployeeSearchWindow').window('open');
	}

	getParams(){
		let params = {};
		params.prodfinishmcsetupId=$('#prodfinishaopmcsetupFrm [name=id]').val();
		params.designation_id=$('#prodfinishaopemployeeSearchFrm [name=designation_id]').val();
		params.department_id=$('#prodfinishaopemployeeSearchFrm [name=department_id]').val();
		params.company_id=$('#prodfinishaopemployeeSearchFrm [name=company_id]').val();
		return params;
	}
	searchProdFinishEmployeeGrid(){
		let params=this.getParams();
		let emp = axios.get(this.route + '/getemployee', { params }).then(function(response){
			$('#prodfinishaopemployeeSearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});

	}

	showEmployeeGrid(data){
		let self = this;
		$('#prodfinishaopemployeeSearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				// self.edit(index,row);
				$('#prodfinishaopmcparameterFrm [name=employee_h_r_id]').val(row.id);
				$('#prodfinishaopmcparameterFrm [name=name]').val(row.name);
				$('#prodfinishAopMcParameterSearchTbl').datagrid('loadData',[]);
				$('#prodfinishaopEmployeeSearchWindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	CalculateWorkingMinute() {
		let rmp;
		let gsm_weight;
		let dia;
		let fabric_wgt;
		rmp = $('#prodfinishaopmcparameterFrm [name=rmp]').val();
		gsm_weight = $('#prodfinishaopmcparameterFrm [name=gsm_weight]').val();
		dia = $('#prodfinishaopmcparameterFrm [name=dia]').val();
		fabric_wgt = $('#prodfinishaopmcparameterFrm [name=fabric_wgt]').val();
		let working_minute = (fabric_wgt * 1000 * 39.37) / (rmp * gsm_weight * dia);
		$('#prodfinishaopmcparameterFrm [name=working_minute]').val(working_minute);
	}

}
window.MsProdFinishAopMcParameter = new MsProdFinishAopMcParameterController(new MsProdFinishAopMcParameterModel());
MsProdFinishAopMcParameter.showbatchGrid([]);
MsProdFinishAopMcParameter.showEmployeeGrid([]);