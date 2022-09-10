require('./../datagrid-filter.js');
let MsPoYarnDyeingShortItemBomQtyModel = require('./MsPoYarnDyeingShortItemBomQtyModel');

class MsPoYarnDyeingShortItemBomQtyController {
	constructor(MsPoYarnDyeingShortItemBomQtyModel)
	{
		this.MsPoYarnDyeingShortItemBomQtyModel = MsPoYarnDyeingShortItemBomQtyModel;
		this.formId='poyarndyeingshortitembomqtyFrm';
		this.dataTable='#poyarndyeingshortitembomqtyTbl';
		this.route=msApp.baseUrl()+"/poyarndyeingshortitembomqty"
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
		let po_yarn_dyeing_id = $('#poyarndyeingshortFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.po_yarn_dyeing_id=po_yarn_dyeing_id;
		if(formObj.id){
			this.MsPoYarnDyeingShortItemBomQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoYarnDyeingShortItemBomQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	submitMalti()
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
		let formObj=msApp.get('poyarndyeingshortitembomqtymultiFrm');
		let po_yarn_dyeing_id = $('#poyarndyeingshortFrm  [name=id]').val();
		let po_yarn_dyeing_item_id=$('#poyarndyeingshortitemFrm  [name=id]').val();
		formObj.po_yarn_dyeing_item_id=po_yarn_dyeing_item_id;
		formObj.po_yarn_dyeing_id=po_yarn_dyeing_id;
		this.MsPoYarnDyeingShortItemBomQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		msApp.resetForm('poyarndyeingshortitembomqtymultiFrm');
		$('#poyarndyeingshortitembomqtymultiWindow').window('close');
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoYarnDyeingShortItemBomQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoYarnDyeingShortItemBomQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let po_yarn_dyeing_item_id=$('#poyarndyeingshortitemFrm  [name=id]').val();
		MsPoYarnDyeingShortItemBomQty.get(po_yarn_dyeing_item_id);
		msApp.resetForm('poyarndyeingshortitembomqtyFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsPoYarnDyeingShortItemBomQtyModel.get(index,row);
	}

	get(po_yarn_dyeing_item_id)
	{
		
		let d = axios.get(this.route+"?po_yarn_dyeing_item_id="+po_yarn_dyeing_item_id)
		.then(function(response){
			$('#poyarndyeingshortitembomqtyTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});

	}
	

	showGrid(data)
	{
		let self=this;
		var dg = $('#poyarndyeingshortitembomqtyTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			showFooter:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;

				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}

				var tRate=0;
				
				if(tQty){
				   tRate=(tAmout/tQty);	
				}

				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dg.datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnDyeingShortItemBomQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	openDyeSaleOrderWindow(){
		$('#poyarndyesaleordercolorsearchwindow').window('open');
	}

	showYarnDyeSaleOrderColorGrid(data){
		let self=this;
		var yds = $('#poyarndyesaleordercolorsearchTbl');
		yds.datagrid({
			border:false,
			fit:true,
			singleSelect:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
		});
		yds.datagrid('enableFilter').datagrid('loadData', data);
	}
	getSaleOrderParams(){
		let params={}
		params.style_ref=$('#poyarndyesaleordercolorsearchFrm [name=style_ref]').val();
		params.job_no=$('#poyarndyesaleordercolorsearchFrm [name=job_no]').val();
		params.sale_order_no=$('#poyarndyesaleordercolorsearchFrm [name=sale_order_no]').val();
		params.po_yarn_dyeing_id=$('#poyarndyeingshortFrm [name=id]').val();
		return params;
	}
	searchDyeSaleOrderGrid(){
		let params=this.getSaleOrderParams();
		let d = axios.get(this.route+"/getyarndyesaleorder",{params})
		.then(function(response){
			$('#poyarndyesaleordercolorsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}

	closeSaleorderColorsearchWindow()
	{
		let po_yarn_dyeing_item_id=$('#poyarndyeingshortitemFrm  [name=id]').val();
		let budget_yarn_dyeing_con_id=[];
		let name=[];
		let checked=$('#poyarndyesaleordercolorsearchTbl').datagrid('getSelections');

		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			budget_yarn_dyeing_con_id.push(val.budget_yarn_dyeing_con_id)
		});
		budget_yarn_dyeing_con_id=budget_yarn_dyeing_con_id.join(',');
		$('#poyarndyesaleordercolorsearchTbl').datagrid('clearSelections');
		$('#poyarndyesaleordercolorsearchwindow').window('close');

		let data= axios.get(this.route+"/create"+"?budget_yarn_dyeing_con_id="+budget_yarn_dyeing_con_id+'&po_yarn_dyeing_item_id='+po_yarn_dyeing_item_id)
		.then(function (response) {
			$('#poyarndyeingshortitembomqtymultiscs').html(response.data);
			$('#poyarndyeingshortitembomqtymultiWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	


	calculateAmount(iteration,count,field)
	{
		let rate=$('#poyarndyeingshortitembomqtymultiFrm input[name="rate['+iteration+']"]').val();
		let qty=$('#poyarndyeingshortitembomqtymultiFrm input[name="qty['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#poyarndyeingshortitembomqtymultiFrm input[name="amount['+iteration+']"]').val(amount)
	}

	calculateAmountfrom()
	{
		let qty=$('#poyarndyeingshortitembomqtyFrm  [name=qty]').val();
		let rate=$('#poyarndyeingshortitembomqtyFrm  [name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#poyarndyeingshortitembomqtyFrm  [name=amount]').val(amount);
	}
	

	calculateReqConefrom()
	{
		let qty=$('#poyarndyeingshortitembomqtyFrm [name=qty]').val();
		let process_loss_per=$('#poyarndyeingshortitembomqtyFrm [name=process_loss_per]').val();
		let wgt_per_cone=$('#poyarndyeingshortitembomqtyFrm [name=wgt_per_cone]').val();
		let processLoss=(qty*process_loss_per*1)/100;
		let req_cone=(qty-processLoss)*1/wgt_per_cone;
		$('#poyarndyeingshortitembomqtyFrm [name=req_cone]').val(req_cone);
	}
	

	calculateReqCone(iteration,count,field)
	{
		let qty=$('#poyarndyeingshortitembomqtymultiFrm input[name="qty['+iteration+']"]').val();
		let process_loss_per=$('#poyarndyeingshortitembomqtymultiFrm input[name="process_loss_per['+iteration+']"]').val();
		let wgt_per_cone=$('#poyarndyeingshortitembomqtymultiFrm input[name="wgt_per_cone['+iteration+']"]').val();
		let processLoss=(qty*process_loss_per*1)/100;
		let req_cone=(qty-processLoss)*1/wgt_per_cone;
		$('#poyarndyeingshortitembomqtymultiFrm input[name="req_cone['+iteration+']"]').val(req_cone);
	}
	
}
window.MsPoYarnDyeingShortItemBomQty=new MsPoYarnDyeingShortItemBomQtyController(new MsPoYarnDyeingShortItemBomQtyModel());
MsPoYarnDyeingShortItemBomQty.showGrid([]);
MsPoYarnDyeingShortItemBomQty.showYarnDyeSaleOrderColorGrid([]);


