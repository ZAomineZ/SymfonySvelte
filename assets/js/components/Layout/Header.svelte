<script>
    import {Link} from "svelte-routing"
    import MenuNavMobile from "./MenuNavMobile.svelte";
</script>

<header>
    <div class="header-area">
        <div class="main-header-area">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-xl-3 col-lg-2">
                        <div class="logo">
                            <Link to="/home">
                                <img alt="logo header" src="/images/logo.png">
                            </Link>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-7">
                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul id="nav">
                                    <li>
                                        <Link to="/home">Home</Link>
                                    </li>
                                    <li>
                                        <Link to="/projects">Projects</Link>
                                    </li>
                                    <li>
                                        <Link to="/categories">Categories</Link>
                                    </li>
                                    <li>
                                        <Link to="/about">About</Link>
                                    </li>
                                    <li>
                                        <Link to="/contact">Contact</Link>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                        <div class="phone-info-header">
                            <div class="btn-phone d-none d-lg-block">
                                <a href="/">
                                    <i class="fa fa-phone"></i>
                                    +10 673 567 367
                                </a>
                            </div>
                        </div>
                    </div>
                    <MenuNavMobile />
                </div>
            </div>
        </div>
    </div>
</header>