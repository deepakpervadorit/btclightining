<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pay Cumbo Tech</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>



    /* Header Container */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #ffffff;
    border-bottom: 1px solid #ddd;
}

/* Header Left Section */
.header-left {
    display: flex;
    align-items: center;
}

/* Header Right Section */
.header-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

/* Dropdown Menu Styles */
.profile-dropdown {
    position: relative;
}

.profile-dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    top: 45px;
    background: #ffffff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    z-index: 1000;
    width: 150px;
    padding: 0;
}

.profile-dropdown-menu a {
    display: block;
    padding: 10px 15px;
    text-decoration: none;
    color: #333;
    transition: background 0.3s;
}

.profile-dropdown-menu a:hover {
    background: #f8f9fa;
}

/* Show dropdown when active */
.profile-dropdown.active .profile-dropdown-menu {
    display: block;
}





   /* General Body and Layout */
body {
    height: 100vh;
    display: flex;
    overflow: hidden;
}

/* Sidebar Styles */
.sidebar {
    height: 100vh;
    width: 250px;
    background-color: #212529;
    color: white;
    transition: all 0.3s;
    overflow: hidden;
    white-space: nowrap;
}

/* Collapsed Sidebar */
.sidebar.collapsed {
    width: 70px; /* Adjust width for icons only */
}

/* Branding Style */
.sidebar .navbar-brand {
    font-size: 27px;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
    white-space: nowrap;
    overflow: hidden;
}

/* Collapsed Branding - Show 'C' Only */
.sidebar.collapsed .navbar-brand {
    font-size: 30px;
    padding-left: 0;
    text-transform: uppercase;
    text-align: center;
}


.sidebar.collapsed .navbar-brand::after {
    content: "";
}

/* Nav Link Styling */
.sidebar .nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Hide Text in Collapsed Mode */
.sidebar.collapsed .nav-link span {
    display: none;
}

/* Align Icons in Collapsed State */
.sidebar.collapsed .nav-link i {
    margin-right: 0;
    text-align: center;
    width: 100%; /* Center align icons */
}

/* Dropdown Arrow Adjustment */
.sidebar .nav-link.dropdown-toggle::after {
    margin-left: auto; /* Align dropdown arrows to the right */
}

.sidebar.collapsed .nav-link.dropdown-toggle::after {
    display: none; /* Hide dropdown arrows when collapsed */
}

/* Dropdown Menu Visibility in Collapsed Mode */
.sidebar.collapsed .dropdown-menu {
    position: absolute;
    left: 70px; /* Adjust based on sidebar width */
    top: 0;
    white-space: nowrap;
    z-index: 1050; /* Ensure visibility above other elements */
}

/* Main Content Styles */
.content {
    flex-grow: 1;
    padding: 20px;
    height: 100vh;
    overflow-y: auto;
}

/* Toggle Button */
.toggle-btn {
    margin-bottom: 10px;
}

/* Branding Style for Collapsed View */
a.navbar-brand.text-white.mb-4 {
    font-weight: bold;
    font-size: 27px;
    text-align: center;
    text-transform: uppercase;
        margin-bottom: 10px !important;
}
 </style>
</head>
<body>






    <!-- Sidebar -->
    <div id="sidebar" class="sidebar d-flex flex-column p-3">
        <a class="navbar-brand text-white mb-4" href="#">Cumbo.Tech</a>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i><span> Dashboard</span>
                </a>
            </li>
            @php
                $staffId = session('staff_id');
            @endphp
            @if($staffId == '3')
                <li class="nav-item"><a class="nav-link" href="{{ route('deposit') }}">
                    <i class="fas fa-wallet"></i> <span>Deposit</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('withdrawal') }}">
                    <i class="fas fa-money-bill"></i><span> Withdrawal</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('staff.index') }}">
                    <i class="fas fa-users"></i> <span>Staff</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('disputes') }}">
                    <i class="fas fa-exclamation-triangle"></i><span> Disputes</span></a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('service_provider') }}">
                    <i class="fas fa-concierge-bell"></i><span> Service Provider</span></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-credit-card"></i><span> Payment Options</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.stripe.keys') }}">Stripe</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.square.keys') }}">Square</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.checkbook.keys') }}">Checkbook</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('roles.index') }}">
                    <i class="fas fa-user-shield"></i> <span>Roles and Permission</span></a></li>
            @endif
            @if ($permissions->contains('Users'))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-users"></i><span> Users
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @if($roleName == "Merchant")
                    <li><a class="dropdown-item" href="{{ route('merchant.users.index') }}">Users</a></li>
                    @else
                        @if($staffId == '3')
                            <li><a class="dropdown-item" href="{{ route('user.index') }}">Users</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.checkbookusers') }}">Checkbook Users</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('user.virtualcard') }}">Virtual Card</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.checkbook_usersbyid') }}">Checkbook Account</a></li>
                        @endif
                    @endif
                </ul>
            </li>
            @endif
            @if($staffId == '3')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-users"></i><span> Merchants
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                        <li><a class="dropdown-item" href="{{ route('admin.merchant.list') }}">Merchants List</a></li>

                </ul>
            </li>
            @endif
        </ul>
        <div class="mt-auto">
            <span class="nav-item">
                @if($roleName == "Merchant")
                <a href="{{ route('logout') }}" class="nav-link" ><i class="fas fa-sign-out"></i><span>Logout</span></a>
                @else
                <a href="{{ route('admin.logout') }}" class="nav-link" ><i class="fas fa-sign-out"></i><span>Logout</span></a>
                @endif
            </span>
        </div>
    </div>




<div class="content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button id="toggleSidebar" class="btn btn-light me-3">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
          <!-- Header Right Section -->
<div class="header-right">
    <div class="profile-dropdown">
        <!-- Profile Name with Dropdown Icon -->
        <div id="profileDropdownBtn" class="d-flex align-items-center" style="cursor: pointer;">
            <img src="https://via.placeholder.com/40" alt="Profile" />
            <span class="ms-2">{{$roleName}}</span>
            <i class="fas fa-chevron-down ms-2"></i> <!-- Dropdown Icon -->
        </div>
        <!-- Dropdown Menu -->
        <ul class="profile-dropdown-menu list-unstyled shadow-sm bg-white rounded">
            <li><a href="#" class="dropdown-item">View Profile</a></li>
            @if($roleName == "Merchant")
            <li><a href="{{ route('logout') }}" class="dropdown-item">Logout</a></li>
            @else
            <li><a href="{{ route('admin.logout') }}" class="dropdown-item">Logout</a></li>
            @endif
        </ul>
    </div>
</div>

        </div>
        <div class="navbar navbar-expand-lg navbar-light bg-white">
            <h5>@yield('breadcrumb')</h5>
        </div>
        <div class="container mt-4">
            @yield('content')
        </div>
</div>


    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Select elements
const sidebar = document.getElementById('sidebar');
const toggleButton = document.getElementById('toggleSidebar');

// Toggle sidebar collapse state
toggleButton.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');

    // Update branding dynamically
    const brand = document.querySelector('.navbar-brand');
    if (sidebar.classList.contains('collapsed')) {
        brand.textContent = "C";
    } else {
        brand.textContent = "Cumbo.Tech";
    }
});

// Ensure dropdown menus behave properly in collapsed state
document.querySelectorAll('.dropdown-toggle').forEach(item => {
    item.addEventListener('click', function () {
        if (sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed'); // Temporarily expand
        }
    });
});

// Maintain proper height on window resize
window.addEventListener('resize', () => {
    document.body.style.height = window.innerHeight + 'px';
});












// Profile dropdown toggle
const profileDropdown = document.querySelector('.profile-dropdown');
const profileDropdownBtn = document.getElementById('profileDropdownBtn');

// Toggle dropdown visibility when clicking on the dropdown icon
profileDropdownBtn.addEventListener('click', () => {
    profileDropdown.classList.toggle('active');
});

// Close dropdown when clicking outside
document.addEventListener('click', (e) => {
    if (!profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('active');
    }
});



    </script>
    @yield('scripts')
</body>
</html>
