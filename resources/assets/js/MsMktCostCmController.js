let MsMktCostCmModel = require('./MsMktCostCmModel');
class MsMktCostCmController {
	constructor(MsMktCostCmModel)
	{
		this.MsMktCostCmModel = MsMktCostCmModel;
		this.formId='mktcostcmFrm';
		this.dataTable='#mktcostcmTbl';
		this.route=msApp.baseUrl()+"/mktcostcm"
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
			this.MsMktCostCmModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsMktCostCmModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostCmModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostCmModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
        var method_id=$('#mktcostcmFrm  [name=method_id]').val();
		msApp.resetForm('mktcostcmFrm');
		$('#mktcostcmFrm  [name=method_id]').val(method_id);
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		$('#mktcostcmFrm  [name=mkt_cost_id]').val(mkt_cost_id);
		$('#mktcosttotalFrm  [name=total_cost]').val(d.totalcost);
		MsMktCostCm.get(mkt_cost_id);
		MsMktCostProfit.get(mkt_cost_id);
		MsMktCostCommission.get(mkt_cost_id);
		MsMktCost.reloadDetails(mkt_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostCmModel.get(index,row);
	}

	get(mkt_cost_id){
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsMktCostCm.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		//var data={};
		//data.mkt_cost_id=mkt_cost_id;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			//queryParams:data,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostCm.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calCmPerPcs(){
		let cpm = $('#mktcostcmFrm [name="cpm"]').val();
		if(cpm=='' || cpm ==0){
			alert('Please set CMP first');
			return;
		}
		let smv = Number($('#mktcostcmFrm [name="smv"]').val());
		let sewing_effi_per = Number($('#mktcostcmFrm [name="sewing_effi_per"]').val())/100;
		cpm=Number(cpm);
		
		let cm_per_pcs=(smv*cpm)/sewing_effi_per;
		$('#mktcostcmFrm [name="cm_per_pcs"]').val(cm_per_pcs.toFixed(4));
		this.calProdHour();
	}

	calProdHour(){
		let smv = Number($('#mktcostcmFrm [name="smv"]').val());
		let sewing_effi_per = Number($('#mktcostcmFrm [name="sewing_effi_per"]').val())/100;
		let no_of_man_power = Number($('#mktcostcmFrm [name="no_of_man_power"]').val());
		let prod_per_hour=(60*no_of_man_power*sewing_effi_per)/smv;
		$('#mktcostcmFrm [name="prod_per_hour"]').val(prod_per_hour.toFixed());
	}

	getGmtItem(){
		let mkt_cost_id = $('#mktcostFrm  [name=id]').val();
		let data= axios.get(this.route+"/gmtitem?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			$('#mktcostgmtsTbl').datagrid('loadData',response.data);
			$('#mktCmGmtItemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridGmtItem(data)
	{
		let self=this;
		$("#mktcostgmtsTbl").datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			data:data,
			onClickRow: function(index,row){
				//self.edit(index,row);
				$('#mktcostcmFrm [name="style_gmt_id"]').val(row.id);
				$('#mktcostcmFrm [name="style_gmt_name"]').val(row.name);
				$('#mktcostcmFrm [name="cpm"]').val(row.cpm);
				$('#mktCmGmtItemWindow').window('close');
			}
		});
	}
}
window.MsMktCostCm=new MsMktCostCmController(new MsMktCostCmModel());
MsMktCostCm.showGridGmtItem();
