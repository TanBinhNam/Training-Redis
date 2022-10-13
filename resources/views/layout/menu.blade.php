<nav class="navbar navbar-expand-lg navbar-light" style="background-color: gray;margin-left:500px;padding:0">
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item {{ Request::routeIs('products.*') ? 'active' : ''  }}" >
        <a class="nav-link" href="{{route('products.index')}}">Sản phẩm</a>
      </li>
      <li class="nav-item {{ Request::routeIs('customers.*') ? 'active' : ''  }}">
        <a class="nav-link" href="{{route('customers.index')}}">Khách hàng</a>
      </li>
 
      <li class="nav-item {{ Request::routeIs('users.*') ? 'active' : ''  }}">
        <a class="nav-link" href="{{route('users.index')}}">Users</a>
      </li>
    </ul>
    <form class="form-inline mr-5">
      <i class="fa fa-user-circle-o" style="font-size: 30px;" aria-hidden="true"></i>
   
      <div class="dropdown">
        <div class="ml-2 dropbtn">{{ Auth::user()->name }}</div>
        <div class="dropdown-content">
          <a href="{{ route('logout') }}">Đăng xuất</a>

        </div>
      </div>
    </form>
  </div>
</nav>