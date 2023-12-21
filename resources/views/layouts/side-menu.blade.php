@php
$RouteName = Route::current()->getName();  
@endphp
<div id="sidebar-menu">
    <ul class="metismenu list-unstyled" id="side-menu">
        <li>
            <a href="{{ route('dashboard') }}" class="waves-effect">
                <i class="bx bx-home-circle"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('company.view') }}" class="waves-effect">
                <i class="bx bx-user-pin"></i>
                <span>Companies</span>
            </a>
        </li>
        <li>
            <a href="{{ route('employee.view') }}" class="waves-effect">
                <i class="bx bxs-user-detail"></i>
                <span>Employees</span>
            </a>
        </li>
    </ul>
</div>