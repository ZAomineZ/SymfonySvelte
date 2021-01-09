<script>
    import {Route, Router} from "svelte-routing"
    import Home from "./Page/Home.svelte";
    import AdminPage from "./Admin/Page/AdminPage.svelte";
</script>

<Router>
    <Route path="/">
        <Home/>
    </Route>
    <Route path="/admin">
        <AdminPage/>
    </Route>
</Router>
