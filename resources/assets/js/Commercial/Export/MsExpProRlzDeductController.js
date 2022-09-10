let MsExpProRlzDeductModel = require('./MsExpProRlzDeductModel');
class MsExpProRlzDeductController {
	constructor(MsExpProRlzDeductModel)
	{
		this.MsExpProRlzDeductModel = MsExpProRlzDeductModel;
		this.formId='expprorlzdeductFrm';
		this.dataTable='#expprorlzdeductTbl';
		this.route=msApp.baseUrl()+"/expprorlzdeduct"
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
			this.MsExpProRlzDeductModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpProRlzDeductModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpProRlzDeductModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpProRlzDeductModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expprorlzdeductTbl').datagrid('reload');
		msApp.resetForm('expprorlzdeductFrm');
		$('#expprorlzdeductFrm [name=exp_pro_rlz_id]').val($('#expprorlzFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpProRlzDeductModel.get(index,row);

	}

	showGrid(data)
	{
		$('#frdeTbl').datagrid({
			title:'Deduction Details',
			iconCls:'icon-edit',
			width:660,
			toolbar:"#expprorlzTb",
			height:250,
			fit:true,
			showFooter:true,
			singleSelect:true,
			idField:'id',
			//data:data.expprorlzdeduct,
			columns:[[
				{
					field:'commercial_head_id',
					title:'Deduction <br/> Head', 
					halign:'center',width:250,
					formatter:function(value,row)
					{
						return row.name || value;
					},
					editor:
					{
						type:'combobox',
						options:{
							valueField:'id',
							textField:'name',
							data:data.head,
							required:true,
							onSelect:function(record){
								console.log(record);
							}
						},
					}
				},
				{
					field:'doc_value',
					title:'Document <br/> Currency',
					width:80,align:'right', 
					halign:'center',
					editor:{
						type:'numberbox',
						options:{
							precision:4
						}
					}
			    },
				{
					field:'exch_rate',
					title:'Conversion <br/>Rate',
					width:80, halign:'center',
					align:'right',
					editor:{
						type:'numberbox',
						options:{
							precision:4
						}
					}
				},
				{
					field:'dom_value',
					title:'Domestic <br/>Currency',
					width:80, halign:'center',
					align:'right',
					editor:{
						type:'numberbox',
						options:{
							precision:4
						}
					}
				},
				{
					field:'attr2',
					title:'',
					width:80
				}
			]],
			onLoadSuccess: function(data){
                let total_doc_value=0;
                let total_dom_value=0;
				for(var i=0; i<data.rows.length; i++){

					if(data.rows[i]['doc_value']){
						total_doc_value+=data.rows[i]['doc_value']*1;
					}
					if(data.rows[i]['dom_value'])
					{
						total_dom_value+=data.rows[i]['dom_value']*1;
					}
				}
				let tdata={};
				tdata.total_doc_value=total_doc_value;
				tdata.total_dom_value=total_dom_value;
				MsExpProRlzDeduct.reloadFooter(tdata);
				$('#d_total_doc_value').val(total_doc_value);
				$('#d_total_dom_value').val(total_dom_value);

				let a_total_doc_value=$('#a_total_doc_value').val();
				let a_total_dom_value=$('#a_total_dom_value').val();
				$('#total_doc_value').val((total_doc_value*1+a_total_doc_value*1).toFixed(4));
				$('#total_dom_value').val((total_dom_value*1+a_total_dom_value*1).toFixed(4));	
			},
			onClickRow:function(rowIndex){
				$('#frdeTbl').datagrid('selectRow',rowIndex);
				$(this).datagrid('beginEdit', rowIndex);
			},
			onBeginEdit:function(rowIndex){
				var editors = $('#frdeTbl').datagrid('getEditors', rowIndex);
				var n1 = $(editors[1].target);
				var n2 = $(editors[2].target);
				var n3 = $(editors[3].target);
				n1.add(n2).add(n3).numberbox({
					onChange:function()
					{
						var radioValue = $("input[name='aa']:checked").val();
						if(radioValue==3)
						{
							var cost = n1.numberbox('getValue')*n2.numberbox('getValue');
							n3.numberbox('setValue',cost);  
						}
						if(radioValue==2)
						{
							var cost = n3.numberbox('getValue')/n1.numberbox('getValue');
							n2.numberbox('setValue',cost);  
						}
						if(radioValue==1)
						{
							var cost = n3.numberbox('getValue')/n2.numberbox('getValue');
							n1.numberbox('setValue',cost);  
						}
						var data={};
						data=MsExpProRlzDeduct.gettotal(rowIndex);
						MsExpProRlzDeduct.reloadFooter(data);
					}
				})
			},
			onEndEdit:function(index,row){
				var ed = $(this).datagrid('getEditor', {
					index: index,
					field: 'commercial_head_id'
				});
				row.name = $(ed.target).combobox('getText');
			},
			onBeforeEdit:function(index,row){
				row.editing = true;
				$(this).datagrid('refreshRow', index);
			},
			onAfterEdit:function(index,row){
				row.editing = false;
				$(this).datagrid('refreshRow', index);
			},
			onCancelEdit:function(index,row){
				row.editing = false;
				$(this).datagrid('refreshRow', index);
			}
		}).datagrid('loadData',data.expprorlzdeduct);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsExpProRlzDeduct.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	 reloadFooter(data)
      {
      $('#frdeTbl').datagrid('reloadFooter', [
      { 
      commercial_head_id:'Total',
      doc_value: data.total_doc_value.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
      dom_value: data.total_dom_value.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,')
      }
      ]);
      }
      
       getRowIndex(target){
         var tr = $(target).closest('tr.datagrid-row');
         return parseInt(tr.attr('datagrid-row-index'));
      }
       editrow(target){
         $('#frdeTbl').datagrid('beginEdit', MsExpProRlzDeduct.getRowIndex(target));
      }
     /* function deleterow(target){
         $.messager.confirm('Confirm','Are you sure?',function(r){
            if (r){
               $('#frdeTbl').datagrid('deleteRow', getRowIndex(target));
            }
         });
      }*/
        deleterow(){
         $.messager.confirm('Confirm','Are you sure?',function(r){
            if (r){
               var row = $('#frdeTbl').datagrid('getSelected');
               var index = $('#frdeTbl').datagrid('getRowIndex', row);
               $('#frdeTbl').datagrid('deleteRow', index);
            }
         });
      }
       saverow(target){
         $('#frdeTbl').datagrid('endEdit', getRowIndex(target));
      }
       cancelrow(target){
         $('#frdeTbl').datagrid('cancelEdit', getRowIndex(target));
      }
       insert(){
         var row = $('#frdeTbl').datagrid('getSelected');
         if (row){
            var index = $('#frdeTbl').datagrid('getRowIndex',row);
            if(!MsExpProRlzDeduct.beforeInsert(index)){
               alert('insert value')
               return ;
            }
         } else {
            index = 0;
         }
         
         $('#frdeTbl').datagrid('insertRow', {
            index: index,
            row:{
               //status:'P'
            }
         });
         $('#frdeTbl').datagrid('selectRow',index);
         $('#frdeTbl').datagrid('beginEdit',index);
         var data={};
         data=MsExpProRlzDeduct.gettotal(index);
         MsExpProRlzDeduct.reloadFooter(data);
         
      }
       beforeInsert(index){
         var editors = $('#frdeTbl').datagrid('getEditors', index);
         var n0 = $(editors[0].target);
         var n1 = $(editors[1].target);
         var n2 = $(editors[2].target);
         var n3 = $(editors[3].target);
         var v0=n0.combobox('getValue');
         var v1=n1.numberbox('getValue');
         var v2=n2.numberbox('getValue');
         var v3=n3.numberbox('getValue');
         if(v0 && v1 && v2 && v3)
         {
            return true;
         }
            return false;
      }
       gettotal(index){
         var editors = $('#frdeTbl').datagrid('getEditors', index);
         var n0 = $(editors[0].target);
         var n1 = $(editors[1].target);
         var n2 = $(editors[2].target);
         var n3 = $(editors[3].target);
         var v0=n0.combobox('getValue');
         var v1=n1.numberbox('getValue');
         var v2=n2.numberbox('getValue');
         var v3=n3.numberbox('getValue');

      let data={};
      let total_doc_value=0;
      let total_dom_value=0;
      $.each($('#frdeTbl').datagrid('getRows'), function (idx, val) {
               if(index!==idx)
               {
               $('#frdeTbl').datagrid('endEdit', idx);
               if(val.doc_value){
                  total_doc_value+=val.doc_value*1;
               }
               if (val.dom_value) {
                  total_dom_value+=val.dom_value*1;
               }  
            }
            
      });
      if(v0 && v1 && v2 && v3)
         {
            total_doc_value+=v1*1;
            total_dom_value+=v3*1;
         }
      data.total_doc_value=total_doc_value;
      data.total_dom_value=total_dom_value;
      
      //let MsExpProRlzAmount=MsExpProRlzAmount.gettotal();
      $('#d_total_doc_value').val(total_doc_value);
      $('#d_total_dom_value').val(total_dom_value);

      let a_total_doc_value=$('#a_total_doc_value').val();
      let a_total_dom_value=$('#a_total_dom_value').val();
      $('#total_doc_value').val((total_doc_value*1+a_total_doc_value*1).toFixed(4));
      $('#total_dom_value').val((total_dom_value*1+a_total_dom_value*1).toFixed(4));

      return data;
   }
	
}
window.MsExpProRlzDeduct=new MsExpProRlzDeductController(new MsExpProRlzDeductModel());
//MsExpProRlzDeduct.showGrid();