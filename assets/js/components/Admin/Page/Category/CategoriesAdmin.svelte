<script>
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    import {Link} from "svelte-routing"
    // LIBS APP
    import {Category} from "../../../../Request/Category";

    // STATE
    let categories = []

    onMount(async () => {
        const response = await (new Category()).getCategories()
        if (response.success) {
            categories = response.data.categories
        }
    })

    /**
     * Method who init a event for delete to category current
     *
     * @param {Event} e
     */
    async function handleDelete(e) {
        const categorySlug = e.target.dataset.slug

        const response = await (new Category()).delete(categorySlug)
        if (response.success) {
            console.log('LOL')
        }
    }

</script>

<div>
    <Sidebar/>
    <div class="page-container">
        <Navbar/>
        <main class="main-content background-grey-light">
            <div id="mainContent">
                <div class="container-fluid">
                    <h4 class="color-grey-bold mt-10 mb-30">Yours categories</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="background-white bd border-radius-3px p-20 mb-20">
                                <h4 class="color-grey-bold mb-20">Categories</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut cum impedit nesciunt
                                    saepe vel? Consectetur consequuntur, dolore enim ipsam iure, magni natus, non
                                    nostrum praesentium quod ratione temporibus tenetur voluptate?</p>
                                <table class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Content</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {#each categories as category, i}
                                        <tr>
                                            <td>{i + 1}</td>
                                            <td>{category.name}</td>
                                            <td>{category.slug}</td>
                                            <td>{category.content}</td>
                                            <td>{category.created_at}</td>
                                            <td>
                                                <Link to={"/admin/category/edit/" + category.slug}
                                                      class="btn btn-sm btn-secondary">Edit
                                                </Link>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        on:click|preventDefault={handleDelete}
                                                        data-slug={category.slug}>Delete
                                                </button>
                                            </td>
                                        </tr>
                                    {/each}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>