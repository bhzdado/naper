<?php
$breadcrumbs = Breadcrumbs::generate();
?>
<div class="breadcrumb-wrapper">
    <h1 id='title'>@yield('title')</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb p-0 breadcrumb-content">
            <li class="breadcrumb-item">
                <a href="#" onclick="toHome();">
                    <span class="mdi mdi-home"></span>                
                </a>
            </li>
            @if (count($breadcrumbs))
                @foreach ($breadcrumbs as $i => $breadcrumb)
                    @if($breadcrumb->title != 'Home')
                    <li class="breadcrumb-item">
                        @if ($breadcrumb->url && !$loop->last)
                            <a onclick="loadRoute('{{ $breadcrumb->url }}');" class="current" style='cursor: pointer;'>{{ $breadcrumb->title }}</a>
                        @else
                            <a>{{ $breadcrumb->title }}</a>
                        @endif
                    </li>
                    @endif

                @endforeach
            @endif

        </ol>
    </nav>

</div>