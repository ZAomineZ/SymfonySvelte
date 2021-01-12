<script>
    // COMPONENT SVELTE
    import {onMount} from "svelte";

    // STATE
    let body = null
    let activeSidebar = true

    onMount(() => {
        body = document.querySelector('body')
    })

    /**
     * Handle event method for hide or show the sidebar to app
     */
    function handleToggleSidebarClick() {
        console.log(body)
        if (activeSidebar) {
            body.classList.add('is-collapsed')
        } else {
            body.classList.remove('is-collapsed')
        }
        activeSidebar = !activeSidebar
    }
</script>

<div class="header navbar">
    <div class="header-container">
        <ul class="nav-left">
            <li>
                <a href="#" id="sidebar-toggle" on:click|preventDefault={handleToggleSidebarClick}>
                    <i class="ti-menu"></i>
                </a>
            </li>
            <li class="search-item">
                <a href="/" id="search-toggle">
                    <i class="ti-search"></i>
                </a>
            </li>
        </ul>
        <ul class="nav-right">
            <li class="dropdown">
                <a class="dropdown-toggle blocks flex-nowrap align-items-center" href="/">
                    <div class="block mr-10" id="navbar-img-profile">
                        <img alt="image profile navbar" class="w-2r border-radius-50" src="/images/image-profile.jpg">
                    </div>
                    <div class="block">
                        <span class="color-grey-bold font-sm">John Doe</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>