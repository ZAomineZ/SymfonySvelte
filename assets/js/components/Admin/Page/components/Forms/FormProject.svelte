<script>
    // COMPONENTS SVELTE
    import Tags from "svelte-tags-input";
    // COMPONENTS HTML
    import ModalImages from "./modal/ModalImages.svelte";

    // PROPS
    export let handleInputValue = () => {
    }
    export let project = null
    export let categories = []
    export let tags = []
    export let tagsProject = []

    // STATE
    let activeModalImages = false

    // METHOD
    function handleChooseImages() {
        activeModalImages = !activeModalImages
    }
</script>

<div id="create-project-form">
    <div class="background-white p-20 bd">
        <h6 class="color-grey-bold">Form project</h6>
        <div class="mt-30">
            <form method="post" on:submit|preventDefault>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input class="form-control" id="title" name="title" on:input={handleInputValue}
                                   placeholder="Your title..."
                                   type="text" value={project && project.title}>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input class="form-control" id="slug" name="slug" on:input={handleInputValue}
                                   placeholder="Your slug..."
                                   type="text" value={project && project.slug}>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id"
                                    on:input={handleInputValue}>
                                <option value="">Choose your category</option>
                                {#each categories as category}
                                    <option value={category.slug}
                                            selected={project && category.slug === project.category}>{category.name}</option>
                                {/each}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <Tags name="tags" id="tags" placeholder="Yours tags..." autoComplete={tags} on:tags
                                  tags={tagsProject}/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" cols="30" id="content" name="content"
                              on:input={handleInputValue}
                              placeholder="Your content..." rows="10">{project && project.content}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-2">Validate ?</div>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <label class="form-check-label" for="validate">
                                        <input checked={project && project.validate} class="form-check-input"
                                               id="validate"
                                               name="validate"
                                               on:input={handleInputValue} type="checkbox">
                                        Validate
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-sm btn-outline-secondary" on:click|preventDefault={handleChooseImages}>Choose
                        images
                    </button>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>
    <ModalImages active={activeModalImages}/>
</div>