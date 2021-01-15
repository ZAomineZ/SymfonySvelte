<script>
    // COMPONENTS SVELTE
    import {Link} from 'svelte-routing'
</script>

<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <div class="blocks flex-nowrap align-items-center">
                <div class="block block-greed">
                    <Link class="sidebar-link td-n" to="/admin">
                        <div class="blocks flex-nowrap align-items-center">
                            <div class="block">
                                <div class="logo">
                                    <img alt="logo sidebar" src="/images/logo.webp">
                                </div>
                            </div>
                            <div class="block block-greed">
                                <h5 class="logo-text mb-0">Adminator</h5>
                            </div>
                        </div>
                    </Link>
                </div>
                <div class="block">
                    <div class="mobile-toggle" id="sidebar-toggle-action">
                        <a class="td-n" href="/">
                            <i class="ti-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu sidebar-menu-items">
            <li class="nav-item mt-30 active">
                <a class="sidebar-link" href="/">
                    <span class="icon-holder">
                        <i class="ti-home color-blue"></i>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link" href="/admin/projects">
                    <span class="icon-holder">
                        <i class="ti-book color-pink"></i>
                    </span>
                    <span class="title">Projects</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link" href="/admin/categories">
                    <span class="icon-holder">
                        <i class="ti-star color-orange"></i>
                    </span>
                    <span class="title">Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link" href="/admin/tags">
                    <span class="icon-holder">
                        <i class="ti-tag color-purple"></i>
                    </span>
                    <span class="title">Tags</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="sidebar-link" href="/admin/images">
                    <span class="icon-holder">
                        <i class="ti-image color-green"></i>
                    </span>
                    <span class="title">Images</span>
                </a>
            </li>
        </ul>
    </div>
</div>