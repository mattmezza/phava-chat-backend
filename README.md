phava-chat-backend
=======

Development
- `export CHAT_DB="sqlite:/Users/Matt/Development/phava-chat-backend/tests/data/test.db"`
- `composer install`
- `composer migrate:test`
- `php -S localhost:8000 -t public`

Testing
- `export CHAT_DB="sqlite:/Users/Matt/Development/phava-chat-backend/tests/data/test.db"`
- `PORT=8000 composer test`

Production
- `export CHAT_DB="sqlite:/Users/Matt/Development/phava-chat-backend/data/chat.db"`
- `composer install`
- `composer migrate`
- `php -S localhost:8000 -t public`

# REST quick specs

Every call should have a header `X-USERID` set to an int value representing the user id. This is used for authentication.

## getting messages

`GET /messages?page=0&perpage=20` with `page` and `perpage` being optional (with default vale `0` and `20`) and establishing how many messages will be fetched and which portion of the data will be given.
### Possible errors
It can throw a `403` if no `X-USERID` header is given (or if it is not valid).
### Example of successful reply
```json
[
  {
    "id": 1,
    "sender": 2,
    "body": "Ohhh mi rispondi???",
    "sentAt": {
      "date": "2018-03-19 04:08:50.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    }
  }
]
```

## sending messages

`POST /messages/send` with two params specified, `recipient` (that must be set to an int value) and `text` that represents the message to be sent.

### Possible errors

It can throw two errors: Invalid Recipient (when the recipient is not an integer) and Missing Recipient (when the recipient is not specified).

### Example of successful reply

```json
{
  "status": 201,
  "title": "Message sent",
  "detail": "Message sent with ID 2 at 19/03/2018 04:37:36"
}
```

## error messages

### Missing or invalid header X-USERID

Thrown when the `X-USERID` header is either not set or not valid (it must be an integer). Returns a 403.

```json
{
  "status": 403,
  "type": "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/403",
  "title": "Forbidden",
  "detail": "Each request should come with a header field `X-USERID` set to an integer representing the user id of the client.",
  "instance": "http://localhost:8000/messages",
  "error_code": "FORBIDDEN"
}
```

### Missing recipient

Thrown when during the message send a recipient is not specified. Returns a 400.

```json
{
  "status": 400,
  "type": "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/400",
  "title": "Bad Request",
  "detail": "You didn't specify a `recipient` numeric field.",
  "instance": "http://localhost:8000/messages/send",
  "error_code": "MISSING_RECIPIENT"
}
```

### Invalid recipient

Thrown when during the message send the recipient is not valid (it must be an integer). Returns a 400.

```json
{
  "status": 400,
  "type": "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/400",
  "title": "Bad Request",
  "detail": "The recipient you specified is invalid, pleasespecify a `recipient` integer field.",
  "instance": "http://localhost:8000/messages/send",
  "error_code": "INVALID_RECIPIENT"
}
```

### Internal error

Thrown when some internal components fail or on system error. Returns a 500. The detail property, when available, contains a description of the generated error.

```json
{
  "status": 500,
  "type": "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/500",
  "title": "Internal Server Error",
  "detail": "Description of the error",
  "instance": "http://localhost:8000/messages",
  "error_code": "INTERNAL_SERVER_ERROR"
}
```

### Method not allowed

Thrown when trying to call an endpoint with an unsupported method. It returns a 405. The instance field reports the hit endpoint.

```json
{
  "status": 405,
  "type": "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/405",
  "title": "Method Not Allowed",
  "detail": "The method POST is not allowed methods are GET",
  "instance": "http://localhost:8000/messages",
  "error_code": "NOT_ALLOWED"
}
```