<script>
    // LIB ROUTES
    import {Route, Router} from "svelte-routing"
    // COMPONENTS FRONT
    import Home from "./Page/Home.svelte";

    // COMPONENTS ADMIN
    import AdminPage from "./Admin/Page/AdminPage.svelte";
    import ProjectsAdmin from "./Admin/Page/Project/ProjectsAdmin.svelte";
    import ProjectCreate from "./Admin/Page/Project/ProjectCreate.svelte";
    import ProjectEdit from "./Admin/Page/Project/ProjectEdit.svelte";

    import CategoriesAdmin from "./Admin/Page/Category/CategoriesAdmin.svelte"
    import CategoryCreate from "./Admin/Page/Category/CategoryCreate.svelte"
    import CategoryEdit from "./Admin/Page/Project/CategoryEdit.svelte";

    import TagsAdmin from "./Admin/Page/Tag/TagsAdmin.svelte"
    import TagCreate from "./Admin/Page/Tag/TagCreate.svelte"
    import TagEdit from "./Admin/Page/Tag/TagEdit.svelte"

    import ImagesAdmin from "./Admin/Page/Image/ImagesAdmin.svelte"
    import ImageCreate from "./Admin/Page/Image/ImageCreate.svelte"
</script>

<Router>
    <Route path="/">
        <Home/>
    </Route>
    <Route path="/admin">
        <AdminPage/>
    </Route>
    <!-- PROJECTS ADMIN ROUTES -->
    <Route path="/admin/projects" let:location>
        <ProjectsAdmin message={location.state && location.state.message ? location.state.message : null} {location}/>
    </Route>
    <Route path="/admin/project/create">
        <ProjectCreate/>
    </Route>
    <Route path="/admin/project/edit/:slug" let:params>
        <ProjectEdit slug={params.slug}/>
    </Route>
    <!-- CATEGORIES ADMIN ROUTES -->
    <Route path="/admin/categories">
        <CategoriesAdmin/>
    </Route>
    <Route path="/admin/category/create">
        <CategoryCreate/>
    </Route>
    <Route path="/admin/category/edit/:slug" let:params>
        <CategoryEdit slug={params.slug}/>
    </Route>
    <!-- TAGS ADMIN ROUTES -->
    <Route path="/admin/tags">
        <TagsAdmin/>
    </Route>
    <Route path="admin/tag/create">
        <TagCreate/>
    </Route>
    <Route path="/admin/tag/edit/:slug" let:params>
        <TagEdit slug={params.slug}/>
    </Route>
    <!-- IMAGES ADMIN ROUTES -->
    <Route path="/admin/images">
        <ImagesAdmin />
    </Route>
    <Route path="/admin/image/create">
        <ImageCreate />
    </Route>
</Router>
