let MsPlKnitItemQtyModel = require('./MsPlKnitItemQtyModel');
//require('./../../datagrid-filter.js');
class MsPlKnitItemQtyController {
	constructor(MsPlKnitItemQtyModel)
	{
		this.MsPlKnitItemQtyModel = MsPlKnitItemQtyModel;
		this.formId='plknititemqtyFrm';
		this.dataTable='#plknititemqtyTbl';
		this.route=msApp.baseUrl()+"/plknititemqty"
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
			this.MsPlKnitItemQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPlKnitItemQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#plknititemqtyFrm [name=pl_knit_item_id]').val($('#plknititemFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPlKnitItemQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPlKnitItemQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		MsPlKnitItemQty.get($('#plknititemFrm  [name=id]').val())
		MsPlKnitItemQty.resetForm();
		
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPlKnitItemQtyModel.get(index,row);
	}

	get(pl_knit_item_id)
	{
		let params={};
		params.pl_knit_item_id=pl_knit_item_id;
		
		let data= axios.get(this.route,{params});
		data.then(function (response) {
			$('#plknititemqtyTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			emptyMsg:'No Record Found',
			//fitColumns:true,
			//url:this.route,
			onLoadSuccess: function(data){
					var qty=0;
					var prod_qty=0;
					for(var i=0; i<data.rows.length; i++){
						qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
						prod_qty+=data.rows[i]['prod_qty'].replace(/,/g,'')*1;
					}
					$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						prod_qty: prod_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
					]);
			},
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsPlKnitItemQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPlKnitItemQty=new MsPlKnitItemQtyController(new MsPlKnitItemQtyModel());
MsPlKnitItemQty.showGrid([]);