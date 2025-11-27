<a href="#" class="dashboard-responsive-nav-trigger"><i class="fa fa-reorder"></i> Dashboard Navigation</a>
<div class="dashboard-sticky-nav">
<div class="content-left pull-left">
<a href="dashboard.html"><img src="{{ asset('public/frontend/images/logo.png') }}" style="height:32px;" alt="logo"></a>
</div>
<div class="content-right pull-right">
<div class="search-bar">
<form>
<div class="form-group">
<input type="text" class="form-control" id="search" placeholder="Search Now">
<a href="#"><span class="search_btn"><i class="fa fa-search" aria-hidden="true"></i></span></a>
</div>
</form>
</div>
<div class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown">
<div class="profile-sec">
<div class="dash-image">
<img src="{{ asset('public/images') }}/{{ Auth::user()->company_logo }}" alt="">
</div>
<div class="dash-content">
<h4>{{ Auth::user()->name }}</h4>
<span>Hotel Manager</span>
</div>
</div>
</a>
<ul class="dropdown-menu">
<li><a href="#"><i class="sl sl-icon-settings"></i>Settings</a></li>
<li><a href="#"><i class="sl sl-icon-user"></i>Profile</a></li>
<li><a href="#"><i class="sl sl-icon-lock"></i>Change Password</a></li>
<li><a href="#"><i class="sl sl-icon-power"></i>Logout</a></li>
</ul>
</div>
<div class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown">
<div class="dropdown-item">
<i class="sl sl-icon-envelope-open"></i>
<span class="notify">3</span>
</div>
</a>
<div class="dropdown-menu notification-menu">
<h4> 23 Messages</h4>
<ul>
<li>
<a href="#">
<div class="notification-item">
<div class="notification-image">
<img src="{{ asset('public/frontend/images/comment.jpg') }}" alt="">
</div>
<div class="notification-content">
<p>You have a notification.</p><span class="notification-time">2 hours ago</span>
</div>
</div>
</a>
</li>
<li>
<a href="#">
<div class="notification-item">
<div class="notification-image">
<img src="{{ asset('public/frontend/images/comment.jpg') }}" alt="">
</div>
<div class="notification-content">
<p>You have a notification.</p><span class="notification-time">2 hours ago</span>
</div>
</div>
</a>
</li>
<li>
<a href="#">
<div class="notification-item">
<div class="notification-image">
<img src="{{ asset('public/frontend/images/comment.jpg') }}" alt="">
</div>
<div class="notification-content">
<p>You have a notification.</p><span class="notification-time">2 hours ago</span>
</div>
</div>
</a>
</li>
</ul>
<p class="all-noti"><a href="#">See all messages</a></p>
</div>
</div>
<div class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown">
<div class="dropdown-item">
<i class="sl sl-icon-bell"></i>
<span class="notify">3</span>
</div>
</a>
<div class="dropdown-menu notification-menu">
<h4> 599 Notifications</h4>
<ul>
<li>
<a href="#">
<div class="notification-item">
<div class="notification-image">
<img src="{{ asset('public/frontend/images/comment.jpg') }}" alt="">
</div>
<div class="notification-content">
<p>You have a notification.</p><span class="notification-time">2 hours ago</span>
</div>
</div>
</a>
</li>
<li>
<a href="#">
<div class="notification-item">
<div class="notification-image">
<img src="{{ asset('public/frontend/images/comment.jpg') }}" alt="">
</div>
<div class="notification-content">
<p>You have a notification.</p><span class="notification-time">2 hours ago</span>
</div>
</div>
 </a>
</li>
<li>
<a href="#">
<div class="notification-item">
<div class="notification-image">
<img src="{{ asset('public/frontend/images/comment.jpg') }}" alt="">
</div>
<div class="notification-content">
<p>You have a notification.</p><span class="notification-time">2 hours ago</span>
</div>
</div>
</a>
</li>
</ul>
<p class="all-noti"><a href="#">See all notifications</a></p>
</div>
</div>
</div>
</div>