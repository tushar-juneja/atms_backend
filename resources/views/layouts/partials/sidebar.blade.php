<nav class="sidebar bg-dark text-white vh-100 p-3" style="width: 250px;">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white" href="#">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white {{request()->routeIs('admin.show_managers.*') ? 'active' : ''}}" href="{{ route('admin.show_managers.index') }}">Show Managers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white {{request()->routeIs('admin.shows.*') ? 'active' : ''}}" href="{{ route('admin.shows.index') }}">Shows</a>
        </li>
    </ul>
</nav>
