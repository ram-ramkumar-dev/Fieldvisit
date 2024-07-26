        <div class="iq-sidebar  sidebar-default  ">
          <div class="iq-sidebar-logo d-flex align-items-end justify-content-between">
               <a href="{{ route('dashboard') }}" class="header-logo">
                  <img src="{{ asset('assets/images/V-Ranger_Sidebar_Text.png') }}" class="img-fluid rounded-normal light-logo" alt="logo">
                  <img src="{{ asset('assets/images/V-Ranger_Sidebar_Text.png') }}" class="img-fluid rounded-normal d-none sidebar-light-img" alt="logo">
                             
              </a>
              <div class="side-menu-bt-sidebar-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="text-light wrapper-menu" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </div>
          </div>
          <div class="data-scrollbar" data-scroll="1">
              <nav class="iq-sidebar-menu">
                  <ul id="iq-sidebar-toggle" class="side-menu"> 
                      <li class="{{ $page == 'Dashboard' ? 'active' : '' }} sidebar-layout">
                          <a href="{{ route('dashboard') }}" class="svg-icon">
                              <i class="">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                  </svg>
                              </i>
                              <span class="ml-2">Dashboard</span>
                              <!--<p class="mb-0 w-10 badge badge-pill badge-primary">6</p>-->
                          </a>
                      </li>
                       
                      @if(empty($permissions) || in_array('setting', $permissions))
                      <li class="sidebar-layout {{ in_array($page, ['Drivers', 'Clientgroups', 'Clients']) ? 'active' : '' }} ">
                          <a href="#settings" class="collapsed svg-icon" data-toggle="collapse" aria-expanded="false">
                              <i>
                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                  </svg>
                              </i>
                              <span class="ml-2">Settings</span>
                              <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon iq-arrow-right arrow-active" width="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                              </svg>
                          </a>
                          
                          <ul id="settings" class="submenu collapse" data-parent="#iq-sidebar-toggle">                        
                               
                              <li class="{{ $page == 'Drivers' ? 'active' : '' }}  sidebar-layout">
                                  <a href="{{ route('drivers.index') }}" class="svg-icon">
                                      <i class="">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
</svg>

                                      </i><span class="">Users</span>
                                  </a>
                              </li>
                              <li class="{{ $page == 'Clientgroups' ? 'active' : '' }}  sidebar-layout">
                                  <a href="{{ route('clientgroups.index') }}" class="svg-icon">
                                      <i class="">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
</svg>

                                      </i><span class="">Client Groups</span>
                                  </a>
                              </li> 
                              <li class="{{ $page == 'Clients' ? 'active' : '' }}  sidebar-layout">
                                  <a href="{{ route('clients.index') }}" class="svg-icon">
                                      <i class="">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
</svg>

                                      </i><span class="">Clients</span>
                                  </a>
                              </li> 
                          </ul>
                      </li>
                      @endif
                      @if(empty($permissions) || in_array('fieldvisit', $permissions))
                      <li class="sidebar-layout {{ in_array($page, ['Status']) ? 'active' : '' }}">
                          <a href="#admin" class="collapsed svg-icon" data-toggle="collapse" aria-expanded="false">
                          <i class="">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                  </svg>
                              </i>
                              <span class="ml-2">Administration</span>
                              <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon iq-arrow-right arrow-active" width="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                              </svg>
                          </a>
                          <ul id="admin" class="submenu collapse" data-parent="#iq-sidebar-toggle">                        
                          
                              <li class="{{ $page == 'Status' ? 'active' : '' }} sidebar-layout">
                                  <a href="{{ route('statuses.index') }}" class="svg-icon">
                                      <i class="">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">  <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" /></svg>
                                      </i><span class="">Status</span>
                                  </a>
                              </li> 
                              <li class="{{ $page == 'Batches' ? 'active' : '' }} sidebar-layout">
                                  <a href="{{ route('batches.index') }}" class="svg-icon">
                                      <i class="">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" /></svg>
                                      </i><span class="">Add Batch</span>
                                  </a>
                              </li>
                              <li class="{{ $page == 'ImportBatch' ? 'active' : '' }} sidebar-layout">
                                  <a href="{{ route('batches.import') }}" class="svg-icon">
                                      <i class="">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">  <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" /></svg>
                                      </i><span class="">Import Batch</span>
                                  </a>
                              </li> 
                              
                              <li class=" sidebar-layout">
                                  <a href="#" class="svg-icon">
                                      <i class="">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">  <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" /></svg>
                                      </i><span class="">Assign Case</span>
                                  </a>
                              </li> 
                          </ul>
                      </li>
                      @endif
 
                      @if(empty($permissions) || in_array('report', $permissions))
                      <li class="sidebar-layout">
                          <a href="#reports" class="collapsed svg-icon" data-toggle="collapse" aria-expanded="false">
                              <i>
                                  <svg class="svg-icon" id="iq-form-1" width="18" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" style="stroke-dasharray: 74, 94; stroke-dashoffset: 0;"></path>
                                  </svg>
                              </i>
                              <span class="ml-2">Reports</span>
                              <svg xmlns="http://www.w3.org/2000/svg" class="svg-icon iq-arrow-right arrow-active" width="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                              </svg>
                          </a>
                          <ul id="reports" class="submenu collapse" data-parent="#iq-sidebar-toggle">                        
                               
                              <li class=" sidebar-layout">
                                  <a href="#" class="svg-icon">
                                      <i class="">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">  <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" /></svg>
                                      </i><span class="">Survey Result</span>
                                  </a>
                              </li>
                              <li class=" sidebar-layout">
                                  <a href="#" class="svg-icon">
                                      <i class="">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">  <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" /></svg>
                                      </i><span class="">Agent KPI</span>
                                  </a>
                              </li> 
                          </ul>
                      </li> 
                      @endif  
                  </ul>
              </nav>
              <div class="pt-5 pb-5"></div>
          </div>
        </div>
     