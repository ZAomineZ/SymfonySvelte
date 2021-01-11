<script>
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    import {Link} from "svelte-routing"
    // LIBS APP
    import {Fetch} from "../../../../utils/ApiFetch";

    // STATE
    let projects = []

    onMount(async () => {
        const response = await (new Fetch()).response('/api/admin/projects', 'GET')
        if (response.success) {
            projects = response.data.projects
        }
        console.log(projects)
    })
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
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {#each projects as project, i}
                                        <tr>
                                            <td>{i + 1}</td>
                                            <td>{project.title}</td>
                                            <td>{project.slug}</td>
                                            <td>{project.content}</td>
                                            <td>{project.category}</td>
                                            <td>{project.validate ? 'YES' : 'NO'}</td>
                                            <td>{project.created_at}</td>
                                            <td>
                                                <Link to={"/admin/project/edit/" + project.slug}
                                                      class="btn btn-sm btn-secondary">Edit
                                                </Link>
                                                <a href="/" class="btn btn-sm btn-danger">Delete</a>
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