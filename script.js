const load_view = (view, args = {}) => {
  fetch(
    '/wp-admin/admin-ajax.php?action=load_view&view=' +
      view +
      '&' +
      new URLSearchParams(args),
    { method: 'GET' }
  )
    .then((response) => response.json())
    .then(
      (response) => (document.getElementById('app').innerHTML = response.data)
    )
}

const login = () => {
  event.preventDefault()

  const form = document.getElementById('login-form')
  const errorWrapper = document.getElementById('response-error')
  const email = form.querySelector('#email')
  const nonce = form.querySelector('#security').value

  const password = form.querySelector('#password')

  errorWrapper.classList.add('d-none')

  const requestData = new FormData()
  requestData.append('email', email.value)
  requestData.append('password', password.value)
  requestData.append('nonce', nonce)

  fetch('/wp-admin/admin-ajax.php?action=login', {
    method: 'POST',
    body: requestData,
  })
    .then(async (response) => {
      if (response.status !== 200) {
        errorWrapper.classList.remove('d-none')
        const parsedResponse = await response.json()
        errorWrapper.innerHTML = parsedResponse.data
        return
      }

      load_view('dashboard')
    })
    .catch((error) => {
      console.log(error)
      errorWrapper.classList.remove('d-none')
      errorWrapper.innerHTML = error
    })
}

function deletePost(event, postId) {
  event.preventDefault()
  const errorWrapper = document.getElementById('response-error')
  errorWrapper.classList.add('d-none')

  fetch('/wp-admin/admin-ajax.php?action=post_delete&post=' + postId, {
    method: 'GET',
  })
    .then(async (response) => {
      if (response.status !== 200) {
        errorWrapper.classList.remove('d-none')

        errorWrapper.innerHTML = await response.text()
        return
      }

      load_view('dashboard')
    })
    .catch((error) => {
      console.log(error)
      errorWrapper.classList.remove('d-none')
      errorWrapper.innerHTML = error
    })
}

const logout = () => {
  event.preventDefault()

  fetch('/wp-admin/admin-ajax.php?action=logout', { method: 'GET' })
    .then(async (response) => {
      if (response.status !== 200) {
        return
      }

      load_view('login')
    })
    .catch((error) => {
      console.log(error)
    })
}

const insertPost = () => {
  event.preventDefault()

  const form = document.getElementById('edit-form')
  const title = form.querySelector('#title')
  const content = form.querySelector('#content')

  const requestData = new FormData()
  requestData.append('title', title.value)
  requestData.append('content', content.value)

  const errorWrapper = document.getElementById('response-error')
  errorWrapper.classList.add('d-none')

  fetch('/wp-admin/admin-ajax.php?action=post_insert', {
    method: 'POST',
    body: requestData,
  })
    .then(async (response) => {
      if (response.status !== 200) {
        errorWrapper.classList.remove('d-none')
        const parsedResponse = await response.json()
        errorWrapper.innerHTML = parsedResponse.data
        return
      }
      load_view('dashboard')
    })
    .catch((error) => {
      console.log(error)
      errorWrapper.classList.remove('d-none')
      errorWrapper.innerHTML = error
    })
}
