let MsSmpCostYarnModel = require('./MsSmpCostYarnModel');
class MsSmpCostYarnController {
	constructor(MsSmpCostYarnModel)
	{
		this.MsSmpCostYarnModel = MsSmpCostYarnModel;
		this.formId='smpcostyarnFrm';
		this.dataTable='#smpcostyarnTbl';
		this.route=msApp.baseUrl()+"/smpcostyarn"
	}

	fabriclist(smp_cost_id){
		let data= axios.get(this.route+"/fabriclist?smp_cost_id="+smp_cost_id)
		.then(function (response) {
			for(var key in response.data.dropDown){
				msApp.setHtml(key,response.data.dropDown[key]);
			}
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	create(smp_cost_fabric_id){
		let data= axios.get(this.route+"/create?smp_cost_fabric_id="+smp_cost_fabric_id);
		let g=data.then(function (response) {
		$('#smpyarndiv').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		let f=g.then(function (response) {
			MsSmpCostYarn.showGridPopUp(smp_cost_fabric_id);
		})
		f.then(function (response) {
			$('#smpcostyarnWindow').window('open');
		})

	}

	openYarnWindow(smp_cost_fabric_id){
		if(!smp_cost_fabric_id){
			alert('Save First');
			return;
		}
		MsSmpCostYarn.create(smp_cost_fabric_id);
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
			this.MsSmpCostYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);

		}else{
			this.MsSmpCostYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostYarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostYarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSmpCostYarn.showGridPopUp($('#smpcostyarnFrm  [name=smp_cost_fabric_id]').val());
		MsSmpCostYarn.get(d.smp_cost_id);
		//MsSmpCostCommercial.get(d.smp_cost_id);
		//MsSmpCostProfit.get(d.smp_cost_id);
		//MsSmpCostCommission.get(d.smp_cost_id);
		//MsSmpCost.reloadDetails(d.smp_cost_id);
		$('#smpcostyarnFrm  [name=id]').val('');
		$('#smpcostyarnFrm  [name=ratio]').val('');
		$('#smpcostyarnFrm  [name=cons]').val('');
		$('#smpcostyarnFrm  [name=rate]').val('');
		$('#smpcostyarnFrm  [name=amount]').val('');
		$('#smpcostyarnFrm  [name=total_cost]').val(d.totalcost);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmpCostYarnModel.get(index,row);
	}

	get(smp_cost_id){
		let data= axios.get(this.route+"?smp_cost_id="+smp_cost_id)
		.then(function (response) {
			MsSmpCostYarn.showGrid(response.data)
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
			showFooter:true,
			fit:true,
			//queryParams:data,
			//url:this.route,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}
	showGridPopUp(smp_cost_fabric_id)
	{
		let self=this;
		var data={};
		data.smp_cost_fabric_id=smp_cost_fabric_id;
		$('#smpcostyarnpopupTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			queryParams:data,
			url:this.route+'/popuplist',
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSmpCostYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	openItemWindow(){

		$('#itemWindow').window('open');
	}
	
	openyarnsearchWindow(){

		$('#smpcostyarnsearchWindow').window('open');
		//$('#mktcostyarnsearchTbl').datagrid('loadData', []);
		
	}
	
	yarnsearch(){

		let count_name=$('#smpcostyarnsearchFrm  [name=count_name]').val()
		let type_name=$('#smpcostyarnsearchFrm  [name=type_name]').val()
		let data= axios.get(this.route+"/getyarn?count_name="+count_name+"&type_name="+type_name);
		data.then(function (response) {
			$('#smpcostyarnsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	yarnSearchGrid(data)
	{
		var dg = $('#smpcostyarnsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				$('#smpcostyarnFrm  [name=item_description]').val(row.itemclass_name+','+row.count+','+row.yarn_type+','+row.composition_name);
				$('#smpcostyarnFrm  [name=item_account_id]').val(row.id);
				$('#smpcostyarnsearchWindow').window('close')
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);

	}
	
	claculateCons(){
		let ratio;
		let fab_cons;
		ratio= $('#smpcostyarnFrm  [name=ratio]').val();
		fab_cons=$('#smpcostyarnFrm  [name=fab_cons]').val();
		let cons=(fab_cons*ratio)/100;
		$('#smpcostyarnFrm  [name=cons]').val(cons)
	}
	claculateAmount(){
		let cons;
		let rate;
		cons= $('#smpcostyarnFrm  [name=cons]').val();
		rate=$('#smpcostyarnFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#smpcostyarnFrm  [name=amount]').val(amount)
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsSmpCostYarn=new MsSmpCostYarnController(new MsSmpCostYarnModel());
MsSmpCostYarn.yarnSearchGrid([])
