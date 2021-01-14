<script>
    // COMPONENT HTML
    import Header from "../../Layout/Header.svelte";
    import Footer from "../../Layout/Footer.svelte";
    // COMPONENT SVELTE
    import {Link} from 'svelte-routing'

    // State
    let items = ['c', 'c', 'c', 'c', 'c', 'c']
</script>

<Header/>
<div class="broadcast">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="text-center">
                    <h3>Categories</h3>
                    <nav class="broadcast_link_lists mt-4">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <Link href="/">Home</Link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Categories
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="categories-list">
    <div class="container">
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
        <div class="row">
            <div class="col-xl-12">
                <div class="load-more text-center">
                    <a class="load-more-btn" href="#">Load More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<Footer/>