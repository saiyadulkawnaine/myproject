let MsProdKnitItemYarnModel = require('./MsProdKnitItemYarnModel');
require('./../../datagrid-filter.js');
class MsProdKnitItemYarnController {
	constructor(MsProdKnitItemYarnModel)
	{
		this.MsProdKnitItemYarnModel = MsProdKnitItemYarnModel;
		this.formId='prodknititemyarnFrm';
		this.dataTable='#prodknititemyarnTbl';
		this.route=msApp.baseUrl()+"/prodknititemyarn"
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
		let prod_knit_item_id = $('#prodknititemFrm  [name=id]').val();
		formObj.prod_knit_item_id=prod_knit_item_id;

		if(formObj.id){
			this.MsProdKnitItemYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdKnitItemYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdKnitItemYarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdKnitItemYarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsProdKnitItemYarn.get($('#prodknititemyarnFrm  [name=prod_knit_item_id]').val())
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let yarn=this.MsProdKnitItemYarnModel.get(index,row);
		
	}
	get(prod_knit_item_id)
	{
		let data= axios.get(this.route+"?prod_knit_item_id="+prod_knit_item_id);
		data.then(function (response) {
			$('#prodknititemyarnTbl').datagrid('loadData', response.data);
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
			fitColumns:true,
			showFooter:true,
			//url:this.route+'?prod_knit_item_id='+prod_knit_item_id,

			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var qty=0;
				var amount=0;
				
				for(var i=0; i<data.rows.length; i++){
				qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdKnitItemYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	prodknititemyarnWindowOpen()
	{
		//this.getyarn();
	   $('#prodknititemyarnWindow').window('open');
	}

	showprodknititemyarnGrid(data){
		let self = this;
		$('#prodknititemyarnsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#prodknititemyarnFrm [name=inv_yarn_isu_item_id]').val(row.id);
					$('#prodknititemyarnFrm [name=inv_yarn_item_id]').val(row.inv_yarn_item_id);
					$('#prodknititemyarnFrm [name=lot]').val(row.lot);
					$('#prodknititemyarnFrm [name=yarn_count]').val(row.yarn_count);
					$('#prodknititemyarnFrm [name=yarn_type]').val(row.yarn_type);
					$('#prodknititemyarnFrm [name=composition]').val(row.composition);
					$('#prodknititemyarnFrm [name=brand]').val(row.brand);
					$('#prodknititemyarnFrm [name=supplier_name]').val(row.supplier_name);
					$('#prodknititemyarnFrm [name=rate]').val(row.rate);
					$('#prodknititemyarnWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	getyarn()
	{
		let params={};
		params.prod_knit_item_id=$('#prodknititemFrm  [name=id]').val();
		params.lot=$('#prodknititemyarnsearchFrm  [name=lot]').val();
		params.brand=$('#prodknititemyarnsearchFrm  [name=brand]').val();
		params.yarn_supplier_id=$('#prodknititemyarnsearchFrm  [name=supplier_id]').val();
		let data= axios.get(this.route+"/getyarn",{params});
		data.then(function (response) {
			$('#prodknititemyarnsearchTbl').datagrid('loadData', response.data);
			$('#prodknititemyarnWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	calculate()
	{
		let qty = $('#prodknititemyarnFrm  [name=qty]').val();
		let rate = $('#prodknititemyarnFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#prodknititemyarnFrm  [name=amount]').val(amount);
	}
}

window.MsProdKnitItemYarn=new MsProdKnitItemYarnController(new MsProdKnitItemYarnModel());
MsProdKnitItemYarn.showGrid([]);
MsProdKnitItemYarn.showprodknititemyarnGrid([]);