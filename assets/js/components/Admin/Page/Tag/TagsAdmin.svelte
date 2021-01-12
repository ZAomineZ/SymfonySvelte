<script>
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    import {Link} from "svelte-routing"
    // LIBS APP
    import {Tag} from "../../../../Request/Tag.js";

    // STATE
    let tags = []

    onMount(async () => {
        const response = await (new Tag()).getTags()
        if (response.success) {
            tags = response.data.tags
        }
    })

    /**
     * Method who init a event for delete to tag current
     *
     * @param {Event} e
     */
    async function handleDelete(e) {
        const tagSlug = e.target.dataset.slug

        const response = await (new Tag()).delete(tagSlug)
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
                    <h4 class="color-grey-bold mt-10 mb-30">Yours tags</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="background-white bd border-radius-3px p-20 mb-20">
                                <h4 class="color-grey-bold mb-20">Tags</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut cum impedit nesciunt
                                    saepe vel? Consectetur consequuntur, dolore enim ipsam iure, magni natus, non
                                    nostrum praesentium quod ratione temporibus tenetur voluptate?</p>
                                <table class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {#each tags as tag, i}
                                        <tr>
                                            <td>{i + 1}</td>
                                            <td>{tag.name}</td>
                                            <td>{tag.slug}</td>
                                            <td>{tag.created_at}</td>
                                            <td>
                                                <Link to={"/admin/tag/edit/" + tag.slug}
                                                      class="btn btn-sm btn-secondary">Edit
                                                </Link>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        on:click|preventDefault={handleDelete}
                                                        data-slug={tag.slug}>Delete
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