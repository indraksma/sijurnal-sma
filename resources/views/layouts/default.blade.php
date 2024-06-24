<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-xl px-4">
        <div class="page-header-content pt-4 pb-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><span wire:ignore><i class="@yield('icon', 'fas fa-th')"></i></span>
                        </div>
                        @yield('title')
                    </h1>
                    {{-- <div class="page-header-subtitle">@yield('subtitle')
                    </div> --}}
                    @yield('topbtn')
                </div>
            </div>
        </div>
    </div>
</header>
