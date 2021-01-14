<script>
    // COMPONENTS SVELTE
    import {onMount} from "svelte";
    import {navigate} from "svelte-routing";
    // LIB APP
    import {Image} from "../../../../Request/Image";
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    import FormImage from "../components/Forms/FormImage.svelte";

    // PROPS
    export let slug

    // STATE
    let image = null

    // STATE FORM
    let data = {
        title: null,
        slug: null
    }
    let files

    onMount(async () => {
        const response = await (new Image()).edit(slug)
        if (response.success) {
            image = response.data.image
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
            name: data && data.name !== null ? data.name : image.name,
            slug: data && data.slug !== null ? data.slug : image.slug
        }
        // Set properties for formData JSON
        let formData = new FormData()
        formData.append('body', JSON.stringify(data_body))
        formData.append('file', files.length !== 0 ? files[0] : null)

        const request = await (new Image()).update(slug, formData)
        if (request.success) {
            navigate('/admin/images', {
                state: {
                    message: request.message
                }
            })
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
                    <h4 class="color-grey-bold mt-10 mb-30">Edit your image "{image && image.title}"</h4>
                    <FormImage handleValue={handleValue} on:submit={handleSubmit} image={image} bind:files/>
                </div>
            </div>
        </main>
    </div>
</div>