let MsSoEmbPrintMcDtlMinajModel = require('./MsSoEmbPrintMcDtlMinajModel');

class MsSoEmbPrintMcDtlMinajController
{
 constructor(MsSoEmbPrintMcDtlMinajModel)
 {
  this.MsSoEmbPrintMcDtlMinajModel = MsSoEmbPrintMcDtlMinajModel;
  this.formId = 'soembprintmcdtlminajFrm';
  this.dataTable = '#soembprintmcdtlminajTbl';
  this.route = msApp.baseUrl() + "/soembprintmcdtlminaj"
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
  let formObj = msApp.get(this.formId);
  if (formObj.id) {
   this.MsSoEmbPrintMcDtlMinajModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
  } else {
   this.MsSoEmbPrintMcDtlMinajModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
  }
 }


 resetForm()
 {
  msApp.resetForm(this.formId);
 }

 remove()
 {
  let formObj = msApp.get(this.formId);
  this.MsSoEmbPrintMcDtlMinajModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
 }

 delete(event, id)
 {
  event.stopPropagation()
  this.MsSoEmbPrintMcDtlMinajModel.save(this.route + "/" + id, 'DELETE', null, this.response);
 }

 response(d)
 {
  let so_emb_print_mc_dtl_id = $('#soembprintmcdtlFrm [name=id]').val();
  MsSoEmbPrintMcDtlMinaj.get(so_emb_print_mc_dtl_id);
  msApp.resetForm('soembprintmcdtlminajFrm');

  $('#soembprintmcdtlminajFrm [name=so_emb_print_mc_dtl_id]').val(so_emb_print_mc_dtl_id);
 }

 edit(index, row)
 {
  row.route = this.route;
  row.formId = this.formId;
  this.MsSoEmbPrintMcDtlMinajModel.get(index, row);

 }
 get(so_emb_print_mc_dtl_id)
 {
  let params = {};
  params.so_emb_print_mc_dtl_id = so_emb_print_mc_dtl_id;
  let d = axios.get(this.route, { params })
   .then(function (response)
   {
    $('#soembprintmcdtlminajTbl').datagrid('loadData', response.data);
   })
   .catch(function (error)
   {
    console.log(error);
   });

 }

 showGrid(data)
 {
  let self = this;
  $(this.dataTable).datagrid({
   method: 'get',
   border: false,
   singleSelect: true,
   fit: true,
   showFooter: true,
   onClickRow: function (index, row)
   {
    self.edit(index, row);
   },
   onLoadSuccess: function (data)
   {
    var no_of_minute = 0;
    for (var i = 0; i < data.rows.length; i++) {
     no_of_minute += data.rows[i]['no_of_minute'].replace(/,/g, '') * 1;
    }
    $(this).datagrid('reloadFooter', [
     {
      no_of_minute: no_of_minute.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
     }
    ]);
   }
  }).datagrid('enableFilter').datagrid('loadData', data);
 }
 calculateMinute()
 {
  let self = this;
  let minute_adj_reason_id = $('#soembprintmcdtlminajFrm [name=minute_adj_reason_id]').val();
  let no_of_hour = $('#soembprintmcdtlminajFrm [name=no_of_hour]').val();
  let no_of_resource = $('#soembprintmcdtlminajFrm [name=no_of_resource]').val();
  let minutecal = no_of_hour * no_of_resource * 60;
  if (minute_adj_reason_id == 1 || minute_adj_reason_id == 2 || minute_adj_reason_id == 7) {
   $('#soembprintmcdtlminajFrm [name=no_of_minute]').val(minutecal);
  }
 }

}
window.MsSoEmbPrintMcDtlMinaj = new MsSoEmbPrintMcDtlMinajController(new MsSoEmbPrintMcDtlMinajModel());
MsSoEmbPrintMcDtlMinaj.showGrid([]);