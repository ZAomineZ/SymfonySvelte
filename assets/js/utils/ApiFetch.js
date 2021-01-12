// LIB
/*import localStorage from 'local-storage-json'
import Cookies from 'js-cookie'*/

export class Fetch {
    /**
     * @param {string} uri
     * @param {string} method
     * @param {FormData|string|null} formData
     * @param {boolean} isJson
     * @param {boolean} authorization_bearer
     * @returns {Promise<Response>}
     */
    async api(
        uri,
        method = 'POST',
        formData,
        isJson = false,
        authorization_bearer = false
    ) {
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
        }
        if (isJson) {
            headers['Content-Type'] = 'application/json'
        }
        if (authorization_bearer) {
            /*const data_token = localStorage.get('data_token') ? localStorage.get('data_token') : Cookies.get('data_token_cookie')
            if (data_token) headers['Authorization'] = `Bearer ${data_token.token_user ? data_token.token_user : null}`*/
        }

        const options = {method, headers}
        if (formData !== null) {
            options['body'] = formData
        }
        if (isJson) {
            options['credentials'] = 'include'
        }
        return fetch(uri, options)
    }

    /**
     * @param {string} uriFetch
     * @param {string} method
     * @param {FormData|string|null} formData
     * @param {boolean} isJson
     * @param {boolean} authorization_bearer
     * @returns {Promise<any>|null}
     */
    async response(
        uriFetch,
        method = 'POST',
        formData = null,
        isJson = false,
        authorization_bearer = false
    ) {
        const request = await this.api(uriFetch, method, formData, isJson, authorization_bearer)
        if (request.status === 200 || request.status === 301 || request.status === 302 || request.status === 401) {
            return await request.json()
        }
        return null
    }

    /**
     * @param {string} value
     * @return {FormData}
     */
    static FormDataCsrf(value) {
        let form = new FormData()
        form.append('authenticity_token', value)
        return form
    }
}