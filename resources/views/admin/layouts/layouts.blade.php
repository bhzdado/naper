<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('admin.layouts.includes.head')
    <body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
        <script>
            NProgress.configure({showSpinner: false});
            NProgress.start();
        </script>
        <div id="toaster"></div>
        <div class="wrapper">
            <!--
               ====================================
               ——— LEFT SIDEBAR WITH FOOTER
               =====================================
            -->
            <aside class="left-sidebar bg-sidebar">
                <div id="sidebar" class="sidebar sidebar-with-footer">
                    <!-- Aplication Brand -->
                    <div class="app-brand">
                        <a href="/admin" title="<?= env('APP_COMPANY_NAME'); ?>">
                            <img src="{{asset('img/logo.png')}}" id="logo" style="max-width:172px; margin-top: 12px; margin-left: 25px;">
                            <!--
                            <span class="brand-name text-truncate"><?= env('APP_COMPANY_NAME'); ?></span>
                            -->
                        </a>
                    </div>
                    <!-- begin sidebar scrollbar -->
                    <div class="sidebar-scrollbar" id="menu-bar">
                        <!-- sidebar menu -->
                        
                    </div>
                </div>
            </aside>
            <div class="page-wrapper">
                <!-- Header -->
                @include('admin.layouts.includes.header')

                <div class="content-wrapper">
                    <div class="content">

                        @include('admin.layouts.includes.breadcrumb')

                        <div class="row">
                            <div class="col-xl-12 ">
                                <div class="card card-default">
                                    <div class="card-body" id='area_content'>
                                        @yield('conteudo')
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="right-sidebar-2">
                        <div class="right-sidebar-container-2">
                            <div class="slim-scroll-right-sidebar-2">
                                <div class="right-sidebar-2-header">
                                    <div class="btn-close-right-sidebar-2">
                                        <i class="mdi mdi-window-close"></i>
                                    </div>
                                    <h2>Configurações do Layout</h2>
                                </div>
                                <div class="right-sidebar-2-body">
                                    <span class="right-sidebar-2-subtitle">Header Layout</span>
                                    <div class="no-col-space">
                                        <a href="javascript:void(0);" class="btn-right-sidebar-2 header-fixed-to btn-right-sidebar-2-active">Fixed</a>
                                        <a href="javascript:void(0);" class="btn-right-sidebar-2 header-static-to">Static</a>
                                    </div>
                                    <span class="right-sidebar-2-subtitle">Sidebar Layout</span>
                                    <div class="no-col-space">
                                        <select class="right-sidebar-2-select" id="sidebar-option-select">
                                            <option value="sidebar-fixed">Fixed Default</option>
                                            <option value="sidebar-fixed-minified">Fixed Minified</option>
                                            <option value="sidebar-fixed-offcanvas">Fixed Offcanvas</option>
                                            <option value="sidebar-static">Static Default</option>
                                            <option value="sidebar-static-minified">Static Minified</option>
                                            <option value="sidebar-static-offcanvas">Static Offcanvas</option>
                                        </select>
                                    </div>
                                    <span class="right-sidebar-2-subtitle">Header Background</span>
                                    <div class="no-col-space">
                                        <a href="javascript:void(0);" class="btn-right-sidebar-2 btn-right-sidebar-2-active header-light-to">Light</a>
                                        <a href="javascript:void(0);" class="btn-right-sidebar-2 header-dark-to">Dark</a>
                                    </div>
                                    <span class="right-sidebar-2-subtitle">Navigation Background</span>
                                    <div class="no-col-space">
                                        <a href="javascript:void(0);" class="btn-right-sidebar-2 btn-right-sidebar-2-active sidebar-dark-to">Dark</a>
                                        <a href="javascript:void(0);" class="btn-right-sidebar-2 sidebar-light-to">Light</a>
                                    </div>
                                    <span class="right-sidebar-2-subtitle">Direction</span>
                                    <div class="no-col-space">
                                        <a href="javascript:void(0);" class="btn-right-sidebar-2 btn-right-sidebar-2-active ltr-to">LTR</a>
                                        <a href="javascript:void(0);" class="btn-right-sidebar-2 rtl-to">RTL</a>
                                    </div>
                                    <div class="d-flex justify-content-center" style="padding-top: 30px">
                                        <div id="reset-options" style="width: auto; cursor: pointer" class="btn-right-sidebar-2 btn-reset">Reset
                                            Settings
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.layouts.includes.script')
        @yield('scripts')
    </body>
</html>
<!--
<body>
   <div id="loading-page"></div>
   <div id="header">
      <h1><a href="dashboard.html"><?= env('APP_COMPANY_NAME'); ?></a></h1>
   </div>
   <div id="sidebar"></div>
   <div id="content">
      <div id="content-header">
      </div>
      <div class="container-fluid">
         <div class="row-fluid">
            <div class="span12">
               <div class="widget-box" id="area_conteudo">
                  @yield('conteudo')
               </div>
            </div>
         </div>
      </div>
   </div>
</body>
</html>
