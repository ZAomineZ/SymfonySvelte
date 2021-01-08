<script>
    import {Route, Router} from "svelte-routing"
    import Home from "./Page/Home.svelte";
</script>

<Router>
    <Route path="/">
        <Home/>
    </Route>
</Router>
