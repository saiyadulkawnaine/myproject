<div data-options="region:'north',border:false" style="height:30px;background: #027be3;padding:0px"> 
    <div style="width:70%; height:100%; float:left">
    </div> 
    <div style="width:30%;float:left; text-align:right">

  <a class="easyui-linkbutton c1" style="height:30px; border-radius:1px" href="<?php echo url('/');?>">Home
  </a>
    <a class="easyui-linkbutton c5" style="height:30px; border-radius:1px" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    </a>
    <a class="easyui-linkbutton c6 icon-more" style="height:30px; border-radius:1px" onClick="return msApp.colExp('LayoutMaster','east')" href="javascript:void(0)">...</a>
    </div>
</div>