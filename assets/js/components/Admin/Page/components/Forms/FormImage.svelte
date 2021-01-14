<script>
    // PROPS
    export let handleValue = () => {
    }
    export let image = null
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
                    <input class="form-control" id="file" type="file" placeholder="Your image..."
                           on:input={handleValue}>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>