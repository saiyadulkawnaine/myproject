let MsProdGmtExFactoryQtyModel = require('./MsProdGmtExFactoryQtyModel');

class MsProdGmtExFactoryQtyController {
	constructor(MsProdGmtExFactoryQtyModel)
	{
		this.MsProdGmtExFactoryQtyModel = MsProdGmtExFactoryQtyModel;
		this.formId='prodgmtexfactoryqtyFrm';
		this.dataTable='#prodgmtexfactoryqtyTbl';
		this.route=msApp.baseUrl()+"/prodgmtexfactoryqty"
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
		let formObj=this.getSelections();
		if(formObj.id){
			this.MsProdGmtExFactoryQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtExFactoryQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	getSelections()
	{
		let formObj={};
		formObj.prod_gmt_ex_factory_id=$('#prodgmtexfactoryFrm  [name=id]').val();
		let i=1;
		$.each($('#prodgmtexfactoryqtyTbl').datagrid('getSelections'), function (idx, val) {
			formObj['prod_gmt_carton_detail_id['+i+']']=val.id
			i++;
		});
		return formObj;
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtExFactoryQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{

		event.stopPropagation()
		this.MsProdGmtExFactoryQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtexfactoryqtyFrm [name=prod_gmt_ex_factory_id]').val($('#prodgmtexfactoryFrm [name=id]').val());
		if(d.action =='delete'){
			MsProdGmtExFactoryQty.searchShipOut();
		}
		else{
			MsProdGmtExFactoryQty.get($('#prodgmtexfactoryFrm [name=id]').val());
		}
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtExFactoryQtyModel.get(index,row);

	}

	showGrid(data){
		
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			rownumbers:true,
			fitColumns:true,
			showFooter:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;			
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')				
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);/*  */
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtExFactoryQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    get(prod_gmt_ex_factory_id)
    {
        let params={};
        params.prod_gmt_ex_factory_id=prod_gmt_ex_factory_id;
		params.country_id = $('#prodgmtexfactoryqtyFrm  [name=country_id]').val();
		params.style_ref = $('#prodgmtexfactoryqtyFrm  [name=style_ref]').val();
		params.job_no = $('#prodgmtexfactoryqtyFrm  [name=job_no]').val();
		params.date_from = $('#prodgmtexfactoryqtyFrm  [name=date_from]').val();
		params.date_to = $('#prodgmtexfactoryqtyFrm  [name=date_to]').val();
		params.sale_order_no = $('#prodgmtexfactoryqtyFrm  [name=sale_order_no]').val();
		if(!params.style_ref){
			alert('Select Style No');
			return;
		}
		let d= axios.get(this.route+'/create',{params})
		.then(function (response) {
			$('#prodgmtexfactoryqtyTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
    }

    search(){
    	 this.get($('#prodgmtexfactoryFrm [name=id]').val())
    }

    showGridShipOut(data){
		
		$('#prodgmtshipoutqtyTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
			showFooter:true,
			rownumbers:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}

				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
						
					}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	

	searchShipOut()
    {
        let params={};
        params.prod_gmt_ex_factory_id = $('#prodgmtexfactoryFrm  [name=id]').val();
		params.country_id = $('#prodgmtshipoutqtyFrm  [name=country_id]').val();
		params.style_ref = $('#prodgmtshipoutqtyFrm  [name=style_ref]').val();
		params.job_no = $('#prodgmtshipoutqtyFrm  [name=job_no]').val();
		params.sale_order_no = $('#prodgmtshipoutqtyFrm  [name=sale_order_no]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#prodgmtshipoutqtyTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
    }

	openExStyleWindow(){
		$('#exstylewindow').window('open');
	}
	showExStyleGrid(){
		let data={};
		data.buyer_id = $('#exstylesearch  [name=buyer_id]').val();
		data.style_ref = $('#exstylesearch  [name=style_ref]').val();
		data.style_description = $('#exstylesearch  [name=style_description]').val();
		let self=this;
		var ex=$('#exstyleTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/prodgmtexfactoryqty/exstyle",
			onClickRow: function(index,row){
				$('#prodgmtexfactoryqtyFrm  [name=style_id]').val(row.id);
				$('#prodgmtexfactoryqtyFrm  [name=style_ref]').val(row.style_ref);
				$('#exstylewindow').window('close')
			}
		});
		ex.datagrid('enableFilter');
	}
	select()
	{
		var no_of_carton=$('#no_of_carton').val();
		var total=no_of_carton*1;
		for(let i=0;i<total;i++){
			$('#prodgmtexfactoryqtyTbl').datagrid('checkRow', i);
		}
	}
  
}
window.MsProdGmtExFactoryQty=new MsProdGmtExFactoryQtyController(new MsProdGmtExFactoryQtyModel());
MsProdGmtExFactoryQty.showGrid([]);
MsProdGmtExFactoryQty.showGridShipOut([]);
