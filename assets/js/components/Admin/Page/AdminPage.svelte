<script>
    import Sidebar from "../Layout/Sidebar.svelte";
    import Navbar from "../Layout/Navbar.svelte";
    import DashboardDataItems from "./dashboard/DashboardDataItems.svelte";
    import BarData from "./dashboard/BarData.svelte";
</script>

<div>
    <Sidebar/>
    <div class="page-container">
        <Navbar />
        <main class="main-content background-grey-light">
            <div id="mainContent">
                <div class="row gap-20 position-relative">
                    <div class="w-100" style="position: absolute; left: 0; top: 0">
                        <div class="row gap-20">
                            <DashboardDataItems/>
                        </div>
                    </div>
                    <div class="col-12">
                        <BarData/>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>