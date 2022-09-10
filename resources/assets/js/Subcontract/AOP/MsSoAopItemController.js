
let MsSoAopItemModel = require('./MsSoAopItemModel');

class MsSoAopItemController {
	constructor(MsSoAopItemModel)
	{
		this.MsSoAopItemModel = MsSoAopItemModel;
		this.formId='soaopitemFrm';
		this.dataTable='#soaopitemTbl';
		this.route=msApp.baseUrl()+"/soaopitem"
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
			this.MsSoAopItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoAopItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#soaopitemFrm [id="uom_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		msApp.resetForm('soaopitemFrm');
		$('#soaopitemFrm [name=so_aop_id]').val($('#soaopFrm [name=id]').val());
		//$('#soaopitemFrm [id="uom_id"]').combobox('setValue', '');
		MsSoAopItem.get($('#soaopFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let workorder = this.MsSoAopItemModel.get(index,row);
		workorder.then(function(response){
			$('#soaopitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
			$('#soaopitemFrm [id="gmt_buyer"]').combobox('setValue', response.data.fromData.gmt_buyer);
			$('#soaopitemFrm [id="fabric_color_id"]').combobox('setValue', response.data.fromData.fabric_color_id);
		}).catch(function(error){
			console.log(errors);
		});

	}
	get(so_aop_id)
	{
		let data= axios.get(this.route+"?so_aop_id="+so_aop_id);
		data.then(function (response) {
			$('#soaopitemTbl').datagrid('loadData', response.data);
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
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				tRate=(tAmout/tQty);
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    calculate()
	{
		let qty = $('#soaopitemFrm  [name=qty]').val();
		let rate = $('#soaopitemFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#soaopitemFrm  [name=amount]').val(amount);
	}
	
	soaopitemWindowOpen(){
		$('#soaopitemWindow').window('open');
	}

	searchItem() 
	{
		let construction_name=$('#soaopitemsearchFrm  [name=construction_name]').val();
		let composition_name=$('#soaopitemsearchFrm  [name=composition_name]').val();
		let data= axios.get(this.route+"/getitem?construction_name="+construction_name+"&composition_name="+composition_name);
		data.then(function (response) {
			$('#soaopitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridItemDescription(data){
		$('#soaopitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#soaopitemFrm [name=autoyarn_id]').val(row.id);
				$('#soaopitemFrm  [name=fabrication]').val(row.name+","+row.composition_name);
				$('#soaopitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsSoAopItem=new MsSoAopItemController(new MsSoAopItemModel());
MsSoAopItem.showGridItemDescription([]);
MsSoAopItem.showGrid([]);