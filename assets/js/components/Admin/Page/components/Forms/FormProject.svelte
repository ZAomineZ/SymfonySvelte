<script>
    // PROPS
    export let callInput = {
        handleTitleValue: () => {
        },
        handleSlugValue: () => {
        },
        handleContentValue: () => {
        },
        handleCategoryValue: () => {
        },
        handleValidateValue: () => {
        }
    }
    export let project = null
    export let categories = []
</script>

<div id="create-project-form">
    <div class="background-white p-20 bd">
        <h6 class="color-grey-bold">Form project</h6>
        <div class="mt-30">
            <form method="post" on:submit|preventDefault>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control" id="title" name="title" on:input={callInput.handleTitleValue}
                           placeholder="Your title..."
                           type="text" value={project && project.title}>
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input class="form-control" id="slug" name="slug" on:input={callInput.handleSlugValue}
                           placeholder="Your slug..."
                           type="text" value={project && project.slug}>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" cols="30" id="content" name="content"
                              on:input={callInput.handleContentValue}
                              placeholder="Your content..." rows="10">{project && project.content}</textarea>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control" id="category_id" name="category_id"
                            on:input={callInput.handleCategoryValue}>
                        <option value="">Choose your category</option>
                        {#each categories as category}
                            <option value={category.slug} selected={category.slug === project.category}>{category.name}</option>
                        {/each}
                    </select>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">Validate ?</div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label class="form-check-label" for="validate">
                                <input checked={project && project.validate} class="form-check-input" id="validate"
                                       name="validate"
                                       on:input={callInput.handleValidateValue} type="checkbox">
                                Validate
                            </label>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
</div>