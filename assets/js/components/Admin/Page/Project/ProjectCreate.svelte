<script>
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    import FormProject from "../components/Forms/FormProject.svelte";
</script>

<div>
    <Sidebar/>
    <div class="page-container">
        <Navbar/>
        <main class="main-content background-grey-light">
            <div id="mainContent">
                <div class="container-fluid">
                    <h4 class="color-grey-bold mt-10 mb-30">Create your project</h4>
                    <FormProject />
                </div>
            </div>
        </main>
    </div>
</div>