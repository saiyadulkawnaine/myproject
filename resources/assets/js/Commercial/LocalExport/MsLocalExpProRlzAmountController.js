let MsLocalExpProRlzAmountModel = require('./MsLocalExpProRlzAmountModel');
class MsLocalExpProRlzAmountController {
	constructor(MsLocalExpProRlzAmountModel)
	{
		this.MsLocalExpProRlzAmountModel = MsLocalExpProRlzAmountModel;
		this.formId='localexpprorlzamountFrm';
		this.dataTable='#localexpprorlzamountTbl';
		this.route=msApp.baseUrl()+"/localexpprorlzamount"
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
			this.MsLocalExpProRlzAmountModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpProRlzAmountModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpProRlzAmountModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpProRlzAmountModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexpprorlzamountTbl').datagrid('reload');
		msApp.resetForm('localexpprorlzamountFrm');
		$('#localexpprorlzamountFrm [name=local_exp_pro_rlz_id]').val($('#localexpprorlzFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsLocalExpProRlzAmountModel.get(index,row);

	}

	

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsLocalExpProRlzAmount.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showGrid(data)
	{
   
         $('#framTbl').datagrid({
            title:'Amount Details',
            iconCls:'icon-edit',
            width:660,
            toolbar:"#localexpprorlzTb2",
            height:250,
            fit:true,
            showFooter:true,
            singleSelect:true,
            idField:'id',
            //data:data.localexpprorlzamount,
            columns:[[
               {field:'commercial_head_id',title:'Distribution <br/> Head', halign:'center',width:250,
                  formatter:function(value,row){
                     return row.name || value;
                  },
                  editor:{
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
              
               
               {field:'doc_value',title:'Document <br/> Currency',width:80,align:'right', halign:'center',editor:{type:'numberbox',options:{precision:4}}},
               {field:'exch_rate',title:'Conversion <br/>Rate',width:80, halign:'center',align:'right',editor:{type:'numberbox',options:{precision:4}}},
               {field:'dom_value',title:'Domestic <br/>Currency',width:80, halign:'center',align:'right',editor:{type:'numberbox',options:{precision:4}}},
               {field:'ac_loan_no',title:'AC/Loan No',width:80,editor:'textbox'},
               {field:'attr2',title:'',width:80}
             
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
            MsLocalExpProRlzAmount.reloadFooter(tdata); 
            $('#a_total_doc_value').val(total_doc_value);
            $('#a_total_dom_value').val(total_dom_value);

            let d_total_doc_value=$('#d_total_doc_value').val();
            let d_total_dom_value=$('#d_total_dom_value').val();
            $('#total_doc_value').val((total_doc_value*1+d_total_doc_value*1).toFixed(4));
            $('#total_dom_value').val((total_dom_value*1+d_total_dom_value*1).toFixed(4));
         },
            onClickRow:function(rowIndex){
            $('#framTbl').datagrid('selectRow',rowIndex);
            $(this).datagrid('beginEdit', rowIndex);
            },
            
            
            onBeginEdit:function(rowIndex){
            var editors = $('#framTbl').datagrid('getEditors', rowIndex);
            
            var n1 = $(editors[1].target);
            var n2 = $(editors[2].target);
            var n3 = $(editors[3].target);
            n1.add(n2).add(n3).numberbox({
            onChange:function(){

            var radioValue = $("input[name='aa']:checked").val();
            if(radioValue==3)
            {
             var cost = n1.numberbox('getValue')*n2.numberbox('getValue');
             n3.numberbox('setValue',cost);  
            }
            if(radioValue==2)
            {
               if(n1.numberbox('getValue')>=1){
               var cost = n3.numberbox('getValue')/n1.numberbox('getValue');
               n2.numberbox('setValue',cost); 
               }
               else{
                  n2.numberbox('setValue',0);
               }
               
            }
            if(radioValue==1)
            {
               if(n2.numberbox('getValue')>=1){
                  var cost = n3.numberbox('getValue')/n2.numberbox('getValue');
                  n1.numberbox('setValue',cost); 
               }
               else{
                  n1.numberbox('setValue',0); 
               }
               
            }
            var data={};
            data=MsLocalExpProRlzAmount.gettotal(rowIndex);
            MsLocalExpProRlzAmount.reloadFooter(data);
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
         }).datagrid('loadData', data.localexpprorlzamount);
      
	}

	reloadFooter(data)
      {
      $('#framTbl').datagrid('reloadFooter', [
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
         $('#framTbl').datagrid('beginEdit', MsLocalExpProRlzAmount.getRowIndex(target));
      }
     /* function deleterow(target){
         $.messager.confirm('Confirm','Are you sure?',function(r){
            if (r){
               $('#framTbl').datagrid('deleteRow', getRowIndex(target));
            }
         });
      }*/
        deleterow(){
         $.messager.confirm('Confirm','Are you sure?',function(r){
            if (r){
               var row = $('#framTbl').datagrid('getSelected');
               var index = $('#framTbl').datagrid('getRowIndex', row);
               $('#framTbl').datagrid('deleteRow', index);
            }
         });
      }
       saverow(target){
         $('#framTbl').datagrid('endEdit', getRowIndex(target));
      }
       cancelrow(target){
         $('#framTbl').datagrid('cancelEdit', getRowIndex(target));
      }
       insert(){
         var row = $('#framTbl').datagrid('getSelected');
         if (row){
            var index = $('#framTbl').datagrid('getRowIndex',row);
            if(!MsLocalExpProRlzAmount.beforeInsert(index)){
               alert('insert value')
               return ;
            }
         } else {
            index = 0;
         }
         
         $('#framTbl').datagrid('insertRow', {
            index: index,
            row:{
               //status:'P'
            }
         });
         $('#framTbl').datagrid('selectRow',index);
         $('#framTbl').datagrid('beginEdit',index);
         var data={};
         data=MsLocalExpProRlzAmount.gettotal(index);
         MsLocalExpProRlzAmount.reloadFooter(data);
         
      }
       beforeInsert(index){
         var editors = $('#framTbl').datagrid('getEditors', index);
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
         var editors = $('#framTbl').datagrid('getEditors', index);
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
      $.each($('#framTbl').datagrid('getRows'), function (idx, val) {
               if(index!==idx)
               {
               $('#framTbl').datagrid('endEdit', idx);
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
      $('#a_total_doc_value').val(total_doc_value);
      $('#a_total_dom_value').val(total_dom_value);
      let d_total_doc_value=$('#d_total_doc_value').val();
      let d_total_dom_value=$('#d_total_dom_value').val();
      $('#total_doc_value').val((total_doc_value*1+d_total_doc_value*1).toFixed(4));
      $('#total_dom_value').val((total_dom_value*1+d_total_dom_value*1).toFixed(4));
      return data;
   }
}
window.MsLocalExpProRlzAmount=new MsLocalExpProRlzAmountController(new MsLocalExpProRlzAmountModel());
