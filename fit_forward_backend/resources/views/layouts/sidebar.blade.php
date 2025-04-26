<!-- Sidebar -->
    <div class="collapse d-md-block sidebar p-3" id="sidebarMenu">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="{{ route('home') }}" class="nav-link {{ request()->segment(1) == '' ? 'active' : '' }}">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                        fill="currentColor" aria-hidden="true" width="12%">
                        <path fill-rule="evenodd" d="M2.25 2.25a.75.75 0 000 1.5H3v10.5a3 3 0 003 3h1.21l-1.172 3.513a.75.75 0 001.424.474l.329-.987h8.418l.33.987a.75.75 0 001.422-.474l-1.17-3.513H18a3 3 0 003-3V3.75h.75a.75.75 0 000-1.5H2.25zm6.04 16.5l.5-1.5h6.42l.5 1.5H8.29zm7.46-12a.75.75 0 00-1.5 0v6a.75.75 0 001.5 0v-6zm-3 2.25a.75.75 0 00-1.5 0v3.75a.75.75 0 001.5 0V9zm-3 2.25a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5z" clip-rule="evenodd"></path>
                    </svg> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('users.index') }}" 
                    class="nav-link {{ request()->segment(1) == 'users' ? 'active' : '' }}">
                    <svg class="me-1 pb-1" width=12% aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                        fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z" clip-rule="evenodd"/>
                      </svg> Users
                </a>
            </li>
            <li class="nav-item mb-2">
                <!-- Toggle -->
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    href="#settingMenu" role="button" aria-expanded="{{ request()->segment(1) == 'bmi_category' ? 'true' : 'false' }}" 
                    aria-controls="settingMenu">
                    <span>
                        <svg class="me-1 pb-1" width="14%" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm4.996 2a1 1 0 0 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 8a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 11a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 14a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Z" clip-rule="evenodd"/>
                        </svg>
                        BMI                          
                    </span>
                    <svg class="ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                        width="20%" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                    </svg>                     
                </a>
            
                <!-- Collapsible Sub-menu -->
                <div class="collapse show" id="settingMenu">
                    <ul class="nav flex-column ms-3 mt-1">
                        <li class="nav-item">
                            <a class="nav-link my-1 {{ request()->routeIs('bmi_category.index') ? 'active' : '' }}" href="{{ route('bmi_category.index') }}">
                                <svg class="me-1 pb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                                    width="14%" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M9 8h10M9 12h10M9 16h10M4.99 8H5m-.02 4h.01m0 4H5"/>
                                </svg>                                  
                                BMI Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link my-1 {{ request()->segment(1) == 'workout_plans' ? 'active' : '' }} or 
                                {{ request()->segment(1) == 'workout_plan_exercises' ? 'active' : '' }}" 
                                href="{{ route('workout_plans.index') }}">
                                <svg class="me-1 pb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                                    width="13%" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z" clip-rule="evenodd"/>
                                  </svg>                                  
                                Workout Plans
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link my-1 {{ request()->segment(1) == 'meal_plans' ? 'active' : '' }}" href="{{ route('meal_plans.index') }}">
                                <svg class="me-1 pb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                                    width="14%" height="24" fill="none" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M16.0001 3c.5522 0 1 .44772 1 1v1.64215l1.5644-1.51254c.397-.38389 1.0301-.37322 1.414.02383.3839.39705.3732 1.03013-.0239 1.41401L18.473 6.99998l1.5271.00001c.5522 0 1 .44772.9999 1 0 .55229-.4477 1-1 1l-2.1048-.00001c.263.59002.4154 1.22712.4292 1.89222.0195.9368-.2372 1.8739-.7777 2.7561l-2.8459-2.7774c-.3952-.3857-1.0284-.378-1.4141.0173-.3857.3952-.378 1.0283.0172 1.4141l2.9486 2.8774c-.8296.7625-2.1057 1.7284-3.5125 2.6476-.0039-.0043-.0079-.0086-.0119-.0129l-2.88472-3.0631c-.37864-.402-1.01152-.421-1.41358-.0424-.40206.3787-.42104 1.0115-.0424 1.4136l2.6129 2.7745c-.189.1089-.3781.2152-.5666.3186-1.21703.6674-2.4674 1.2427-3.553 1.5412-.54097.1487-1.09271.2436-1.60567.2207-.51242-.0228-1.08874-.1699-1.53164-.6128-.44291-.4429-.58999-1.0192-.61281-1.5316-.02284-.513.07199-1.0647.22074-1.6057.29851-1.0856.87376-2.336 1.54118-3.553.62156-1.1334 1.35187-2.2891 2.07312-3.3185l2.71447 2.617c.39761.3834 1.03061.3718 1.41401-.0258.3833-.3976.3717-1.0306-.0259-1.414L8.17235 8.74115c.3837-.47727.7425-.88572 1.05449-1.19772 1.22356-1.22358 2.57606-1.84689 3.94556-1.84108.6464.00273 1.2596.1455 1.8277.39684V4c0-.55228.4477-1 1-1Z"/>
                                </svg>                                  
                                Meal Plans
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('exercises.index') }}" 
                    class="nav-link {{ request()->segment(1) == 'exercises' ? 'active' : '' }}">
                    <svg class="me-1" width="12%" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                        fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 3a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5Zm14 18a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4ZM5 11a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H5Zm14 2a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h4Z"/>
                    </svg>
                    Exercises
                </a>
            </li>

            <li class="nav-item mb-2">
                <!-- Toggle -->
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    href="#bmiSubMenu" role="button" aria-expanded="{{ request()->segment(1) == 'bmi_category' ? 'true' : 'false' }}" 
                    aria-controls="bmiSubMenu">
                    <span>
                        <svg class="me-1 pb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9.586 2.586A2 2 0 0 1 11 2h2a2 2 0 0 1 2 2v.089l.473.196.063-.063a2.002 2.002 0 0 1 2.828 0l1.414 1.414a2 2 0 0 1 0 2.827l-.063.064.196.473H20a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-.089l-.196.473.063.063a2.002 2.002 0 0 1 0 2.828l-1.414 1.414a2 2 0 0 1-2.828 0l-.063-.063-.473.196V20a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-.089l-.473-.196-.063.063a2.002 2.002 0 0 1-2.828 0l-1.414-1.414a2 2 0 0 1 0-2.827l.063-.064L4.089 15H4a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2h.09l.195-.473-.063-.063a2 2 0 0 1 0-2.828l1.414-1.414a2 2 0 0 1 2.827 0l.064.063L9 4.089V4a2 2 0 0 1 .586-1.414ZM8 12a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd"/>
                        </svg>                          
                        Setting                   
                    </span>
                    <svg class="ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                        width="8%" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                    </svg>                     
                </a>
            
                <!-- Collapsible Sub-menu -->
                <div class="collapse show" id="bmiSubMenu">
                    <ul class="nav flex-column ms-3 mt-1">
                        <li class="nav-item">
                            <a class="nav-link my-1 {{ request()->segment(1) == 'admin' ? 'active' : '' }}" href="{{ route('admin.index') }}">
                                <svg class="me-1 pb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M17 10v1.126c.367.095.714.24 1.032.428l.796-.797 1.415 1.415-.797.796c.188.318.333.665.428 1.032H21v2h-1.126c-.095.367-.24.714-.428 1.032l.797.796-1.415 1.415-.796-.797a3.979 3.979 0 0 1-1.032.428V20h-2v-1.126a3.977 3.977 0 0 1-1.032-.428l-.796.797-1.415-1.415.797-.796A3.975 3.975 0 0 1 12.126 16H11v-2h1.126c.095-.367.24-.714.428-1.032l-.797-.796 1.415-1.415.796.797A3.977 3.977 0 0 1 15 11.126V10h2Zm.406 3.578.016.016c.354.358.574.85.578 1.392v.028a2 2 0 0 1-3.409 1.406l-.01-.012a2 2 0 0 1 2.826-2.83ZM5 8a4 4 0 1 1 7.938.703 7.029 7.029 0 0 0-3.235 3.235A4 4 0 0 1 5 8Zm4.29 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h6.101A6.979 6.979 0 0 1 9 15c0-.695.101-1.366.29-2Z" clip-rule="evenodd"/>
                                </svg>                                                                    
                                Admins
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>