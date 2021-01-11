import {Fetch} from "../utils/ApiFetch";

export class Category
{

    constructor() {
        this.fetch = new Fetch()
    }

    /**
     * @returns {Promise<*|null>}
     */
    async getCategories()
    {
        return this.fetch.response('/api/admin/categories', 'GET')
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

}