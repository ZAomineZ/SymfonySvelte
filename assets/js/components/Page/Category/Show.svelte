<script>
    // COMPONENT HTML
    import Footer from "../../Layout/Footer.svelte";
    import Header from "../../Layout/Header.svelte";
    // COMPONENT SVELTE
    import {Link} from 'svelte-routing'

    // PROPS
    export let slug

    // State
    let items = ['c', 'c', 'c']
</script>

<Header/>
<div class="broadcast">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="broadcast-text">
                    <h3>Category Design</h3>
                    <nav class="broadcast_link_lists mt-4">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <Link href="/">Home</Link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Category
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container" id="category_show">
    <div class="border-bottom">
        <div class="row">
            <div class="col-xl-12">
                <div class="case_category_thumb">
                    <img src="/images/category-single.png" alt="">
                </div>
            </div>
            <div class="col-xl-9">
                <div class="details_category">
                    <p class="details_category_info">
                        “Donec imperdiet congue orci consequat mattis. Donec rutrum porttitor
                        sollicitudin. Pellentesque id dolor tempor sapien feugiat ultrices nec sed neque.
                        Fusce ac mattis nulla. Morbi eget ornare dui.
                    </p>
                    <div class="single-category_details">
                        <span>Description</span>
                        <p>
                            Esteem spirit temper too say adieus who direct esteem. It esteems luckily or picture
                            placing drawing. Apartments frequently or motionless on reasonable sed do eiusmod
                            tempor inciunt ut labore et dolore magna liqua.abore et dolore incididunt ut labore
                            et dolore magna liqua abore et dolore
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="category-related categories-list">
    <div class="container">
        <div class="row">
            <div class="rol-xl-12">
                <div class="category-related-title">
                    <h3>Related Category</h3>
                </div>
            </div>
        </div>
        <div class="row">
            {#each items as item }
                <div class="col-lg-4 col-md-6">
                    <div class="single-category text-center">
                        <div class="thumb">
                            <a href="#">
                                <img src="/images/category.webp" alt="">
                            </a>
                        </div>
                        <div class="single-category-body">
                            <h4>
                                <a href="#">Category Design</a>
                            </h4>
                            <p>UI/UX, Design</p>
                        </div>
                    </div>
                </div>
            {/each}
        </div>
    </div>
</div>
<Footer/>