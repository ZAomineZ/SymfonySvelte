<script>
    // PROPS
    export let handleValue = () => {
    }
    export let handleFile = () => {
    }
    export let image = null
    export let files
</script>

<div id="create-project-form">
    <div class="background-white p-20 bd">
        <h6 class="color-grey-bold">Form image</h6>
        <div class="mt-30">
            <form method="post" on:submit|preventDefault>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control" id="title" name="title" on:input={handleValue}
                           placeholder="Your title..."
                           type="text" value={image && image.title}>
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input class="form-control" id="slug" name="slug" on:input={handleValue}
                           placeholder="Your slug..."
                           type="text" value={image && image.slug}>
                </div>
                <div class="form-group">
                    <label for="file">Image</label>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file" bind:files
                                   aria-describedby="file">
                            <label class="custom-file-label" for="file">Choose file</label>
                        </div>
                    </div>
                </div>
                {#if image}
                    <div id="image" class="mb-4">
                        <img src={'/uploads/images/' + image.path} alt="image path to '{image.title}'" width="350"
                             height="300">
                    </div>
                {/if}
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>