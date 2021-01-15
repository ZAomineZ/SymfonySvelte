<script>
    // STATE
    let projects = ['p', 'p', 'p']
</script>

<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Slug</th>
        <th scope="col">Content</th>
        <th scope="col">Category</th>
        <th scope="col">Tags</th>
        <th scope="col">Validate</th>
        <th scope="col">Created</th>
    </tr>
    </thead>
    <tbody>
    {#each projects as project}
        <tr>
            <th scope="row">1</th>
            <td>Project Title</td>
            <td>Project Slug</td>
            <td>Project Content...</td>
            <td>Project Category</td>
            <td>Project Tags</td>
            <td>Project Validate</td>
            <td>Project Created</td>
        </tr>
    {/each}
    </tbody>
</table>