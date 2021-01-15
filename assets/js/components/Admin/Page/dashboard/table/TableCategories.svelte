<script>
    // STATE
    let categories = ['c', 'c', 'c']
</script>

<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Slug</th>
        <th scope="col">Content</th>
        <th scope="col">Created</th>
    </tr>
    </thead>
    <tbody>
    {#each categories as category}
        <tr>
            <th scope="row">1</th>
            <td>Category Name</td>
            <td>Category Slug</td>
            <td>Category Content...</td>
            <td>Category Created</td>
        </tr>
    {/each}
    </tbody>
</table>