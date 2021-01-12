<script>
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    // LIB APP
    import {Tag} from "../../../../Request/Tag";
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    import FormTag from "../components/Forms/FormTag.svelte";

    // PROPS
    export let slug

    // STATE
    let tag = null

    // STATE FORM
    let data = {
        name: null,
        slug: null
    }

    onMount(async () => {
        const response = await (new Tag()).edit(slug)
        if (response.success) {
            tag = response.data.tag
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
            name: data && data.name !== null ? data.name : tag.name,
            slug: data && data.slug !== null ? data.slug : tag.slug
        }
        // Set properties for formData JSON
        let formData = new FormData()
        formData.append('body', JSON.stringify(data_body))

        const request = await (new Tag()).update(slug, formData)
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
                    <h4 class="color-grey-bold mt-10 mb-30">Edit your tag "{tag && tag.name}"</h4>
                    <FormTag handleValue={handleValue} on:submit={handleSubmit} tag={tag}/>
                </div>
            </div>
        </main>
    </div>
</div>