<?php
  require_once(__DIR__ . '/settings.php');
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/header_style.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <i class="fas fa-user userProfile"></i>
                </span>

                <div class="text logo-text">
                    <span class="userName"><?=$_SESSION['user']?></span>
                    <span class="role"><?=$_SESSION['role_name']?></span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" id="headerSearchBox" placeholder="Search...">
                </li>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="<?= BASE_URL ?>dashboard.php">
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?= BASE_URL ?>receiving/">
                            <i class='fas fa-boxes icon' ></i>
                            <span class="text nav-text">Receiving</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?= BASE_URL ?>shipping/">
                            <i class='fas fa-truck icon'></i>
                            <span class="text nav-text">Shipping</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?= BASE_URL ?>inventory/">
                            <i class='fas fa-dolly-flatbed icon' ></i>
                            <span class="text nav-text">Inventory</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?= BASE_URL ?>edi/">
                            <i class="fas fa-solid fa-inbox icon"></i>
                            <span class="text nav-text">EDI</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?= BASE_URL ?>warehouse_view/">
                            <i class='fas fa-warehouse icon' ></i>
                            <span class="text nav-text">Warehouse View</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?= BASE_URL ?>reports/">
                            <i class='fas fa-chart-bar icon' ></i>
                            <span class="text nav-text">Reports</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?= BASE_URL ?>administration/alerts.php">
                              <i class='fas fa-envelope-open-text icon' ></i>
                            <span class="text nav-text">Alerts</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="<?= BASE_URL ?>administration/">
                            <i class='fas fa-cog icon' ></i>
                            <span class="text nav-text">Admin</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="<?= BASE_URL ?>logout.php">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

            </div>
        </div>

    </nav>
</body>
</html>
<script>
const body = document.querySelector('body'),
      sidebar = body.querySelector('nav'),
      toggle = body.querySelector(".toggle"),
      searchBtn = body.querySelector(".search-box"),
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text"),
      userProfile = body.querySelector(".userProfile"),
      headerSearchBox = body.querySelector("#headerSearchBox"),
      bxSearch = body.querySelector(".bx-search");

toggle.addEventListener("click" , () =>{
    sidebar.classList.toggle("close");
})

searchBtn.addEventListener("click" , () =>{
    sidebar.classList.remove("close");
})

userProfile.addEventListener("click" , () =>{
    window.location.href = "<?= BASE_URL ?>administration/account.php?id=<?=$_SESSION['user_id']?>";
})

searchBtn.addEventListener("keyup", function(event) {
  event.preventDefault();
  if (event.keyCode === 13) {
      window.location.href = "<?= BASE_URL ?>system_search/search.php?data=" + headerSearchBox.value;
  }
})

bxSearch.addEventListener("click", function(event) {

  if(headerSearchBox.value.length > 0) {
    window.location.href = "<?= BASE_URL ?>system_search/search.php?data=" + headerSearchBox.value;
  }
})
</script>
