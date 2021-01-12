<script>
    // PROPS
    export let handleValue = () => {
    }
    export let tag = null
</script>

<div id="create-project-form">
    <div class="background-white p-20 bd">
        <h6 class="color-grey-bold">Form tag</h6>
        <div class="mt-30">
            <form method="post" on:submit|preventDefault>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" on:input={handleValue}
                           placeholder="Your name..."
                           type="text" value={tag && tag.name}>
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input class="form-control" id="slug" name="slug" on:input={handleValue}
                           placeholder="Your slug..."
                           type="text" value={tag && tag.slug}>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>