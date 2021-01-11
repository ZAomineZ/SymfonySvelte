<script>
    // PROPS
    export let handleValue = () => {
    }
    export let category = null
</script>

<div id="create-project-form">
    <div class="background-white p-20 bd">
        <h6 class="color-grey-bold">Form category</h6>
        <div class="mt-30">
            <form method="post" on:submit|preventDefault>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" on:input={handleValue}
                           placeholder="Your name..."
                           type="text" value={category && category.name}>
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input class="form-control" id="slug" name="slug" on:input={handleValue}
                           placeholder="Your slug..."
                           type="text" value={category && category.slug}>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" cols="30" id="content" name="content"
                              on:input={handleValue}
                              placeholder="Your content..." rows="10">{category && category.content}</textarea>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>