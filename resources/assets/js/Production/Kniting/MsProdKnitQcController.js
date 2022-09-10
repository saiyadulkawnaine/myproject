let MsProdKnitQcModel = require('./MsProdKnitQcModel');
require('./../../datagrid-filter.js');
class MsProdKnitQcController {
	constructor(MsProdKnitQcModel)
	{
		this.MsProdKnitQcModel = MsProdKnitQcModel;
		this.formId='prodknitqcFrm';
		this.dataTable='#prodknitqcTbl';
		this.route=msApp.baseUrl()+"/prodknitqc"
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
			this.MsProdKnitQcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdKnitQcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	submitAndClose()
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
		let formObj=this.getSelections();
		if(formObj.id)
		{
			this.MsProdKnitQcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else
		{
			this.MsProdKnitQcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm (){
		msApp.resetForm(this.formId);
	}

	remove(){
		let formObj=msApp.get(this.formId);
		this.MsProdKnitQcModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id){
		event.stopPropagation()
		this.MsProdKnitQcModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsProdKnitQc.get();
		msApp.resetForm('prodknitqcFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
	    this.MsProdKnitQcModel.get(index,row);
	}

	get(){
		let params={};
		//let qc_date=$('#prodknitqcFrm [name=qc_date]').val();
		/*let from_qc_date=$('#from_qc_date').val();
		let to_qc_date=$('#to_qc_date').val();
		

		params.from_qc_date=from_qc_date;
		params.to_qc_date=to_qc_date;
		if(!params.from_qc_date){
			alert('Select Qc Date');
			return;
		}
		if(!params.to_qc_date){
			alert('Select Qc Date');
			return;
		}*/
		let data= axios.get(this.route,{params})
		.then(function (response) {
			$('#prodknitqcTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdKnit.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	rollWindow()
	{
		$('#prodknitqcWindow').window('open');
		//this.getRoll();

	}
	showGridRoll(data){
		let self=this;
		var dg=$('#prodknitqcrollTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
				$('#prodknitqcFrm [name=prod_knit_item_roll_id]').val(row.prod_knit_item_roll_id);
				$('#prodknitqcFrm [name=roll_no]').val(row.prod_knit_item_roll_id);
				$('#prodknitqcFrm [name=other_prod_knit_item_roll_id]').val(row.prod_knit_item_roll_id);
				$('#prodknitqcFrm [name=prod_no]').val(row.prod_no);
				$('#prodknitqcFrm [name=body_part]').val(row.body_part);
				$('#prodknitqcFrm [name=fabrication]').val(row.fabrication);
				$('#prodknitqcFrm [name=fabric_shape]').val(row.fabric_shape);
				$('#prodknitqcFrm [name=fabric_look]').val(row.fabric_look);
				$('#prodknitqcFrm [name=gsm_weight]').val(row.gsm_weight);
				$('#prodknitqcFrm [name=dia_width]').val(row.dia_width);
				$('#prodknitqcFrm [name=measurement]').val(row.measurement);
				$('#prodknitqcFrm [name=roll_length]').val(row.roll_length);
				$('#prodknitqcFrm [name=colorrange_name]').val(row.colorrange_name);
				$('#prodknitqcFrm [name=fabric_color_name]').val(row.fabric_color_name);
				$('#prodknitqcFrm [name=stitch_length]').val(row.stitch_length);
				$('#prodknitqcFrm [name=roll_weight]').val(row.roll_weight);
				$('#prodknitqcFrm [name=qty_pcs]').val(row.qty_pcs);
				$('#prodknitqcFrm [name=floor_name]').val(row.floor_name);
				$('#prodknitqcFrm [name=machine_no]').val(row.machine_no);
				$('#prodknitqcFrm [name=machine_dia]').val(row.machine_dia);
				$('#prodknitqcFrm [name=machine_gg]').val(row.machine_gg);
				$('#prodknitqcFrm [name=shift_name]').val(row.shift_name);
				$('#prodknitqcFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodknitqcFrm [name=style_ref]').val(row.style_ref);
				$('#prodknitqcFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodknitqcFrm [name=gmt_sample]').val(row.gmt_sample);
				$('#prodknitqcFrm [name=supplier_name]').val(row.supplier_name);
				$('#prodknitqcFrm [name=customer_name]').val(row.customer_name);
				$('#prodknitqcWindow').window('close');

			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	getRoll(){
		let params={};
		params.buyer_id=$('#prodknitqcsearchFrm [name=buyer_id]').val();
		params.supplier_id=$('#prodknitqcsearchFrm [name=supplier_id]').val();
		params.date_from=$('#prodknitqcsearchFrm [name=date_from]').val();
		params.date_to=$('#prodknitqcsearchFrm [name=date_to]').val();
		let data= axios.get(this.route+"/importroll",{params})
		.then(function (response) {
			$('#prodknitqcrollTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getRollSimilar(){
		let prod_knit_item_roll_id=$('#prodknitqcFrm [name=prod_knit_item_roll_id]').val();
		let params={};
		params.prod_knit_item_roll_id=prod_knit_item_roll_id;
		params.buyer_id=$('#prodknitqcsearchsimilarFrm [name=buyer_id]').val();
		params.supplier_id=$('#prodknitqcsearchsimilarFrm [name=supplier_id]').val();
		params.date_from=$('#prodknitqcsearchsimilarFrm [name=date_from]').val();
		params.date_to=$('#prodknitqcsearchsimilarFrm [name=date_to]').val();

		if(!params.prod_knit_item_roll_id){
			alert('Select Roll First');
			return;
		}

		let data= axios.get(this.route+"/importroll",{params})
		.then(function (response) {
			$('#prodknitqcrollsimilarTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	rollWindowSimilar()
	{
		$('#prodknitqcsimilarWindow').window('open');
		//this.getRollSimilar();

	}
	showGridRollSimilar(data){
		let self=this;
		var dg=$('#prodknitqcrollsimilarTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			onClickRow: function(index,row){
				$('#prodknitqcrollsimilarselectedTbl').datagrid('appendRow',row);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	showGridRollSimilarSelected(data){
		let self=this;
		var dg=$('#prodknitqcrollsimilarselectedTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			onClickRow: function(index,row){
				$('#prodknitqcrollsimilarselectedTbl').datagrid('deleteRow', index);

			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}



	/*create(data)
	{
		MsProdKnitQc.getRoll()
	}

	getRoll(){
		let data= axios.get(this.route+"/importroll")
		.then(function (response) {
			$('#prodknitqcGetTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	

	
	

	*/
	getSelections()
	{
		var roll = [];
		let i=1;
		$.each($('#prodknitqcrollsimilarselectedTbl').datagrid('getRows'), function (idx, val) {
		roll.push(val.prod_knit_item_roll_id);
		});
		MsProdKnitQc.showGridRollSimilarSelected([]);

        
		let rolls= roll.join(',');
		$('#prodknitqcFrm [name=other_roll_no]').val(rolls);
        $('#prodknitqcFrm [name=other_prod_knit_item_roll_id]').val(rolls);
        $('#prodknitqcsimilarWindow').window('close');
	}

	calculate_kg(){
		let roll_weight=$('#prodknitqcFrm [name=roll_weight]').val();
		let reject_qty=$('#prodknitqcFrm [name=reject_qty]').val();
		$('#prodknitqcFrm [name=qc_pass_qty]').val((roll_weight*1)-(reject_qty*1));

	}

	calculate_pcs(){
		let qty_pcs=$('#prodknitqcFrm [name=qty_pcs]').val();
		let reject_qty_pcs=$('#prodknitqcFrm [name=reject_qty_pcs]').val();
		$('#prodknitqcFrm [name=qc_pass_qty_pcs]').val((qty_pcs*1)-(reject_qty_pcs*1));
		
	}

	resetSearchForm (formId)
	{
		msApp.resetForm(formId);
		$('#prodknitqcsearchFrm [id="buyer_id"]').combobox('setValue', '');
		$('#prodknitqcsearchFrm [id="supplier_id"]').combobox('setValue', '');
		$('#prodknitqcsearchsimilarFrm [id="buyer_id"]').combobox('setValue', '');
		$('#prodknitqcsearchsimilarFrm [id="supplier_id"]').combobox('setValue', '');
	}

	searchRoll(){
		//let qc_date=$('#prodknitqcFrm [name=qc_date]').val();
		let from_qc_date=$('#from_qc_date').val();
		let to_qc_date=$('#to_qc_date').val();
		let params={};

		params.from_qc_date=from_qc_date;
		params.to_qc_date=to_qc_date;
		if(!params.from_qc_date){
			alert('Select Qc Date');
			return;
		}
		if(!params.to_qc_date){
			alert('Select Qc Date');
			return;
		}
		let data= axios.get(this.route+'/searchroll',{params})
		.then(function (response) {
			$('#prodknitqcTbl').datagrid('loadData', response.data).datagrid('enableFilter');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

}
window.MsProdKnitQc=new MsProdKnitQcController(new MsProdKnitQcModel());
//$('#prodknitqcGetTbl').datagrid();
MsProdKnitQc.showGrid([]);
MsProdKnitQc.showGridRoll([]);
MsProdKnitQc.showGridRollSimilar([]);
MsProdKnitQc.showGridRollSimilarSelected([]);
MsProdKnitQc.get();