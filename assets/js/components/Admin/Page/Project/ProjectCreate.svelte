<script>
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    // LIB APP
    import {Project} from "../../../../Request/Project";
    import {Category} from "../../../../Request/Category";
    import {Tag} from "../../../../Request/Tag";
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    import FormProject from "../components/Forms/FormProject.svelte";

    // STATE
    let categories = []
    let tags = []

    // STATE FORM
    let data = {
        title: null,
        slug: null,
        content: null,
        category: null,
        tags: null,
        validate: null
    }
    // STATE
    let fetch = null

    onMount(async () => {
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
            title: data.title,
            slug: data.slug,
            content: data.content,
            category: data.category,
            tags: data.tags ? data.tags.join(', ') : '',
            validate: data.validate === 'on'
        }

        let formData = new FormData()
        formData.append('body', JSON.stringify(data_body))

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
                            handleInputValue={handleValue} categories={categories} on:submit={handleSubmit}
                            tags={tags.map(tag => tag.name)} on:tags={handleTagsValue}/>
                </div>
            </div>
        </main>
    </div>
</div>