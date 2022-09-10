<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@includeFirst(['System.Layout.head', 'Defult.System.Layout.head'])
<body class="easyui-layout" id="LayoutMaster">
     @includeFirst(['System.Layout.header', 'Defult.System.Layout.header'])
     @includeFirst(['System.Layout.left_sidebar', 'Defult.System.Layout.left_sidebar'])
     @includeFirst(['System.Layout.right_sidebar', 'Defult.System.Layout.right_sidebar'])
     @includeFirst(['System.Layout.container', 'Defult.System.Layout.container'])
     
</body>
</html>