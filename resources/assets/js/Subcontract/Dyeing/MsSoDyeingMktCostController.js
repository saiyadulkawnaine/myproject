let MsSoDyeingMktCostModel = require('./MsSoDyeingMktCostModel');
require('./../../datagrid-filter.js');
class MsSoDyeingMktCostController {
	constructor(MsSoDyeingMktCostModel)
	{
		this.MsSoDyeingMktCostModel = MsSoDyeingMktCostModel;
		this.formId='sodyeingmktcostFrm';
		this.dataTable='#sodyeingmktcostTbl';
		this.route=msApp.baseUrl()+"/sodyeingmktcost"
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
			this.MsSoDyeingMktCostModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingMktCostModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingMktCostModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingMktCostModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingmktcostTbl').datagrid('reload');
		msApp.resetForm('sodyeingmktcostFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoDyeingMktCostModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingMktCost.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
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
				$('#sodyeingmktcostFrm [name=sub_inb_service_id]').val(row.id);
				$('#sodyeingmktcostFrm [name=company_id]').val(row.company_id);
				$('#sodyeingmktcostFrm [name=buyer_id]').val(row.buyer_id);
				$('#sodyeingmktcostFrm [name=amount]').val(row.amount);
				$('#sodyeingmktcostFrm [name=currency_id]').val(row.currency_id);
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
		var id= $('#sodyeingmktcostFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/getpdf?id="+id);
	}
}
window.MsSoDyeingMktCost=new MsSoDyeingMktCostController(new MsSoDyeingMktCostModel());
MsSoDyeingMktCost.showGrid();
MsSoDyeingMktCost.subInbServiceShowGrid([]);

 $('#sodyeingmktcosttabs').tabs({
	onSelect:function(title,index){
        let so_dyeing_mkt_cost_id = $('#sodyeingmktcostFrm  [name=id]').val();
		let currency_id = $('#sodyeingmktcostFrm  [name=currency_id]').val();
        let so_dyeing_mkt_cost_fab_id = $('#sodyeingmktcostfabFrm  [name=id]').val();
		let fabric_wgt = $('#sodyeingmktcostfabFrm  [name=fabric_wgt]').val();
		let liqure_wgt = $('#sodyeingmktcostfabFrm  [name=liqure_wgt]').val();
        
        if(index==1){
            if(so_dyeing_mkt_cost_id===''){
                $('#sodyeingmktcosttabs').tabs('select',0);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('sodyeingmktcostfabFrm');
			$('#sodyeingmktcostqpricedtlcosi').html('');
            $('#sodyeingmktcostfabFrm  [name=so_dyeing_mkt_cost_id]').val(so_dyeing_mkt_cost_id);
            MsSoDyeingMktCostFab.get(so_dyeing_mkt_cost_id);
        }

        if(index==2){
            if(so_dyeing_mkt_cost_fab_id===''){
                $('#sodyeingmktcosttabs').tabs('select',1);
                msApp.showError('Select a Fabric First',0);
                return;
            }
            msApp.resetForm('sodyeingmktcostfabitemFrm');
			$('#sodyeingmktcostqpricedtlcosi').html('');
            $('#sodyeingmktcostfabitemFrm  [name=so_dyeing_bom_fabric_id]').val(so_dyeing_mkt_cost_fab_id);
			$('#sodyeingmktcostfabitemFrm  [name=fabric_wgt]').val(fabric_wgt);
			$('#sodyeingmktcostfabitemFrm  [name=liqure_wgt]').val(liqure_wgt);
			$('#sodyeingmktcostfabitemFrm  [name=currency_id]').val(currency_id);
            MsSoDyeingMktCostFabItem.get(so_dyeing_mkt_cost_fab_id);
        }

        if(index==3){
            if(so_dyeing_mkt_cost_fab_id===''){
                $('#sodyeingmktcosttabs').tabs('select',1);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('sodyeingmktcostfabfinFrm');
			$('#sodyeingmktcostqpricedtlcosi').html('');
            $('#sodyeingmktcostfabfinFrm  [name=so_dyeing_mkt_cost_fab_id]').val(so_dyeing_mkt_cost_fab_id);
            MsSoDyeingMktCostFabFin.get(so_dyeing_mkt_cost_fab_id);
        }
		
        if(index==4){
            if(so_dyeing_mkt_cost_id===''){
                $('#sodyeingmktcosttabs').tabs('select',1);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('sodyeingmktcostqpriceFrm');
            $('#sodyeingmktcostqpriceFrm  [name=so_dyeing_mkt_cost_id]').val(so_dyeing_mkt_cost_id);
            MsSoDyeingMktCostQprice.get(so_dyeing_mkt_cost_id);
        }
		if(index==5){
            if(so_dyeing_mkt_cost_id===''){
                $('#sodyeingmktcosttabs').tabs('select',1);
                msApp.showError('Select a Start Up First',0);
                return;
            }
            msApp.resetForm('purchasetermsconditionFrm');
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(so_dyeing_mkt_cost_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(351)
			MsPurchaseTermsCondition.get();
        }

    }
}); 
