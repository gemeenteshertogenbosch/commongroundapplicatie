#love-common-ground




## Setting up your component for use with the common ground dev platform
Following the data at the source principle, the The commonground dev platform stores as little information as possible about your components. Instead it will try to analyze the repository holding your code. This means that your repository must be public in order for you to offer it as a common ground component. And that if you want to change any or all of the information displayed on the common ground platform your own codebase is the place to go.
 
When analyzing your repository the platform will try the following operations 
- Get the name, description and logo for your component from your repository settings
- Get license and owner information for your component from your repository settings
- Read the following files for display purposes on the platform (must all be located at root level)
- - README.md
- - LICENSE
- - CHANGELOG.md
- - CONTRIBUTING.md
- - INSTALLATION.md
- - CODE_OF_CONDUCT.md

It will then try to find an openapi(preferred) or swagger file in either yaml (preferred) or json format in the following directories(in order) of your repository.
- '' (root)
- 'schema/'
- 'public/'	
- 'public/schema/'	
- 'api/'
- 'api/schema/'
- 'api/public/'
- 'api/public/schema/'

Specifications files will only be processed when they conform to AOS3 or higher. If no (correct) specification file can be found the component will be viewed as an 'application' type component, if an valid specification file is found then the component is viewed as 'source' type component. It is possible to overwrite this behavior trough the info.x-commonground.type field in your AO3 specification.

Docker information will be procced from the docker-compose.yml file that should be included at the route of repository. 

## Extending on AOS3 specifications
It is possible extend the OAS3 specification with commonground specific information using the [x-commonground](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#specificationExtensions) field. This field should extend in the info field.

At this moment the follwing fields are in use:

| Field      | Value         | Usage  |
| ------------- |:-------------:| :-----|
| type      | enum: 'application','source','tool' | Determines the type of the component |
| developers      | Array of [contact objects](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#contactObject) + Git profile | Lists all developing parties (preferably organizations, optionally single developers that work on this project. In order for logo's and avatars to be displayed on the platform be sure to link to a git (Github, Gitlab or Bitbucket) acount in the url |

```yaml
info:
  ...
  x-commonground:
    type: source
    developers:
      -
        name: Conduction
        url: https://github.com/ConductionNL
        email: ruben@conduction.nl
      -
        name: Ruben van der Linde
        url: https://github.com/rubenvdlinde
        email: ruben@conduction.nl
```

