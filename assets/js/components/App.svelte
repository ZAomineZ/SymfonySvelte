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
</script>

<Router>
    <Route path="/">
        <Home/>
    </Route>
    <Route path="/admin">
        <AdminPage/>
    </Route>
    <!-- PROJECTS ADMIN ROUTES -->
    <Route path="/admin/projects">
        <ProjectsAdmin/>
    </Route>
    <Route path="/admin/project/create">
        <ProjectCreate />
    </Route>
    <Route path="/admin/project/edit/:slug" let:params>
        <ProjectEdit slug={params.slug}/>
    </Route>
    <!-- CATEGORIES ADMIN ROUTES -->
    <Route path="/admin/categories">
        <CategoriesAdmin/>
    </Route>
</Router>
