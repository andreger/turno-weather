# Turno Weather

## About me
Hi. My name is André Gervásio.

Thank you for this opportunity. Over the past few days, I have dedicated time and effort to developing this task. I sincerely hope it meets your expectations. I would appreciate the chance to discuss the decisions I made, explore areas for improvement, and receive any feedback you may have.

https://www.linkedin.com/in/andregervasio/

## Installation

1. Clone this repository:
    ```bash
    git clone git@github.com:andreger/turno-weather.git
    ```

2. Navigate to the `turno-weather` folder:
    ```bash
    cd turno-weather
    ```

3. Start Docker Compose:
    ```bash
    docker compose up -d
    ```
    The Vue.js application will run on port `8081`, while the Laravel API is hosted on port `8080`.

4. Run the setup script for the initial setup. This script will download the project dependencies, run database migrations, and seed the database:
    ```bash
    ./setup.sh
    ```

5. Open your browser and go to `http://localhost:8081`.

6. Use the following credentials to log in:

    - **User 1**
      - Email: jagger@turno.com
      - Password: Mick1234

    - **User 2**
      - Email: lennon@turno.com
      - Password: John1234

## Database Seed

You can reset the database at any time by running the following migration command:
```bash
docker exec -it turno-api-laravel php artisan migrate:fresh --seed
```

## Unit Tests

To run automated tests, use the following command:
```bash
./vendor/bin/phpunit test
```

To generate a coverage report, use the command:
```bash
./vendor/bin/phpunit --coverage-html coverage
```

A `coverage` folder will be created, containing the report in HTML format.

## API Endpoints

`turno_forecast.postman_collection.json` file, located in the project's root folder, is a Postman-exported collection containing all API endpoints.

- **GET** Locations - http://localhost:8080/api/locations
- **POST** Create Location - http://localhost:8080/api/locations
- **POST** Login - http://localhost:8080/api/login
- **DELETE** Delete Location - http://localhost:8080/api/locations/{id}


## The Task 

You will develop a web application using Vue JS (2 or 3), PHP 8+, Laravel 11+, MySQL 8+ or SQLite database. You need to publish on github or other git repository with all instructions to execute your project.

The project will be a Weather App In order to do that, we recommend using Current weather and forecast  (free account), but you can choose any API of your choice An example of call to get the weather for London is http://api.openweathermap.org/data/2.5/forecast?q=London,uk&APPID=YOUR_API_KEY

Specifications below. You don't need to do anything extra than what was asked. Keep in mind that we are evaluating if you code is clean, well structured, good database design, follow industry standards and patterns:

1. The system must have 2 existing already registered users 
2. User will login with email and password. You don't need to create a registration form but must have a login page. 
3. There should be a form where user can input city and state to get forecast 
4. There should be an endpoint that will receive city and state info, will fetch data from the 3rd party API and save relevant info in database, including date, min/max temperature and condition (sun, rain, etc) 
5. User should be able to register up to 3 locations 
6. User can remove saved locations 
7. Locations saved by one user won't affect another user locations 
8. When adding a city, the system must save the city associated with the user in the database, along with the forecast for a couple days (limit by your API. Feel free to decide how many days. The important part is to fetch info from the 3rd party API). 
9. The web app should display an icon associated with the weather, such as sun, rain, clouds, etc. 
10. When user log in again in the app, existing locations should be loaded with saved forecast 
11. Backend must have automated testing using PHPUnit or Pest: - Feature test for the endpoint described on item #4, with a mock of the API return. Test should not do a real API call - this test must check for a valid input and an invalid input

What do we look at when checking out the solution:

- Functionality: We’ll look at the application and verify if everything’s working properly
- Performance: We’ll analyze your code to see how performing it is, and how well it behaves with the requests being made
- Code quality and organization: We’ll check your knowledge to see if your code does what it’s meant to be done, if it’s easy to understand, if it’s testable and follows a consistent baseline throughout the app, as well as it’s properly organized
- Framework knowledge: We’ll verify your knowledge regarding the chosen framework; If you know how to use its tools properly, if you understand what those tools are meant to do, and how well you applied them, as well as following the framework’s rules and patterns;

For the back-end specific requirements:
- Automated Tests / Test Coverage
- API Access Control (Policies, Gates, etc.)
- And big extra points if you: Implement a design pattern (DDD, Repository Pattern, etc.) and make a good and cohesive database design

For the front-end specific requirements:
- TypeScript
- Code structure and organization (How you organize it is a personal choice, but it needs to be well organized and structured, as well as following your choice’s framework patterns and guidelines)
- And big extra points if you have a solid knowledge of Front-end cache and Performance and monitoring tools