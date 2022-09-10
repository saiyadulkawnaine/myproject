
let MsSoEmbItemModel = require('./MsSoEmbItemModel');

class MsSoEmbItemController {
	constructor(MsSoEmbItemModel)
	{
		this.MsSoEmbItemModel = MsSoEmbItemModel;
		this.formId='soembitemFrm';
		this.dataTable='#soembitemTbl';
		this.route=msApp.baseUrl()+"/soembitem"
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
			this.MsSoEmbItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoEmbItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soembitemFrm [name=so_emb_id]').val($('#soembFrm [name=id]').val());
		let production_area_id = $('#soembFrm  [name=production_area_id]').val();
		if (production_area_id==45) {
			$('#soembitemFrm  [name=embelishment_id]').val(1);
			//MsSoEmbItem.embnameChange(1); //embelishment_id=printing
		 }
		 if (production_area_id==50) {
			$('#soembitemFrm  [name=embelishment_id]').val(21);
			//MsSoEmbItem.embnameChange(21); //embelishment_id=embroydary
		 }
		$('#soembitemFrm [id="uom_id"]').combobox('setValue', '');
		$('#soembitemFrm [id="gmt_buyer"]').combobox('setValue', '');
		$('#soembitemFrm [id="item_account_id"]').combobox('setValue', '');
		$('#soembitemFrm [id="gmtspart_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{	
		MsSoEmbItem.resetForm();
		MsSoEmbItem.get($('#soembFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		//let data=MsSoEmbItem.getembType (row.embelishment_id);

		let workorder = this.MsSoEmbItemModel.get(index,row);
		workorder.then(function(response){
			$('#soembitemFrm [name="embelishment_type_id"]').empty();
			$('#soembitemFrm [name="embelishment_type_id"]').append('<option value="">-Select-</option>');
			$.each(response.data.embelishmenttype, function(key, value) {
			$('#soembitemFrm [name="embelishment_type_id"]').append('<option value="'+ value.id +'">'+ value.name+'</option>');
			});
			$('#soembitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
			$('#soembitemFrm [id="gmt_buyer"]').combobox('setValue', response.data.fromData.gmt_buyer);
			$('#soembitemFrm [id="item_account_id"]').combobox('setValue', response.data.fromData.item_account_id);
			$('#soembitemFrm [id="gmtspart_id"]').combobox('setValue', response.data.fromData.gmtspart_id);
			$('#soembitemFrm [name="embelishment_type_id"]').val(response.data.fromData.embelishment_type_id);
		}).catch(function(error){
			console.log(errors);
		});

	}
	get(so_emb_id)
	{
		let data= axios.get(this.route+"?so_emb_id="+so_emb_id);
		data.then(function (response) {
			$('#soembitemTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    calculate()
	{
		let qty = $('#soembitemFrm  [name=qty]').val();
		let rate = $('#soembitemFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#soembitemFrm  [name=amount]').val(amount);
	}
	
	/*soembitemWindowOpen(){
		$('#soembitemWindow').window('open');
	}

	searchItem() 
	{
		let construction_name=$('#soembitemsearchFrm  [name=construction_name]').val();
		let composition_name=$('#soembitemsearchFrm  [name=composition_name]').val();
		let data= axios.get(this.route+"/getitem?construction_name="+construction_name+"&composition_name="+composition_name);
		data.then(function (response) {
			$('#soembitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridItemDescription(data){
		$('#soembitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#soembitemFrm [name=autoyarn_id]').val(row.id);
				$('#soembitemFrm  [name=fabrication]').val(row.name+","+row.composition_name);
				$('#soembitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}*/

	embnameChange(embelishment_id){
		MsSoEmbItem.getembType (embelishment_id)
		//$('#washchargeFrm  [name=rate]').val('');
	}
	
	getembType (embelishment_id){
		let data= axios.get(this.route+"/embtype?embelishment_id="+embelishment_id)
		.then(function (response) {
		    $('#soembitemFrm [name="embelishment_type_id"]').empty();
			$('#soembitemFrm [name="embelishment_type_id"]').append('<option value="">-Select-</option>');
            $.each(response.data.embelishmenttype, function(key, value) {
				$('#soembitemFrm [name="embelishment_type_id"]').append('<option value="'+ value.id +'">'+ value.name+'</option>');
            });
		})
		.catch(function (error) {
			console.log(error);
		});
		return data;
	}
}
window.MsSoEmbItem=new MsSoEmbItemController(new MsSoEmbItemModel());
MsSoEmbItem.showGrid([]);
//MsSoEmbItem.showGridItemDescription([]);