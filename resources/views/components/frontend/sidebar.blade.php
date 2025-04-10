<aside class="sidebar">
    <h2>MENU</h2>
    <ul>
        <li class="{{ Request::routeIs('dashboard-siswa.index') ? 'active' : '' }}">
            <a href="{{ route('dashboard-siswa.index') }}"><i class="fas fa-home"></i> Dashboard</a>
        </li>                
        <li class="{{ Request::routeIs('profile-siswa.edit') ? 'active' : '' }}">
            <a href="{{ route('profile-siswa.edit') }}"><i class="fas fa-user"></i> Data Diri</a>
        </li>
        <li class="{{ Request::routeIs('mata-pelajaran.index') ? 'active' : '' }}">
            <a href="{{ route('mata-pelajaran.index') }}"><i class="fas fa-book"></i> Mata Pelajaran</a>
        </li>
        <li class="{{ Request::routeIs('list-tugas.index') ? 'active' : '' }}">
            <a href="{{ route('list-tugas.index') }}"><i class="fas fa-tasks"></i> Tugas</a>
        </li>
        <li class="">
            <a href="#"><i class="fas fa-question-circle"></i> Kuis</a>
        </li>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</aside>
