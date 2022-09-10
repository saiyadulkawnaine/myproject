let MsProdGmtCartonDetailModel = require('./MsProdGmtCartonDetailModel');

class MsProdGmtCartonDetailController {
	constructor(MsProdGmtCartonDetailModel)
	{
		this.MsProdGmtCartonDetailModel = MsProdGmtCartonDetailModel;
		this.formId='prodgmtcartondetailFrm';
		this.dataTable='#prodgmtcartondetailTbl';
		this.route=msApp.baseUrl()+"/prodgmtcartondetail"
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
			alert('update not possible');
			return;
			//this.MsProdGmtCartonDetailModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtCartonDetailModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtCartonDetailModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtCartonDetailModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtcartondetailTbl').datagrid('reload');
		msApp.resetForm('prodgmtcartondetailFrm');
		$('#prodgmtcartondetailFrm [name=prod_gmt_carton_entry_id]').val($('#prodgmtcartonentryFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtCartonDetailModel.get(index,row);

	}

	showGrid(prod_gmt_carton_entry_id){
		let self=this;
		let data = {};
		data.prod_gmt_carton_entry_id=prod_gmt_carton_entry_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			showFooter:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['carton_amount'].replace(/,/g,'')*1;
				}

				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						carton_amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						ac_button:'<a></a>'
						
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row){

		if(row.prod_gmt_ex_factory_qty_id)
		{
			return "Ship Out";
		}
		else
		{
			return '<a href="javascript:void(0)"  onClick="MsProdGmtCartonDetail.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Remove</span></a>';
		}
    }

    /* calculate()
	{
		let qty = $('#prodgmtcartondetailFrm  [name=qty]').val();
		let rate = $('#prodgmtcartondetailFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#prodgmtcartondetailFrm  [name=amount]').val(amount);
	} */
	
	openSalesOrderCartonWindow(){
		$('#opencartoncountrywindow').window('open');
	}

	openStylePkgWindow(){
		 let params={};
		
        params.style_id=$('#prodgmtcartondetailFrm [name=style_id]').val();
		$('#opencartonpkgwindow').window('open');
		let d= axios.get(this.route+'/getpkgratio',{params})
		.then(function (response) {
			$('#cartonpackingratioTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getParams()
	{
	    let params={};
		
        params.sale_order_no=$('#salesordernosearchFrm [name=sale_order_no]').val();
        params.job_no=$('#salesordernosearchFrm [name=job_no]').val();
        params.style_ref=$('#salesordernosearchFrm [name=style_ref]').val();
        params.prodgmtcartonid=$('#prodgmtcartonentryFrm [name=id]').val();
		return 	params;
	}
	/*get(){
		let params=this.getParams(); 
		let d= axios.get(this.route+'/html',{params})
		.then(function (response) {
			$('#libcodata').html('');
			$('#libcodata').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}*/

	getSalesOrderCountry(){
		let params=this.getParams(); 
		let d= axios.get(this.route+'/getcartoncountry',{params})
		.then(function (response) {
			//MsProjectionProgress.showGrid(response.data.datad)
			$('#salesordernosearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
        
	
	}
	showCountryGrid(data)
	{
		let self = this;
		$('#salesordernosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtcartondetailFrm [name=sales_order_country_id]').val(row.id);
				$('#prodgmtcartondetailFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtcartondetailFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtcartondetailFrm [name=ship_date]').val(row.ship_date);
				$('#prodgmtcartondetailFrm [name=job_no]').val(row.job_no);
				$('#prodgmtcartondetailFrm [name=style_ref]').val(row.style_ref);
				$('#prodgmtcartondetailFrm [name=style_id]').val(row.style_id);
				$('#prodgmtcartondetailFrm [name=country_id]').val(row.country_id);
				$('#prodgmtcartondetailFrm [name=buyer_name]').val(row.buyer_name);

				$('#prodgmtcartondetailunassortedFrm [name=sales_order_country_id]').val(row.id);
				$('#prodgmtcartondetailunassortedFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtcartondetailunassortedFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtcartondetailunassortedFrm [name=ship_date]').val(row.ship_date);
				$('#prodgmtcartondetailunassortedFrm [name=job_no]').val(row.job_no);
				$('#prodgmtcartondetailunassortedFrm [name=style_ref]').val(row.style_ref);
				$('#prodgmtcartondetailunassortedFrm [name=style_id]').val(row.style_id);
				$('#prodgmtcartondetailunassortedFrm [name=country_id]').val(row.country_id);
				$('#prodgmtcartondetailunassortedFrm [name=buyer_name]').val(row.buyer_name);
				
				$('#opencartoncountrywindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	showPkgRatioGrid(data)
	{
		let self = this;
		$('#cartonpackingratioTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtcartondetailFrm [name=style_pkg_id]').val(row.id);
				$('#prodgmtcartondetailFrm [name=style_pkg_name]').val(row.style_pkg_name+', '+row.assortment_name);
                $('#prodgmtcartondetailFrm [name=gmt_per_carton]').val(row.qty);
				$('#opencartonpkgwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}
  
}
window.MsProdGmtCartonDetail=new MsProdGmtCartonDetailController(new MsProdGmtCartonDetailModel());
MsProdGmtCartonDetail.showCountryGrid([]);
MsProdGmtCartonDetail.showPkgRatioGrid([]);
