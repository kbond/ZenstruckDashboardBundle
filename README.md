# ZenstruckDashboardBundle

Provides a configurable administration menu and dashboard with customizable widgets.

[![Screenshot][1]][2]

[View Demo][2]

## Installation

1. Add to your `composer.json`:

    ```json
    {
        "require": {
            "zenstruck/dashboard-bundle": "*"
        }
    }
    ```

2. Register the bundle with Symfony2:

    ```php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Zenstruck\Bundle\DashboardBundle\ZenstruckDashboardBundle(),
        );
        // ...
    }
    ```

## Full Default Config

```yaml
zenstruck_dashboard:
    user_service:         false
    title:                Administration
    theme:                ZenstruckDashboardBundle:Twitter
    dashboard_template:   ~
    layout:               ~
    widgets:

        # Prototype
        name:
            title:                ~
            content:              ~ # Required

            # route, controller, template
            content_type:         controller

            # embed, hinclude, esi, ajax
            include_type:         embed
            group:                ~
            role:                 ~
            params:               []
    menu:

        # Prototype
        name:
            label:                ~
            group:                primary
            icon:                 ~
            nested:               true
            items:

                # Prototype
                name:
                    label:                ~
                    uri:                  ~
                    route:                ~
                    routeParameters:      ~
                    role:                 ~
                    icon:                 ~
```

[1]: https://lh5.googleusercontent.com/-TmZs6sGhZBU/UTEd0bKCJrI/AAAAAAAAKE0/lKclhxNEYec/s969/zenstruckdashboardbundle.jpg
[2]: http://sandbox.zenstruck.com/