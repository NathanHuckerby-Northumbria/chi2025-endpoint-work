# chi2025-endpoint-work
A PHP REST API built as part of my Software Architecture module at Northumbria University. The API uses data from the CHI 20205 conference database.

Technologies used:
  - PHP - core API development
  - MySQL - used to access database
  - REST architecture - HTTP methods such as GET, POST, PATCH, PUT, DELETE
  - BearerToken Authentication - secure API access
  - JSON - data format for all responses

Features:
  - Author endpoint - returns the id and name of each author. Acheived by either author ID or presentation ID
  - Presentation endpoint - returns information about each presentation including type, abstract, doi, video, type
  - Type endpoint - full CRUD operations (CREATE, READ, UPDATE, DELETE)
