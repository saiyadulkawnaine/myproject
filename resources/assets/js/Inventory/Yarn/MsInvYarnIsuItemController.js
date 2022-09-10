let MsInvYarnIsuItemModel = require('./MsInvYarnIsuItemModel');
require('./../../datagrid-filter.js');
class MsInvYarnIsuItemController {
	constructor(MsInvYarnIsuItemModel)
	{
		this.MsInvYarnIsuItemModel = MsInvYarnIsuItemModel;
		this.formId='invyarnisuitemFrm';
		this.dataTable='#invyarnisuitemTbl';
		this.route=msApp.baseUrl()+"/invyarnisuitem"
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
			this.MsInvYarnIsuItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnIsuItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let inv_isu_id = $('#invyarnisuFrm [name=id]').val();
		$('#invyarnisuitemFrm  [name=inv_isu_id]').val(inv_isu_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnIsuItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnIsuItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsInvYarnIsuItem.get(d.inv_isu_id);
		msApp.resetForm('invyarnisuitemFrm');
		$('#invyarnisuitemFrm  [name=inv_isu_id]').val(d.inv_isu_id);
	}
	get(inv_isu_id)
	{
		let params={};
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invyarnisuitemTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnIsuItemModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsInvYarnIsuItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		$('#openinvyarnisuitemwindow').window('open');
		$('#invyarnisuitemsearchTbl').datagrid('loadData',[]);

	}

	itemSearchGrid(data){
		let self=this;
		$('#invyarnisuitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
				//self.resetForm();
				$('#invyarnisuitemFrm  [name=inv_yarn_item_id]').val(row.inv_yarn_item_id);
				$('#invyarnisuitemFrm  [name=rq_yarn_item_id]').val(row.rq_yarn_item_id);
				$('#invyarnisuitemFrm  [name=po_yarn_dyeing_item_bom_qty_id]').val(row.po_yarn_dyeing_item_bom_qty_id);
				$('#invyarnisuitemFrm  [name=yarn_count]').val(row.yarn_count);
				$('#invyarnisuitemFrm  [name=composition]').val(row.composition);
				$('#invyarnisuitemFrm  [name=yarn_type]').val(row.yarn_type);
				$('#invyarnisuitemFrm  [name=itemcategory_name]').val(row.itemcategory_name);
				$('#invyarnisuitemFrm  [name=itemclass_name]').val(row.itemclass_name);
				$('#invyarnisuitemFrm  [name=lot]').val(row.lot);
				$('#invyarnisuitemFrm  [name=brand]').val(row.brand);
				$('#invyarnisuitemFrm  [name=color_id]').val(row.color_name);
				$('#invyarnisuitemFrm  [name=sale_order_no]').val(row.sale_order_no);
				$('#invyarnisuitemFrm  [name=style_ref]').val(row.style_ref);
				$('#invyarnisuitemFrm  [name=buyer_name]').val(row.buyer_name);
				$('#openinvyarnisuitemwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	serachItem(){
		let po_no=$('#invyarnisuitemsearchFrm [name=po_no]').val();
		let rq_no=$('#invyarnisuitemsearchFrm [name=rq_no]').val();
		let inv_isu_id=$('#invyarnisuFrm [name=id]').val();
		let params={};
		params.po_no=po_no;
		params.rq_no=rq_no;
		params.inv_isu_id=inv_isu_id;
		let d=axios.get(this.route+'/getyarnisuitem',{params})
		.then(function(response){
			$('#invyarnisuitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

}
window.MsInvYarnIsuItem=new MsInvYarnIsuItemController(new MsInvYarnIsuItemModel());
MsInvYarnIsuItem.showGrid();
MsInvYarnIsuItem.itemSearchGrid([]);