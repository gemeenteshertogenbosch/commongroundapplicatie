#TUTORIAL


## Setting up your component for use with the common ground dev platform
Following the data at the source principle, the common ground dev platform stores as little information as possible about your components. Instead it will try to analyze the repository holding your code. This means that your repository must be public in order for you to offer it as a common ground component. And that if you want to change any or all of the information displayed on the common ground platform your own codebase is the place to go.
 
When analyzing your repository the platform will try the following operations 

* Get the name, description and logo for your component from your repository settings
* Get license and owner information for your component from your repository settings
* Read the following files for display purposes on the platform (must all be located at root level) markdown (.md) is preffered but reStructuredText (.rts) is also suported
** README
** LICENSE
** CHANGELOG
** CONTRIBUTING
** INSTALLATION
** CODE_OF_CONDUCT

It will then try to find an openapi(preferred) or swagger file in either yaml (preferred) or json format in the following directories(in order) of your repository.

* '' (root)
* 'schema/'
* 'public/'	
* 'public/schema/'	
* 'api/'
* 'api/schema/'
* 'api/public/'
* 'api/public/schema/'

Specifications files will only be processed when they conform to AOS3 or higher. If no (correct) specification file can be found the component will be viewed as an 'application' type component, if an valid specification file is found then the component is viewed as 'source' type component. It is possible to overwrite this behavior trough the info.x-commonground.type field in your AO3 specification.

Docker information will be procced from the docker-compose.yml file that should be included at the route of repository. 

## Adding your components to the common ground dev platform
Simply log in at [www.common-ground.dev](www.common-ground.dev) using the git provider that host your repository, then either create a new organization or open an existing one. After that you will see a list of al your repositories that are available for publication on common ground. Simply click connect to add your repository.   

## Extending on AOS3 specifications
It is possible extend the OAS3 specification with common ground specific information using the [x-commonground](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#specificationExtensions) field. This field should extend the info field.

At this moment the follwing fields are in use:

| Field      | Value         | Usage  |
| ------------- |:-------------:| :-----|
| type      | enum: 'application','source','tool' | Determines the type of the component |
| developers      | Array of [contact object](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md#contactObject) + Git profile | Lists all developing parties (preferably organizations, optionally single developers that work on this project. In order for logo's and avatars to be displayed on the platform be sure to link to a git (Github, Gitlab or Bitbucket) acount in the url |
| builds      | Array of [build object] | references external code certifications |
| build object      | Array of [build object] | references external code certifications |


Would result in the following example code


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
    builds:
      -
        name: travis
        logo: https://travis-ci.org/api-platform/core.svg?branch=master
        url: https://travis-ci.org/api-platform/core
      -
        name: appveyor
        logo: https://ci.appveyor.com/api/projects/status/grwuyprts3wdqx5l?svg=true
        url: https://ci.appveyor.com/project/dunglas/dunglasapibundle
      -
        name: codecov
        logo: https://codecov.io/gh/api-platform/core/branch/master/graph/badge.svg
        url: https://codecov.io/gh/api-platform/core
      -
        name: SensioLabsInsight
        logo: https://insight.sensiolabs.com/projects/92d78899-946c-4282-89a3-ac92344f9a93/mini.png
        url: https://insight.sensiolabs.com/projects/92d78899-946c-4282-89a3-ac92344f9a93
      -
        name: Scrutinizer Code Quality
        logo: https://scrutinizer-ci.com/g/api-platform/core/badges/quality-score.png?b=master
        url: https://scrutinizer-ci.com/g/api-platform/core/?branch=master
```



