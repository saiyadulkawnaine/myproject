let MsBudgetYarnModel = require('./MsBudgetYarnModel');
class MsBudgetYarnController {
	constructor(MsBudgetYarnModel)
	{
		this.MsBudgetYarnModel = MsBudgetYarnModel;
		this.formId='budgetyarnFrm';
		this.dataTable='#budgetyarnTbl';
		this.route=msApp.baseUrl()+"/budgetyarn"
	}
	loadview(budget_fabric_id){
		let data= axios.get(this.route+"/create?budget_fabric_id="+budget_fabric_id);
		let g=data.then(function (response) {
		$('#yarndiv').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		let f=g.then(function (response) {
			MsBudgetYarn.showGridPopUp(budget_fabric_id);
		})
		f.then(function (response) {
			$('#yarnWindow').window('open');
		})

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
			this.MsBudgetYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);

		}else{
			this.MsBudgetYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetYarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetYarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{



		MsBudgetYarn.showGridPopUp($('#budgetyarnFrm  [name=budget_fabric_id]').val());
		MsBudgetYarn.get(d.budget_id);
		MsBudgetCommercial.get(d.budget_id);
		

		/*$('#budgetyarnFrm  [name=id]').val('');
		$('#budgetyarnFrm  [name=item_description]').val('');
		$('#budgetyarnFrm  [name=item_account_id]').val('');
		$('#budgetyarnFrm  [name=ratio]').val('');
		$('#budgetyarnFrm  [name=cons]').val('');
		$('#budgetyarnFrm  [name=rate]').val('');
		$('#budgetyarnFrm  [name=amount]').val('');*/
		MsBudgetYarn.resetForm()

		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
	}

	resetForm()
	{
		$('#budgetyarnFrm  [name=id]').val('');
		$('#budgetyarnFrm  [name=item_description]').val('');
		$('#budgetyarnFrm  [name=item_account_id]').val('');
		$('#budgetyarnFrm  [name=ratio]').val('');
		$('#budgetyarnFrm  [name=cons]').val('');
		$('#budgetyarnFrm  [name=rate]').val('');
		$('#budgetyarnFrm  [name=amount]').val('');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetYarnModel.get(index,row);
	}

	get(budget_id){
		let data= axios.get(this.route+"?budget_id="+budget_id)
		.then(function (response) {
			MsBudgetYarn.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		//var data={};
		//data.budget_id=budget_id;
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
	showGridPopUp(budget_fabric_id)
	{
		let self=this;
		var data={};
		data.budget_fabric_id=budget_fabric_id;
		//alert(budget_fabric_id);
		$('#budgetyarnpopupTbl').datagrid({
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
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	openItemWindow(){

		$('#itemWindow').window('open');
	}
	claculateCons(){
		let ratio;
		let fab_cons;
		ratio= $('#budgetyarnFrm  [name=ratio]').val();
		fab_cons=$('#budgetyarnFrm  [name=fab_cons]').val();
		let cons=(fab_cons*ratio)/100;
		$('#budgetyarnFrm  [name=cons]').val(cons)
	}
	claculateAmount(){
		let cons;
		let rate;
		cons= $('#budgetyarnFrm  [name=cons]').val();
		rate=$('#budgetyarnFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#budgetyarnFrm  [name=amount]').val(amount)
	}
	

	////////////////////
	openbudgetyarnsearchWindow(){
		$('#budgetyarnsearchWindow').window('open');
	}
	
	budgetyarnsearch(){

		let count_name=$('#budgetyarnsearchFrm  [name=count_name]').val()
		let type_name=$('#budgetyarnsearchFrm  [name=type_name]').val()
		let budget_fabric_id=$('#budgetyarnFrm  [name=budget_fabric_id]').val()
		let data= axios.get(this.route+"/getbudgetyarn?count_name="+count_name+"&type_name="+type_name+"&budget_fabric_id="+budget_fabric_id);
		data.then(function (response) {
			$('#budgetyarnsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	budgetYarnSearchGrid(data)
	{
		var dg = $('#budgetyarnsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){

				$('#budgetyarnFrm  [name=item_description]').val(row.itemclass_name+','+row.count+','+row.yarn_type+','+row.composition_name);
				
				$('#budgetyarnFrm  [name=item_account_id]').val(row.id);
				$('#budgetyarnFrm  [name=ratio]').val(row.smp_ratio);
				$('#budgetyarnFrm  [name=rate]').val(row.rate);

				MsBudgetYarn.claculateCons()
				MsBudgetYarn.claculateAmount()

				$('#budgetyarnsearchWindow').window('close')
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);

	}
}
window.MsBudgetYarn=new MsBudgetYarnController(new MsBudgetYarnModel());
MsBudgetYarn.budgetYarnSearchGrid([]);
