<script>
    // COMPONENTS HTML
    import Sidebar from "../Layout/Sidebar.svelte";
    import Navbar from "../Layout/Navbar.svelte";
    import DashboardDataItems from "./dashboard/DashboardDataItems.svelte";
    import BarData from "./dashboard/BarData.svelte";
    // COMPONENTS TABLE
    import TableProjects from "./dashboard/table/TableProjects.svelte"
    import TableCategories from "./dashboard/table/TableCategories.svelte"
    import TableTags from "./dashboard/table/TableTags.svelte"
</script>

<div>
    <Sidebar/>
    <div class="page-container">
        <Navbar/>
        <main class="main-content background-grey-light">
            <div id="mainContent">
                <div class="row gap-20 position-relative">
                    <div class="w-100">
                        <div class="row gap-20">
                            <DashboardDataItems/>
                        </div>
                    </div>
                    <div class="col-12">
                        <BarData/>
                    </div>
                    <div class="col-12">
                        <div class="bgc-white bd border-radius-3px p-20 mb-20">
                            <h4 class="color-grey-bold mb-20">Table Projects</h4>
                            <TableProjects/>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6">
                                <TableCategories/>
                            </div>
                            <div class="col-md-6">
                                <TableTags/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>