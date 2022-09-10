
let MsSoKnitItemModel = require('./MsSoKnitItemModel');

class MsSoKnitItemController {
	constructor(MsSoKnitItemModel)
	{
		this.MsSoKnitItemModel = MsSoKnitItemModel;
		this.formId='soknititemFrm';
		this.dataTable='#soknititemTbl';
		this.route=msApp.baseUrl()+"/soknititem"
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
			this.MsSoKnitItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoKnitItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#soknititemFrm [id="uom_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		msApp.resetForm('soknititemFrm');
		$('#soknititemFrm [name=so_knit_id]').val($('#soknitFrm [name=id]').val());
		//$('#soknititemFrm [id="uom_id"]').combobox('setValue', '');
		MsSoKnitItem.get($('#soknitFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let workorder = this.MsSoKnitItemModel.get(index,row);
		workorder.then(function(response){
			$('#soknititemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
			$('#sodyeingitemFrm [id="gmt_buyer"]').combobox('setValue', response.data.fromData.gmt_buyer);
		}).catch(function(error){
			console.log(errors);
		});

	}
	get(so_knit_id)
	{
		let data= axios.get(this.route+"?so_knit_id="+so_knit_id);
		data.then(function (response) {
			$('#soknititemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tPcsQty=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tPcsQty+=data.rows[i]['pcs_qty'].replace(/,/g,'')*1;
				}
				tRate=(tAmout/tQty);
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						pcs_qty: tPcsQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoKnitItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    calculate()
	{
		let qty = $('#soknititemFrm  [name=qty]').val();
		let rate = $('#soknititemFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#soknititemFrm  [name=amount]').val(amount);
	}
	
	soknititemWindowOpen(){
		$('#soknititemWindow').window('open');
	}

	searchItem() 
	{
		let construction_name=$('#soknititemsearchFrm  [name=construction_name]').val();
		let composition_name=$('#soknititemsearchFrm  [name=composition_name]').val();
		let data= axios.get(this.route+"/getitem?construction_name="+construction_name+"&composition_name="+composition_name);
		data.then(function (response) {
			$('#soknititemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridItemDescription(data){
		$('#soknititemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#soknititemFrm [name=autoyarn_id]').val(row.id);
				$('#soknititemFrm  [name=fabrication]').val(row.name+","+row.composition_name);
				$('#soknititemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsSoKnitItem=new MsSoKnitItemController(new MsSoKnitItemModel());
MsSoKnitItem.showGridItemDescription([]);