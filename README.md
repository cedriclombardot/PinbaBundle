# PinbaBundle

## Setup


Add in your composer.json :

```
"require": {
   "cedriclombardot/pinba-bundle": "dev-master"
}
```


Configure your kernel

``` php
$bundles = array(
    new Cedriclombardot\PinbaBundle\CedriclombardotPinbaBundle(),
);
```

## With propel

Update your config.yml

```
propel:
    dbal:
        connections:
            default:
                classname:            "Cedriclombardot\\PinbaBundle\\Propel\\PinbaPropelPDO"
                driver:               %database_driver%
                user:                 %database_user%
                password:             %database_password%
                dsn:                  %database_driver%:host=%database_host%;dbname=%database_name%;port=%database_port%;charset=UTF8
                options:              {}
                attributes:           {}
```
