<script>
    // COMPONENTS SVELTE
    // LIB APP
    import {Tag} from "../../../../Request/Tag";
    // COMPONENTS HTML
    import Sidebar from "../../Layout/Sidebar.svelte";
    import Navbar from "../../Layout/Navbar.svelte";
    import FormTag from "../components/Forms/FormTag.svelte";

    // STATE FORM
    let data = {
        name: null,
        slug: null
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
            name: data && data.name,
            slug: data && data.slug
        }
        // Set properties for formData JSON
        let formData = new FormData()
        formData.append('body', JSON.stringify(data_body))

        const request = (new Tag()).create(formData)
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
                    <h4 class="color-grey-bold mt-10 mb-30">Create your tag</h4>
                    <FormTag handleValue={handleValue} on:submit={handleSubmit}/>
                </div>
            </div>
        </main>
    </div>
</div>