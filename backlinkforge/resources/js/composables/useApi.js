import { router } from '@inertiajs/vue3'

export function useApi() {
  const apiCall = async (url, options = {}) => {
    const defaultOptions = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
    }

    const config = {
      ...defaultOptions,
      ...options,
      headers: {
        ...defaultOptions.headers,
        ...options.headers,
      },
    }

    try {
      const response = await fetch(url, config)
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      
      const data = await response.json()
      return { success: true, data }
    } catch (error) {
      console.error('API call failed:', error)
      return { success: false, error: error.message }
    }
  }

  const get = (url, params = {}) => {
    const queryString = new URLSearchParams(params).toString()
    const fullUrl = queryString ? `${url}?${queryString}` : url
    
    return apiCall(fullUrl, {
      method: 'GET',
    })
  }

  const post = (url, data = {}) => {
    return apiCall(url, {
      method: 'POST',
      body: JSON.stringify(data),
    })
  }

  const put = (url, data = {}) => {
    return apiCall(url, {
      method: 'PUT',
      body: JSON.stringify(data),
    })
  }

  const patch = (url, data = {}) => {
    return apiCall(url, {
      method: 'PATCH',
      body: JSON.stringify(data),
    })
  }

  const del = (url) => {
    return apiCall(url, {
      method: 'DELETE',
    })
  }

  const upload = (url, formData, onProgress = null) => {
    return new Promise((resolve, reject) => {
      const xhr = new XMLHttpRequest()
      
      if (onProgress) {
        xhr.upload.addEventListener('progress', onProgress)
      }
      
      xhr.addEventListener('load', () => {
        if (xhr.status >= 200 && xhr.status < 300) {
          try {
            const data = JSON.parse(xhr.responseText)
            resolve({ success: true, data })
          } catch (error) {
            resolve({ success: true, data: xhr.responseText })
          }
        } else {
          reject(new Error(`Upload failed with status: ${xhr.status}`))
        }
      })
      
      xhr.addEventListener('error', () => {
        reject(new Error('Upload failed'))
      })
      
      xhr.open('POST', url)
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
      xhr.send(formData)
    })
  }

  return {
    apiCall,
    get,
    post,
    put,
    patch,
    delete: del,
    upload,
  }
} 