let MsInvYarnIsuSamSecItemModel = require('./MsInvYarnIsuSamSecItemModel');
require('./../../datagrid-filter.js');
class MsInvYarnIsuSamSecItemController {
	constructor(MsInvYarnIsuSamSecItemModel)
	{
		this.MsInvYarnIsuSamSecItemModel = MsInvYarnIsuSamSecItemModel;
		this.formId='invyarnisusamsecitemFrm';
		this.dataTable='#invyarnisusamsecitemTbl';
		this.route=msApp.baseUrl()+"/invyarnisusamsecitem"
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
		formObj.isu_basis_id=$('#invyarnisusamsecFrm [name=isu_basis_id]').val()
		if(formObj.id){
			this.MsInvYarnIsuSamSecItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnIsuSamSecItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let inv_isu_id = $('#invyarnisusamsecFrm [name=id]').val();
		$('#invyarnisusamsecitemFrm  [name=inv_isu_id]').val(inv_isu_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnIsuSamSecItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnIsuSamSecItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvYarnIsuSamSecItem.get(d.inv_isu_id);
		msApp.resetForm('invyarnisusamsecitemFrm');
		$('#invyarnisusamsecitemFrm  [name=inv_isu_id]').val(d.inv_isu_id);
	}
	get(inv_isu_id)
	{
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invyarnisusamsecitemTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	edit(index,row)
	{
		let self=this;
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnIsuSamSecItemModel.get(index,row);
		data.then(function (response) {
			self.createDropDownOption(response.data.sampleDropDown)
			msApp.set(index,row,response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
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
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tReturnableQty=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tReturnableQty+=data.rows[i]['returnable_qty'].replace(/,/g,'')*1;
				}			
				
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						returnable_qty: tReturnableQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvYarnIsuSamSecItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openInvYarnWindow()
	{
		$('#invyarnisusamsecitemWindow').window('open');
		$('#invyarnisusamsecitemsearchTbl').datagrid('loadData',[]);

	}

	

	

	itemSearchGrid(data){
		let self=this;
		$('#invyarnisusamsecitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invyarnisusamsecitemFrm  [name=inv_yarn_item_id]').val(row.id);
				$('#invyarnisusamsecitemFrm  [name=yarn_count]').val(row.yarn_count);
				$('#invyarnisusamsecitemFrm  [name=composition]').val(row.composition);
				$('#invyarnisusamsecitemFrm  [name=yarn_type]').val(row.yarn_type);
				$('#invyarnisusamsecitemFrm  [name=itemcategory_name]').val(row.itemcategory_name);
				$('#invyarnisusamsecitemFrm  [name=itemclass_name]').val(row.itemclass_name);
				$('#invyarnisusamsecitemFrm  [name=lot]').val(row.lot);
				$('#invyarnisusamsecitemFrm  [name=brand]').val(row.brand);
				$('#invyarnisusamsecitemFrm  [name=color_id]').val(row.color_name);
				$('#invyarnisusamsecitemFrm  [name=supplier_name]').val(row.supplier_name);
				$('#invyarnisusamsecitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchYarnItem(){
		let supplier_id=$('#invyarnisusamsecitemsearchFrm [name=supplier_id]').val();
		let lot=$('#invyarnisusamsecitemsearchFrm [name=lot]').val();
		let brand=$('#invyarnisusamsecitemsearchFrm [name=brand]').val();
		let params={};
		params.supplier_id=supplier_id;
		params.lot=lot;
		params.brand=brand;
		let d=axios.get(this.route+'/getyarnitem',{params})
		.then(function(response){
			$('#invyarnisusamsecitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}


	openStyleWindow()
	{
		$('#invyarnisusamsecstyleWindow').window('open');
		$('#invyarnisusamsecstylesearchTbl').datagrid('loadData',[]);

	}

	styleSearchGrid(data){
		let self=this;
		$('#invyarnisusamsecstylesearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invyarnisusamsecitemFrm  [name=style_id]').val(row.id);
				$('#invyarnisusamsecitemFrm  [name=style_ref]').val(row.style_ref);
				$('#invyarnisusamsecitemFrm  [name=buyer_name]').val(row.buyer);
				$('#invyarnisusamsecstyleWindow').window('close');
				self.getSample(row.id);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchStyle(){
		let style_ref=$('#invyarnisusamsecstylesearchFrm [name=style_ref]').val();
		let inv_isu_id=$('#invyarnisusamsecFrm [name=id]').val();
		let params={};
		params.style_ref=style_ref;
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route+'/getstyle',{params})
		.then(function(response){
			$('#invyarnisusamsecstylesearchTbl').datagrid('loadData',response.data);
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


	openOrderWindow()
	{
		$('#invyarnisusamsecorderWindow').window('open');
		$('#invyarnisusamsecordersearchTbl').datagrid('loadData',[]);

	}

	orderSearchGrid(data){
		let self=this;
		$('#invyarnisusamsecordersearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				$('#invyarnisusamsecitemFrm  [name=sale_order_id]').val(row.id);
				$('#invyarnisusamsecitemFrm  [name=sale_order_no]').val(row.sale_order_no);
				$('#invyarnisusamsecitemFrm  [name=style_id]').val(row.style_id);
				$('#invyarnisusamsecitemFrm  [name=style_ref]').val(row.style_ref);
				$('#invyarnisusamsecitemFrm  [name=buyer_name]').val(row.buyer_name);
				$('#invyarnisusamsecorderWindow').window('close');
				self.getSample(row.style_id);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchOrder(){
		let sale_order_no=$('#invyarnisusamsecordersearchFrm [name=sale_order_no]').val();
		let style_ref=$('#invyarnisusamsecitemFrm [name=style_ref]').val();
		let style_id=$('#invyarnisusamsecitemFrm [name=style_id]').val();
		let inv_isu_id=$('#invyarnisusamsecFrm [name=id]').val();
		let params={};
		params.sale_order_no=sale_order_no;
		params.inv_isu_id=inv_isu_id;
		params.style_ref=style_ref;
		params.style_id=style_id;
		let d=axios.get(this.route+'/getorder',{params})
		.then(function(response){
			$('#invyarnisusamsecordersearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}


}
window.MsInvYarnIsuSamSecItem=new MsInvYarnIsuSamSecItemController(new MsInvYarnIsuSamSecItemModel());
MsInvYarnIsuSamSecItem.showGrid();
MsInvYarnIsuSamSecItem.itemSearchGrid([]);
MsInvYarnIsuSamSecItem.styleSearchGrid([]);
MsInvYarnIsuSamSecItem.orderSearchGrid([]);