<script>
    // STATE
    let tags = ['t', 't', 't']
</script>

<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Slug</th>
        <th scope="col">Created</th>
    </tr>
    </thead>
    <tbody>
    {#each tags as tag}
        <tr>
            <th scope="row">1</th>
            <td>Tag Name</td>
            <td>Tag Slug</td>
            <td>Tag Created</td>
        </tr>
    {/each}
    </tbody>
</table>