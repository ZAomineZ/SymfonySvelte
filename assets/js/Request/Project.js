import {Fetch} from "../utils/ApiFetch";
import {slug} from "../components/Admin/Page/Project/ProjectEdit.svelte";

/**
 * @property {Fetch} fetch
 */
export class Project
{
    constructor() {
        this.fetch = new Fetch()
    }

    /**
     * Return the request API get who recuperate all project validate
     *
     * @returns {Promise<*|null>}
     */
    async getProjects()
    {
        return this.fetch.response('/api/admin/projects', 'GET')
    }

    /**
     * Return the request API POST who create a new project
     *
     * @param {FormData} formData
     * @returns {Promise<*|null>}
     */
    async create(formData)
    {
        return this.fetch.response('/api/admin/project/create', 'POST', formData)
    }

    /**
     * Return the request API GET who recuperate the project current
     *
     * @param {string} slug
     * @returns {Promise<*|null>}
     */
    async edit(slug)
    {
        return this.fetch.response('/api/admin/project/edit/' + slug, 'GET')
    }

    /**
     * Return the request API POST who update the project current
     *
     * @param {string} slug
     * @param {FormData} formData
     * @returns {Promise<*|null>}
     */
    async update(slug, formData)
    {
        return this.fetch.response('/api/admin/project/update/' + slug, 'POST', formData)
    }


    /**
     * Return the request API post for update the project current
     *
     * @param slug
     * @returns {Promise<*|null>}
     */
    async delete(slug)
    {
        return this.fetch.response('/api/admin/project/delete/' + slug, 'DELETE')
    }
}