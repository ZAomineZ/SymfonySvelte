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

    // PROPS
    export let slug;

    // STATE
    let project = null
    let categories = []

    // STATE FORM
    let title = null
    let _slug = null
    let content = null
    let category = null
    let validate = null

    onMount(async () => {
        // Get project current
        const response = await (new Project()).edit(slug)
        if (response.success) {
            project = response.data.project
        }
        // Get all categories
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
        _slug = event.target.value
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
     * Change value field category
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
        const data = {
            title: title === null ? project.title : title,
            slug: _slug === null ? project.slug : _slug,
            content: content === null ? project.content : content,
            category: category === null ? project.category : category,
            validate: validate === null ? project.validate : validate === 'on'
        }

        let formData = new FormData()
        formData.append('body', JSON.stringify(data))

        const request = await (new Project()).update(slug, formData)
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
                    <h4 class="color-grey-bold mt-10 mb-30">Edit your project "{project && project.title}"</h4>
                    <FormProject
                            callInput={{handleTitleValue, handleSlugValue, handleContentValue, handleCategoryValue, handleValidateValue}}
                            on:submit={handleSubmit} project={project} categories={categories}/>
                </div>
            </div>
        </main>
    </div>
</div>