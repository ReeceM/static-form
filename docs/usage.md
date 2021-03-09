# Usage

As an overview, the usage of the current version is that you can use the packages middleware on controllers that you define, this will then use your controller to handle the request data.

## Create The Token
The first step is to generate your token, to do that you can use the console command:

```bash
php artisan static-form --refresh
```

This will generate your token, it will show you the plain text version only during that session.

The other way is to call the API endpoint to generate a new one. The API is secured via the Gate that is defined in the `App\Providers\StaticFormServiceProvider::class`

You can define any logic in there that would allow only authorized people to access the application.

To call the API endpoint, for now you can make a request to the endpoint through a custom UI and javascript code.

| Method      | Endpoint    | Description |
|:----------- | ----------- | ----------- |
| GET         | domain.tld/api/static-form/token | This will return a 200 status and the static-form package version if the toke is found |
| POST|PATCH  | domain.tld/api/static-form/token | A `POST` or `PATCH` request to this endpoint will create a new token |

---

The Laravel routes would be the following from `php artisan route:list`

| Domain | Method   | URI                              | Name                            | Action                                                                          | Middleware                                                |
|--------|----------|----------------------------------|---------------------------------|---------------------------------------------------------------------------------|-----------------------------------------------------------|
|        | PATCH    | static-form/api/token            | static-form.token.update        | ReeceM\StaticForm\Http\Controllers\Api\ManageStaticTokenController@update       | web                                                       |
|        |          |                                  |                                 |                                                                                 | ReeceM\StaticForm\Http\Middleware\Authorize               |
|        | POST     | static-form/api/token            | static-form.token.update        | ReeceM\StaticForm\Http\Controllers\Api\ManageStaticTokenController@update       | web                                                       |
|        |          |                                  |                                 |                                                                                 | ReeceM\StaticForm\Http\Middleware\Authorize               |
|        | GET|HEAD | static-form/api/token            | static-form.token.index         | ReeceM\StaticForm\Http\Controllers\Api\ManageStaticTokenController@index        | web                                                       |
|        |          |                                  |                                 |                                                                                 | ReeceM\StaticForm\Http\Middleware\Authorize               |
|        | POST     | static-form/{form}               | static-form.create              | ReeceM\StaticForm\Http\Controllers\HandleStaticFormController                   | ReeceM\StaticForm\Http\Middleware\ValidStaticSiteKey      |
|        |          |                                  |                                 |                                                                                 | Illuminate\Routing\Middleware\ThrottleRequests:30,3       |


---

The url part that says `static-form` can be changed and comes from the config key `static-form.path`

The response for creating a token would be the following JSON with a 201 status:

```json
{
    "plain_token": "random_string_that_is_40_characters_long",
    "message": "Token Created, please keep this as it is available once"
}
```

- [ ] Make a plugin UI, just deciding on if it should be in package or a separate snippet.

## Use the Middleware

To use the middleware, you can define a route using the config file, I do suggest that you use the API endpoint, this is as it is stateless and also would not require the CSRF token.

```php
// routes/api.php

Route::group([
    'middleware' => config('static-form.middleware.forms'),
], function () {
    // A controller that you have created.
    Route::post('/static-form/contact', StaticContactController::class)->name('contact.create');
});
```

## Static Site side

### Submitting Forms

On your static site, you can have your contact form. The way of handling the form is done using the API part of the hosting provider.

So for Vercel apps, you can create a new file under the `api` directory.

#### Api Code
For the API part of the code:

```javascript
// api/contactus.js

/**
 * Create a contact request to the main server.
 *
 * @param {http.IncomingMessage} req
 * @param {*} res
 */
import { APP_TOKEN, APP_URL } from "../../../lib/constants"

export default async function contactus(req, res) {

  if (req.method !== 'POST') {
    return res.status(400);
  }

  if (req.body?.website) {
    return res.status(200);
  }

  let body = JSON.parse(req.body)
  let {_token, xsrf} = body.token;
  delete body.token

  const request = await fetch(
    `${APP_URL}/api/static-form/contactus`,
    {
      method: 'POST',
      body: JSON.stringify(body),
      headers: {
        'X-STATIC-FORM': APP_TOKEN, /** This header is the important point here */
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        "x-requested-with": "XMLHttpRequest",
      },
    }
  )

  if (request.status !== 201 ) {
    let json = await request.json()

    throw new Error(json.message || 'Failed to fetch API');
  }

  const json = await request.json()

  if (json.errors) {
    console.error(json.errors)
    throw new Error('Failed to fetch API, json errors')
  }

  return res.status(201).json(json.data ?? {});
}
```

#### Frontend Code

You can try a simple form layout for the frontend:

```javascript
import React, { useCallback, useEffect, useRef, useState } from 'react'

const ContactUs = () => {
    const [contactName, setContactName] = useState('')
    const [contactEmail, setContactEmail] = useState('')
    const [honey, setHoney] = useState('')

    function handleForm(e) {
        e.preventDefault()

        if (honey.length >= 1) {
            return
        }

        let body = {
            name: contactName,
            email: contactEmail,
        }

        fetch(
            `${location.origin}/api/contactus`,
            {
                method: 'POST',
                body: JSON.stringify(body)
            }
        )
        .then(response => {
            console.debug(response);
        })
        .catch(error => {
            console.error(error)
        })
    }

    return (
        <>
            <form onSubmit={handleForm}>
                <div style={{marginBottom: '0.75rem'}}>
                    <label htmlFor="name">Name</label>
                    <input 
                        name="name" 
                        id="name"
                        value={contactName}
                        onChange={e => setContactName(e.target.value)}
                        placeholder="Your Name"
                        type="text"
                    />
                </div>
                <div style={{marginBottom: '0.75rem'}}>
                    <label htmlFor="email">Email</label>
                    <input 
                        name="email" 
                        id="email"
                        value={contactEmail}
                        onChange={e => setContactEmail(e.target.value)}
                        placeholder="Your email"
                        type="email"
                    />
                </div>
                <input style={{display: 'none'}} name="website" value={honey} onChange={e => setHoney(e.target.value)}> <!-- The honeypot field -->
                <button type="submit">Submit</button>
            </form>
        </>
    )
}

export default ContactUs;

```
