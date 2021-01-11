<script>
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    // LIB APP
    import {Category} from "../../../../Request/Category";
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    import FormCategory from "../components/Forms/FormCategory.svelte";

    // PROPS
    export let slug

    // STATE
    let category = null

    // STATE FORM
    let data = {
        name: null,
        slug: null,
        content: null
    }

    onMount(async () => {
        const response = await (new Category()).edit(slug)
        if (response.success) {
            category = response.data.category
        }
    })

    /**
     * Handle change value input to form
     *
     * @param {Event} e
     **/
    function handleValue(e) {
        data[e.target.name] = e.target.value
    }

    /**
     * @param {Event} event
     */
    async function handleSubmit(event) {
        const data_body = {
            name: data && data.name !== null ? data.name : category.name,
            slug: data && data.slug !== null ? data.slug : category.slug,
            content: data && data.content !== null ? data.content : category.content
        }
        // Set properties for formData JSON
        let formData = new FormData()
        formData.append('body', JSON.stringify(data_body))

        const request = (new Category()).update(formData, slug)
        if (request.success) {
            console.log('Nice !')
        }
    }

</script>

<div>
    <Sidebar/>
    <div class="page-container">
        <Navbar/>
        <main class="main-content background-grey-light">
            <div id="mainContent">
                <div class="container-fluid">
                    <h4 class="color-grey-bold mt-10 mb-30">Create your category</h4>
                    <FormCategory handleValue={handleValue} on:submit={handleSubmit} category={category}/>
                </div>
            </div>
        </main>
    </div>
</div>