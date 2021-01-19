# shorty
simple PHP/Symfony API to store an URL hash with an opportunity to use it as a redirect alias

endpoints:
  - create: POST shorty.domain/api/short-url store an URL
 
      {
        "original": "http://your-url.org"
      }  

  - list: GET shorty.domain/api/short-url get all the url (without offsets and filters)
  - statistics: GET shorty.domain/api/short-url/statistics aggregation stats page, by default returns users with created urls statistics
  - redirect by alias: GET shorty.domain/api/short-url/redirect/{hash} redirects if url exists
