# Test API

This test API is developed on Lumen framework.

## API usage

All requests to API should contain `x-api-key` header

In case of missing `x-api-key` header, server will respond:

```
    Status: 401 Unauthorized
```

```
    {
        "result": "error",
        "message": "header `x-api-key` is not provided"
    } 
```
## Terms:
`email` valid email address, like: something@gmail.com
`code` 4-digits code, no alpha chars, no spaces, like: 1234, 5030 

### POST /api/sendCode

This endpoint performs generating 4-digits code and sending to specified email address

URL:

http://localhost/api/sendCode

Request type : `POST`

parameters:
```
    email : email@example.com
```   
headers:
```
    x-api-key : xOqzNUmFhRBnh29hMPXibo4OmoBpjw02VnvP
```

HTTP responses:

```
    Status: 200 OK 
```

```
    {
        "result": "ok",
        "message": "Code is sent"
    }
```
or
```
    {
        "result": "error",
        "message": "too often requests, try in 3 minutes"
    }
```

Limit: 
* API accepts only 1 request `/sendCode` within last 5 minutes
* API accepts only 5 requests `/sendCode` within last hour




### POST /api/checkCode

This endpoint compares specified code with generated code in `sendCode` method   

URL:

http://localhost/api/sendCode

Request type: `POST`

parameters:
```
    email : email@example.com
    code : 1537
```

headers:
```
    x-api-key : xOqzNUmFhRBnh29hMPXibo4OmoBpjw02VnvP
```

HTTP responses:

```
    Status: 200 OK 
```

```
    {
        "result": "ok",
    }
```
or
```
    {
        "result": "error",
        "message": "wrong code"
    }
```

Limit:
* API accepts only 3 failed requests, when 3rd failed request is performed,
code will be deleted, client should request for new code by sending another `sendCode` request
