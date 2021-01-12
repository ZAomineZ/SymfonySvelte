import {Fetch} from "../utils/ApiFetch";

export class Category
{

    constructor() {
        this.fetch = new Fetch()
    }

    /**
     * @returns {Array}
     */
    async getCategories()
    {
        const response = await this.fetch.response('/api/admin/categories', 'GET')
        if (response.success) {
            return response.data.categories
        }
        return []
    }

    /**
     * Return the request API post for create a category
     *
     * @param {FormData} formData
     * @returns {Promise<*|null>}
     */
    async create(formData)
    {
        return this.fetch.response('/api/admin/category/create', 'POST', formData)
    }

    /**
     * Return the request API get for edit a category
     *
     * @param {string} slug
     * @returns {Promise<*|null>}
     */
    async edit(slug)
    {
        return this.fetch.response('/api/admin/category/edit/' + slug, 'GET')
    }

    /**
     * Return the request API post for update a category
     *
     * @param {FormData} formData
     * @param {string} slug
     * @returns {Promise<*|null>}
     */
    async update(formData, slug)
    {
        return this.fetch.response('/api/admin/category/update/' + slug, 'POST', formData)
    }

    /**
     * Return the request API post for update the category current
     *
     * @param {string} slug
     * @returns {Promise<*|null>}
     */
    async delete(slug)
    {
        return this.fetch.response('/api/admin/category/delete/' + slug, 'DELETE')
    }

}