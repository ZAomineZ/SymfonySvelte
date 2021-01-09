<script>
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    // LIB APP
    import {Fetch} from "../../../../utils/ApiFetch";
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    import FormProject from "../components/Forms/FormProject.svelte";

    // STATE FORM
    let title = null
    let slug = null
    let content = null
    let category_id = null
    let validate = null
    // STATE
    let fetch = null

    onMount(() => {
        fetch = new Fetch()
    })

    /**
     * Change value field title
     *
     * @param {Event} event
     **/
    function handleTitleValue(event) {
        title = event.target.value
    }

    /**
     * Change value field slug
     *
     * @param {Event} event
     **/
    function handleSlugValue(event) {
        slug = event.target.value
    }

    /**
     * Change value field content
     *
     * @param {Event} event
     **/
    function handleContentValue(event) {
        content = event.target.value
    }

    /**
     * Change value field category_id
     *
     * @param {Event} event
     **/
    function handleCategoryIDValue(event) {
        category_id = event.target.value
    }

    /**
     * Change value field validate
     *
     * @param {Event} event
     **/
    function handleValidateValue(event) {
        validate = event.target.value
    }

    /**
     * @param {Event} event
     */
    async function handleSubmit(event) {
        const data = {title, slug, content, category_id, validate: validate === 'on'}

        let formData = new FormData()
        formData.append('body', JSON.stringify(data))

        const request = await fetch.response('/api/admin/project/create', 'POST', formData)
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
                    <h4 class="color-grey-bold mt-10 mb-30">Create your project</h4>
                    <FormProject
                            callInput={{handleTitleValue, handleSlugValue, handleContentValue, handleCategoryIDValue, handleValidateValue}}
                            on:submit={handleSubmit}/>
                </div>
            </div>
        </main>
    </div>
</div>