let MsInvYarnIsuRtnItemModel = require('./MsInvYarnIsuRtnItemModel');
class MsInvYarnIsuRtnItemController {
	constructor(MsInvYarnIsuRtnItemModel)
	{
		this.MsInvYarnIsuRtnItemModel = MsInvYarnIsuRtnItemModel;
		this.formId='invyarnisurtnitemFrm';
		this.dataTable='#invyarnisurtnitemTbl';
		this.route=msApp.baseUrl()+"/invyarnisurtnitem"
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

		let inv_rcv_id=$('#invyarnisurtnFrm [name=id]').val()
		let inv_yarn_rcv_id=$('#invyarnisurtnFrm [name=inv_yarn_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_yarn_rcv_id=inv_yarn_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;
		if(formObj.id){
			this.MsInvYarnIsuRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnIsuRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnIsuRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnIsuRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invyarnisurtnitemTbl').datagrid('reload');
		//MsInvYarnIsuRtnItem.create()
		msApp.resetForm('invyarnisurtnitemFrm');
        $('#invyarnisurtnitemFrm  [name=inv_yarn_rcv_id]').val($('#invyarnisurtnFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvYarnIsuRtnItemModel.get(index,row);
	}

	showGrid(inv_yarn_rcv_id)
	{
		let self=this;
        var data={};
		data.inv_yarn_rcv_id=inv_yarn_rcv_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
            queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvYarnIsuRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    calculate_qty_form(iteration,count)
	{
		let cone_per_bag=$('#invyarnisurtnitemFrm input[name=cone_per_bag]').val();
		let wgt_per_cone=$('#invyarnisurtnitemFrm input[name=wgt_per_cone]').val();
		let no_of_bag=$('#invyarnisurtnitemFrm input[name=no_of_bag]').val();
		if(Number.isInteger(no_of_bag*1)==false){
              alert('Decimal not allowed in no of bag');
              $('#invyarnisurtnitemFrm input[name=no_of_bag]').val('')
              return;
		}
		wgt_per_cone=wgt_per_cone*1;
		wgt_per_cone=wgt_per_cone.toFixed(4);
		$('#invyarnisurtnitemFrm input[name=wgt_per_cone]').val(wgt_per_cone);

		let qty=cone_per_bag*wgt_per_cone*no_of_bag;
		let rate=$('#invyarnisurtnitemFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invyarnisurtnitemFrm input[name=qty]').val(qty);
		$('#invyarnisurtnitemFrm input[name=amount]').val(amount);
	}
    
    /*wgtPerBag(){
        let self=this;
        let cone_per_bag;
        let wgt_per_cone;
        cone_per_bag=$('#invyarnisurtnitemFrm [name=cone_per_bag]').val();
        wgt_per_cone=$('#invyarnisurtnitemFrm [name=wgt_per_cone]').val();
        if(Number.isInteger(cone_per_bag*1)==false){
            alert('Decimal not allowed in Cone per Bag');
            return;
        }
        wgt_per_bag=cone_per_bag*wgt_per_cone;
        $('#invyarnisurtnitemFrm [name=wgt_per_bag]').val(wgt_per_bag);
    }
    
    returnQty(){
        let self=this;
        let wgt_per_bag;
        let no_of_bag;
        wgt_per_bag=$('#invyarnisurtnitemFrm [name=wgt_per_bag]').val();
        no_of_bag=$('#invyarnisurtnitemFrm [name=no_of_bag]').val();
        if(Number.isInteger(no_of_bag*1)==false){
            alert('Decimal not allowed in No of Bag');
            return;
      }
        qty=wgt_per_bag*no_of_bag;
        $('#invyarnisurtnitemFrm [name=qty]').val(qty);
	}*/
	
	openReturnInvYarnWindow()
	{
		$('#rtninvyarnitemWindow').window('open');
	}

	getRtnYarnItemParams(){
		let params={}
		params.color_id=$('#invyarnitemsearchFrm [name=color_id]').val();
		params.brand=$('#invyarnitemsearchFrm [name=brand]').val();
		params.inv_rcv_id=$('#invyarnisurtnFrm [name=id]').val();
		return params;
	}

	searchReturnedYarnItem(){
		let params=this.getRtnYarnItemParams();
		let d = axios.get(this.route+"/getinvrcvyarnitem",{params})
		.then(function(response){
			$('#rtnyarnitemsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}

	showRtnYarnItemGrid(data){
		let self=this;
		var ryt = $('#rtnyarnitemsearchTbl');
		ryt.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.getRate(row.id);
				$('#invyarnisurtnitemFrm  [name=inv_yarn_item_id]').val(row.id);
				$('#invyarnisurtnitemFrm  [name=yarn_des]').val(row.yarn_des);
				$('#invyarnisurtnitemFrm  [name=supplier_name]').val(row.supplier_name);
				$('#invyarnisurtnitemFrm  [name=lot]').val(row.lot);
				$('#rtninvyarnitemWindow').window('close');
			},
		});
		ryt.datagrid('enableFilter').datagrid('loadData', data);
	}

	getRate(inv_yarn_item_id){
		let self=this;
		let params={};
		params.inv_yarn_item_id=inv_yarn_item_id
		let d = axios.get(this.route+"/getrate",{params})
		.then(function(response){
				$('#invyarnisurtnitemFrm  [name=rate]').val(response.data.store_rate);
				self.calculate_qty_form();
		})
		.catch(function(error){
			console.log(error);
		});
	}

	openReturnSaleOrderWindow()
	{
		$('#rtnSaleOrdersearchWindow').window('open');
	}

	getRtnSalesOrderParams(){
		let params={}
		params.style_ref=$('#rtnsaleordersearchFrm [name=style_ref]').val();
		params.job_no=$('#rtnsaleordersearchFrm [name=job_no]').val();
		params.sale_order_no=$('#rtnsaleordersearchFrm [name=sale_order_no]').val();
		return params;
	}

	searchReturnedSaleOrder(){
		let params=this.getRtnSalesOrderParams();
		let d = axios.get(this.route+"/getyarnsalesorder",{params})
		.then(function(response){
			$('#rtnsaleordersearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}

	showRtnSaleOrderGrid(data){
		let self=this;
		var so = $('#rtnsaleordersearchTbl');
		so.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				$('#invyarnisurtnitemFrm  [name=sales_order_id]').val(row.sales_order_id);
				$('#invyarnisurtnitemFrm  [name=sale_order_no]').val(row.sale_order_no);
				$('#invyarnisurtnitemFrm  [name=style_ref]').val(row.style_ref);
				$('#invyarnisurtnitemFrm  [name=buyer_name]').val(row.buyer_name);
				$('#rtnSaleOrdersearchWindow').window('close');
			},
		});
		so.datagrid('enableFilter').datagrid('loadData', data);
	}
}
window.MsInvYarnIsuRtnItem=new MsInvYarnIsuRtnItemController(new MsInvYarnIsuRtnItemModel());
MsInvYarnIsuRtnItem.showGrid();
MsInvYarnIsuRtnItem.showRtnYarnItemGrid([]);
MsInvYarnIsuRtnItem.showRtnSaleOrderGrid([]);