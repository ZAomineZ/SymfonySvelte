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
    import {Tag} from "../../../../Request/Tag";

    // PROPS
    export let slug;

    // STATE
    let project = null
    let categories = []
    let tags = []
    let tagsProject = []

    // STATE FORM
    let data = {
        title: null,
        slug: null,
        content: null,
        category: null,
        tags: null,
        validate: null
    }

    onMount(async () => {
        // Get project current
        const response = await (new Project()).edit(slug)
        if (response.success) {
            project = response.data.project
            tagsProject = project.tags
        }
        // Get all categories and tags
        categories = await (new Category()).getCategories()
        tags = await (new Tag()).getTags()
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
     * Handle change value input tags to form
     *
     * @param {Event} e
     **/
    function handleTagsValue(e) {
        data['tags'] = e.detail.tags
    }

    /**
     * @param {Event} event
     */
    async function handleSubmit(event) {
        const data_body = {
            title: data.title === null ? project.title : data.title,
            slug: data.slug === null ? project.slug : data.slug,
            content: data.content === null ? project.content : data.content,
            category: data.category === null ? project.category : data.category,
            tags: data.tags === null ? project.tags.join(', ') : data.tags.join(', '),
            validate: data.validate === null ? project.validate : data.validate === 'on'
        }

        let formData = new FormData()
        formData.append('body', JSON.stringify(data_body))

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
                            handleInputValue={handleValue} project={project} categories={categories}
                            on:submit={handleSubmit} on:tags={handleTagsValue} tags={tags.map(tag => tag.name)}
                            tagsProject={tagsProject}/>
                </div>
            </div>
        </main>
    </div>
</div>