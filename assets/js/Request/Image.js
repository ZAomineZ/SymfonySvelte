import {Fetch} from "../utils/ApiFetch";

/**
 * @property {Fetch} fetch
 */
export class Image
{
    constructor() {
        this.fetch = new Fetch()
    }

    /**
     * Return the request API GET for recuperate all images
     *
     * @returns {Array}
     */
    async getImages()
    {
        const request = await this.fetch.response('/api/admin/images', 'GET')
        if (request.success) {
            return request.data.images
        }
        return []
    }

    /**
     * Return the request API POST for create a new Image
     *
     * @param {FormData} formData
     * @returns {Promise<void>}
     */
    async create(formData)
    {
        return this.fetch.response('/api/admin/create', 'POST', formData)
    }

    /**
     * Return the request API GET for recuperate to image entity current
     *
     * @param {string} slug
     * @returns {Promise<void>}
     */
    async edit(slug)
    {
        return this.fetch.response('/api/admin/edit/' + slug, 'GET')
    }

    /**
     * Return the request API POST for update to image entity current
     *
     * @param {string} slug
     * @param {FormData} formData
     * @returns {Promise<void>}
     */
    async update(slug, formData)
    {
        return this.fetch.response('/api/admin/update/' + slug, 'POST', formData)
    }

    /**
     * Return the request API GET for delete to image entity current
     *
     * @param {string} slug
     * @returns {Promise<*|null>}
     */
    async delete(slug)
    {
        return this.fetch.response('/api/admin/delete/' + slug, 'GET')
    }
}