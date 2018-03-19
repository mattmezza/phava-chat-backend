phava-chat-backend
=======

Development
- export CHAT_DB="sqlite:/Users/Matt/Development/phava-chat-backend/tests/data/test.db"
- composer install
- composer migrate:test
- php -S localhost:8000 -t public

Testing
- export CHAT_DB="sqlite:/Users/Matt/Development/phava-chat-backend/tests/data/test.db"
- PORT=8000 composer test

Production
- export CHAT_DB="sqlite:/Users/Matt/Development/phava-chat-backend/data/chat.db"
- composer install
- composer migrate
- php -S localhost:8000 -t public