<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="purchasetermsconditionTbl" style="width:100%">
    <thead>
        <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'term'" width="100">Name</th>
            <th data-options="field:'sort_id'" width="100">Sequence</th>
       </tr>
    </thead>
    </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New Purchase Terms & Condition',footer:'#purchasetermsconditionFrmft'" style="width: 350px; padding:2px">
    <form id="purchasetermsconditionFrm">
        <div id="container">
             <div id="body">
               <code>
    
                    <div class="row">
                        <div class="col-sm-4 req-text">Term</div>
                        <div class="col-sm-8">
                        <textarea name="term" id="term" cols="20" rows="5"></textarea>
                        <input type="hidden" name="id" id="id" />
                        <input type="hidden" name="purchase_order_id" id="purchase_order_id" />
                        <input type="hidden" name="menu_id" id="menu_id" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Sequence  </div>
                        <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" class="number integer"/></div>
                    </div>
    
              </code>
           </div>
        </div>
        <div id="purchasetermsconditionFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurchaseTermsCondition.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('purchasetermsconditionFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurchaseTermsCondition.remove()" >Delete</a>
        </div>
    
      </form>
    </div>
    </div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPurchaseTermsConditionController.js"></script>
<script>
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
</script>
    