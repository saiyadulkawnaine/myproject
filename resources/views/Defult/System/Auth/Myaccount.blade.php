<div class="easyui-layout" data-options="fit:true">
 <div data-options="region:'center', border:false">
  <div class="easyui-layout" data-options="fit:true" id="group_lay">
   <div data-options="region:'center',border:true,footer:'#ft2'" style="padding:2px">
    <div id="container">
     <div id="body">
      <code>
        <form id="myaccountFrm">
            <div class="row">
                <div class="col-sm-2 req-text">Name</div>
                <div class="col-sm-3">
                <input type="text" name="name" id="name" value="{{ $name }}"/>
                <input type="hidden" name="id" id="id" value="{{ $id }}"/>
                </div>
                <div class="col-sm-2 req-text">Email</div>
                <div class="col-sm-5"><input type="text" name="email" id="email" value="{{ $email }}" /></div>
            </div>
             <div class="row middle">
                <div class="col-sm-2">Password</div>
                <div class="col-sm-3">
                <input type="password" name="password" id="password" value=""/>
                </div>
                <div class="col-sm-2">Confirm Password</div>
                <div class="col-sm-5"><input type="password" name="password_confirmation" id="password_confirmation" value="" /></div>
            </div>
           
        </form>
      </code>
     </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsMyaccount.submit()">Save</a>

    </div>
   </div>

  </div>
 </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/user-accounts/MsMyaccountController.js"></script>