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
}