let MsSoAopMktCostModel = require('./MsSoAopMktCostModel');
require('./../../datagrid-filter.js');
class MsSoAopMktCostController {
	constructor(MsSoAopMktCostModel)
	{
		this.MsSoAopMktCostModel = MsSoAopMktCostModel;
		this.formId='soaopmktcostFrm';
		this.dataTable='#soaopmktcostTbl';
		this.route=msApp.baseUrl()+"/soaopmktcost"
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
			this.MsSoAopMktCostModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopMktCostModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopMktCostModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopMktCostModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopmktcostTbl').datagrid('reload');
		msApp.resetForm('soaopmktcostFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoAopMktCostModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopMktCost.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	subinbserviceWindow(){
		$('#subinbserviceWindow').window('open');
	}

	subInbServiceShowGrid(data){
		let self = this;
		$('#subinbservicesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopmktcostFrm [name=sub_inb_service_id]').val(row.id);
				$('#soaopmktcostFrm [name=company_id]').val(row.company_id);
				$('#soaopmktcostFrm [name=buyer_id]').val(row.buyer_id);
				$('#soaopmktcostFrm [name=amount]').val(row.amount);
				$('#soaopmktcostFrm [name=currency_id]').val(row.currency_id);
				$('#subinbserviceWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getSubInbService()
	{
		let date_from=$('#subinbservicesearchFrm  [name=date_from]').val();
		let date_to=$('#subinbservicesearchFrm  [name=date_to]').val();
		let buyer_id=$('#subinbservicesearchFrm  [name=buyer_id]').val();
		let company_id=$('#subinbservicesearchFrm  [name=company_id]').val();
		let data= axios.get(this.route+"/getsubinbservice?date_from="+date_from+"&date_to="+date_to+"&buyer_id="+buyer_id+"&company_id="+company_id);
		data.then(function (response) {
			$('#subinbservicesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	pdf()
	{
		var id= $('#soaopmktcostFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/getpdf?id="+id);
	}
}
window.MsSoAopMktCost=new MsSoAopMktCostController(new MsSoAopMktCostModel());
MsSoAopMktCost.showGrid();
MsSoAopMktCost.subInbServiceShowGrid([]);

 $('#soaopmktcosttabs').tabs({
	onSelect:function(title,index){
        let so_aop_mkt_cost_id = $('#soaopmktcostFrm  [name=id]').val();
		let currency_id = $('#soaopmktcostFrm  [name=currency_id]').val();
        let so_aop_mkt_cost_param_id = $('#soaopmktcostparamFrm  [name=id]').val();
		let fabric_wgt = $('#soaopmktcostparamFrm  [name=fabric_wgt]').val();
		let paste_wgt = $('#soaopmktcostparamFrm  [name=paste_wgt]').val();
        
        if(index==1){
            if(so_aop_mkt_cost_id===''){
                $('#soaopmktcosttabs').tabs('select',0);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('soaopmktcostparamFrm');
			$('#soaopmktcostqpricedtlcosi').html('');
            $('#soaopmktcostparamFrm  [name=so_aop_mkt_cost_id]').val(so_aop_mkt_cost_id);
            MsSoAopMktCostParam.get(so_aop_mkt_cost_id);
        }

        if(index==2){
            if(so_aop_mkt_cost_param_id===''){
                $('#soaopmktcosttabs').tabs('select',1);
                msApp.showError('Select a Fabric First',0);
                return;
            }
            msApp.resetForm('soaopmktcostparamitemFrm');
			$('#soaopmktcostqpricedtlcosi').html('');
            $('#soaopmktcostparamitemFrm  [name=so_aop_mkt_cost_param_id]').val(so_aop_mkt_cost_param_id);
			$('#soaopmktcostparamitemFrm  [name=fabric_wgt]').val(fabric_wgt);
			$('#soaopmktcostparamitemFrm  [name=paste_wgt]').val(paste_wgt);
			$('#soaopmktcostparamitemFrm  [name=currency_id]').val(currency_id);
            MsSoAopMktCostParamItem.get(so_aop_mkt_cost_param_id);
        }

        if(index==3){
            if(so_aop_mkt_cost_param_id===''){
                $('#soaopmktcosttabs').tabs('select',1);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('soaopmktcostparamfinFrm');
			$('#soaopmktcostqpricedtlcosi').html('');
            $('#soaopmktcostparamfinFrm  [name=so_aop_mkt_cost_param_id]').val(so_aop_mkt_cost_param_id);
            MsSoAopMktCostParamFin.get(so_aop_mkt_cost_param_id);
        }
		
        if(index==4){
            if(so_aop_mkt_cost_id===''){
                $('#soaopmktcosttabs').tabs('select',1);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('soaopmktcostqpriceFrm');
            $('#soaopmktcostqpriceFrm  [name=so_aop_mkt_cost_id]').val(so_aop_mkt_cost_id);
            MsSoAopMktCostQprice.get(so_aop_mkt_cost_id);
        }
		if(index==5){
            if(so_aop_mkt_cost_id===''){
                $('#soaopmktcosttabs').tabs('select',1);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('purchasetermsconditionFrm');
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(so_aop_mkt_cost_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(352)
			MsPurchaseTermsCondition.get();
        }

    }
}); 
