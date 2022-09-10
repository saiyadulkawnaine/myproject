let MsMktCostYarnModel = require('./MsMktCostYarnModel');
class MsMktCostYarnController {
	constructor(MsMktCostYarnModel)
	{
		this.MsMktCostYarnModel = MsMktCostYarnModel;
		this.formId='mktcostyarnFrm';
		this.dataTable='#mktcostyarnTbl';
		this.route=msApp.baseUrl()+"/mktcostyarn"
	}
	loadview(mkt_cost_fabric_id){
		let data= axios.get(this.route+"/create?mkt_cost_fabric_id="+mkt_cost_fabric_id);
		let g=data.then(function (response) {
		$('#yarndiv').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		let f=g.then(function (response) {
			MsMktCostYarn.showGridPopUp(mkt_cost_fabric_id);
		})
		f.then(function (response) {
			$('#mktcostyarnWindow').window('open');
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
			this.MsMktCostYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);

		}else{
			this.MsMktCostYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsMktCostYarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsMktCostYarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{



		MsMktCostYarn.showGridPopUp($('#mktcostyarnFrm  [name=mkt_cost_fabric_id]').val());
		MsMktCostYarn.get(d.mkt_cost_id);
		MsMktCostCommercial.get(d.mkt_cost_id);
		MsMktCostProfit.get(d.mkt_cost_id);
		MsMktCostCommission.get(d.mkt_cost_id);
		MsMktCost.reloadDetails(d.mkt_cost_id);
		$('#mktcostyarnFrm  [name=id]').val('');
		$('#mktcostyarnFrm  [name=ratio]').val('');
		$('#mktcostyarnFrm  [name=cons]').val('');
		$('#mktcostyarnFrm  [name=rate]').val('');
		$('#mktcostyarnFrm  [name=amount]').val('');
		$('#mktcostyarnFrm  [name=total_cost]').val(d.totalcost);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsMktCostYarnModel.get(index,row);
	}

	get(mkt_cost_id){
		let data= axios.get(this.route+"?mkt_cost_id="+mkt_cost_id)
		.then(function (response) {
			MsMktCostYarn.showGrid(response.data)
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
	showGridPopUp(mkt_cost_fabric_id)
	{
		let self=this;
		var data={};
		data.mkt_cost_fabric_id=mkt_cost_fabric_id;
		$('#mktcostyarnpopupTbl').datagrid({
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
		return '<a href="javascript:void(0)"  onClick="MsMktCostYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	openItemWindow(){

		$('#itemWindow').window('open');
	}
	
	openyarnsearchWindow(){

		$('#mktcostyarnsearchWindow').window('open');
		//$('#mktcostyarnsearchTbl').datagrid('loadData', []);
		
	}
	
	yarnsearch(){

		let count_name=$('#mktcostyarnsearchFrm  [name=count_name]').val()
		let type_name=$('#mktcostyarnsearchFrm  [name=type_name]').val()
		let data= axios.get(this.route+"/getyarn?count_name="+count_name+"&type_name="+type_name);
		data.then(function (response) {
			$('#mktcostyarnsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	yarnSearchGrid(data)
	{
		var dg = $('#mktcostyarnsearchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				/*if ( $("#mktcostyarnFrm [name=item_account_id] option[value="+row.id+"]").length == 0 ){
				$('#mktcostyarnFrm [name="item_account_id"]').append('<option value="'+ row.id +'">'+ row.itemclass_name+','+row.count+','+row.yarn_type+','+row.composition_name +'</option>');
				}*/
				$('#mktcostyarnFrm  [name=item_description]').val(row.itemclass_name+','+row.count+','+row.yarn_type+','+row.composition_name);
				
				$('#mktcostyarnFrm  [name=item_account_id]').val(row.id);
				//$('#mktcostyarnFrm  [name=fabrication]').val(row.name+","+row.composition_name);
				$('#mktcostyarnsearchWindow').window('close')
				//MsMktCostYarn.yarnSearchGrid([])
			}
			
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);

	}
	
	claculateCons(){
		let ratio;
		let fab_cons;
		ratio= $('#mktcostyarnFrm  [name=ratio]').val();
		fab_cons=$('#mktcostyarnFrm  [name=fab_cons]').val();
		let cons=(fab_cons*ratio)/100;
		$('#mktcostyarnFrm  [name=cons]').val(cons)
	}
	claculateAmount(){
		let cons;
		let rate;
		cons= $('#mktcostyarnFrm  [name=cons]').val();
		rate=$('#mktcostyarnFrm  [name=rate]').val();
		let amount=cons*rate;
		$('#mktcostyarnFrm  [name=amount]').val(amount)
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsMktCostYarn=new MsMktCostYarnController(new MsMktCostYarnModel());
MsMktCostYarn.yarnSearchGrid([])
