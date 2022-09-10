let MsInvYarnIsuRtnItemSamSecModel = require('./MsInvYarnIsuRtnItemSamSecModel');
class MsInvYarnIsuRtnItemSamSecController {
	constructor(MsInvYarnIsuRtnItemSamSecModel)
	{
		this.MsInvYarnIsuRtnItemSamSecModel = MsInvYarnIsuRtnItemSamSecModel;
		this.formId='invyarnisurtnitemsamsecFrm';
		this.dataTable='#invyarnisurtnitemsamsecTbl';
		this.route=msApp.baseUrl()+"/invyarnisurtnitemsamsec"
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

		let inv_rcv_id=$('#invyarnisurtnsamsecFrm [name=id]').val()
		let inv_yarn_rcv_id=$('#invyarnisurtnsamsecFrm [name=inv_yarn_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_yarn_rcv_id=inv_yarn_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;
		if(formObj.id){
			this.MsInvYarnIsuRtnItemSamSecModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnIsuRtnItemSamSecModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnIsuRtnItemSamSecModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnIsuRtnItemSamSecModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invyarnisurtnitemsamsecTbl').datagrid('reload');
		//MsInvYarnIsuRtnItem.create()
		msApp.resetForm('invyarnisurtnitemsamsecFrm');
        $('#invyarnisurtnitemsamsecFrm  [name=inv_yarn_rcv_id]').val($('#invyarnisurtnsamsecFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data = this.MsInvYarnIsuRtnItemSamSecModel.get(index,row);
		data.then(function (response) {
			self.createDropDownOption(response.data.sampleDropDown)
			msApp.set(index,row,response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
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
		return '<a href="javascript:void(0)"  onClick="MsInvYarnIsuRtnItemSamSec.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    calculate_qty_form(iteration,count)
	{
		let cone_per_bag=$('#invyarnisurtnitemsamsecFrm input[name=cone_per_bag]').val();
		let wgt_per_cone=$('#invyarnisurtnitemsamsecFrm input[name=wgt_per_cone]').val();
		let no_of_bag=$('#invyarnisurtnitemsamsecFrm input[name=no_of_bag]').val();
		if(Number.isInteger(no_of_bag*1)==false){
              alert('Decimal not allowed in no of bag');
              $('#invyarnisurtnitemsamsecFrm input[name=no_of_bag]').val('')
              return;
		}
		wgt_per_cone=wgt_per_cone*1;
		wgt_per_cone=wgt_per_cone.toFixed(4);
		$('#invyarnisurtnitemsamsecFrm input[name=wgt_per_cone]').val(wgt_per_cone);

		let qty=cone_per_bag*wgt_per_cone*no_of_bag;
		let rate=$('#invyarnisurtnitemsamsecFrm input[name=rate]').val();
		let amount=qty*1*rate*1;
		$('#invyarnisurtnitemsamsecFrm input[name=qty]').val(qty);
		$('#invyarnisurtnitemsamsecFrm input[name=amount]').val(amount);
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
		$('#rtninvyarnitemsamsecWindow').window('open');
	}

	getRtnYarnItemParams(){
		let params={}
		params.color_id=$('#invyarnitemsamsecsearchFrm [name=color_id]').val();
		params.brand=$('#invyarnitemsamsecsearchFrm [name=brand]').val();
		params.inv_rcv_id=$('#invyarnisurtnsamsecFrm [name=id]').val();
		return params;
	}

	searchReturnedYarnItem(){
		let params=this.getRtnYarnItemParams();
		let d = axios.get(this.route+"/getinvrcvyarnitem",{params})
		.then(function(response){
			$('#rtnyarnitemsamsecsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}

	showRtnYarnItemGrid(data){
		let self=this;
		var ryt = $('#rtnyarnitemsamsecsearchTbl');
		ryt.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.getRate(row.id);
				$('#invyarnisurtnitemsamsecFrm  [name=inv_yarn_item_id]').val(row.id);
				$('#invyarnisurtnitemsamsecFrm  [name=yarn_des]').val(row.yarn_des);
				$('#invyarnisurtnitemsamsecFrm  [name=supplier_name]').val(row.supplier_name);
				$('#invyarnisurtnitemsamsecFrm  [name=lot]').val(row.lot);
				$('#rtninvyarnitemsamsecWindow').window('close');
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
				$('#invyarnisurtnitemsamsecFrm  [name=rate]').val(response.data.store_rate);
				self.calculate_qty_form();
		})
		.catch(function(error){
			console.log(error);
		});
	}

	openReturnSaleOrderWindow()
	{
		$('#rtnSaleOrdersamsecsearchWindow').window('open');
	}

	getRtnSalesOrderParams(){
		let params={}
		params.style_ref=$('#rtnsaleordersamsecsearchFrm [name=style_ref]').val();
		params.job_no=$('#rtnsaleordersamsecsearchFrm [name=job_no]').val();
		params.sale_order_no=$('#rtnsaleordersamsecsearchFrm [name=sale_order_no]').val();
		return params;
	}

	searchReturnedSaleOrder(){
		let params=this.getRtnSalesOrderParams();
		let d = axios.get(this.route+"/getyarnsalesorder",{params})
		.then(function(response){
			$('#rtnsaleordersamsecsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}

	showRtnSaleOrderGrid(data){
		let self=this;
		var so = $('#rtnsaleordersamsecsearchTbl');
		so.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				$('#invyarnisurtnitemsamsecFrm  [name=sales_order_id]').val(row.sales_order_id);
				$('#invyarnisurtnitemsamsecFrm  [name=sale_order_no]').val(row.sale_order_no);
				$('#invyarnisurtnitemsamsecFrm  [name=style_id]').val(row.style_id);
				$('#invyarnisurtnitemsamsecFrm  [name=style_ref]').val(row.style_ref);
				$('#invyarnisurtnitemsamsecFrm  [name=buyer_name]').val(row.buyer_name);
				$('#rtnSaleOrdersamsecsearchWindow').window('close');
				self.getSample(row.style_id);
			},
		});
		so.datagrid('enableFilter').datagrid('loadData', data);
	}


	openStyleWindow()
	{
		$('#invyarnisurtnsamsecstyleWindow').window('open');
		$('#invyarnisurtnsamsecstylesearchTbl').datagrid('loadData',[]);

	}

	styleSearchGrid(data){
		let self=this;
		$('#invyarnisurtnsamsecstylesearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invyarnisurtnitemsamsecFrm  [name=style_id]').val(row.id);
				$('#invyarnisurtnitemsamsecFrm  [name=style_ref]').val(row.style_ref);
				$('#invyarnisurtnitemsamsecFrm  [name=buyer_name]').val(row.buyer);
				$('#invyarnisurtnsamsecstyleWindow').window('close');
				self.getSample(row.id);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchStyle(){
		let style_ref=$('#invyarnisurtnsamsecstylesearchFrm [name=style_ref]').val();
		let inv_rcv_id=$('#invyarnisurtnsamsecFrm [name=id]').val();
		let params={};
		params.style_ref=style_ref;
		params.inv_rcv_id=inv_rcv_id;
		let d=axios.get(this.route+'/getstyle',{params})
		.then(function(response){
			$('#invyarnisurtnsamsecstylesearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	getSample(style_id){
		let self=this;
		let params={};
		params.style_id=style_id;
		let d=axios.get(this.route+'/getsample',{params})
		.then(function(response){
			self.createDropDownOption(response.data);
		}).catch(function(error){
			console.log(error);
		})
		return d;
	}

	createDropDownOption(data)
	{
		$('select[name="style_sample_id"]').empty();
		$('select[name="style_sample_id"]').append('<option value="">-Select-</option>');
		$.each(data, function(key, value) {
		$('select[name="style_sample_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
		});

	}
}
window.MsInvYarnIsuRtnItemSamSec=new MsInvYarnIsuRtnItemSamSecController(new MsInvYarnIsuRtnItemSamSecModel());
MsInvYarnIsuRtnItemSamSec.showGrid();
MsInvYarnIsuRtnItemSamSec.showRtnYarnItemGrid([]);
MsInvYarnIsuRtnItemSamSec.showRtnSaleOrderGrid([]);
MsInvYarnIsuRtnItemSamSec.styleSearchGrid([]);