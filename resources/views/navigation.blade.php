<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
  <li class="nav-item">
    <a href="{{ Auth::guard('web')->check() ? route('admin.dashboard') : route('dashboard') }}" class="nav-link">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>
        Dashboard
      </p>
    </a>
  </li>

  <li class="nav-item">
    <a href="{{ Auth::guard('web')->check() ? route('admin.companies') : route('companies') }}" class="nav-link">
      <i class="nav-icon fas fa-suitcase"></i>
      <p>
        Company
      </p>
    </a>
  </li>

  <li class="nav-item">
    <a href="{{ Auth::guard('web')->check() ? route('admin.employees') : route('employees') }}" class="nav-link">
      <i class="nav-icon fas fa-user"></i>
      <p>
        Employee
      </p>
    </a>
  </li>

  <li class="nav-item">
    <a href="{{ route('emails') }}" class="nav-link">
      <i class="nav-icon fas fa-envelope"></i>
      <p>
        Mail
      </p>
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
      <i class="nav-icon fas fa-power-off"></i>
      <p>
        {{ __('Logout') }}
      </p>
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
  </li>

</ul>