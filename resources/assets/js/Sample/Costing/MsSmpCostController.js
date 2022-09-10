require('./../../datagrid-filter.js');
let MsSmpCostModel = require('./MsSmpCostModel');
class MsSmpCostController {
	constructor(MsSmpCostModel)
	{
		this.MsSmpCostModel = MsSmpCostModel;
		this.formId='smpcostFrm';
		this.dataTable='#smpcostTbl';
		this.route=msApp.baseUrl()+"/smpcost"
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
			this.MsSmpCostModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmpCostModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#smpcostTbl').datagrid('reload');
		msApp.resetForm('smpcostFrm');
		MsSmpCost.get();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsSmpCostModel.get(index,row);
		data.then(function (response) {
			//
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	get(){
		let data= axios.get(this.route)
		.then(function (response) {
			MsSmpCost.showGrid(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showGrid()//data
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSmpCost.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	openStyleSampleWindow()
	{
		$('#smpwindow').window('open');
    }
	showStyleSampleGrid()
	{
		let data={};
		data.buyer_id = $('#stylesamplesearch  [name=buyer_id]').val();
		data.style_ref = $('#stylesamplesearch  [name=style_ref]').val();
		data.style_description = $('#stylesamplesearch  [name=style_description]').val();
		let self=this;
		var ff=$('#searchstylesampleTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/smpcost/getstylesample",
			onClickRow: function(index,row){
				$('#smpcostFrm  [name=style_sample_id]').val(row.id);
				$('#smpcostFrm  [name=style_ref]').val(row.style_ref);
				$('#smpcostFrm  [name=buyer_id]').val(row.buyer_id);
				$('#smpcostFrm  [name=uom_id]').val(row.uom_id);
				$('#smpcostFrm  [name=team_id]').val(row.team_id);
				$('#smpcostFrm  [name=qty]').val(row.qty);
				$('#smpwindow').window('close')
			}
		});
		ff.datagrid('enableFilter');
	}
	getTotal (smp_cost_id)
	{

		let data= axios.get(this.route+"/getTotal?smp_cost_id="+smp_cost_id)
		.then(function (response) {
			//MsSmpCost.showGrid(response.data)
			$('#smpcosttotalFrm  [name=total_cost]').val(response.data.total_cost);
			$('#smpcosttotalFrm  [name=total_cost_pcs]').val(response.data.total_cost_pcs);
		})
		.catch(function (error) {
			console.log(error);
		});

	}
}
window.MsSmpCost=new MsSmpCostController(new MsSmpCostModel());
MsSmpCost.get();



$('#smpCostAccordion').accordion({
	onSelect:function(title,index){
		let smp_cost_id = $('#smpcostFrm  [name=id]').val();
		if(index==1){//fabric
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',1);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			MsSmpCostFabric.create(smp_cost_id);
		}

		if(index==2){//Yarn
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',2);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			MsSmpCostYarn.fabriclist(smp_cost_id);
		}
		if(index==3){ //Yarn Dyeing
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',3);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('smpcostyarndyeingFrm');
			$('#smpcostyarndyeingFrm  [name=smp_cost_id]').val(smp_cost_id);
			$('#smpcostyarndyeingFrm  [name=production_process_id]').val(22);
			$('#smpcostyarndyeingFrm  [name=production_area_id]').val(5);
			MsSmpCostYarnDyeing.fabricDropDown(smp_cost_id);
			MsSmpCostYarnDyeing.get(smp_cost_id);
		}

		if(index==4){//Knitting
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',4);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('smpcostknittingFrm');
			$('#smpcostknittingFrm  [name=smp_cost_id]').val(smp_cost_id);
			$('#smpcostknittingFrm  [name=production_process_id]').val(1);
			$('#smpcostknittingFrm  [name=production_area_id]').val(10);
			MsSmpCostKnitting.fabricDropDown(smp_cost_id);
			MsSmpCostKnitting.get(smp_cost_id);
		}
		if(index==5){//Dyeing
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',5);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('smpcostdyeingFrm');
			$('#smpcostdyeingFrm  [name=smp_cost_id]').val(smp_cost_id);
			$('#smpcostdyeingFrm  [name=production_process_id]').val(4);
			$('#smpcostdyeingFrm  [name=production_area_id]').val(20);
			MsSmpCostDyeing.fabricDropDown(smp_cost_id);
			MsSmpCostDyeing.get(smp_cost_id);
		}

		if(index==6){//Aop
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',6);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('smpcostaopFrm');
			$('#smpcostaopFrm  [name=smp_cost_id]').val(smp_cost_id);
			$('#smpcostaopFrm  [name=production_process_id]').val(61);
			$('#smpcostaopFrm  [name=production_area_id]').val(25);
			MsSmpCostAop.fabricDropDown(smp_cost_id);
			MsSmpCostAop.get(smp_cost_id);
		}

		if(index==7){//Finishing
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',7);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			
			msApp.resetForm('smpcostfinishingFrm');
			$('#smpcostfinishingFrm  [name=smp_cost_id]').val(smp_cost_id);
			//$('#smpcostdyeingFrm  [name=production_process_id]').val(4);
			//$('#smpcostdyeingFrm  [name=production_area_id]').val(20);
			MsSmpCostFinishing.fabricDropDown(smp_cost_id);
			MsSmpCostFinishing.get(smp_cost_id);
		}

		if(index==8){//Trims
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',8);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			msApp.resetForm('smpcosttrimFrm');
			$('#smpcosttrimFrm  [name=smp_cost_id]').val(smp_cost_id);
			MsSmpCostTrim.get(smp_cost_id);
		}

		if(index==9){//Emb
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',9);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			//alert('select cost first');
			//return;
			//msApp.resetForm('smpcostembFrm');
			//$('#smpcostembFrm  [name=smp_cost_id]').val(smp_cost_id);
			MsSmpCostEmb.create(smp_cost_id);
		}

		if(index==10){//CM
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',10);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			$('#smpcostcmFrm  [name=smp_cost_id]').val(smp_cost_id);
			$('#smpcostcmFrm  [name=amount]').val('');
			MsSmpCostCm.get(smp_cost_id);
		}

		if(index==11){//Total
			if(smp_cost_id===''){
				msApp.showError('Select Cost First',0);
				$('#smpCostAccordion').accordion('unselect',11);
				$('#smpCostAccordion').accordion('select',0);
				return;
			}
			$('#smpcostcmFrm  [name=smp_cost_id]').val(smp_cost_id);
			$('#smpcostcmFrm  [name=amount]').val('');
			MsSmpCost.getTotal(smp_cost_id);
		}
	}
})


