let MsPoYarnDyeingShortItemModel = require('./MsPoYarnDyeingShortItemModel');
class MsPoYarnDyeingShortItemController {
	constructor(MsPoYarnDyeingShortItemModel)
	{
		this.MsPoYarnDyeingShortItemModel = MsPoYarnDyeingShortItemModel;
		this.formId='poyarndyeingshortitemFrm';
		this.dataTable='#poyarndyeingshortitemTbl';
		this.route=msApp.baseUrl()+"/poyarndyeingshortitem"
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
			this.MsPoYarnDyeingShortItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoYarnDyeingShortItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let po_yarn_dyeing_id=$('#poyarndyeingshortFrm  [name=id]').val();
		$('#poyarndyeingshortitemFrm [name=po_yarn_dyeing_id]').val(po_yarn_dyeing_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoYarnDyeingShortItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoYarnDyeingShortItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#poyarndyeingshortitemTbl').datagrid('reload');
		let po_yarn_dyeing_id=$('#poyarndyeingshortFrm  [name=id]').val();
		MsPoYarnDyeingShortItem.get(po_yarn_dyeing_id);
		msApp.resetForm(this.formId);
		//$('#poyarndyeingshortitemFrm [name=po_yarn_dyeing_id]').val($('#poyarndyeingshortFrm  [name=id]').val());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoYarnDyeingShortItemModel.get(index,row);	
	}

	get(po_yarn_dyeing_id)
	{
		let d = axios.get(this.route+"?po_yarn_dyeing_id="+po_yarn_dyeing_id)
		.then(function(response){
			$('#poyarndyeingshortitemTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		//let data={}
		//data.po_yarn_dyeing_id=po_yarn_dyeing_id;
		var pyi = $('#poyarndyeingshortitemTbl');
		pyi.datagrid({
			//method:'get',
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			showFooter: 'true',
			//queryParams:data,
			//url:this.route,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data)
			{
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				var tRate=0;
				
				if(tQty){
				   tRate=(tAmout/tQty);	
				}	
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);

			}
		});
		pyi.datagrid('enableFilter').datagrid('loadData', data);
	}
	openYarnDyeInvYarnWindow()
	{
		$('#dyeinvyarnitemwindow').window('open');
	}

	getRcvYarnItemParams(){
		let params={}
		params.color_id=$('#yarndyeinvyarnitemsearchFrm [name=color_id]').val();
		params.brand=$('#yarndyeinvyarnitemsearchFrm [name=brand]').val();
		params.itemcategory_id=$('#yarndyeinvyarnitemsearchFrm [name=itemcategory_id]').val();
		return params;
	}

	searchYarnDyeRcvYarnItem(){
		let params=this.getRcvYarnItemParams();
		let d = axios.get(this.route+"/getinvrcvyarnitem",{params})
		.then(function(response){
			$('#yarndyeinvyarnitemsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}

	showYarnDyeRcvYarnItemGrid(data){
		let self=this;
		var ryt = $('#yarndyeinvyarnitemsearchTbl');
		ryt.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				$('#poyarndyeingshortitemFrm  [name=inv_yarn_item_id]').val(row.id);
				$('#poyarndyeingshortitemFrm  [name=yarn_des]').val(row.yarn_des);
				$('#poyarndyeingshortitemFrm  [name=supplier_name]').val(row.supplier_name);
				$('#poyarndyeingshortitemFrm  [name=lot]').val(row.lot);
				$('#poyarndyeingshortitemFrm  [name=brand]').val(row.brand);
				$('#poyarndyeingshortitemFrm  [name=yarn_color_name]').val(row.yarn_color_name);
				$('#dyeinvyarnitemwindow').window('close');
			},
		});
		ryt.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	openpoyarndyeitembomqtyWindow(id)
	{
         $('#poyarndyeqtywindow').window('open');
	}
	
}
window.MsPoYarnDyeingShortItem=new MsPoYarnDyeingShortItemController(new MsPoYarnDyeingShortItemModel());
MsPoYarnDyeingShortItem.showGrid([]);
MsPoYarnDyeingShortItem.showYarnDyeRcvYarnItemGrid([]);