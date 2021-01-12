<script>
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    // LIB APP
    import {Project} from "../../../../Request/Project";
    import {Category} from "../../../../Request/Category";
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    import FormProject from "../components/Forms/FormProject.svelte";

    // STATE
    let categories = []

    // STATE FORM
    let title = null
    let slug = null
    let content = null
    let category = null
    let validate = null
    // STATE
    let fetch = null

    onMount(async () => {
        const request_categories = await (new Category()).getCategories()
        categories = request_categories.data.categories
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
    function handleCategoryValue(event) {
        category = event.target.value
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
        const data = {title, slug, content, category, validate: validate === 'on'}

        let formData = new FormData()
        formData.append('body', JSON.stringify(data))

        const request = (new Project()).create(formData)
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
                            callInput={{handleTitleValue, handleSlugValue, handleContentValue, handleCategoryValue, handleValidateValue}}
                            categories={categories} on:submit={handleSubmit}/>
                </div>
            </div>
        </main>
    </div>
</div>