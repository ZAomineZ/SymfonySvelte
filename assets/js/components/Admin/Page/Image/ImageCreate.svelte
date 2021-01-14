<script>
    // COMPONENTS SVELTE
    import {navigate} from "svelte-routing";
    // LIB APP
    import {Image} from "../../../../Request/Image";
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    import FormImage from "../components/Forms/FormImage.svelte";

    // STATE FORM
    let data = {
        title: null,
        slug: null,
        file: null
    }

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
            title: data && data.title,
            slug: data && data.slug,
            file: data && data.file
        }
        // Set properties for formData JSON
        let formData = new FormData()
        formData.append('body', JSON.stringify(data_body))

        const request = await (new Image()).create(formData)
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
                    <h4 class="color-grey-bold mt-10 mb-30">Create your image</h4>
                    <FormImage handleValue={handleValue} on:submit={handleSubmit}/>
                </div>
            </div>
        </main>
    </div>
</div>