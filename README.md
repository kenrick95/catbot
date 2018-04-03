# catbot

Bot that meows.

## TODO:

- [x] Routing to make "web" driver works
- [ ] Make "telegram" driver works

## Docs

- `POST`: `/api/message`
  - Content: Use URL-encoded Form Data
    - `message`: string
    - `driver`: string, must be exactly `web`
    - `userId`: number, anything
