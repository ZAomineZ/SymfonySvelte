<script>
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    import {Link, navigate} from "svelte-routing"
    // LIBS APP
    import {Image} from "../../../../Request/Image.js";
    import Alert from "../components/Helper/Alert.svelte";

    // PROPS
    export let message

    // STATE
    let images = []

    onMount(async () => {
        images = await (new Image()).getImages()
    })

    /**
     * Method who init a event for delete to image current
     *
     * @param {Event} e
     */
    async function handleDelete(e) {
        const tagSlug = e.target.dataset.slug

        const response = await (new Image()).delete(tagSlug)
        if (response.success) {
            navigate('/admin/images', {
                state: {
                    message: response.message
                }
            })
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
                    <h4 class="color-grey-bold mt-10 mb-30">Yours images</h4>
                    <Link to="/admin/image/create" class="btn btn-sm btn-primary">Create image</Link>
                    {#if message}
                        <Alert message={message}/>
                    {/if}
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="background-white bd border-radius-3px p-20 mb-20">
                                <h4 class="color-grey-bold mb-20">Images</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut cum impedit nesciunt
                                    saepe vel? Consectetur consequuntur, dolore enim ipsam iure, magni natus, non
                                    nostrum praesentium quod ratione temporibus tenetur voluptate?</p>
                                <table class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Path</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {#each images as image, i}
                                        <tr>
                                            <td>{i + 1}</td>
                                            <td>{image.title}</td>
                                            <td>{image.slug}</td>
                                            <td>{image.path}</td>
                                            <td>{image.created_at}</td>
                                            <td>
                                                <Link to={"/admin/image/edit/" + image.slug}
                                                      class="btn btn-sm btn-secondary">Edit
                                                </Link>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                        on:click|preventDefault={handleDelete}
                                                        data-slug={image.slug}>Delete
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