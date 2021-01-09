<script>
    // LIB ROUTES
    import {Route, Router} from "svelte-routing"
    // COMPONENTS FRONT
    import Home from "./Page/Home.svelte";

    // COMPONENTS ADMIN
    import AdminPage from "./Admin/Page/AdminPage.svelte";
    import ProjectsAdmin from "./Admin/Page/Project/ProjectsAdmin.svelte";
    import ProjectCreate from "./Admin/Page/Project/ProjectCreate.svelte";
</script>

<Router>
    <Route path="/">
        <Home/>
    </Route>
    <Route path="/admin">
        <AdminPage/>
    </Route>
    <Route path="/admin/projects">
        <ProjectsAdmin/>
    </Route>
    <Route path="/admin/project/create">
        <ProjectCreate />
    </Route>
</Router>
