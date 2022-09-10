let MsSoEmbMktCostModel = require('./MsSoEmbMktCostModel');
require('./../../datagrid-filter.js');
class MsSoEmbMktCostController {
	constructor(MsSoEmbMktCostModel)
	{
		this.MsSoEmbMktCostModel = MsSoEmbMktCostModel;
		this.formId='soembmktcostFrm';
		this.dataTable='#soembmktcostTbl';
		this.route=msApp.baseUrl()+"/soembmktcost"
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
			this.MsSoEmbMktCostModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoEmbMktCostModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbMktCostModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbMktCostModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soembmktcostTbl').datagrid('reload');
		msApp.resetForm('soembmktcostFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoEmbMktCostModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbMktCost.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	subinbserviceWindow(){
		$('#soembmktcostsubinbserviceWindow').window('open');
	}

	subInbServiceEmbShowGrid(data){
		let self = this;
		$('#soembsubinbservicesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soembmktcostFrm [name=sub_inb_service_id]').val(row.id);
				$('#soembmktcostFrm [name=company_id]').val(row.company_id);
				$('#soembmktcostFrm [name=buyer_id]').val(row.buyer_id);
				$('#soembmktcostFrm [name=amount]').val(row.amount);
				$('#soembmktcostFrm [name=currency_id]').val(row.currency_id);
				$('#soembmktcostsubinbserviceWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchSoEmbSubInbService()
	{
		let date_from=$('#soembsubinbservicesearchFrm  [name=date_from]').val();
		let date_to=$('#soembsubinbservicesearchFrm  [name=date_to]').val();
		let buyer_id=$('#soembsubinbservicesearchFrm  [name=buyer_id]').val();
		let company_id=$('#soembsubinbservicesearchFrm  [name=company_id]').val();
		let data= axios.get(this.route+"/getsubinbservice?date_from="+date_from+"&date_to="+date_to+"&buyer_id="+buyer_id+"&company_id="+company_id);
		data.then(function (response) {
			$('#soembsubinbservicesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	pdf()
	{
		var id= $('#soembmktcostFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/getpdf?id="+id);
	}
}
window.MsSoEmbMktCost=new MsSoEmbMktCostController(new MsSoEmbMktCostModel());
MsSoEmbMktCost.showGrid();
MsSoEmbMktCost.subInbServiceEmbShowGrid([]);

 $('#soembmktcosttabs').tabs({
	onSelect:function(title,index){
        let so_aop_mkt_cost_id = $('#soembmktcostFrm  [name=id]').val();
		let currency_id = $('#soembmktcostFrm  [name=currency_id]').val();
        let so_aop_mkt_cost_param_id = $('#soembmktcostparamFrm  [name=id]').val();
		let fabric_wgt = $('#soembmktcostparamFrm  [name=fabric_wgt]').val();
		let paste_wgt = $('#soembmktcostparamFrm  [name=paste_wgt]').val();
        
        if(index==1){
            if(so_aop_mkt_cost_id===''){
                $('#soembmktcosttabs').tabs('select',0);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('soembmktcostparamFrm');
			$('#soembmktcostqpricedtlcosi').html('');
            $('#soembmktcostparamFrm  [name=so_aop_mkt_cost_id]').val(so_aop_mkt_cost_id);
            MsSoEmbMktCostParam.get(so_aop_mkt_cost_id);
        }

        if(index==2){
            if(so_aop_mkt_cost_param_id===''){
                $('#soembmktcosttabs').tabs('select',1);
                msApp.showError('Select a Fabric First',0);
                return;
            }
            msApp.resetForm('soembmktcostparamitemFrm');
			$('#soembmktcostqpricedtlcosi').html('');
            $('#soembmktcostparamitemFrm  [name=so_aop_mkt_cost_param_id]').val(so_aop_mkt_cost_param_id);
			$('#soembmktcostparamitemFrm  [name=fabric_wgt]').val(fabric_wgt);
			$('#soembmktcostparamitemFrm  [name=paste_wgt]').val(paste_wgt);
			$('#soembmktcostparamitemFrm  [name=currency_id]').val(currency_id);
            MsSoEmbMktCostParamItem.get(so_aop_mkt_cost_param_id);
        }

        if(index==3){
            if(so_aop_mkt_cost_param_id===''){
                $('#soembmktcosttabs').tabs('select',1);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('soembmktcostparamfinFrm');
			$('#soembmktcostqpricedtlcosi').html('');
            $('#soembmktcostparamfinFrm  [name=so_aop_mkt_cost_param_id]').val(so_aop_mkt_cost_param_id);
            MsSoEmbMktCostParamFin.get(so_aop_mkt_cost_param_id);
        }
		
        if(index==4){
            if(so_aop_mkt_cost_id===''){
                $('#soembmktcosttabs').tabs('select',1);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('soembmktcostqpriceFrm');
            $('#soembmktcostqpriceFrm  [name=so_aop_mkt_cost_id]').val(so_aop_mkt_cost_id);
            MsSoEmbMktCostQprice.get(so_aop_mkt_cost_id);
        }
		if(index==5){
            if(so_aop_mkt_cost_id===''){
                $('#soembmktcosttabs').tabs('select',1);
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
