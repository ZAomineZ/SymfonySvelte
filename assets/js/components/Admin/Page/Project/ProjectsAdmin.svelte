<script>
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
</script>

<div>
    <Sidebar/>
    <div class="page-container">
        <Navbar/>
        <main class="main-content background-grey-light">
            <div id="mainContent">
                <div class="container-fluid">
                    <h4 class="color-grey-bold mt-10 mb-30">Yours projects</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="background-white bd border-radius-3px p-20 mb-20">
                                <h4 class="color-grey-bold mb-20">Projects</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut cum impedit nesciunt
                                    saepe vel? Consectetur consequuntur, dolore enim ipsam iure, magni natus, non
                                    nostrum praesentium quod ratione temporibus tenetur voluptate?</p>
                                <table class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Content</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Valid ?</th>
                                        <th scope="col">Created</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>