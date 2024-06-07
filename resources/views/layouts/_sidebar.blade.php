<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar" class="hide">

        <ul class="navbar-nav theme-brand flex-row  text-center">
            <li class="nav-item theme-logo">
            @if(Auth::user()->company_id == 1)
                <a href="{{route('home')}}">
                    <img src="{{asset('assets/img/dahnay.png')}}" style="width: 90%; height: 75px;" class="navbar-logo" alt="logo">
                </a>
                @elseif(Auth::user()->company_id == 2)
                <a href="{{route('home')}}">
                    <img src="{{asset('assets/img/MSM.png')}}" style="width: 100%; height: 100px;" class="navbar-logo" alt="logo">
                </a>
            @endif
            </li>
                <li class="nav-item theme-text">
                        <a href="{{route('home')}}" class="nav-link"></a>
                </li>
            <li class="nav-item toggle-sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-arrow-left sidebarCollapse">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </li>
        </ul>
</br>
        <ul class="list-unstyled menu-categories" id="accordionExample" style="padding:0px;">
        @permission('User-List')
        @if(Auth::user()->is_super_admin == 1)

            <li class="menu">
                        <a href="#component66" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <span>Administration</span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="component66" data-parent="#accordionExample">
                                @permission('User-List')
                                    <li>
                                        <a href="{{route('users.index')}}"> Users </a>
                                    </li>
                                @endpermission
                                @permission('Role-List')
                                    <li>
                                        <a href="{{route('roles.index')}}"> Roles</a>
                                    </li>
                                @endpermission
                                </ul>
                </li>
                @endif

                @endpermission

            <li class="menu">
                    @permission('Ports-List')
                        <a href="#components" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                            <span>Master Data</span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        @endpermission

                        <ul class="collapse submenu list-unstyled" id="components" data-parent="#accordionExample">
                        @permission('Setting-Edit')
                            <li>
                                    <a href="{{route('settings.edit',1)}}">Settings</a>
                                </li>
                        @endpermission
                        @permission('Ports-List')
                        <li>
                            <a href="#pages" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Ports & Terminals<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="pages" data-parent="#pages">
                                    <li>
                                        <a href="{{route('ports.index')}}"> Ports </a>
                                    </li>

                                <!-- @permission('PortTypes-List')
                                    <li>
                                        <a href="{{route('port-types.index')}}"> Port Types </a>
                                    </li>
                                @endpermission -->
                                @permission('Terminals-List')
                                    <li>
                                        <a href="{{route('terminals.index')}}"> Terminals </a>
                                    </li>
                                @endpermission
                                </ul>
                        </li>
                        @endpermission
                        @permission('Lines-List')

                        <li>
                            <a href="#lines" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Lines <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="lines" data-parent="#lines">
                                @permission('Lines-List')
                                    <li>
                                        <a href="{{route('lines.index')}}"> Liners & Operators </a>
                                    </li>
                                @endpermission

                                <!-- @permission('LinesType-List')
                                    <li>
                                        <a href="{{route('line-types.index')}}"> Line Types </a>
                                    </li>
                                @endpermission -->
                                </ul>
                        </li>
                        @endpermission

                        @permission('LocalPortTriff-List')
                                <li>
                                    <a href="#Triffs" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Triffs <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                        <ul class="collapse list-unstyled sub-submenu" id="Triffs" data-parent="#Triffs">
                                        @permission('Invoice-List')
                                            <li>
                                                <a href="{{route('chargesDesc.index')}}">Charges Description</a>
                                            </li>
                                        @endpermission
                                        @permission('LocalPortTriff-List')
                                            <li>
                                                <a href="{{route('localporttriff.index')}}">Local Port Triff</a>
                                            </li>
                                        @endpermission
                                        <!-- @permission('Demurrage-List')
                                            <li>
                                                <a href="{{route('demurrage.index')}}">Demurrage and Detention Tariffs</a>
                                            </li>
                                        @endpermission -->
                                        <!-- @permission('SupplierPrice-List')
                                            <li>
                                                <a href="{{route('supplierPrice.index')}}"> Slot Rates Triffs </a>
                                            </li>
                                        @endpermission -->
                                        </ul>
                                </li>
                        @endpermission

                                <!-- @permission('Agents-List')
                                    <li>
                                        <a href="{{route('agents.index')}}"> Agents</a>
                                    </li>
                                @endpermission -->

                                <!-- @permission('Suppliers-List')
                                    <li>
                                        <a href="{{route('suppliers.index')}}"> Suppliers</a>
                                    </li>
                                @endpermission -->
                                @permission('Customers-List')
                                    <li>
                                        <a href="{{route('customers.index')}}"> Customers</a>
                                    </li>
                                @endpermission

            </li>
        </ul>
            @permission('Voyages-List')
            <li class="menu">
                    @permission('Voyages-List')
                        <a href="#component88" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 112.63" style="enable-background:new 0 0 122.88 112.63; fill: #ebedf2;" xml:space="preserve"><g><path d="M35.58,24.13h3.6v-3.28v0h0.02c0-1.21,0.49-2.31,1.28-3.1l-0.01-0.01c0.78-0.78,1.87-1.27,3.09-1.27h0.01v-0.02h0h6.74 V5.84v0h0.02c0-1.61,0.66-3.07,1.71-4.12c1.05-1.05,2.5-1.69,4.1-1.7V0h0h10.2l0,0v0.02c1.61,0,3.07,0.66,4.12,1.71 c1.04,1.05,1.69,2.5,1.7,4.1h0.01v0v10.63h6.74h0v0.02c1.21,0,2.31,0.49,3.1,1.29c0.78,0.78,1.27,1.87,1.27,3.07v0.01h0.01v0v3.28 h3.6v-0.02l0,0v0.02c1.31,0,2.49,0.53,3.35,1.38l0.01-0.01c0.85,0.85,1.37,2.03,1.38,3.35h0.02v0v18.88v0h-0.02 c0,0.46-0.07,0.91-0.19,1.34l13.62,6.62L91.17,69.84v14.12c2.13-1.07,3.81-2.66,5.39-4.16c3.19-3.02,6.1-5.78,11.13-5.84 c5.66-0.06,7.49,2.86,11.39,5.86c0.63,0.49,1.28,0.99,2.15,1.62l0.1,0.07c1.67,1.21,2.05,3.55,0.83,5.22 c-1.21,1.68-3.55,2.05-5.23,0.84l-0.1-0.07c-0.65-0.47-1.49-1.12-2.32-1.76c-2.86-2.21-3.68-4.34-6.77-4.31 c-2.06,0.02-3.96,1.82-6.03,3.79c-3.91,3.71-8.26,7.83-16.81,7.44c-7.23,0.33-11.45-2.57-14.95-5.72H51.79 c-3.66,2.75-7.64,5.25-13.43,5.18c-6.62,0.08-10.88-3.2-15-6.38c-2.86-2.21-5.63-4.34-8.72-4.31c-2.06,0.02-3.96,1.82-6.03,3.79 c-0.77,0.73-1.56,1.48-2.44,2.23C4.6,88.8,2.24,88.61,0.9,87.04c-1.34-1.57-1.16-3.93,0.41-5.27c0.72-0.62,1.44-1.3,2.15-1.97 c3.19-3.02,6.1-5.78,11.13-5.84c5.66-0.06,9.44,2.86,13.34,5.86c1.76,1.36,3.55,2.73,5.5,3.67V69.38L21.6,55.46l9.9-5.32 c-0.41-0.7-0.65-1.52-0.65-2.39h-0.01v0V28.86l0,0h0.01c0-1.32,0.53-2.51,1.38-3.36c0.15-0.15,0.31-0.28,0.47-0.4 C33.49,24.5,34.49,24.14,35.58,24.13l0-0.02h0V24.13L35.58,24.13z M38.36,104.59h0.04c4.05,0.06,7.27-2.42,10.39-4.83 c3.9-3.01,7.68-5.93,13.34-5.86c5.03,0.05,7.94,2.81,11.13,5.84c2.83,2.69,5.98,5.67,11.46,5.39c0.07,0,0.13,0,0.2,0 c0.07,0,0.13,0,0.2,0c5.47,0.29,8.62-2.7,11.46-5.39c3.19-3.02,6.1-5.78,11.13-5.84c5.66-0.06,7.49,2.85,11.39,5.86 c0.63,0.49,1.28,0.99,2.15,1.62l0,0l0.09,0.07c1.67,1.2,2.05,3.54,0.85,5.21c-1.21,1.67-3.54,2.05-5.21,0.85l-0.1-0.07l-0.01-0.01 l-0.01,0.01c-0.65-0.47-1.49-1.12-2.32-1.76c-2.86-2.21-3.68-4.34-6.77-4.31c-2.06,0.02-3.96,1.82-6.03,3.79 c-3.91,3.71-8.26,7.83-16.81,7.44c-8.54,0.39-12.89-3.73-16.81-7.44c-2.08-1.97-3.97-3.76-6.03-3.79 c-3.08-0.03-5.86,2.11-8.72,4.31c-4.12,3.18-8.38,6.46-15,6.38c-6.62,0.08-10.88-3.2-15-6.38c-2.86-2.21-5.63-4.34-8.72-4.31 c-2.06,0.02-3.96,1.82-6.03,3.79c-0.77,0.73-1.56,1.48-2.44,2.23c-1.57,1.34-3.93,1.16-5.27-0.41c-1.34-1.57-1.16-3.93,0.41-5.27 c0.72-0.62,1.44-1.3,2.15-1.97c3.19-3.02,6.1-5.78,11.13-5.84c5.66-0.06,9.44,2.85,13.34,5.86c3.12,2.4,6.34,4.89,10.39,4.83H38.36 L38.36,104.59z M41.08,44.99L61,34.28l22.05,10.71h1.12V31.6h-4.27c-0.11,0.01-0.22,0.01-0.34,0.01c-2.07,0-3.75-1.68-3.75-3.75 v-3.9h-7.38c-2.07,0-3.75-1.68-3.75-3.75V7.5h-6.88v12.72c0,2.07-1.68,3.75-3.75,3.75h-7.38v3.9h-0.01c0,2.06-1.67,3.73-3.73,3.73 h-4.6v13.39H41.08L41.08,44.99z"/></g></svg>
                            <span>Vessels & Voyages </span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        @endpermission

                        <ul class="collapse submenu list-unstyled" id="component88" data-parent="#accordionExample">
                        @permission('VesselType-List')
                        <li>
                            <a href="#vessels" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Vessels <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="vessels" data-parent="#lines">
                                @permission('Vessels-List')
                                    <li>
                                        <a href="{{route('vessels.index')}}"> Vessels </a>
                                    </li>
                                @endpermission

                                @permission('VesselType-List')
                                    <li>
                                        <a href="{{route('vessel-types.index')}}"> Vessel Types </a>
                                    </li>
                                @endpermission
                                </ul>
                                @permission('Voyages-List')
                                    <li>
                                        <a href="{{route('voyages.index')}}"> Voyages Gate</a>
                                    </li>
                                @endpermission
                        </li>
                        @endpermission        
            </li>
            </ul>

            @endpermission


                <li class="menu">
                        <a href="#component3" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 60 60" style="enable-background:new 0 0 122.88 112.63; fill: #ebedf2;" xml:space="preserve"><g><g>
                                <path d="M59,30h-1v-4V16v-5c0-0.6-0.4-1-1-1h-6V4h2V2h-6v2h2v6h-6c-0.6,0-1,0.4-1,1v5v2H5c-0.6,0-1,0.4-1,1v10v9H1
                                    c-0.6,0-1,0.4-1,1v11c0,4.5,3.5,8,8,8h44c4.5,0,8-3.5,8-8V31C60,30.4,59.6,30,59,30z M32,30h4v5h2v-5h4v8H32V30z M44,19v-2h5v3h-5
                                    V19z M44,22h12v3H44V22z M56,20h-5v-3h5V20z M44,29v-2h12v3h-3c-0.6,0-1,0.4-1,1v7h-8V29z M56,12v3H44v-3H56z M6,20h7v5h2v-5h8v5
                                    h2v-5h8v5h2v-5h7v6v2H31H6V20z M6,30h4v5h2v-5h5v5h2v-5h5v5h2v-5h4v8H6V30z M5,40h26h12h10c0.6,0,1-0.4,1-1v-7h4v11H2v-3H5z
                                    M52,56H8c-3.4,0-6-2.6-6-6v-5h56v5C58,53.4,55.4,56,52,56z"/>
                                <rect x="55" y="33" width="2" height="2"/>
                                <rect x="4" y="47" width="2" height="2"/>
                                <rect x="9" y="47" width="2" height="2"/>
                                <rect x="14" y="47" width="2" height="2"/>
                                <rect x="19" y="47" width="2" height="2"/>
                                <rect x="24" y="47" width="2" height="2"/>
                                <rect x="29" y="47" width="2" height="2"/>
                                <rect x="34" y="47" width="2" height="2"/>
                                <rect x="39" y="47" width="2" height="2"/>
                                <rect x="44" y="47" width="2" height="2"/>
                                <rect x="49" y="47" width="2" height="2"/>
                                <rect x="54" y="47" width="2" height="2"/>
                                <rect x="46" y="34" width="4" height="2"/>
                                <rect x="46" y="29" width="4" height="2"/>
                            </g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                <span>Containers Movement</span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="component3" data-parent="#accordionExample">
                                @permission('StockTypes-List')
                                    <li>
                                        <a href="{{route('stock-types.index')}}"> Stock Types </a>
                                    </li>
                                @endpermission
                                @permission('ContainersTypes-List')
                                    <li>
                                        <a href="{{route('container-types.index')}}"> Container Types </a>
                                    </li>
                                @endpermission
                                
                                @permission('LessorSeller-List')
                                    <li>
                                        <a href="{{route('seller.index')}}">Container Ownerships</a>
                                    </li>
                                @endpermission

                                @permission('Containers-List')
                                    <li>
                                        <a href="{{route('containers.index')}}"> Containers </a>
                                    </li>
                                @endpermission
                                <!-- @permission('Containers-List')
                                    <li>
                                        <a href="{{route('tracking.create')}}"> Containers Tracking</a>
                                    </li>
                                @endpermission -->


                                @permission('ContainersMovement-List')
                                    <li>
                                        <a href="{{route('container-movement.index')}}"> Movements Activity codes </a>
                                    </li>
                                @endpermission
                                <!-- @permission('Movements-List')
                                    <li>
                                        <a href="{{route('movements.index')}}"> Movements</a>
                                    </li>
                                @endpermission -->
                                <!--
                                @permission('Demurrage-List')
                                <li>
                                    <a href="{{route('demurrage.index')}}">Demurrage & Dentention</a>
                                </li>
                                @endpermission -->
                                </ul>
                </li>
        @if(Auth::user()->is_super_admin == 1)

            @permission('Demurrage-List')
                    <li class="menu">
                        <a href="#component7" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 60 60" style="enable-background:new 0 0 122.88 112.63; fill: #ebedf2;" xml:space="preserve"><g><g>
                                <path d="M59,30h-1v-4V16v-5c0-0.6-0.4-1-1-1h-6V4h2V2h-6v2h2v6h-6c-0.6,0-1,0.4-1,1v5v2H5c-0.6,0-1,0.4-1,1v10v9H1
                                    c-0.6,0-1,0.4-1,1v11c0,4.5,3.5,8,8,8h44c4.5,0,8-3.5,8-8V31C60,30.4,59.6,30,59,30z M32,30h4v5h2v-5h4v8H32V30z M44,19v-2h5v3h-5
                                    V19z M44,22h12v3H44V22z M56,20h-5v-3h5V20z M44,29v-2h12v3h-3c-0.6,0-1,0.4-1,1v7h-8V29z M56,12v3H44v-3H56z M6,20h7v5h2v-5h8v5
                                    h2v-5h8v5h2v-5h7v6v2H31H6V20z M6,30h4v5h2v-5h5v5h2v-5h5v5h2v-5h4v8H6V30z M5,40h26h12h10c0.6,0,1-0.4,1-1v-7h4v11H2v-3H5z
                                    M52,56H8c-3.4,0-6-2.6-6-6v-5h56v5C58,53.4,55.4,56,52,56z"/>
                                <rect x="55" y="33" width="2" height="2"/>
                                <rect x="4" y="47" width="2" height="2"/>
                                <rect x="9" y="47" width="2" height="2"/>
                                <rect x="14" y="47" width="2" height="2"/>
                                <rect x="19" y="47" width="2" height="2"/>
                                <rect x="24" y="47" width="2" height="2"/>
                                <rect x="29" y="47" width="2" height="2"/>
                                <rect x="34" y="47" width="2" height="2"/>
                                <rect x="39" y="47" width="2" height="2"/>
                                <rect x="44" y="47" width="2" height="2"/>
                                <rect x="49" y="47" width="2" height="2"/>
                                <rect x="54" y="47" width="2" height="2"/>
                                <rect x="46" y="34" width="4" height="2"/>
                                <rect x="46" y="29" width="4" height="2"/>
                            </g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                <span>Demurrage & Dentention</span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="component7" data-parent="#accordionExample">
                                @permission('Demurrage-List')
                                    <li>
                                        <a href="{{route('storage.create')}}"> Dentention Calculation </a>
                                    </li>
                                @endpermission
                                @permission('Invoice-List')
                                    <li>
                                        <a href="{{route('storage.index')}}">Storage Calculation</a>
                                    </li>
                                @endpermission
                        </ul>
            </li>
            @endpermission

            @permission('Quotation-List')
            <li class="menu">
                        <a href="#component8" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" strokewidth="2" fill="none" strokelinecap="round" strokelinejoin="round" classname="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                             <span>Quotations</span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="component8" data-parent="#accordionExample">
                                @permission('Quotation-List')
                                <li>
                                    <a href="{{route('quotations.index')}}">Quotations Gates</a>
                                </li>
                                @endpermission
                        </ul>
            </li>
            @endpermission


            <ul class="list-unstyled menu-categories" id="accordionExample" style="padding:0px;">
            @permission('Booking-List')
            <li class="menu">
                        <a href="#booking" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div>
                                <svg style="color: ; fill: #fff;" xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 0 24 24"><path d="M8,8a1,1,0,0,0,0,2H9A1,1,0,0,0,9,8Zm5,12H6a1,1,0,0,1-1-1V5A1,1,0,0,1,6,4h5V7a3,3,0,0,0,3,3h3v2a1,1,0,0,0,2,0V9s0,0,0-.06a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19.29.29,0,0,0-.1,0A1.1,1.1,0,0,0,12.06,2H6A3,3,0,0,0,3,5V19a3,3,0,0,0,3,3h7a1,1,0,0,0,0-2ZM13,5.41,15.59,8H14a1,1,0,0,1-1-1ZM14,12H8a1,1,0,0,0,0,2h6a1,1,0,0,0,0-2Zm6.71,6.29a1,1,0,0,0-1.42,0l-.29.3V16a1,1,0,0,0-2,0v2.59l-.29-.3a1,1,0,0,0-1.42,1.42l2,2a1,1,0,0,0,.33.21.94.94,0,0,0,.76,0,1,1,0,0,0,.33-.21l2-2A1,1,0,0,0,20.71,18.29ZM12,18a1,1,0,0,0,0-2H8a1,1,0,0,0,0,2Z"/></svg>
                                    <span>Documentation</span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="booking" data-parent="#accordionExample">

                        @permission('Booking-List')

                                @permission('Booking-List')
                                <li>
                                    <a href="{{route('booking.index')}}">Booking Gates</a>
                                </li>
                                @endpermission


                                @permission('BlDraft-List')
                                <li>
                                    <a href="{{route('bldraft.index')}}"> Bl Gates </a>
                                </li>
                                @endpermission

                                @permission('XML-List')
                                <li>
                                    <a href="{{route('xml.index')}}"> Manifest XML Gates </a>
                                </li>
                                @endpermission
                                </ul>
                        </li>
                        @endpermission
            </li>
            @endpermission
        </ul>
        @permission('Trucker-List')
            <li class="menu">
                        <a href="#component9" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                             <span>Operations</span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="component9" data-parent="#accordionExample">
                            @permission('Trucker-List')
                            <li>
                                <a href="{{route('trucker.index')}}">Truckers List</a>
                            </li>
                            @endpermission
                            @permission('TruckerGates-List')
                            <li>
                                <a href="{{route('truckergate.index')}}">Trucker Gates</a>
                            </li>
                            @endpermission
                            <li>
                                <a href="{{ route('port-charges.index') }}">Port Charges Matrices</a>
                            </li>
                            <li>
                                <a href="{{ route('port-charge-invoices.index') }}">Port Charges Invoice</a>
                            </li>
                        </ul>
            </li>
            @endpermission
            @permission('Invoice-List')
                <li class="menu">
                        <a href="#component10" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" strokewidth="2" fill="none" strokelinecap="round" strokelinejoin="round" classname="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                             <span>Accounting</span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="component10" data-parent="#accordionExample">
                                @permission('Invoice-List')
                                <li>
                                    <a href="{{route('chargesDesc.index')}}">Portal Charges</a>
                                </li>
                                @endpermission
                                @permission('Invoice-List')
                                <li>
                                    <a href="{{route('invoice.index')}}">Invoice Gates</a>
                                </li>
                                @endpermission
                                @permission('Invoice-List')
                                <li>
                                    <a href="{{route('receipt.index')}}">Receipt Gates</a>
                                </li>
                                @endpermission
                                @permission('Invoice-List')
                                <li>
                                    <a href="{{route('creditNote.index')}}">Credit Note</a>
                                </li>
                                @endpermission
                                @permission('Refund-List')
                                <li>
                                    <a href="{{route('refund.index')}}">Refund Gates</a>
                                </li>
                                @endpermission
                                @permission('Invoice-List')
                                <li>
                                    <a href="{{route('statements.index')}}">Customer Statements</a>
                                </li>
                                @endpermission

                        </ul>
            </li>
            @endpermission
            @endif
    </nav>
</div>
<style>
@media print {
    .search_row,
    .hide {
        display: none !important;
        }
    }
</style>
