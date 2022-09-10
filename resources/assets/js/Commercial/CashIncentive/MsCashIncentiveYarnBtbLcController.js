let MsCashIncentiveYarnBtbLcModel = require('./MsCashIncentiveYarnBtbLcModel');
class MsCashIncentiveYarnBtbLcController {
	constructor(MsCashIncentiveYarnBtbLcModel)
	{
		this.MsCashIncentiveYarnBtbLcModel = MsCashIncentiveYarnBtbLcModel;
		this.formId='cashincentiveyarnbtblcFrm';
		this.dataTable='#cashincentiveyarnbtblcTbl';
		this.route=msApp.baseUrl()+"/cashincentiveyarnbtblc"
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
			this.MsCashIncentiveYarnBtbLcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCashIncentiveYarnBtbLcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	calculateAmount(){
		let qty;
		let rate;
		qty=$('#cashincentiveyarnbtblcFrm [name=qty]').val();
		rate=$('#cashincentiveyarnbtblcFrm [name=rate]').val();
		let amount=qty*rate;
		$('#cashincentiveyarnbtblcFrm [name=amount]').val(amount);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#cashincentiveyarnbtblcFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCashIncentiveYarnBtbLcModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCashIncentiveYarnBtbLcModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cashincentiveyarnbtblcTbl').datagrid('reload');
		msApp.resetForm('cashincentiveyarnbtblcFrm');
		$('#cashincentiveyarnbtblcFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCashIncentiveYarnBtbLcModel.get(index,row);
	}

	showGrid(cash_incentive_ref_id)
	{
		let self=this;
		var data={};
		data.cash_incentive_ref_id=cash_incentive_ref_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			//fitColumns:true,
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tConsumedQty=0;
				var tConsumedAmount=0;
				var tLcYarnQty=0;
				var tLcYarnAmount=0;
				var tPrevUsedQty=0;
				var tBalanceQty=0;
				for(var i=0; i<data.rows.length; i++){
					tConsumedQty+=data.rows[i]['consumed_qty'].replace(/,/g,'')*1;
					tConsumedAmount+=data.rows[i]['comsumed_amount'].replace(/,/g,'')*1;
					tLcYarnQty+=data.rows[i]['lc_yarn_qty'].replace(/,/g,'')*1;
					tLcYarnAmount+=data.rows[i]['lc_yarn_amount'].replace(/,/g,'')*1;
					tPrevUsedQty+=data.rows[i]['prev_used_qty'].replace(/,/g,'')*1;
					tBalanceQty+=data.rows[i]['balance_qty'].replace(/,/g,'')*1;
				}
				$('#cashincentiveyarnbtblcTbl').datagrid('reloadFooter', [
				{ 
					consumed_qty: tConsumedQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					comsumed_amount: tConsumedAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_yarn_qty: tLcYarnQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					lc_yarn_amount: tLcYarnAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					prev_used_qty: tPrevUsedQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					balance_qty: tBalanceQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
							
			}
		}).datagrid('enableFilter');//.datagrid('loadData', data)
	
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveYarnBtbLc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openBtbLcYarnWindow(){
		$('#openbtbyarnlcwindow').window('open');
	}

	getParams(){
		let params={}
		params.company_id=$('#btbyarnlcsearchFrm [name=company_id]').val();
		params.supplier_id=$('#btbyarnlcsearchFrm [name=supplier_id]').val();
		params.last_delilvery_date=$('#btbyarnlcsearchFrm [name=last_delilvery_date]').val();
	}

	searchBtbYarnLc(){
		let params=this.getParams();
		let d = axios.get(this.route+'/getbtbimplc',{params})
		.then(function(response){
			$('#btbyarnlcsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	showBtbYarnLcGrid(){
		let self = this;
		$('#btbyarnlcsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#cashincentiveyarnbtblcFrm [name=imp_lc_id]').val(row.id);
				// var lc_no_i=$('#cashincentiveyarnbtblcFrm [name=lc_no_i]').val(lc_no_i);
				// var lc_no_ii=$('#cashincentiveyarnbtblcFrm [name=lc_no_ii]').val(lc_no_ii);
				// var lc_no_iii=$('#cashincentiveyarnbtblcFrm [name=lc_no_iii]').val(lc_no_iii);
				// var lc_no_iv=$('#cashincentiveyarnbtblcFrm [name=lc_no_iv]').val(lc_no_iv);
				// var lc_no=lc_no_i+lc_no_ii+lc_no_iii+lc_no_iv;
				$('#cashincentiveyarnbtblcFrm [name=lc_no]').val(row.lc_no);
				$('#cashincentiveyarnbtblcFrm [name=supplier_name]').val(row.supplier_name);
				$('#cashincentiveyarnbtblcFrm [name=lc_sc_date]').val(row.lc_sc_date);
				$('#btbyarnlcsearchTbl').datagrid('loadData',[]);
				$('#openbtbyarnlcwindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	openBtbPoYarnItemDescWindow(){
		$('#openpoyarnitemdescwindow').window('open');
	}

	getItemParams(){
		let params={}
		params.imp_lc_id=$('#cashincentiveyarnbtblcFrm [name=imp_lc_id]').val();
		return params;

		//alert(params.imp_lc_id);
		//params.po_no=$('#poyarnitemdescsearchFrm [name=po_no]').val();
		//params.supplier_id=$('#poyarnitemdescsearchFrm [name=supplier_id]').val();
	}

	searchBtbPoYarnItemDesc(){
		let params=this.getItemParams();
		let d = axios.get(this.route+'/getbtbpoyarnitemdesc',{params})
		.then(function(response){
			$('#poyarnitemdescsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	showBtbYarnItemDescGrid(){
		let self = this;
		$('#poyarnitemdescsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#cashincentiveyarnbtblcFrm [name=po_yarn_item_id]').val(row.po_yarn_item_id);
				$('#cashincentiveyarnbtblcFrm [name=item_description]').val(row.item_description);
				$('#cashincentiveyarnbtblcFrm [name=lc_yarn_qty]').val(row.lc_yarn_qty);
				$('#cashincentiveyarnbtblcFrm [name=rate]').val(row.rate);
				$('#cashincentiveyarnbtblcFrm [name=lc_yarn_amount]').val(row.lc_yarn_amount);
				$('#poyarnitemdescsearchTbl').datagrid('loadData',[]);
				$('#openpoyarnitemdescwindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	calculateConsumedValue(){
		let self = this;
		let rate = $('#cashincentiveyarnbtblcFrm [name=rate]').val();
		let consumed_qty = $('#cashincentiveyarnbtblcFrm [name=consumed_qty]').val();
		let amount = msApp.multiply(consumed_qty,rate);
		$('#cashincentiveyarnbtblcFrm [name=comsumed_amount]').val(amount);
	}
}
window.MsCashIncentiveYarnBtbLc=new MsCashIncentiveYarnBtbLcController(new MsCashIncentiveYarnBtbLcModel());
MsCashIncentiveYarnBtbLc.showBtbYarnLcGrid([]);
MsCashIncentiveYarnBtbLc.showBtbYarnItemDescGrid([]);