# task-2
Recruitment task

#### Requirements:
Requires php >= 8.3 and composer locally or just docker compose.

#### Run locally:
Install composer dependencies `make install`

Run tests `make tests`

#### Run using docker compose:
Set up app in docker: `make init` `make dev`

Install composer dependencies `make install-in-docker-container`

Run tests `tests-in-docker-container`

### Assumptions:
1. Integer was used for prices storage - Money package should be used for calculations: https://github.com/moneyphp/money
2. "Entities and properties" - accessibility and readonly option should be checked
3. Using InMemory repository instead of Doctrine or any other ORM - for sake of "simplicity"
4. Error handling should be implemented
5. Logging should be implemented
