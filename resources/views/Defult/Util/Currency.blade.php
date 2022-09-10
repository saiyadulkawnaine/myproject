<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="currencyTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
        <th data-options="field:'code'" width="100">Code</th>
        <th data-options="field:'symbol'" width="100">Symbol</th>
   </tr>
</thead>
</table>

</div>
<div data-options="region:'west',border:true,title:'Add New Section',footer:'#ft2'" style="width: 350px; padding:2px">
<form id="currencyFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Name</div>
                    <div class="col-sm-8">
                    <input type="text" name="name" id="name" value=""/>
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                </div>
                <div class="row middle">
                     <div class="col-sm-4 req-text">Code </div>
                    <div class="col-sm-8"><input type="text" name="code" id="code" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Country</div>
                    <div class="col-sm-8">
                    {!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Symbol </div>
                    <div class="col-sm-8"><input type="text" name="symbol" id="symbol" value="" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Hundreds Name  </div>
                    <div class="col-sm-8"><input type="text" name="hundreds_name" id="hundreds_name" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Auto Update </div>
                    <div class="col-sm-8">{!! Form::select('auto_update', $yesno,'',array('id'=>'auto_update')) !!}</div>
                </div>

                 <div class="row middle">
                    <div class="col-sm-4">Tree</div>
                    <div class="col-sm-8"><input id="ccd" name="ccd"  value="01" style="width: 100%; border-radius:2px "></div>
                </div> 

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCurrency.submit()">Save</a>
         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('currencyFrm')" >Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCurrency.remove()" >Delete</a>
    </div>

</form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsCurrencyController.js"></script>

<script>





/*(function(){
    $.fn.combotree.defaults.editable = true;
    $.extend($.fn.combotree.defaults.keyHandler,{
        up:function(){
            console.log('up')
        },
        down:function(){
            console.log('down')
        },
        enter:function(){
            console.log('enter')
        },
        query:function(q){
            var t = $(this).combotree('tree');
            var nodes = t.tree('getChildren');
            //alert(q)
            for(var i=0; i<nodes.length; i++){
                var node = nodes[i];
                if (node.text.indexOf(q) > -1){
                   // alert(node.id)
                    $(node.target).show();
                    let nodes = t.tree('getChildren');
                    alert(nodes.length)

                } else {
                    $(node.target).hide();
                }
            }
            var opts = $(this).combotree('options');
            if (!opts.hasSetEvents){
                opts.hasSetEvents = true;
                var onShowPanel = opts.onShowPanel;
                opts.onShowPanel = function(){
                    var nodes = t.tree('getChildren');
                    for(var i=0; i<nodes.length; i++){
                        $(nodes[i].target).show();
                    }
                    onShowPanel.call(this);
                }
                $(this).combo('options').onShowPanel = opts.onShowPanel;
            }
        }
    });
})(jQuery);*/


</script>


