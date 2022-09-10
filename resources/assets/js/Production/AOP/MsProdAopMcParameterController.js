let MsProdAopMcParameterModel = require('./MsProdAopMcParameterModel');
class MsProdAopMcParameterController {
	constructor(MsProdAopMcParameterModel) {
		this.MsProdAopMcParameterModel = MsProdAopMcParameterModel;
		this.formId = 'prodaopmcparameterFrm';
		this.dataTable = '#prodaopmcparameterTbl';
		this.route = msApp.baseUrl() + "/prodaopmcparameter"
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
			this.MsProdAopMcParameterModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsProdAopMcParameterModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#prodaopmcparameterFrm [name=prod_aop_mc_date_id]').val($('#prodaopmcdateFrm [name=id]').val());
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsProdAopMcParameterModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsProdAopMcParameterModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#prodaopmcparameterTbl').datagrid('reload');
		MsProdAopMcParameter.resetForm();
		$('#prodaopmcparameterFrm [name=prod_aop_mc_date_id]').val($('#prodaopmcdateFrm [name=id]').val());
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		this.MsProdAopMcParameterModel.get(index, row);
	}

	showGrid(prod_aop_mc_date_id) {
		let self = this;
		var data = {};
		data.prod_aop_mc_date_id = prod_aop_mc_date_id;
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
				var tTgtQty = 0 ;
				var tProductionPerHr = 0 ;
				for(var i=0; i<data.rows.length; i++){
					tTgtQty+=data.rows[i]['tgt_qty'].replace(/,/g,'')*1;
					tProductionPerHr+=data.rows[i]['production_per_hr'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{
						tgt_qty: tTgtQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						production_per_hr: tProductionPerHr.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
					}
				]);

			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row) {
		return '<a href="javascript:void(0)" onClick="MsProdAopMcParameter.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	prodAopParameterWindow(){
		$('#prodaopmcWindow').window('open');
	}

	getBatch()
	{
		let params={};
		params.company_id=$('#prodaopmcbatchparametersearchFrm  [name=company_id]').val();
		params.batch_no=$('#prodaopmcbatchparametersearchFrm  [name=batch_no]').val();
		params.batch_for=$('#prodaopmcbatchparametersearchFrm  [name=batch_for]').val();
		let data= axios.get(this.route+"/getbatch",{params});
		data.then(function (response) {
			$('#prodaopmcbatchsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	showbatchGrid(data){
		let self = this;
		$('#prodaopmcbatchsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodaopmcparameterFrm [name=prod_aop_batch_id]').val(row.id);
					$('#prodaopmcparameterFrm [name=batch_no]').val(row.batch_no);
					$('#prodaopmcWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}



	openManpowerEmployee(){
		$('#prodaopMcParameterEmployeeWindow').window('open');
	}

	getParams(){
		let params = {};
		params.prodfinishmcsetupId=$('#prodaopmcsetupFrm [name=id]').val();
		params.designation_id=$('#prodaopMcParametersearchFrm [name=designation_id]').val();
		params.department_id=$('#prodaopMcParametersearchFrm [name=department_id]').val();
		params.company_id=$('#prodaopMcParametersearchFrm [name=company_id]').val();
		return params;
	}
	searchEmployeeGrid(){
		let params=this.getParams();
		let emp = axios.get(this.route + '/getemployee', { params }).then(function(response){
			$('#prodaopMcParameterSearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});

	}

	showEmployeeGrid(data){
		let self = this;
		$('#prodaopMcParameterSearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				// self.edit(index,row);
				$('#prodaopmcparameterFrm [name=employee_h_r_id]').val(row.id);
				$('#prodaopmcparameterFrm [name=name]').val(row.name);
				$('#prodAopMcParameterSearchTbl').datagrid('loadData',[]);
				$('#prodaopMcParameterEmployeeWindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	prodAopMcParameterCalculateProdunctionPerHr() {
		let rpm;
		let gsm_weight;
		let dia;
		let repeat_size;
		rpm = $('#prodaopmcparameterFrm [name=rpm]').val();
		gsm_weight = $('#prodaopmcparameterFrm [name=gsm_weight]').val();
		dia = $('#prodaopmcparameterFrm [name=dia]').val();
		repeat_size = $('#prodaopmcparameterFrm [name=repeat_size]').val();
		let production_per_hr = (rpm * gsm_weight *dia*repeat_size* 60) / (1000*39.37*36);
		$('#prodaopmcparameterFrm [name=production_per_hr]').val(production_per_hr);
	}

}
window.MsProdAopMcParameter = new MsProdAopMcParameterController(new MsProdAopMcParameterModel());
MsProdAopMcParameter.showbatchGrid([]);
MsProdAopMcParameter.showEmployeeGrid([]);

