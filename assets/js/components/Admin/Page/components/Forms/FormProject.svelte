<div id="create-project-form">
    <div class="background-white p-20 bd">
        <h6 class="color-grey-bold">Form project</h6>
        <div class="mt-30">
            <form method="post">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="form-control" id="title" placeholder="Your title..." type="text">
                </div>
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input class="form-control" id="slug" placeholder="Your slug..." type="text">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" cols="30" id="content" name="content" placeholder="Your content..."
                              rows="10"></textarea>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control" id="category_id" name="category_id">
                        <option value="">Choose your category</option>
                    </select>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">Validate ?</div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <label for="validate" class="form-check-label">
                                <input type="checkbox" class="form-check-input">
                                Validate
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>