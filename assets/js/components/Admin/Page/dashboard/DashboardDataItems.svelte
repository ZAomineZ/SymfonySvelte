<script>
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    // COMPONENTS LIB
    import sparkline from "@fnando/sparkline";

    onMount(() => {
        let sparkline_element = document.querySelectorAll('.sparkline')
        if (sparkline_element) {
            sparkline_element.forEach((element) => {
                sparkline(element, [1, 5, 6, 9, 10, 2, 13])
            })
        }
    })
</script>

<div class="col-md-3">
    <div class="layers bd bgc-white p-20">
        <div class="layer w-100 mb-10">
            <h6 class="lh-1">Total Projects</h6>
        </div>
        <div class="layer w-100">
            <div class="blocks flex-nowrap">
                <div class="block block-greed">
                    <svg class="sparkline" width="100" height="30" stroke-width="3"></svg>
                </div>
                <div class="block">
                    <span class="color-green bcg-green border-radius-10em lh-0 fw-600 px-15 py-15 vertical-align-middle d-inline-block">
                        +10%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="layers bd bgc-white p-20">
        <div class="layer w-100 mb-10">
            <h6 class="lh-1">Total Categories</h6>
        </div>
        <div class="layer w-100">
            <div class="blocks flex-nowrap">
                <div class="block block-greed">
                    <svg class="sparkline" width="100" height="30" stroke-width="3"></svg>
                </div>
                <div class="block">
                    <span class="color-green bcg-green border-radius-10em lh-0 fw-600 px-15 py-15 vertical-align-middle d-inline-block">
                        +10%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="layers bd bgc-white p-20">
        <div class="layer w-100 mb-10">
            <h6 class="lh-1">Total Tags</h6>
        </div>
        <div class="layer w-100">
            <div class="blocks flex-nowrap">
                <div class="block block-greed">
                    <svg class="sparkline" width="100" height="30" stroke-width="3"></svg>
                </div>
                <div class="block">
                    <span class="color-green bcg-green border-radius-10em lh-0 fw-600 px-15 py-15 vertical-align-middle d-inline-block">
                        +10%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="layers bd bgc-white p-20">
        <div class="layer w-100 mb-10">
            <h6 class="lh-1">Total Images</h6>
        </div>
        <div class="layer w-100">
            <div class="blocks flex-nowrap">
                <div class="block block-greed">
                    <svg class="sparkline" width="100" height="30" stroke-width="3"></svg>
                </div>
                <div class="block">
                    <span class="color-green bcg-green border-radius-10em lh-0 fw-600 px-15 py-15 vertical-align-middle d-inline-block">
                        +10%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
