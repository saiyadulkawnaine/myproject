<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@includeFirst(['System.Layout.head', 'Defult.System.Layout.head'])
<body class="easyui-layout" id="LayoutMaster">
     @includeFirst(['System.Layout.header', 'Defult.System.Layout.header'])
     @includeFirst(['System.Layout.right_sidebar', 'Defult.System.Layout.right_sidebar'])
     <div data-options="region:'center'" id="menu_content" style="padding:5px;">
    <div class="error-page">

        <h2 class="headline text-info"> 403</h2>

        <div class="error-content">

            <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>

            <p>

                We could not find the page you were looking for.

                Meanwhile, you may <a href="">return to dashboard</a> or try using the search form.

            </p>

        </div>

    </div></div>
</body>
</html>
