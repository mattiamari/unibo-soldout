# SoldOut
(university project)

A (pretend) ticket selling platform.

Through a web app, SoldOut allows customers to buy tickets for shows and concerts.  
Tickets may have many types, each one with a different price, such as "adult" or "child".

The web app is built as a single page application using vanilla Javascript because one of the
constraints was that we couldn't use frameworks, and communicates with the backend through a REST API.

The API makes use of the [Slim framework](http://www.slimframework.com/) and it's written in PHP (another constraint).

For ease of development and deployment, the whole app can be spun up in a Docker container.
