<div id="header" class="navbar-toggleable-md sticky clearfix">

    <!-- TOP NAV -->
    <header id="topNav">
        <div class="container">

            <!-- Mobile Menu Button -->
            <button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Logo -->
            <a class="logo float-left" href="/">
                <img src="assets/templates/portal/images/_smarty/logo_dark.png" alt=""/>
            </a>

            <ul class="float-right nav nav-pills nav-second-main">
                <li>
                    <a href="/lang/change_r/ru" class="text-uppercase">RU</a>
                </li>
                <li>
                    <a href="/lang/change_r/uz" class="text-uppercase">UZ</a>
                </li>
            </ul>

            <div class="navbar-collapse collapse float-right nav-main-collapse submenu-dark">
                <nav class="nav-main">

                    <!--
                        NOTE

                        For a regular link, remove "dropdown" class from LI tag and "dropdown-toggle" class from the href.
                        Direct Link Example:

                        <li>
                            <a href="#">HOME</a>
                        </li>
                    -->
                    <ul id="topMain" class="nav nav-pills nav-main">
                        {menu}
                        <li class="{active}">
                            <a href="{url}" class="text-uppercase">{title}</a>
                        </li>
                        {/menu}
                        
                    </ul>

                </nav>
            </div>

        </div>
    </header>
    <!-- /Top Nav -->

</div>