let MsProdKnitItemRollModel = require('./MsProdKnitItemRollModel');
require('./../../datagrid-filter.js');
class MsProdKnitItemRollController {
	constructor(MsProdKnitItemRollModel)
	{
		this.MsProdKnitItemRollModel = MsProdKnitItemRollModel;
		this.formId='prodknititemrollFrm';
		this.dataTable='#prodknititemrollTbl';
		this.route=msApp.baseUrl()+"/prodknititemroll"
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
			this.MsProdKnitItemRollModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdKnitItemRollModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdKnitItemRollModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdKnitItemRollModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#prodknititemrollTbl').datagrid('reload');
		MsProdKnitRollItem.get($('#prodknititemrollFrm  [name=prod_knit_item_id]').val())
		//msApp.resetForm('prodknititemrollFrm');
		$('#prodknititemrollFrm  [name=id]').val('');
		$('#prodknititemrollFrm  [name=roll_no]').val('');
		$('#prodknititemrollFrm  [name=custom_no]').val('');
		$('#prodknititemrollFrm  [name=roll_weight]').val('');
		$('#prodknititemrollFrm  [name=qty_pcs]').val('');
		$('#prodknititemrollFrm  [name=qty_pcs]').val('');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let roll=this.MsProdKnitItemRollModel.get(index,row);
		
	}
	get(prod_knit_item_id)
	{
		let data= axios.get(this.route+"?prod_knit_item_id="+prod_knit_item_id);
		data.then(function (response) {
			$('#prodknititemrollTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			showFooter:true,
			//url:this.route+'?prod_knit_item_id='+prod_knit_item_id,

			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var troll_weight=0;
				var tqty_pcs=0;
				
				for(var i=0; i<data.rows.length; i++){
				troll_weight+=data.rows[i]['roll_weight'].replace(/,/g,'')*1;
				tqty_pcs+=data.rows[i]['qty_pcs'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						roll_weight: troll_weight.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						qty_pcs: tqty_pcs.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdKnitRollItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	prodknitcolorWindowOpen()
	{
		this.getFabricColor();
	   $('#prodknitcolorWindow').window('open');
	}

	showprodknitcolorGrid(data){
		let self = this;
		$('#prodknitcolorsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodknititemrollFrm [name=fabric_color]').val(row.name);
					$('#prodknititemrollFrm [name=fabric_color_id]').val(row.id);
					$('#prodknitcolorWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getFabricColor()
	{
		let params={};
		params.prod_knit_item_id=$('#prodknititemFrm  [name=id]').val();
		let data= axios.get(this.route+"/getfabriccolor",{params});
		data.then(function (response) {
			$('#prodknitcolorsearchTbl').datagrid('loadData', response.data);
			$('#prodknitcolorWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	prodknitsampleWindowOpen()
	{
		this.getSample();
	   $('#prodknitsampleWindow').window('open');
	}

	showprodknitsampleGrid(data){
		let self = this;
		$('#prodknitsamplesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodknititemrollFrm [name=gmt_sample_name]').val(row.name);
					$('#prodknititemrollFrm [name=gmt_sample]').val(row.id);
					$('#prodknitsampleWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getSample()
	{
		let params={};
		params.prod_knit_item_id=$('#prodknititemFrm  [name=id]').val();
		let data= axios.get(this.route+"/getsample",{params});
		data.then(function (response) {
			$('#prodknitsamplesearchTbl').datagrid('loadData', response.data);
			$('#prodknitsampleWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getWgt()
	{
		let params={};
		let data= axios.get(this.route+"/getwgt",{params});
		data.then(function (response) {
			$('#prodknititemrollFrm [name=roll_weight]').val(response.data);
			
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
}
window.MsProdKnitRollItem=new MsProdKnitItemRollController(new MsProdKnitItemRollModel());
MsProdKnitRollItem.showGrid([]);
MsProdKnitRollItem.showprodknitcolorGrid([]);
MsProdKnitRollItem.showprodknitsampleGrid([]);

