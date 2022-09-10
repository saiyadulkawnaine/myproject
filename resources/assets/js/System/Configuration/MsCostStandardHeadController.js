let MsCostStandardHeadModel = require('./MsCostStandardHeadModel');
require('./../../datagrid-filter.js');
class MsCostStandardHeadController {
	constructor(MsCostStandardHeadModel)
	{
		this.MsCostStandardHeadModel = MsCostStandardHeadModel;
		this.formId='coststandardheadFrm';
		this.dataTable='#coststandardheadTbl';
		this.route=msApp.baseUrl()+"/coststandardhead";
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
		let cost_standard_id = $('#coststandardFrm  [name=id]').val();

		let formObj=msApp.get(this.formId);
		formObj.cost_standard_id=cost_standard_id;
		if(formObj.id){
			this.MsCostStandardHeadModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCostStandardHeadModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#coststandardheadFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
		let cost_standard_id = $('#coststandardFrm  [name=id]').val();
		$('#coststandardheadFrm  [name=costs_tandard_id]').val(cost_standard_id)
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsCostStandardHeadModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCostStandardHeadModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#coststandardheadTbl').datagrid('reload');
		
		msApp.resetForm('coststandardheadFrm');
		$('#coststandardheadFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
		let cost_standard_id = $('#coststandardFrm  [name=id]').val();
		MsCostStandardHead.get(cost_standard_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsCostStandardHeadModel.get(index,row)
		data.then(function(response){
			$('#coststandardheadFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', response.data.fromData.acc_chart_ctrl_head_id);

		}).catch(function(error){
			console.log(error);
		});


	}

	get(cost_standard_id){
		let data= axios.get(this.route+"?cost_standard_id="+cost_standard_id);
		data.then(function (response) {
			$('#coststandardheadTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);;
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCostStandardHead.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCostStandardHead=new MsCostStandardHeadController(new MsCostStandardHeadModel());
MsCostStandardHead.showGrid([]);
